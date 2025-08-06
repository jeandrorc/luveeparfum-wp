#!/bin/bash

# =============================================================================
# SINCRONIZAÇÃO AUTOMÁTICA DE HORA EM HORA
# Gera dumps do banco remoto e mantém ambiente local atualizado
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
}

# Configurações do banco remoto
REMOTE_HOST="108.167.168.18"
REMOTE_PORT="3306"
REMOTE_USER="jeandr00_prd"
REMOTE_PASSWORD="7Y\$Tdv{m_S&+"
REMOTE_DATABASE="jeandr00_wp167"
REMOTE_URL="https://luveeparfum.com.br"
LOCAL_URL="http://localhost:8000"
BACKUP_DIR="./database-backups"

# Configurações de limpeza
KEEP_BACKUPS_DAYS=7  # Manter backups por 7 dias
MAX_BACKUPS=48       # Máximo de 48 backups (2 dias de backups horários)

echo "🔄 SINCRONIZAÇÃO AUTOMÁTICA HORÁRIA"
echo "=================================="
log "Iniciando sincronização automática..."

# Configurar PATH para mysql-client
if [[ -d "/usr/local/opt/mysql-client/bin" ]]; then
    export PATH="/usr/local/opt/mysql-client/bin:$PATH"
fi

# Verificar se mysql client está disponível
if ! command -v mysql &> /dev/null; then
    error "Cliente MySQL não encontrado. Execute: brew install mysql-client"
    exit 1
fi

# Criar diretório de backups
mkdir -p "$BACKUP_DIR"

# Nome do arquivo com timestamp
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
DUMP_FILE="$BACKUP_DIR/hourly-dump-$TIMESTAMP.sql"
LATEST_DUMP="$BACKUP_DIR/latest-dump.sql"

# Testar conectividade
log "Testando conectividade com banco remoto..."
if ! mysql -h"$REMOTE_HOST" -P"$REMOTE_PORT" -u"$REMOTE_USER" -p"$REMOTE_PASSWORD" -e "SELECT 1" "$REMOTE_DATABASE" &>/dev/null; then
    error "Falha na conectividade com banco remoto"
    exit 1
fi
success "Conectividade OK"

# Fazer dump do banco remoto
log "Fazendo dump do banco remoto..."
if mysqldump -h"$REMOTE_HOST" -P"$REMOTE_PORT" -u"$REMOTE_USER" -p"$REMOTE_PASSWORD" \
    --single-transaction \
    --routines \
    --triggers \
    --add-drop-database \
    --databases "$REMOTE_DATABASE" > "$DUMP_FILE" 2>/dev/null; then
    
    size=$(du -h "$DUMP_FILE" | cut -f1)
    success "Dump criado: $DUMP_FILE ($size)"
    
    # Criar link simbólico para o backup mais recente
    ln -sf "$(basename "$DUMP_FILE")" "$LATEST_DUMP"
    success "Link atualizado: $LATEST_DUMP"
else
    error "Falha ao criar dump"
    exit 1
fi

# Verificar se deve importar automaticamente (apenas se especificado)
if [[ "$1" == "--import" ]]; then
    log "Importando dump no ambiente local..."
    
    # Verificar se containers estão rodando
    if ! docker ps --format "table {{.Names}}" | grep -q "mysql-local-luvee"; then
        log "Iniciando containers..."
        docker-compose up -d mysql wordpress >/dev/null 2>&1
        sleep 10
    fi
    
    # Fazer backup do banco local atual
    LOCAL_BACKUP="$BACKUP_DIR/local-before-hourly-$TIMESTAMP.sql"
    docker-compose exec -T mysql mysqldump -u root -proot_password wordpress_local > "$LOCAL_BACKUP" 2>/dev/null || true
    
    # Importar novo dump
    docker-compose exec -T mysql mysql -u root -proot_password -e "DROP DATABASE IF EXISTS wordpress_local; CREATE DATABASE wordpress_local;" 2>/dev/null
    
    # Modificar o dump para usar o banco local
    sed "s/jeandr00_wp167/wordpress_local/g" "$DUMP_FILE" | docker-compose exec -T mysql mysql -u root -proot_password 2>/dev/null
    
    # Atualizar URLs
    docker-compose exec -T mysql mysql -u root -proot_password -e "
        USE wordpress_local;
        UPDATE wp_options SET option_value = '$LOCAL_URL' WHERE option_name = 'home';
        UPDATE wp_options SET option_value = '$LOCAL_URL' WHERE option_name = 'siteurl';
        UPDATE wp_posts SET post_content = REPLACE(post_content, '$REMOTE_URL', '$LOCAL_URL');
    " 2>/dev/null
    
    success "Importação concluída - Site local atualizado"
fi

# Limpeza de backups antigos
log "Limpando backups antigos..."
find "$BACKUP_DIR" -name "hourly-dump-*.sql" -type f -mtime +$KEEP_BACKUPS_DAYS -delete 2>/dev/null || true

# Manter apenas os últimos N backups
ls -t "$BACKUP_DIR"/hourly-dump-*.sql 2>/dev/null | tail -n +$((MAX_BACKUPS + 1)) | xargs rm -f 2>/dev/null || true

# Estatísticas
BACKUP_COUNT=$(ls -1 "$BACKUP_DIR"/hourly-dump-*.sql 2>/dev/null | wc -l | tr -d ' ')
TOTAL_SIZE=$(du -sh "$BACKUP_DIR" 2>/dev/null | cut -f1)

success "=========================================="
success "SINCRONIZAÇÃO HORÁRIA CONCLUÍDA!"
success "=========================================="
log "Dump criado: $DUMP_FILE"
log "Backups mantidos: $BACKUP_COUNT"
log "Tamanho total: $TOTAL_SIZE"
log "Próxima execução: $(date -d '+1 hour' +'%Y-%m-%d %H:%M:%S')"

# Log para arquivo
echo "$(date): Dump criado com sucesso - $DUMP_FILE" >> "$BACKUP_DIR/sync.log"