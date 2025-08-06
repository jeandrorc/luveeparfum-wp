# 🔄 Guia de Sincronização - Luvee WordPress

Este guia oferece diferentes métodos para sincronizar dados do servidor remoto com o ambiente local.

## 🚨 Situação Atual

- ✅ **Ambiente local**: Funcionando (http://localhost:8000)
- ❌ **Conectividade SSH**: Sem acesso direto ao servidor
- ❌ **Site remoto**: Erro SSL/TLS (código 526)

## 📋 Métodos de Sincronização

### Método 1: Backup Manual (Recomendado Agora)

Se você tem acesso ao painel do servidor/hosting:

```bash
# Use este script para importar um backup existente
./import-backup.sh
```

**Como obter o backup:**
1. **Painel do Hosting**: Baixe backup via cPanel, Plesk, etc.
2. **phpMyAdmin**: Exportar banco de dados
3. **WordPress Admin**: Plugin de backup
4. **FTP/SFTP**: Acessar arquivos do servidor

### Método 2: Conexão Direta MySQL (Se disponível)

Se o MySQL remoto aceita conexões externas:

```bash
# Conectar diretamente e fazer dump
mysqldump -h SERVIDOR_MYSQL -u USUARIO -p BANCO > backup-remoto.sql

# Importar no local
./import-backup.sh
# (selecione o arquivo backup-remoto.sql)
```

### Método 3: SSH Quando Disponível

Quando o servidor estiver acessível:

```bash
# Testar conectividade primeiro
./test-connection.sh

# Sincronizar automaticamente
./sync-simple.sh
```

## 🛠️ Resolução de Problemas de Conectividade

### Verificar Status do Servidor

```bash
# Testar diferentes protocolos
curl -I https://luvee.com.br
curl -I http://luvee.com.br
ping luvee.com.br

# Testar portas SSH comuns
nc -zv luvee.com.br 22
nc -zv luvee.com.br 2222
```

### Possíveis Soluções

1. **VPN/Proxy**: Servidor pode estar atrás de VPN
2. **Whitelist IP**: Seu IP pode precisar ser liberado
3. **Porta SSH**: Pode ser diferente da 22
4. **Manutenção**: Servidor pode estar em manutenção
5. **Cloudflare**: CDN pode estar bloqueando

### Configurar Acesso SSH

Se você conseguir acesso SSH mais tarde:

```bash
# Editar configurações
nano .env.sync

# Testar conexão
ssh -i deploy_key usuario@servidor

# Executar sincronização
./sync-simple.sh
```

## 📁 Estrutura dos Scripts

```
sync-database.sh      # Script completo com todas opções
sync-simple.sh        # Versão simplificada
sync-uploads.sh       # Sincronização de mídia
import-backup.sh      # Importação manual (NOVO)
test-connection.sh    # Teste de conectividade
setup-local-db.sh     # Setup inicial do banco local
```

## 🎯 Próximos Passos Recomendados

### Agora (Sem SSH):
1. **Obtenha um backup** do banco via painel do hosting
2. **Execute:** `./import-backup.sh`
3. **Baixe uploads** via FTP se necessário

### Quando SSH estiver disponível:
1. **Teste:** `./test-connection.sh`
2. **Configure:** Editar `.env.sync` se necessário
3. **Sincronize:** `./sync-simple.sh`
4. **Mídia:** `./sync-uploads.sh`

## 🔧 Comandos de Diagnóstico

```bash
# Status dos containers
docker-compose ps

# Logs dos serviços
docker-compose logs mysql
docker-compose logs wordpress

# Testar banco local
docker-compose run --rm wpcli db check

# Ver configurações WordPress
docker-compose run --rm wpcli option get home
docker-compose run --rm wpcli option get siteurl
```

## 📞 Quando Precisar de Ajuda

1. **Erro de importação**: Verifique formato do arquivo SQL
2. **Site não carrega**: Verificar URLs com `wp search-replace`
3. **Erro de permissão**: Verificar `docker-compose logs`
4. **Banco não conecta**: Reiniciar containers

## 🎉 Status Atual

- ✅ **Banco local**: MySQL rodando
- ✅ **WordPress local**: Funcionando
- ✅ **Scripts**: Prontos para usar
- ⏳ **Sincronização**: Aguardando backup/conectividade

**Próximo passo:** Execute `./import-backup.sh` quando tiver um arquivo SQL do servidor remoto!