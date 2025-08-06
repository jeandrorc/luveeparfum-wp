#!/bin/bash

# =============================================================================
# TESTE DE CONECTIVIDADE SSH
# Script para testar diferentes configurações de SSH
# =============================================================================

set -e

echo "🔍 TESTE DE CONECTIVIDADE SSH"
echo "============================="
echo

# Função para testar conexão SSH
test_ssh() {
    local host=$1
    local user=$2
    local port=${3:-22}
    local key=${4:-""}
    
    echo "🔄 Testando: ${user}@${host}:${port}"
    
    if [[ -n "$key" ]]; then
        ssh_cmd="ssh -i $key -o ConnectTimeout=10 -p $port $user@$host"
    else
        ssh_cmd="ssh -o ConnectTimeout=10 -p $port $user@$host"
    fi
    
    if $ssh_cmd "echo '✅ Conexão funcionando!' && pwd" 2>/dev/null; then
        echo "✅ SUCESSO: Conexão estabelecida!"
        echo "   Comando: $ssh_cmd"
        return 0
    else
        echo "❌ FALHOU: Não foi possível conectar"
        return 1
    fi
    echo
}

echo "📝 Testando configurações comuns..."
echo

# Configurações para testar
configs=(
    "luvee.com.br:ubuntu:22"
    "luvee.com.br:root:22"
    "luvee.com.br:www-data:22"
    "luvee.com.br:ubuntu:2222"
    "luvee.com.br:ubuntu:2200"
)

for config in "${configs[@]}"; do
    IFS=':' read -r host user port <<< "$config"
    test_ssh "$host" "$user" "$port" || true
done

# Testar com chave SSH se existir
if [[ -f "deploy_key" ]]; then
    echo "🔑 Testando com chave SSH encontrada (deploy_key)..."
    test_ssh "luvee.com.br" "ubuntu" "22" "deploy_key" || true
fi

echo
echo "📋 COMANDOS PARA TESTAR MANUALMENTE:"
echo "=================================="
echo "# Teste básico:"
echo "ssh ubuntu@luvee.com.br"
echo
echo "# Com porta específica:"
echo "ssh -p 2222 ubuntu@luvee.com.br"
echo
echo "# Com chave SSH:"
echo "ssh -i deploy_key ubuntu@luvee.com.br"
echo
echo "# Verificar se o site responde:"
echo "curl -I https://luvee.com.br"
echo