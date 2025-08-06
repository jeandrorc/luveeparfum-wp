#!/bin/bash

# =============================================================================
# SCRIPT SIMPLES DE SINCRONIZAÇÃO
# Versão simplificada que usa arquivo de configuração
# =============================================================================

set -e

# Carregar configurações do arquivo .env.sync se existir
if [[ -f ".env.sync" ]]; then
    source .env.sync
    echo "✓ Configurações carregadas de .env.sync"
else
    echo "⚠ Arquivo .env.sync não encontrado. Usando configurações padrão."
    echo "  Copie .env.sync.example para .env.sync e ajuste suas configurações."
    
    # Configurações padrão (AJUSTE AQUI)
    REMOTE_HOST="luvee.com.br"
    REMOTE_USER="ubuntu" 
    REMOTE_PATH="/var/www/html"
    REMOTE_URL="https://luvee.com.br"
    LOCAL_URL="http://localhost:8000"
    BACKUP_DIR="./database-backups"
fi

echo "🚀 Iniciando sincronização com configurações:"
echo "   Remoto: ${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_PATH}"
echo "   URL: ${REMOTE_URL} → ${LOCAL_URL}"
echo

# Confirmar antes de continuar
read -p "Confirma a sincronização? Isso irá SUBSTITUIR o banco local! (y/n): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Sincronização cancelada."
    exit 0
fi

# Executar o script principal com as configurações
exec ./sync-database.sh \
    --remote-host "$REMOTE_HOST" \
    --remote-user "$REMOTE_USER" \
    --remote-path "$REMOTE_PATH" \
    --remote-url "$REMOTE_URL" \
    --local-url "$LOCAL_URL" \
    --backup-dir "$BACKUP_DIR"