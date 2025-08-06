#!/bin/bash

# =============================================================================
# IMPORTAR ÚLTIMO DUMP DISPONÍVEL
# Importa o backup mais recente no ambiente local
# =============================================================================

set -e

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

log() {
    echo -e "${BLUE}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1"
}

success() {
    echo -e "${GREEN}✓${NC} $1"
}

warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

error() {
    echo -e "${RED}✗${NC} $1"
    exit 1
}

BACKUP_DIR="./database-backups"
REMOTE_URL="https://luveeparfum.com.br"
LOCAL_URL="http://localhost:8000"

echo "📥 IMPORTAR ÚLTIMO DUMP DISPONÍVEL"
echo "=================================="

# Encontrar o dump mais recente
LATEST_DUMP=$(ls -t "$BACKUP_DIR"/hourly-dump-*.sql 2>/dev/null | head -1)

if [[ -z "$LATEST_DUMP" ]]; then
    error "Nenhum dump horário encontrado. Execute primeiro: ./sync-hourly.sh"
fi

DUMP_SIZE=$(du -h "$LATEST_DUMP" | cut -f1)
DUMP_TIME=$(stat -f "%Sm" -t "%Y-%m-%d %H:%M:%S" "$LATEST_DUMP" 2>/dev/null || stat -c "%y" "$LATEST_DUMP" 2>/dev/null | cut -d'.' -f1)

log "Dump mais recente encontrado:"
log "  Arquivo: $(basename "$LATEST_DUMP")"
log "  Tamanho: $DUMP_SIZE"
log "  Criado em: $DUMP_TIME"

# Verificar se containers estão rodando
if ! docker ps --format "table {{.Names}}" | grep -q "mysql-local-luvee\|wordpress-local-luvee"; then
    warning "Containers não estão rodando. Iniciando..."
    docker-compose up -d mysql wordpress
    log "Aguardando containers inicializarem..."
    sleep 15
fi

echo
warning "ATENÇÃO: Esta operação irá SUBSTITUIR o banco local atual!"
echo
read -p "Confirma a importação do dump mais recente? (y/n): " -n 1 -r
echo

if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    log "Importação cancelada."
    exit 0
fi

# Fazer backup do banco local atual
log "Fazendo backup do banco local atual..."
LOCAL_BACKUP="$BACKUP_DIR/local-before-latest-$(date +%Y%m%d_%H%M%S).sql"
docker-compose exec -T mysql mysqldump -u root -proot_password wordpress_local > "$LOCAL_BACKUP" 2>/dev/null || true
success "Backup local salvo: $(basename "$LOCAL_BACKUP")"

# Resetar banco local
log "Resetando banco local..."
docker-compose exec -T mysql mysql -u root -proot_password -e "DROP DATABASE IF EXISTS wordpress_local; CREATE DATABASE wordpress_local;" 2>/dev/null

# Importar dump
log "Importando dump ($DUMP_SIZE)..."
# Modificar o dump para usar o banco local e importar
sed "s/jeandr00_wp167/wordpress_local/g" "$LATEST_DUMP" | docker-compose exec -T mysql mysql -u root -proot_password 2>/dev/null

# Atualizar URLs
log "Atualizando URLs para ambiente local..."
docker-compose exec -T mysql mysql -u root -proot_password -e "
    USE wordpress_local;
    UPDATE wp_options SET option_value = '$LOCAL_URL' WHERE option_name = 'home';
    UPDATE wp_options SET option_value = '$LOCAL_URL' WHERE option_name = 'siteurl';
    UPDATE wp_posts SET post_content = REPLACE(post_content, '$REMOTE_URL', '$LOCAL_URL');
" 2>/dev/null

# Verificar resultado
log "Verificando importação..."
TABLES_COUNT=$(docker-compose exec -T mysql mysql -u root -proot_password -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'wordpress_local';" 2>/dev/null | tail -1)

if [[ "$TABLES_COUNT" -gt 50 ]]; then
    success "Importação bem-sucedida! ($TABLES_COUNT tabelas)"
else
    error "Problemas na importação (apenas $TABLES_COUNT tabelas encontradas)"
fi

# Testar site
log "Testando site local..."
if curl -s -o /dev/null -w "%{http_code}" http://localhost:8000 | grep -q "200"; then
    success "Site local funcionando: http://localhost:8000"
else
    warning "Site pode ter problemas. Verifique manualmente."
fi

echo
success "=========================================="
success "IMPORTAÇÃO DO ÚLTIMO DUMP CONCLUÍDA!"
success "=========================================="
echo
log "Dados importados de: $DUMP_TIME"
log "Site local: http://localhost:8000"
log "Admin: http://localhost:8000/wp-admin"
log "Backup anterior: $(basename "$LOCAL_BACKUP")"