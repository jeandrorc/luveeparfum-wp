# WordPress com Banco Local Docker

Este projeto agora inclui um banco de dados MySQL local usando Docker Compose.

## 🚀 Setup Inicial

### 1. Configuração rápida (Recomendada)
```bash
# Executa setup automático do banco local
./setup-local-db.sh
```

### 2. Configuração manual

**Passo 1: Verificar configurações**
```bash
# O arquivo .env já foi criado com configurações padrão
cat .env
```

**Passo 2: Iniciar serviços**
```bash
# Iniciar MySQL e WordPress
docker-compose up -d

# Verificar status
docker-compose ps
```

**Passo 3: Aguardar MySQL inicializar**
```bash
# Verificar se MySQL está pronto
docker-compose logs mysql

# Testar conectividade
docker-compose run --rm wpcli db check
```

## 🗄️ Configuração do Banco

### Credenciais padrão:
- **Host:** `mysql` (interno) / `localhost:3306` (externo)
- **Banco:** `wordpress_local`
- **Usuário:** `wordpress`
- **Senha:** `wordpress_password`
- **Root:** `root_password`

### Estrutura dos serviços:
```yaml
mysql:       # Banco de dados
wordpress:   # Aplicação WordPress
wpcli:       # WordPress CLI
```

## 📝 Comandos Úteis

### Docker Compose
```bash
# Iniciar todos os serviços
docker-compose up -d

# Parar todos os serviços
docker-compose down

# Ver logs
docker-compose logs mysql
docker-compose logs wordpress

# Reiniciar um serviço específico
docker-compose restart mysql
```

### WordPress CLI
```bash
# Verificar status da instalação
docker-compose run --rm wpcli core is-installed

# Instalar WordPress (se necessário)
docker-compose run --rm wpcli core install \
  --url="http://localhost:8000" \
  --title="Meu Site Local" \
  --admin_user="admin" \
  --admin_password="admin123" \
  --admin_email="admin@localhost.local"

# Verificar conectividade com banco
docker-compose run --rm wpcli db check

# Listar tabelas
docker-compose run --rm wpcli db tables
```

### MySQL Direto
```bash
# Conectar no MySQL como root
docker-compose exec mysql mysql -u root -p

# Conectar com usuário WordPress
docker-compose exec mysql mysql -u wordpress -p wordpress_local

# Backup do banco
docker-compose exec mysql mysqldump -u root -p wordpress_local > backup.sql

# Importar banco
docker-compose exec -T mysql mysql -u root -p wordpress_local < backup.sql
```

## 🔄 Sincronização com Remoto

### Sincronizar banco de dados
```bash
# Configurar credenciais remotas
cp sync-config.example .env.sync
# Editar .env.sync com suas configurações

# Sincronizar (substitui banco local)
./sync-simple.sh
```

### Sincronizar uploads
```bash
# Sincronizar mídia do remoto
./sync-uploads.sh \
    --remote-host servidor.com \
    --remote-user usuario \
    --remote-path /var/www/html
```

## 🔧 Resolução de Problemas

### MySQL não inicializa
```bash
# Ver logs detalhados
docker-compose logs mysql

# Recriar volume (PERDA DE DADOS!)
docker-compose down -v
docker-compose up -d
```

### WordPress não conecta no banco
```bash
# Verificar se MySQL está rodando
docker-compose ps mysql

# Testar conectividade
docker-compose run --rm wpcli db check

# Verificar variáveis de ambiente
docker-compose exec wordpress env | grep WORDPRESS_DB
```

### Erro de permissão
```bash
# Ajustar permissões dos arquivos
sudo chown -R $USER:$USER .
chmod -R 755 wp-content
```

### Resetar banco local
```bash
# Parar containers
docker-compose down

# Remover volume do MySQL (PERDA DE DADOS!)
docker volume rm wordpress_mysql_data

# Recriar
docker-compose up -d
```

## 📦 Volumes e Persistência

- **`mysql_data`** - Dados do MySQL (persistente)
- **`./database-backups`** - Backups de banco (mapeado)
- **`./wp-content`** - Conteúdo WordPress (mapeado)

## ⚠️ Importante

- **Dados persistem** entre reinicializações do Docker
- **Backups automáticos** são salvos em `./database-backups/`
- **Porta 3306** fica exposta para acesso externo
- **Logs** disponíveis via `docker-compose logs`

## 🌐 URLs de Acesso

- **WordPress:** http://localhost:8000
- **MySQL:** localhost:3306
- **phpMyAdmin** (se adicionado): http://localhost:8080