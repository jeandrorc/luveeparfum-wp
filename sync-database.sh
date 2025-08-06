#!/bin/bash

# =============================================================================
# SCRIPT DE SINCRONIZAÇÃO DE BANCO DE DADOS
# Sincroniza banco de dados remoto com ambiente local Docker
# =============================================================================

set -e

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Função para logging
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

# Configurações padrão
BACKUP_DIR="./database-backups"
BACKUP_FILE="remote-backup-$(date +%Y%m%d_%H%M%S).sql"
LOCAL_URL="http://localhost:8000"

# Função para mostrar ajuda
show_help() {
    cat << EOF
SINCRONIZAÇÃO DE BANCO DE DADOS - WordPress Docker

USO:
    ./sync-database.sh [OPÇÕES]

OPÇÕES:
    --remote-host HOST          Host do servidor remoto (obrigatório)
    --remote-user USER          Usuário SSH do servidor remoto (obrigatório)
    --remote-path PATH          Caminho do WordPress no servidor remoto (obrigatório)
    --remote-url URL            URL do site remoto (obrigatório)
    --local-url URL             URL local (padrão: http://localhost:8000)
    --backup-dir DIR            Diretório para backups (padrão: ./database-backups)
    --skip-search-replace       Pula a substituição de URLs
    --dry-run                   Executa sem fazer alterações reais
    -h, --help                  Mostra esta ajuda

EXEMPLOS:
    # Sincronização básica
    ./sync-database.sh \\
        --remote-host luvee.com.br \\
        --remote-user ubuntu \\
        --remote-path /var/www/html \\
        --remote-url https://luvee.com.br

    # Com configurações customizadas
    ./sync-database.sh \\
        --remote-host luvee.com.br \\
        --remote-user ubuntu \\
        --remote-path /var/www/html \\
        --remote-url https://luvee.com.br \\
        --local-url http://localhost:8080 \\
        --backup-dir ./backups

PRÉ-REQUISITOS:
    - Docker e docker-compose instalados
    - Acesso SSH ao servidor remoto
    - WP-CLI instalado no servidor remoto
    - Container WordPress local em execução

EOF
}

# Parse dos argumentos
while [[ $# -gt 0 ]]; do
    case $1 in
        --remote-host)
            REMOTE_HOST="$2"
            shift 2
            ;;
        --remote-user)
            REMOTE_USER="$2"
            shift 2
            ;;
        --remote-path)
            REMOTE_PATH="$2"
            shift 2
            ;;
        --remote-url)
            REMOTE_URL="$2"
            shift 2
            ;;
        --local-url)
            LOCAL_URL="$2"
            shift 2
            ;;
        --backup-dir)
            BACKUP_DIR="$2"
            shift 2
            ;;
        --skip-search-replace)
            SKIP_SEARCH_REPLACE=true
            shift
            ;;
        --dry-run)
            DRY_RUN=true
            shift
            ;;
        -h|--help)
            show_help
            exit 0
            ;;
        *)
            error "Opção desconhecida: $1"
            ;;
    esac
done

# Validação dos parâmetros obrigatórios
if [[ -z "$REMOTE_HOST" || -z "$REMOTE_USER" || -z "$REMOTE_PATH" || -z "$REMOTE_URL" ]]; then
    error "Parâmetros obrigatórios faltando. Use --help para ver a sintaxe."
fi

log "Iniciando sincronização do banco de dados..."
log "Servidor remoto: ${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_PATH}"
log "URL remota: ${REMOTE_URL}"
log "URL local: ${LOCAL_URL}"

# Criar diretório de backup se não existir
mkdir -p "$BACKUP_DIR"

# Verificar se o Docker está rodando
if ! docker ps > /dev/null 2>&1; then
    error "Docker não está rodando ou não há permissão para acessá-lo"
fi

# Verificar se os containers estão rodando
if ! docker ps --format "table {{.Names}}" | grep -q "wordpress-local-luvee"; then
    warning "Containers WordPress/MySQL não estão rodando. Iniciando..."
    if [[ "$DRY_RUN" != "true" ]]; then
        docker-compose up -d mysql wordpress
        log "Aguardando MySQL inicializar..."
        sleep 15
        
        # Verificar se MySQL está pronto
        timeout=60
        counter=0
        while ! docker-compose exec -T mysql mysqladmin ping -h"localhost" --silent 2>/dev/null; do
            sleep 2
            counter=$((counter + 2))
            if [ $counter -gt $timeout ]; then
                error "Timeout aguardando MySQL inicializar"
            fi
            echo -n "."
        done
        echo
        success "MySQL e WordPress iniciados com sucesso!"
    fi
fi

# 1. Fazer backup do banco remoto
log "Fazendo backup do banco de dados remoto..."
REMOTE_BACKUP_PATH="/tmp/wp-remote-backup-$(date +%Y%m%d_%H%M%S).sql"

if [[ "$DRY_RUN" != "true" ]]; then
    ssh "${REMOTE_USER}@${REMOTE_HOST}" "cd ${REMOTE_PATH} && wp db export ${REMOTE_BACKUP_PATH} --allow-root"
    success "Backup remoto criado: ${REMOTE_BACKUP_PATH}"
    
    # 2. Baixar o backup
    log "Baixando backup do servidor remoto..."
    scp "${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_BACKUP_PATH}" "${BACKUP_DIR}/${BACKUP_FILE}"
    success "Backup baixado para: ${BACKUP_DIR}/${BACKUP_FILE}"
    
    # 3. Limpar arquivo temporário no servidor remoto
    ssh "${REMOTE_USER}@${REMOTE_HOST}" "rm ${REMOTE_BACKUP_PATH}"
else
    success "[DRY-RUN] Backup seria criado em: ${REMOTE_BACKUP_PATH}"
fi

# 4. Fazer backup do banco local (por segurança)
log "Fazendo backup do banco local atual..."
LOCAL_BACKUP_FILE="local-backup-$(date +%Y%m%d_%H%M%S).sql"

if [[ "$DRY_RUN" != "true" ]]; then
    docker-compose run --rm wpcli db export "/var/www/html/${BACKUP_DIR}/${LOCAL_BACKUP_FILE}" || true
    success "Backup local salvo: ${BACKUP_DIR}/${LOCAL_BACKUP_FILE}"
else
    success "[DRY-RUN] Backup local seria salvo em: ${BACKUP_DIR}/${LOCAL_BACKUP_FILE}"
fi

# 5. Importar o banco remoto no ambiente local
log "Importando banco remoto no ambiente local..."

if [[ "$DRY_RUN" != "true" ]]; then
    # Reset do banco local
    docker-compose run --rm wpcli db reset --yes
    
    # Importar o novo banco
    docker-compose run --rm wpcli db import "/var/www/html/${BACKUP_DIR}/${BACKUP_FILE}"
    success "Banco importado com sucesso!"
else
    success "[DRY-RUN] Banco seria importado de: ${BACKUP_DIR}/${BACKUP_FILE}"
fi

# 6. Atualizar URLs (search-replace)
if [[ "$SKIP_SEARCH_REPLACE" != "true" ]]; then
    log "Atualizando URLs do site..."
    
    if [[ "$DRY_RUN" != "true" ]]; then
        docker-compose run --rm wpcli search-replace "${REMOTE_URL}" "${LOCAL_URL}" --dry-run
        read -p "Confirma a substituição de URLs? (y/n): " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            docker-compose run --rm wpcli search-replace "${REMOTE_URL}" "${LOCAL_URL}"
            success "URLs atualizadas de ${REMOTE_URL} para ${LOCAL_URL}"
        else
            warning "Substituição de URLs cancelada"
        fi
    else
        success "[DRY-RUN] URLs seriam atualizadas de ${REMOTE_URL} para ${LOCAL_URL}"
    fi
fi

# 7. Limpar cache (se existir)
log "Limpando cache..."
if [[ "$DRY_RUN" != "true" ]]; then
    docker-compose run --rm wpcli cache flush || true
    success "Cache limpo"
else
    success "[DRY-RUN] Cache seria limpo"
fi

# 8. Verificar permalinks
log "Atualizando permalinks..."
if [[ "$DRY_RUN" != "true" ]]; then
    docker-compose run --rm wpcli rewrite flush
    success "Permalinks atualizados"
else
    success "[DRY-RUN] Permalinks seriam atualizados"
fi

# 9. Informações finais
echo
success "=========================================="
success "SINCRONIZAÇÃO CONCLUÍDA COM SUCESSO!"
success "=========================================="
echo
log "Site local disponível em: ${LOCAL_URL}"
log "Backup local anterior salvo em: ${BACKUP_DIR}/${LOCAL_BACKUP_FILE}"
log "Backup remoto baixado em: ${BACKUP_DIR}/${BACKUP_FILE}"
echo
warning "IMPORTANTE:"
warning "- Verifique se o site está funcionando corretamente"
warning "- Faça login com as credenciais do ambiente remoto"
warning "- Uploads de mídia não são sincronizados automaticamente"
echo
log "Para sincronizar uploads, execute:"
log "rsync -avz ${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_PATH}/wp-content/uploads/ ./wp-content/uploads/"