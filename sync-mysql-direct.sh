#!/bin/bash

# =============================================================================
# SINCRONIZAÇÃO DIRETA VIA MySQL
# Conecta diretamente no banco remoto e sincroniza com o local
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

echo "🔄 SINCRONIZAÇÃO DIRETA VIA MySQL"
echo "================================="
echo

# Função para mostrar ajuda
show_help() {
    cat << EOF
SINCRONIZAÇÃO DIRETA MySQL - WordPress

USO:
    ./sync-mysql-direct.sh [OPÇÕES]

OPÇÕES:
    --remote-host HOST          Host do MySQL remoto (obrigatório)
    --remote-port PORT          Porta do MySQL remoto (padrão: 3306)
    --remote-user USER          Usuário do MySQL remoto (obrigatório)
    --remote-password PASS      Senha do MySQL remoto (obrigatório)
    --remote-database DB        Nome do banco remoto (obrigatório)
    --remote-url URL            URL do site remoto (obrigatório)
    --local-url URL             URL local (padrão: http://localhost:8000)
    --backup-dir DIR            Diretório para backups (padrão: ./database-backups)
    --dry-run                   Simula a sincronização sem aplicar
    -h, --help                  Mostra esta ajuda

EXEMPLOS:
    # Sincronização básica
    ./sync-mysql-direct.sh \\
        --remote-host db.luvee.com.br \\
        --remote-user wordpress \\
        --remote-password senha123 \\
        --remote-database luvee_db \\
        --remote-url https://luvee.com.br

    # Com configurações customizadas
    ./sync-mysql-direct.sh \\
        --remote-host 192.168.1.100 \\
        --remote-port 3307 \\
        --remote-user wp_user \\
        --remote-password minhasenha \\
        --remote-database wordpress \\
        --remote-url https://luvee.com.br \\
        --local-url http://localhost:8080

EOF
}

# Configurações padrão
REMOTE_PORT=3306
LOCAL_URL="http://localhost:8000"
BACKUP_DIR="./database-backups"

# Parse dos argumentos
while [[ $# -gt 0 ]]; do
    case $1 in
        --remote-host)
            REMOTE_HOST="$2"
            shift 2
            ;;
        --remote-port)
            REMOTE_PORT="$2"
            shift 2
            ;;
        --remote-user)
            REMOTE_USER="$2"
            shift 2
            ;;
        --remote-password)
            REMOTE_PASSWORD="$2"
            shift 2
            ;;
        --remote-database)
            REMOTE_DATABASE="$2"
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

# Se não foram passados parâmetros, solicitar interativamente
if [[ -z "$REMOTE_HOST" || -z "$REMOTE_USER" || -z "$REMOTE_PASSWORD" || -z "$REMOTE_DATABASE" || -z "$REMOTE_URL" ]]; then
    echo "📝 Configure as credenciais do banco remoto:"
    echo
    
    if [[ -z "$REMOTE_HOST" ]]; then
        read -p "Host do MySQL remoto (ex: db.luvee.com.br): " REMOTE_HOST
    fi
    
    if [[ -z "$REMOTE_PORT" || "$REMOTE_PORT" == "3306" ]]; then
        read -p "Porta do MySQL remoto (padrão 3306): " input_port
        REMOTE_PORT=${input_port:-3306}
    fi
    
    if [[ -z "$REMOTE_USER" ]]; then
        read -p "Usuário do MySQL remoto: " REMOTE_USER
    fi
    
    if [[ -z "$REMOTE_PASSWORD" ]]; then
        read -s -p "Senha do MySQL remoto: " REMOTE_PASSWORD
        echo
    fi
    
    if [[ -z "$REMOTE_DATABASE" ]]; then
        read -p "Nome do banco de dados remoto: " REMOTE_DATABASE
    fi
    
    if [[ -z "$REMOTE_URL" ]]; then
        read -p "URL do site remoto (ex: https://luvee.com.br): " REMOTE_URL
    fi
fi

# Validar parâmetros obrigatórios
if [[ -z "$REMOTE_HOST" || -z "$REMOTE_USER" || -z "$REMOTE_PASSWORD" || -z "$REMOTE_DATABASE" || -z "$REMOTE_URL" ]]; then
    error "Parâmetros obrigatórios faltando. Use --help para ver a sintaxe."
fi

log "Configurações da sincronização:"
log "  Host remoto: ${REMOTE_HOST}:${REMOTE_PORT}"
log "  Banco remoto: ${REMOTE_DATABASE}"
log "  Usuário: ${REMOTE_USER}"
log "  URL remota: ${REMOTE_URL}"
log "  URL local: ${LOCAL_URL}"

if [[ "$DRY_RUN" == "true" ]]; then
    warning "MODO DRY-RUN: Apenas simulação"
fi

# Configurar PATH para mysql-client (se instalado via Homebrew)
if [[ -d "/usr/local/opt/mysql-client/bin" ]]; then
    export PATH="/usr/local/opt/mysql-client/bin:$PATH"
fi

# Verificar se mysql client está disponível
if ! command -v mysql &> /dev/null; then
    error "Cliente MySQL não encontrado. Instale com: brew install mysql-client (macOS)"
fi

if ! command -v mysqldump &> /dev/null; then
    error "mysqldump não encontrado. Instale com: brew install mysql-client (macOS)"
fi

# Verificar conectividade com banco remoto
log "Testando conectividade com banco remoto..."
if mysql -h"$REMOTE_HOST" -P"$REMOTE_PORT" -u"$REMOTE_USER" -p"$REMOTE_PASSWORD" -e "SELECT 1" "$REMOTE_DATABASE" &>/dev/null; then
    success "Conectividade com banco remoto verificada!"
else
    error "Falha ao conectar no banco remoto. Verifique as credenciais."
fi

# Verificar se containers locais estão rodando
if ! docker ps --format "table {{.Names}}" | grep -q "mysql-local-luvee\|wordpress-local-luvee"; then
    warning "Containers locais não estão rodando. Iniciando..."
    if [[ "$DRY_RUN" != "true" ]]; then
        docker-compose up -d mysql wordpress
        log "Aguardando containers inicializarem..."
        sleep 15
    fi
fi

# Criar diretório de backups
mkdir -p "$BACKUP_DIR"

# Fazer backup do banco local
log "Fazendo backup do banco local atual..."
LOCAL_BACKUP_FILE="local-backup-$(date +%Y%m%d_%H%M%S).sql"
if [[ "$DRY_RUN" != "true" ]]; then
    docker-compose run --rm wpcli db export "/var/www/html/${BACKUP_DIR}/${LOCAL_BACKUP_FILE}" || true
    success "Backup local salvo: ${BACKUP_DIR}/${LOCAL_BACKUP_FILE}"
fi

# Fazer dump do banco remoto
log "Fazendo dump do banco remoto..."
REMOTE_BACKUP_FILE="remote-backup-$(date +%Y%m%d_%H%M%S).sql"

if [[ "$DRY_RUN" != "true" ]]; then
    mysqldump -h"$REMOTE_HOST" -P"$REMOTE_PORT" -u"$REMOTE_USER" -p"$REMOTE_PASSWORD" \
        --single-transaction \
        --routines \
        --triggers \
        "$REMOTE_DATABASE" > "${BACKUP_DIR}/${REMOTE_BACKUP_FILE}"
    
    success "Dump remoto criado: ${BACKUP_DIR}/${REMOTE_BACKUP_FILE}"
    
    # Verificar tamanho do arquivo
    size=$(du -h "${BACKUP_DIR}/${REMOTE_BACKUP_FILE}" | cut -f1)
    log "Tamanho do backup: $size"
fi

# Confirmar importação
echo
warning "ATENÇÃO: Esta operação irá SUBSTITUIR completamente o banco local!"
warning "Backup local salvo em: ${BACKUP_DIR}/${LOCAL_BACKUP_FILE}"
echo
if [[ "$DRY_RUN" != "true" ]]; then
    read -p "Confirma a importação? (y/n): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        log "Importação cancelada."
        exit 0
    fi
fi

# Importar banco remoto no local
log "Importando banco remoto no ambiente local..."
if [[ "$DRY_RUN" != "true" ]]; then
    # Reset do banco local
    docker-compose run --rm wpcli db reset --yes
    
    # Importar o backup remoto
    docker-compose run --rm wpcli db import "/var/www/html/${BACKUP_DIR}/${REMOTE_BACKUP_FILE}"
    success "Banco importado com sucesso!"
fi

# Atualizar URLs
log "Atualizando URLs do site..."
if [[ "$DRY_RUN" != "true" ]]; then
    docker-compose run --rm wpcli search-replace "$REMOTE_URL" "$LOCAL_URL" --dry-run
    echo
    read -p "Confirma a substituição de URLs? (y/n): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        docker-compose run --rm wpcli search-replace "$REMOTE_URL" "$LOCAL_URL"
        success "URLs atualizadas de $REMOTE_URL para $LOCAL_URL"
    fi
fi

# Limpar cache e permalinks
if [[ "$DRY_RUN" != "true" ]]; then
    log "Limpando cache e atualizando permalinks..."
    docker-compose run --rm wpcli cache flush || true
    docker-compose run --rm wpcli rewrite flush
    success "Cache e permalinks atualizados"
fi

# Verificar resultado
if [[ "$DRY_RUN" != "true" ]]; then
    log "Verificando instalação..."
    if docker-compose run --rm wpcli core is-installed; then
        success "WordPress funcionando corretamente!"
    fi
fi

echo
success "=========================================="
success "SINCRONIZAÇÃO DIRETA CONCLUÍDA!"
success "=========================================="
echo
log "Site local: $LOCAL_URL"
log "Backup local anterior: ${BACKUP_DIR}/${LOCAL_BACKUP_FILE}"
log "Backup remoto baixado: ${BACKUP_DIR}/${REMOTE_BACKUP_FILE}"
echo
warning "PRÓXIMOS PASSOS:"
warning "- Acesse http://localhost:8000 para verificar o site"
warning "- Use as credenciais do ambiente remoto para login"
warning "- Para sincronizar uploads: ./sync-uploads.sh (quando SSH disponível)"