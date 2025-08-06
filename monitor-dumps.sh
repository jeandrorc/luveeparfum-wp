#!/bin/bash

# =============================================================================
# MONITOR DE DUMPS HORÁRIOS
# Mostra status e estatísticas dos backups automáticos
# =============================================================================

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

BACKUP_DIR="./database-backups"

echo "📊 MONITOR DE DUMPS HORÁRIOS"
echo "============================"
echo

# Status do cron
echo "⏰ STATUS DO AGENDAMENTO:"
echo "========================"
if crontab -l 2>/dev/null | grep -q "sync-hourly.sh"; then
    success "Sincronização automática: ATIVA"
    echo "📅 Configuração:"
    crontab -l 2>/dev/null | grep "sync-hourly.sh" | sed 's/^/    /'
    
    # Próxima execução
    NEXT_HOUR=$(date -d '+1 hour' -d "$(date +%Y-%m-%d\ %H:00:00)" +'%Y-%m-%d %H:%M:%S')
    echo "📍 Próxima execução: $NEXT_HOUR"
else
    warning "Sincronização automática: INATIVA"
    echo "    Para ativar: ./setup-hourly-sync.sh"
fi

echo
echo "📂 BACKUPS DISPONÍVEIS:"
echo "======================"

if ls "$BACKUP_DIR"/hourly-dump-*.sql >/dev/null 2>&1; then
    BACKUP_COUNT=$(ls -1 "$BACKUP_DIR"/hourly-dump-*.sql 2>/dev/null | wc -l | tr -d ' ')
    TOTAL_SIZE=$(du -sh "$BACKUP_DIR" 2>/dev/null | cut -f1)
    
    success "$BACKUP_COUNT backups encontrados (Tamanho total: $TOTAL_SIZE)"
    echo
    
    echo "📋 Últimos 10 backups:"
    echo "----------------------"
    ls -lt "$BACKUP_DIR"/hourly-dump-*.sql 2>/dev/null | head -10 | while read line; do
        file=$(echo "$line" | awk '{print $9}')
        size=$(echo "$line" | awk '{print $5}')
        date_time=$(echo "$line" | awk '{print $6, $7, $8}')
        if [[ -n "$file" ]]; then
            basename_file=$(basename "$file")
            echo "  📄 $basename_file ($size bytes) - $date_time"
        fi
    done
    
    echo
    echo "📈 ESTATÍSTICAS:"
    echo "==============="
    
    # Backup mais recente
    LATEST=$(ls -t "$BACKUP_DIR"/hourly-dump-*.sql 2>/dev/null | head -1)
    if [[ -n "$LATEST" ]]; then
        LATEST_SIZE=$(du -h "$LATEST" | cut -f1)
        LATEST_TIME=$(stat -f "%Sm" -t "%Y-%m-%d %H:%M:%S" "$LATEST" 2>/dev/null || stat -c "%y" "$LATEST" 2>/dev/null | cut -d'.' -f1)
        success "Último backup: $(basename "$LATEST") ($LATEST_SIZE) - $LATEST_TIME"
        
        # Verificar se é recente (menos de 2 horas)
        if [[ $(find "$LATEST" -mmin -120 2>/dev/null) ]]; then
            success "Status: Atualizado (menos de 2 horas)"
        else
            warning "Status: Pode estar desatualizado (mais de 2 horas)"
        fi
    fi
    
    # Backup mais antigo
    OLDEST=$(ls -t "$BACKUP_DIR"/hourly-dump-*.sql 2>/dev/null | tail -1)
    if [[ -n "$OLDEST" && "$OLDEST" != "$LATEST" ]]; then
        OLDEST_TIME=$(stat -f "%Sm" -t "%Y-%m-%d %H:%M:%S" "$OLDEST" 2>/dev/null || stat -c "%y" "$OLDEST" 2>/dev/null | cut -d'.' -f1)
        echo "  📅 Mais antigo: $(basename "$OLDEST") - $OLDEST_TIME"
    fi
    
    # Média de tamanho
    if [[ "$BACKUP_COUNT" -gt 1 ]]; then
        AVG_SIZE=$(ls -l "$BACKUP_DIR"/hourly-dump-*.sql 2>/dev/null | awk 'NR>1 {sum+=$5; count++} END {if(count>0) printf "%.1f MB", sum/count/1024/1024}')
        echo "  📊 Tamanho médio: $AVG_SIZE"
    fi
    
else
    warning "Nenhum backup horário encontrado"
    echo "    Para criar: ./sync-hourly.sh"
fi

echo
echo "📝 LOGS RECENTES:"
echo "================"

# Log do cron
if [[ -f "$BACKUP_DIR/cron.log" ]]; then
    echo "🤖 Log do cron (últimas 5 linhas):"
    tail -5 "$BACKUP_DIR/cron.log" 2>/dev/null | sed 's/^/    /' || echo "    Log vazio"
else
    echo "    Nenhum log do cron encontrado"
fi

echo

# Log de sincronização
if [[ -f "$BACKUP_DIR/sync.log" ]]; then
    echo "🔄 Log de sincronização (últimas 5 linhas):"
    tail -5 "$BACKUP_DIR/sync.log" 2>/dev/null | sed 's/^/    /' || echo "    Log vazio"
else
    echo "    Nenhum log de sincronização encontrado"
fi

echo
echo "🔧 COMANDOS ÚTEIS:"
echo "================="
echo "  📥 Importar último dump:    ./import-latest.sh"
echo "  ⚙️  Configurar automação:    ./setup-hourly-sync.sh"
echo "  🔄 Dump manual:             ./sync-hourly.sh"
echo "  📊 Ver este monitor:        ./monitor-dumps.sh"
echo "  🗑️  Limpar backups antigos: find $BACKUP_DIR -name 'hourly-dump-*.sql' -mtime +7 -delete"