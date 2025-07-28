#!/bin/bash

# Script para upload do tema Astra Child para o servidor
# Uso: ./upload-theme.sh

echo "🚀 Iniciando upload do tema Astra Child..."

# Configurações
LOCAL_THEME_DIR="wp-content/themes/astra-child"
REMOTE_HOST="luvee-hostgator"
REMOTE_THEME_DIR="~/public_html/wp-content/themes/astra-child"

# Verificar se o diretório existe
if [ ! -d "$LOCAL_THEME_DIR" ]; then
    echo "❌ Erro: Diretório do tema não encontrado: $LOCAL_THEME_DIR"
    exit 1
fi

echo "📁 Diretório local: $LOCAL_THEME_DIR"
echo "🌐 Servidor: $REMOTE_HOST"
echo "📂 Diretório remoto: $REMOTE_THEME_DIR"

# Criar backup do tema atual no servidor (se existir)
echo "💾 Criando backup do tema atual..."
ssh $REMOTE_HOST "if [ -d $REMOTE_THEME_DIR ]; then cp -r $REMOTE_THEME_DIR ${REMOTE_THEME_DIR}_backup_$(date +%Y%m%d_%H%M%S); fi"

# Upload dos arquivos
echo "📤 Fazendo upload dos arquivos..."
scp -r $LOCAL_THEME_DIR $REMOTE_HOST:$REMOTE_THEME_DIR

if [ $? -eq 0 ]; then
    echo "✅ Upload concluído com sucesso!"
    echo "🎉 Tema Astra Child foi enviado para o servidor"
    echo ""
    echo "📋 Próximos passos:"
    echo "1. Acesse o WordPress Admin"
    echo "2. Vá em Aparência > Temas"
    echo "3. Ative o tema 'Astra Child - Ecommerce'"
    echo "4. Teste os widgets no Elementor"
    echo ""
    echo "🔗 Para verificar: ssh $REMOTE_HOST 'ls -la $REMOTE_THEME_DIR'"
else
    echo "❌ Erro no upload. Verifique a conexão SSH."
    exit 1
fi 