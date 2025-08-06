#!/bin/bash

# =============================================================================
# SCRIPT DE SINCRONIZAÇÃO DE UPLOADS
# Sincroniza uploads de mídia do servidor remoto para o local
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

# Função para mostrar ajuda
show_help() {
    cat << EOF
SINCRONIZAÇÃO DE UPLOADS - WordPress

USO:
    ./sync-uploads.sh [OPÇÕES]

OPÇÕES:
    --remote-host HOST          Host do servidor remoto (obrigatório)
    --remote-user USER          Usuário SSH do servidor remoto (obrigatório)
    --remote-path PATH          Caminho do WordPress no servidor remoto (obrigatório)
    --local-path PATH           Caminho local do WordPress (padrão: .)
    --dry-run                   Simula a sincronização sem transferir arquivos
    --delete                    Remove arquivos locais que não existem no remoto
    --progress                  Mostra progresso da transferência
    -h, --help                  Mostra esta ajuda

EXEMPLOS:
    # Sincronização básica
    ./sync-uploads.sh \\
        --remote-host luvee.com.br \\
        --remote-user ubuntu \\
        --remote-path /var/www/html

    # Com dry-run para testar
    ./sync-uploads.sh \\
        --remote-host luvee.com.br \\
        --remote-user ubuntu \\
        --remote-path /var/www/html \\
        --dry-run

    # Sincronização completa (remove arquivos locais não existentes no remoto)
    ./sync-uploads.sh \\
        --remote-host luvee.com.br \\
        --remote-user ubuntu \\
        --remote-path /var/www/html \\
        --delete

PRÉ-REQUISITOS:
    - rsync instalado
    - Acesso SSH ao servidor remoto

EOF
}

# Configurações padrão
LOCAL_PATH="."
RSYNC_OPTIONS="-avz"

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
        --local-path)
            LOCAL_PATH="$2"
            shift 2
            ;;
        --dry-run)
            DRY_RUN=true
            RSYNC_OPTIONS="${RSYNC_OPTIONS} --dry-run"
            shift
            ;;
        --delete)
            RSYNC_OPTIONS="${RSYNC_OPTIONS} --delete"
            shift
            ;;
        --progress)
            RSYNC_OPTIONS="${RSYNC_OPTIONS} --progress"
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
if [[ -z "$REMOTE_HOST" || -z "$REMOTE_USER" || -z "$REMOTE_PATH" ]]; then
    error "Parâmetros obrigatórios faltando. Use --help para ver a sintaxe."
fi

# Carregar configurações do arquivo .env.sync se existir e parâmetros não foram fornecidos
if [[ -f ".env.sync" && -z "$REMOTE_HOST" ]]; then
    source .env.sync
    log "Configurações carregadas de .env.sync"
fi

log "Iniciando sincronização de uploads..."
log "Origem: ${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_PATH}/wp-content/uploads/"
log "Destino: ${LOCAL_PATH}/wp-content/uploads/"

if [[ "$DRY_RUN" == "true" ]]; then
    warning "MODO DRY-RUN: Nenhum arquivo será transferido"
fi

# Verificar se rsync está disponível
if ! command -v rsync &> /dev/null; then
    error "rsync não está instalado. Instale com: brew install rsync (macOS) ou apt install rsync (Ubuntu)"
fi

# Criar diretório de uploads local se não existir
mkdir -p "${LOCAL_PATH}/wp-content/uploads"

# Verificar conectividade SSH
log "Testando conectividade SSH..."
if ! ssh -o ConnectTimeout=10 "${REMOTE_USER}@${REMOTE_HOST}" "test -d ${REMOTE_PATH}/wp-content/uploads" 2>/dev/null; then
    error "Falha ao conectar no servidor remoto ou diretório de uploads não encontrado"
fi

success "Conectividade SSH verificada"

# Mostrar estatísticas antes da sincronização
log "Calculando diferenças..."
REMOTE_SIZE=$(ssh "${REMOTE_USER}@${REMOTE_HOST}" "du -sh ${REMOTE_PATH}/wp-content/uploads 2>/dev/null | cut -f1" || echo "N/A")
LOCAL_SIZE=$(du -sh "${LOCAL_PATH}/wp-content/uploads" 2>/dev/null | cut -f1 || echo "0B")

echo
log "Estatísticas:"
log "  Tamanho remoto: ${REMOTE_SIZE}"
log "  Tamanho local:  ${LOCAL_SIZE}"
echo

# Confirmar antes de continuar (apenas se não for dry-run)
if [[ "$DRY_RUN" != "true" ]]; then
    read -p "Confirma a sincronização? (y/n): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        log "Sincronização cancelada."
        exit 0
    fi
fi

# Executar rsync
log "Iniciando sincronização..."
echo

if rsync $RSYNC_OPTIONS \
    "${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_PATH}/wp-content/uploads/" \
    "${LOCAL_PATH}/wp-content/uploads/"; then
    
    echo
    success "=========================================="
    success "SINCRONIZAÇÃO DE UPLOADS CONCLUÍDA!"
    success "=========================================="
    
    # Mostrar estatísticas após sincronização
    if [[ "$DRY_RUN" != "true" ]]; then
        NEW_LOCAL_SIZE=$(du -sh "${LOCAL_PATH}/wp-content/uploads" 2>/dev/null | cut -f1 || echo "N/A")
        log "Novo tamanho local: ${NEW_LOCAL_SIZE}"
    fi
    
    echo
    warning "LEMBRE-SE:"
    warning "- Verifique se as imagens estão aparecendo corretamente no site"
    warning "- Alguns plugins podem precisar regenerar thumbnails"
    warning "- Execute 'wp media regenerate' se necessário"
    
else
    error "Falha na sincronização"
fi