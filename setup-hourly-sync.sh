#!/bin/bash

# =============================================================================
# CONFIGURAR SINCRONIZAÇÃO AUTOMÁTICA DE HORA EM HORA
# Configura cron job para executar dumps automáticos
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

echo "⏰ CONFIGURAÇÃO DE SINCRONIZAÇÃO AUTOMÁTICA"
echo "==========================================="
echo

# Obter caminho absoluto do script
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SYNC_SCRIPT="$SCRIPT_DIR/sync-hourly.sh"

# Verificar se script existe
if [[ ! -f "$SYNC_SCRIPT" ]]; then
    error "Script sync-hourly.sh não encontrado!"
fi

echo "📝 Opções de configuração:"
echo "1. Apenas dumps (não importa automaticamente)"
echo "2. Dumps + importação automática"
echo "3. Remover sincronização automática"
echo "4. Ver status atual"
echo

read -p "Escolha uma opção (1-4): " choice

case $choice in
    1)
        CRON_COMMAND="0 * * * * cd $SCRIPT_DIR && ./sync-hourly.sh >> database-backups/cron.log 2>&1"
        ACTION="dump apenas"
        ;;
    2)
        CRON_COMMAND="0 * * * * cd $SCRIPT_DIR && ./sync-hourly.sh --import >> database-backups/cron.log 2>&1"
        ACTION="dump + importação"
        ;;
    3)
        log "Removendo cron jobs existentes..."
        crontab -l 2>/dev/null | grep -v "sync-hourly.sh" | crontab - 2>/dev/null || true
        success "Sincronização automática removida"
        exit 0
        ;;
    4)
        echo "📊 STATUS ATUAL:"
        echo "==============="
        
        if crontab -l 2>/dev/null | grep -q "sync-hourly.sh"; then
            echo "✅ Sincronização automática: ATIVA"
            echo "📅 Configuração atual:"
            crontab -l 2>/dev/null | grep "sync-hourly.sh"
        else
            echo "❌ Sincronização automática: INATIVA"
        fi
        
        echo
        echo "📂 Backups recentes:"
        ls -la database-backups/hourly-dump-*.sql 2>/dev/null | tail -5 || echo "Nenhum backup horário encontrado"
        
        echo
        echo "📝 Log recente:"
        tail -5 database-backups/sync.log 2>/dev/null || echo "Nenhum log encontrado"
        
        exit 0
        ;;
    *)
        error "Opção inválida!"
        ;;
esac

log "Configurando sincronização automática: $ACTION"

# Criar diretório de backups
mkdir -p database-backups

# Remover cron jobs existentes do sync-hourly
crontab -l 2>/dev/null | grep -v "sync-hourly.sh" | crontab - 2>/dev/null || true

# Adicionar novo cron job
(crontab -l 2>/dev/null; echo "$CRON_COMMAND") | crontab -

success "Cron job configurado com sucesso!"

echo
log "Configuração aplicada:"
echo "  Frequência: A cada hora (no minuto 0)"
echo "  Script: $SYNC_SCRIPT"
echo "  Ação: $ACTION"
echo "  Log: database-backups/cron.log"

echo
log "Testando execução manual..."
cd "$SCRIPT_DIR"

if [[ "$choice" == "2" ]]; then
    ./sync-hourly.sh --import
else
    ./sync-hourly.sh
fi

echo
success "=========================================="
success "SINCRONIZAÇÃO AUTOMÁTICA CONFIGURADA!"
success "=========================================="
echo
warning "IMPORTANTE:"
warning "- Dumps serão criados a cada hora no minuto 0"
warning "- Logs em: database-backups/cron.log"
warning "- Backups mantidos por 7 dias"
warning "- Para parar: execute este script e escolha opção 3"
echo
log "Próxima execução: $(date -d '+1 hour' -d "$(date +%Y-%m-%d\ %H:00:00)" +'%Y-%m-%d %H:%M:%S')"