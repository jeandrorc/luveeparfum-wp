#!/bin/bash

# Upload simples do tema Astra Child
# Uso: ./upload-simple.sh

echo "📤 Fazendo upload do tema Astra Child..."

# Upload direto
scp -r wp-content/themes/astra-child luvee-hostgator:~/public_html/wp-content/themes/

if [ $? -eq 0 ]; then
    echo "✅ Upload concluído!"
    echo "🎉 Tema enviado para o servidor"
else
    echo "❌ Erro no upload"
fi 