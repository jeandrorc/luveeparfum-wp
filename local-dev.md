# 🚀 Guia de Desenvolvimento Local - Luvee WordPress

> **Guia completo para configurar e desenvolver o projeto Luvee WordPress em ambiente local**

---

## �� **Índice**

1. [Pré-requisitos](#pré-requisitos)
2. [Setup Inicial](#setup-inicial)
3. [Configuração do Ambiente](#configuração-do-ambiente)
4. [Sincronização com Produção](#sincronização-com-produção)
5. [Desenvolvimento](#desenvolvimento)
6. [Comandos Úteis](#comandos-úteis)
7. [Resolução de Problemas](#resolução-de-problemas)
8. [FAQ](#faq)

---

## 🛠️ **Pré-requisitos**

### **Software Necessário:**
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (versão 20.10+)
- [Git](https://git-scm.com/) (versão 2.30+)
- Editor de código (VS Code recomendado)
- Terminal/Shell (Git Bash no Windows)

### **Requisitos do Sistema:**
- **Windows:** Windows 10/11 Pro, Enterprise ou Education
- **macOS:** macOS 10.15+ (Catalina ou superior)
- **Linux:** Ubuntu 18.04+, CentOS 7+, ou similar
- **RAM:** Mínimo 4GB, recomendado 8GB+
- **Espaço:** Mínimo 2GB livres

---

## 🚀 **Setup Inicial**

### **Passo 1: Clonar o Repositório**

```bash
# Criar diretório de projetos
mkdir ~/Projects
cd ~/Projects

# Clonar o repositório Luvee
git clone https://github.com/jeandrorc/luveeparfum-wp.git
cd luveeparfum-wp

# Verificar estrutura do projeto
ls -la
```

**Estrutura esperada:**
```
luveeparfum-wp/
├── .git/
├── wp-content/
│   └── themes/
│       └── luvee-theme/          # ✅ Tema customizado
├── docker-compose.yml            # ✅ Configuração Docker
├── setup-local-db.sh            # ✅ Script de setup
├── sync-*.sh                    # ✅ Scripts de sincronização
├── README-docker.md             # ✅ Documentação Docker
└── .gitignore                   # ✅ Arquivos ignorados
```

### **Passo 2: Verificar Docker**

```bash
# Verificar se Docker está rodando
docker --version
docker-compose --version

# Verificar status dos containers
docker ps

# Se Docker não estiver rodando, iniciar Docker Desktop
```

---

## ⚙️ **Configuração do Ambiente**

### **Passo 3: Configurar Variáveis de Ambiente**

```bash
# Verificar se existe arquivo .env
ls -la .env

# Se não existir, criar com configurações padrão
if [ ! -f ".env" ]; then
    cat > .env << 'EOF'
# =============================================================================
# CONFIGURAÇÕES DO AMBIENTE LOCAL
# =============================================================================

# Configurações do Banco de Dados
MYSQL_ROOT_PASSWORD=root_password
WORDPRESS_DB_HOST=mysql
WORDPRESS_DB_USER=wordpress
WORDPRESS_DB_PASSWORD=wordpress_password
WORDPRESS_DB_NAME=wordpress_local
MYSQL_CHARSET=utf8mb4
MYSQL_COLLATION=utf8mb4_unicode_ci

# Configurações do WordPress
WORDPRESS_DEBUG=true
WORDPRESS_DEBUG_LOG=true
WORDPRESS_DEBUG_DISPLAY=false
EOF
    echo "✅ Arquivo .env criado com configurações padrão"
else
    echo "✅ Arquivo .env já existe"
fi
```

### **Passo 4: Setup Automático do Banco**

```bash
# Dar permissão de execução ao script
chmod +x setup-local-db.sh

# Executar setup automático
./setup-local-db.sh
```

**O que o script faz automaticamente:**
1. ✅ Verifica se Docker está rodando
2. ✅ Cria arquivo .env se necessário
3. ✅ Inicia serviço MySQL
4. ✅ Aguarda MySQL estar pronto
5. ✅ Inicia WordPress
6. ✅ Verifica conectividade
7. ✅ Oferece instalação inicial (se necessário)

### **Passo 5: Verificar Status dos Serviços**

```bash
# Verificar containers rodando
docker-compose ps

# Verificar logs dos serviços
docker-compose logs --tail=20

# Testar conectividade
curl -I http://localhost:8000
```

**URLs de Acesso:**
- 🌐 **WordPress:** http://localhost:8000
- 🗄️ **MySQL:** localhost:3306
- 📊 **phpMyAdmin:** Não configurado por padrão

---

## 🔄 **Sincronização com Produção**

### **Passo 6: Configurar Sincronização**

```bash
# Copiar arquivo de exemplo
cp sync-config.example .env.sync

# Editar com credenciais reais
nano .env.sync
# ou
code .env.sync
```

**Conteúdo do `.env.sync`:**
```bash
# =============================================================================
# CONFIGURAÇÕES DE SINCRONIZAÇÃO
# =============================================================================

# Configurações do servidor remoto
REMOTE_HOST=luvee.com.br
REMOTE_USER=ubuntu
REMOTE_PATH=/var/www/html
REMOTE_URL=https://luvee.com.br

# Configurações locais
LOCAL_URL=http://localhost:8000
BACKUP_DIR=./database-backups

# Opções adicionais
SKIP_SEARCH_REPLACE=false
```

### **Passo 7: Sincronizar Dados**

```bash
# Sincronizar banco de dados (SUBSTITUI banco local!)
./sync-simple.sh

# Sincronizar uploads (mídia)
./sync-uploads.sh \
    --remote-host luvee.com.br \
    --remote-user ubuntu \
    --remote-path /var/www/html

# Verificar arquivos sincronizados
ls -la wp-content/uploads/
```

**⚠️ IMPORTANTE:** A sincronização do banco **SUBSTITUI** completamente o banco local. Faça backup se necessário!

---

## 💻 **Desenvolvimento**

### **Passo 8: Ativar Tema e Plugins**

```bash
# Verificar status do WordPress
docker-compose run --rm wpcli core is-installed

# Listar temas disponíveis
docker-compose run --rm wpcli theme list

# Ativar tema Luvee
docker-compose run --rm wpcli theme activate luvee-theme

# Listar plugins
docker-compose run --rm wpcli plugin list

# Instalar plugins necessários (se houver)
docker-compose run --rm wpcli plugin install [nome-do-plugin] --activate
```

### **Passo 9: Estrutura do Tema**

```
wp-content/themes/luvee-theme/
├── style.css                 # ✅ Estilos principais
├── functions.php             # ✅ Funções do tema
├── index.php                 # ✅ Template principal
├── header.php                # ✅ Cabeçalho
├── footer.php                # ✅ Rodapé
├── single.php                # ✅ Template de post único
├── page.php                  # ✅ Template de página
├── archive.php               # ✅ Template de arquivo
├── search.php                # ✅ Template de busca
├── 404.php                  # ✅ Página de erro
├── screenshot.png            # ✅ Preview do tema
└── assets/                   # ✅ CSS, JS, imagens
    ├── css/
    ├── js/
    └── images/
```

---

## 🎯 **Comandos Úteis**

### **Gerenciamento de Containers**

```bash
# Iniciar ambiente
docker-compose up -d

# Parar ambiente
docker-compose down

# Reiniciar serviços
docker-compose restart

# Ver logs em tempo real
docker-compose logs -f

# Ver logs de serviço específico
docker-compose logs -f wordpress
docker-compose logs -f mysql
```

### **WordPress CLI**

```bash
# Verificar status
docker-compose run --rm wpcli core is-installed

# Informações do site
docker-compose run --rm wpcli core version
docker-compose run --rm wpcli site info

# Gerenciar usuários
docker-compose run --rm wpcli user list
docker-compose run --rm wpcli user create admin admin@localhost.local --role=administrator

# Gerenciar opções
docker-compose run --rm wpcli option get siteurl
docker-compose run --rm wpcli option update siteurl http://localhost:8000
```

### **Banco de Dados**

```bash
# Conectar no MySQL
docker-compose exec mysql mysql -u root -proot_password

# Backup manual
docker-compose exec mysql mysqldump -u root -proot_password wordpress_local > backup_$(date +%Y%m%d_%H%M%S).sql

# Restaurar backup
docker-compose exec -T mysql mysql -u root -proot_password wordpress_local < backup.sql

# Ver tabelas
docker-compose exec mysql mysql -u wordpress -pwordpress_password -e "SHOW TABLES;" wordpress_local
```

---

## 🔧 **Resolução de Problemas**

### **Problema: Porta 8000 já em uso**

```bash
# Verificar o que está usando a porta
lsof -i :8000          # macOS/Linux
netstat -ano | findstr :8000  # Windows

# Parar processo ou alterar porta no docker-compose.yml
# Alterar linha: "8000:80" para "8001:80"
```

### **Problema: MySQL não inicializa**

```bash
# Ver logs detalhados
docker-compose logs mysql

# Verificar se há conflito de porta 3306
lsof -i :3306

# Recriar volume (PERDA DE DADOS!)
docker-compose down -v
docker-compose up -d
```

### **Problema: WordPress não conecta no banco**

```bash
# Verificar se MySQL está rodando
docker-compose ps mysql

# Testar conectividade
docker-compose run --rm wpcli db check

# Verificar variáveis de ambiente
docker-compose exec wordpress env | grep WORDPRESS_DB
```

### **Problema: Permissões de arquivo**

```bash
# Ajustar permissões
sudo chown -R $USER:$USER .
chmod -R 755 wp-content

# No Windows, executar como administrador
```

### **Problema: Containers não iniciam**

```bash
# Verificar logs do Docker
docker system info

# Limpar recursos não utilizados
docker system prune -f

# Reiniciar Docker Desktop
```

---

## ❓ **FAQ**

### **Q: Posso usar outra porta além da 8000?**
**A:** Sim! Edite o `docker-compose.yml` e altere `"8000:80"` para `"8001:80"` (ou outra porta).

### **Q: Como faço backup antes de sincronizar?**
**A:** Execute: `docker-compose exec mysql mysqldump -u root -proot_password wordpress_local > backup_antes_sync.sql`

### **Q: Posso usar XAMPP/MAMP em vez do Docker?**
**A:** Sim, mas você precisará configurar manualmente o banco e ajustar os scripts de sincronização.

### **Q: Como atualizar o WordPress?**
**A:** Use: `docker-compose run --rm wpcli core update`

### **Q: Onde ficam os logs de erro?**
**A:** Em `error_log` na raiz do projeto e nos logs do Docker: `docker-compose logs wordpress`

---

## 🚀 **Aliases Úteis**

Adicione ao seu `~/.bashrc` ou `~/.zshrc`:

```bash
# Aliases para o projeto Luvee
alias luvee-start="cd ~/Projects/luveeparfum-wp && docker-compose up -d"
alias luvee-stop="cd ~/Projects/luveeparfum-wp && docker-compose down"
alias luvee-logs="cd ~/Projects/luveeparfum-wp && docker-compose logs -f"
alias luvee-status="cd ~/Projects/luveeparfum-wp && docker-compose ps"
alias luvee-restart="cd ~/Projects/luveeparfum-wp && docker-compose restart"
alias luvee-backup="cd ~/Projects/luveeparfum-wp && docker-compose exec mysql mysqldump -u root -proot_password wordpress_local > backup_$(date +%Y%m%d_%H%M%S).sql"
```

**Reiniciar shell:** `source ~/.bashrc` ou `source ~/.zshrc`

---

##  **Recursos Adicionais**

- [Documentação Docker](https://docs.docker.com/)
- [WordPress CLI Handbook](https://developer.wordpress.org/cli/commands/)
- [WordPress Developer Handbook](https://developer.wordpress.org/)
- [Docker Compose Reference](https://docs.docker.com/compose/)

---

## 🎉 **Próximos Passos**

1. **Configurar ambiente local** ✅
2. **Sincronizar com produção** ✅
3. **Ativar tema Luvee** ✅
4. **Começar desenvolvimento** 🚀

**URL de acesso:** http://localhost:8000

**Boa sorte com o desenvolvimento!** 🎯

---

*Última atualização: $(date)*
*Versão do guia: 1.0*
```

Este arquivo `local-dev.md` contém um guia completo e detalhado para seu funcionário configurar o ambiente de desenvolvimento local. Ele inclui:

✅ **Setup passo a passo** com todos os comandos necessários
✅ **Configuração do Docker** e variáveis de ambiente
✅ **Sincronização com produção** de forma segura
✅ **Comandos úteis** para desenvolvimento diário
✅ **Resolução de problemas** comuns
✅ **FAQ** com perguntas frequentes
✅ **Aliases úteis** para agilizar o trabalho

