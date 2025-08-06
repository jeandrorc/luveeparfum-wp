#!/bin/bash

# =============================================================================
# SETUP DO BANCO DE DADOS LOCAL
# Configura e inicializa o banco de dados MySQL local no Docker
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

echo "🚀 SETUP DO BANCO DE DADOS LOCAL - WordPress Docker"
echo "=================================================="
echo

# 1. Verificar se o Docker está rodando
log "Verificando Docker..."
if ! docker ps > /dev/null 2>&1; then
    error "Docker não está rodando ou não há permissão para acessá-lo"
fi
success "Docker está rodando"

# 2. Verificar/criar arquivo .env
log "Verificando configuração..."
if [[ ! -f ".env" ]]; then
    warning "Arquivo .env não encontrado. Criando a partir do exemplo..."
    if [[ -f ".env.example" ]]; then
        cp .env.example .env
        success "Arquivo .env criado a partir de .env.example"
        warning "IMPORTANTE: Edite o arquivo .env com suas configurações se necessário"
    else
        error "Arquivo .env.example não encontrado!"
    fi
else
    success "Arquivo .env encontrado"
fi

# 3. Verificar se há containers rodando
log "Verificando containers existentes..."
if docker ps --format "table {{.Names}}" | grep -q "mysql-local-luvee\|wordpress-local-luvee"; then
    warning "Containers já estão rodando. Parando para recriar..."
    docker-compose down
fi

# 4. Criar diretório de backups se não existir
mkdir -p database-backups
success "Diretório database-backups criado/verificado"

# 5. Iniciar serviços
log "Iniciando serviços MySQL e WordPress..."
docker-compose up -d mysql

# Aguardar o MySQL estar pronto
log "Aguardando MySQL inicializar..."
timeout=60
counter=0
while ! docker-compose exec -T mysql mysqladmin ping -h"localhost" --silent; do
    sleep 2
    counter=$((counter + 2))
    if [ $counter -gt $timeout ]; then
        error "Timeout aguardando MySQL inicializar"
    fi
    echo -n "."
done
echo
success "MySQL está pronto!"

# Iniciar WordPress
log "Iniciando WordPress..."
docker-compose up -d wordpress
sleep 5

# 6. Verificar se WordPress consegue conectar no banco
log "Verificando conectividade WordPress -> MySQL..."
if docker-compose run --rm wpcli db check; then
    success "Conectividade com banco verificada!"
else
    error "Falha na conectividade com o banco"
fi

# 7. Verificar se o banco está vazio e oferecer instalação
log "Verificando status do WordPress..."
if ! docker-compose run --rm wpcli core is-installed 2>/dev/null; then
    warning "WordPress não está instalado"
    echo
    read -p "Deseja fazer a instalação inicial do WordPress? (y/n): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        echo
        echo "📝 Configuração da instalação inicial:"
        read -p "Título do site: " site_title
        read -p "Usuário admin: " admin_user
        read -s -p "Senha admin: " admin_password
        echo
        read -p "Email admin: " admin_email
        
        log "Instalando WordPress..."
        docker-compose run --rm wpcli core install \
            --url="http://localhost:8000" \
            --title="$site_title" \
            --admin_user="$admin_user" \
            --admin_password="$admin_password" \
            --admin_email="$admin_email"
        
        success "WordPress instalado com sucesso!"
    fi
else
    success "WordPress já está instalado"
fi

# 8. Informações finais
echo
success "=========================================="
success "BANCO DE DADOS LOCAL CONFIGURADO!"
success "=========================================="
echo
log "Serviços disponíveis:"
log "  📱 WordPress: http://localhost:8000"
log "  🗄️  MySQL: localhost:3306"
echo
log "Credenciais do banco de dados:"
log "  Host: mysql (interno) / localhost:3306 (externo)"
log "  Banco: wordpress_local"
log "  Usuário: wordpress"
log "  Senha: wordpress_password"
log "  Root: root_password"
echo
log "Comandos úteis:"
log "  docker-compose up -d          # Iniciar serviços"
log "  docker-compose down           # Parar serviços"
log "  docker-compose logs mysql     # Ver logs do MySQL"
log "  docker-compose logs wordpress # Ver logs do WordPress"
echo
warning "Para sincronizar com dados remotos, use os scripts:"
warning "  ./sync-simple.sh              # Sincronizar banco"
warning "  ./sync-uploads.sh [options]   # Sincronizar uploads"