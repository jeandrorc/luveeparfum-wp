#!/bin/bash

echo "🔄 IMPORTANDO BACKUP COMPLETO"
echo ""

# Usar as credenciais do ambiente
DB_NAME=${WORDPRESS_DB_NAME:-"jeandr00_staging"}
DB_USER=${WORDPRESS_DB_USER:-"jeandr00_staging"}  
DB_PASSWORD=${WORDPRESS_DB_PASSWORD:-"J@8AZXqhU6r3vun"}
DB_HOST=${WORDPRESS_DB_HOST:-"108.167.168.18"}

BACKUP_FILE="./database-backups/hourly-dump-20250804_165855.sql"

echo "📋 Configuração:"
echo "  - Host: $DB_HOST"
echo "  - Database: $DB_NAME"
echo "  - Backup: $BACKUP_FILE"
echo "  - Tamanho: $(ls -lh $BACKUP_FILE | awk '{print $5}')"
echo ""

# Testar conexão primeiro
echo "🔍 Testando conexão..."
mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASSWORD" -e "SELECT 1;" "$DB_NAME" 2>/dev/null
if [ $? -eq 0 ]; then
    echo "✅ Conexão OK"
else
    echo "❌ Erro de conexão"
    exit 1
fi

# Fazer backup das tabelas atuais antes de sobrescrever
echo ""
echo "💾 Fazendo backup das tabelas atuais..."
mysqldump -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" > "./current-tables-backup-$(date +%Y%m%d_%H%M%S).sql" 2>/dev/null
if [ $? -eq 0 ]; then
    echo "✅ Backup atual salvo"
else
    echo "⚠️  Não foi possível fazer backup atual"
fi

# Limpar banco atual
echo ""
echo "🧹 Limpando banco atual..."
mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -e "
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS wp_commentmeta, wp_comments, wp_links, wp_options, wp_postmeta, wp_posts, wp_terms, wp_term_relationships, wp_term_taxonomy, wp_usermeta, wp_users;
SET FOREIGN_KEY_CHECKS = 1;
" 2>/dev/null

echo "✅ Tabelas removidas"

# Importar backup completo
echo ""
echo "📥 Importando backup completo..."
mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" < "$BACKUP_FILE"
if [ $? -eq 0 ]; then
    echo "✅ Backup importado com sucesso!"
else
    echo "❌ Erro na importação"
    exit 1
fi

# Forçar tema Luvee
echo ""
echo "🎨 Ativando tema Luvee..."
mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" -e "
UPDATE wp_options SET option_value = 'luvee-theme' WHERE option_name = 'stylesheet';
UPDATE wp_options SET option_value = 'luvee-theme' WHERE option_name = 'template';
UPDATE wp_options SET option_value = 'http://localhost:8000' WHERE option_name = 'home';
UPDATE wp_options SET option_value = 'http://localhost:8000' WHERE option_name = 'siteurl';
" 2>/dev/null

echo "✅ Tema Luvee ativado"

echo ""
echo "🎉 IMPORTAÇÃO CONCLUÍDA!"
echo "🔄 Teste: http://localhost:8000/"