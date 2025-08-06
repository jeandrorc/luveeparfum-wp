#!/bin/bash

# =============================================================================
# IMPORTAÇÃO DE BACKUP MANUAL
# Para quando não há acesso SSH direto ao servidor
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

echo "📥 IMPORTAÇÃO DE BACKUP MANUAL"
echo "=============================="
echo

# Verificar se há arquivos SQL na pasta de backups
mkdir -p database-backups

SQL_FILES=($(find database-backups -name "*.sql" 2>/dev/null || true))

if [[ ${#SQL_FILES[@]} -gt 0 ]]; then
    echo "📂 Arquivos SQL encontrados:"
    for i in "${!SQL_FILES[@]}"; do
        size=$(du -h "${SQL_FILES[i]}" | cut -f1)
        echo "  $((i+1)). ${SQL_FILES[i]} (${size})"
    done
    echo
    
    read -p "Escolha um arquivo (número) ou 0 para usar arquivo externo: " choice
    
    if [[ "$choice" -gt 0 && "$choice" -le "${#SQL_FILES[@]}" ]]; then
        SQL_FILE="${SQL_FILES[$((choice-1))]}"
        log "Arquivo selecionado: $SQL_FILE"
    else
        read -p "Digite o caminho completo do arquivo SQL: " SQL_FILE
    fi
else
    echo "❌ Nenhum arquivo SQL encontrado em database-backups/"
    echo
    echo "📥 Como obter um backup:"
    echo "1. Baixe pelo painel do servidor/hosting"
    echo "2. Use phpMyAdmin para exportar"
    echo "3. Conecte diretamente no banco remoto"
    echo "4. Use outro método de acesso (FTP, etc.)"
    echo
    read -p "Digite o caminho do arquivo SQL para importar: " SQL_FILE
fi

# Verificar se o arquivo existe
if [[ ! -f "$SQL_FILE" ]]; then
    error "Arquivo não encontrado: $SQL_FILE"
fi

log "Arquivo a ser importado: $SQL_FILE"
size=$(du -h "$SQL_FILE" | cut -f1)
log "Tamanho do arquivo: $size"

# Verificar se containers estão rodando
if ! docker ps --format "table {{.Names}}" | grep -q "mysql-local-luvee\|wordpress-local-luvee"; then
    warning "Containers não estão rodando. Iniciando..."
    docker-compose up -d mysql wordpress
    log "Aguardando MySQL..."
    sleep 15
fi

# Fazer backup do banco atual
log "Fazendo backup do banco local atual..."
LOCAL_BACKUP="database-backups/local-backup-before-import-$(date +%Y%m%d_%H%M%S).sql"
docker-compose run --rm wpcli db export "/var/www/html/$LOCAL_BACKUP" || true
success "Backup local salvo: $LOCAL_BACKUP"

# Confirmar importação
echo
warning "ATENÇÃO: Esta operação irá SUBSTITUIR completamente o banco local!"
warning "Backup atual salvo em: $LOCAL_BACKUP"
echo
read -p "Confirma a importação? (y/n): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    log "Importação cancelada."
    exit 0
fi

# Importar o banco
log "Resetando banco local..."
docker-compose run --rm wpcli db reset --yes

log "Importando backup..."
# Copiar arquivo para dentro do container se necessário
if [[ "$SQL_FILE" != ./database-backups/* ]]; then
    cp "$SQL_FILE" "database-backups/temp-import.sql"
    IMPORT_FILE="database-backups/temp-import.sql"
else
    IMPORT_FILE="$SQL_FILE"
fi

docker-compose run --rm wpcli db import "/var/www/html/$IMPORT_FILE"
success "Banco importado com sucesso!"

# Limpar arquivo temporário
if [[ -f "database-backups/temp-import.sql" ]]; then
    rm "database-backups/temp-import.sql"
fi

# Atualizar URLs
log "Atualizando URLs..."
REMOTE_URL=$(docker-compose run --rm wpcli option get home 2>/dev/null | tr -d '\r' || echo "https://luvee.com.br")
LOCAL_URL="http://localhost:8000"

echo "URL remota detectada: $REMOTE_URL"
echo "URL local: $LOCAL_URL"
echo

read -p "Confirma a atualização de URLs? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    docker-compose run --rm wpcli search-replace "$REMOTE_URL" "$LOCAL_URL"
    success "URLs atualizadas"
else
    warning "URLs não foram atualizadas. Site pode não funcionar corretamente."
fi

# Limpar cache e permalinks
log "Limpando cache e atualizando permalinks..."
docker-compose run --rm wpcli cache flush || true
docker-compose run --rm wpcli rewrite flush
success "Cache e permalinks atualizados"

# Verificar resultado
log "Verificando instalação..."
if docker-compose run --rm wpcli core is-installed; then
    success "WordPress funcionando corretamente!"
else
    warning "Pode haver problemas com a instalação"
fi

echo
success "=========================================="
success "IMPORTAÇÃO CONCLUÍDA!"
success "=========================================="
echo
log "Site local: http://localhost:8000"
log "Backup anterior: $LOCAL_BACKUP"
echo
warning "LEMBRE-SE:"
warning "- Teste o site para verificar se está funcionando"
warning "- Uploads/mídia não foram sincronizados"
warning "- Use ./sync-uploads.sh quando tiver acesso SSH"