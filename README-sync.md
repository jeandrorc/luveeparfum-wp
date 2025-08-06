# Sincronização de Banco de Dados

Scripts para sincronizar o banco de dados remoto com o ambiente local Docker.

## 📋 Pré-requisitos

- Docker e docker-compose instalados
- Acesso SSH ao servidor remoto
- WP-CLI instalado no servidor remoto
- Container WordPress local configurado

## 🚀 Como usar

### Opção 1: Script Simples (Recomendado)

1. **Configure suas credenciais:**
   ```bash
   cp sync-config.example .env.sync
   # Edite o arquivo .env.sync com suas configurações
   ```

2. **Execute a sincronização:**
   ```bash
   ./sync-simple.sh
   ```

### Opção 2: Script Completo com Parâmetros

```bash
./sync-database.sh \
    --remote-host luvee.com.br \
    --remote-user ubuntu \
    --remote-path /var/www/html \
    --remote-url https://luvee.com.br
```

## 📁 Arquivos criados

- `sync-database.sh` - Script principal com todas as opções
- `sync-simple.sh` - Script simplificado que usa arquivo de configuração
- `sync-uploads.sh` - Script para sincronizar uploads de mídia
- `sync-config.example` - Exemplo de arquivo de configuração
- `database-backups/` - Diretório onde os backups são salvos

## ⚙️ Opções do script principal

```
--remote-host HOST          Host do servidor remoto (obrigatório)
--remote-user USER          Usuário SSH do servidor remoto (obrigatório)  
--remote-path PATH          Caminho do WordPress no servidor remoto (obrigatório)
--remote-url URL            URL do site remoto (obrigatório)
--local-url URL             URL local (padrão: http://localhost:8000)
--backup-dir DIR            Diretório para backups (padrão: ./database-backups)
--skip-search-replace       Pula a substituição de URLs
--dry-run                   Executa sem fazer alterações reais
-h, --help                  Mostra ajuda
```

## 🔄 O que o script faz

1. **Backup remoto** - Cria backup do banco no servidor remoto
2. **Download** - Baixa o backup para o ambiente local
3. **Backup local** - Faz backup do banco local atual (segurança)
4. **Importação** - Importa o banco remoto no ambiente local
5. **URLs** - Atualiza URLs do site para o ambiente local
6. **Cache** - Limpa cache e atualiza permalinks

## 🗂️ Sincronização de uploads

### Usando o script dedicado (Recomendado)

```bash
# Teste primeiro com dry-run
./sync-uploads.sh \
    --remote-host luvee.com.br \
    --remote-user ubuntu \
    --remote-path /var/www/html \
    --dry-run

# Sincronização real
./sync-uploads.sh \
    --remote-host luvee.com.br \
    --remote-user ubuntu \
    --remote-path /var/www/html
```

### Usando rsync diretamente

```bash
# Sincronizar uploads do remoto para local
rsync -avz usuario@servidor:/caminho/wp-content/uploads/ ./wp-content/uploads/
```

## ⚠️ Importante

- **SEMPRE** faça backup antes de sincronizar
- O banco local será **completamente substituído**
- Verifique as URLs após a sincronização
- Use credenciais do ambiente remoto para fazer login
- Teste em ambiente de desenvolvimento primeiro

## 🛠️ Resolução de problemas

### Container não está rodando
```bash
docker-compose up -d wordpress
```

### Erro de conexão SSH
Verifique:
- Credenciais SSH
- Acesso ao servidor
- Caminho do WordPress no servidor

### Erro de permissão
```bash
chmod +x sync-database.sh sync-simple.sh
```

### URLs não atualizadas
Execute manualmente:
```bash
docker-compose run --rm wpcli search-replace "https://site-remoto.com" "http://localhost:8000"
```