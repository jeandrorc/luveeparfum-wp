# 🚀 Luvee WordPress - Projeto de Desenvolvimento

> **Sistema WordPress customizado para Luvee com ambiente de desenvolvimento Docker e sincronização automatizada**

[![WordPress](https://img.shields.io/badge/WordPress-6.4+-blue.svg)](https://wordpress.org/)
[![Docker](https://img.shields.io/badge/Docker-20.10+-blue.svg)](https://www.docker.com/)
[![PHP](https://img.shields.io/badge/PHP-8.0+-purple.svg)](https://php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://www.mysql.com/)

---

## 📋 **Índice**

- [Sobre o Projeto](#sobre-o-projeto)
- [🚀 Início Rápido](#-início-rápido)
- [📚 Documentação](#-documentação)
- [🏗️ Arquitetura](#️-arquitetura)
- [🛠️ Desenvolvimento](#️-desenvolvimento)
- [�� Sincronização](#-sincronização)
- [�� Docker](#-docker)
- [📁 Estrutura do Projeto](#-estrutura-do-projeto)
- [🤝 Contribuição](#-contribuição)
- [�� Suporte](#-suporte)

---

## 🎯 **Sobre o Projeto**

O **Luvee WordPress** é um projeto customizado que combina a flexibilidade do WordPress com um ambiente de desenvolvimento moderno usando Docker. O projeto inclui:

- �� **Tema customizado** `luvee-theme` desenvolvido especificamente para Luvee
- 🐳 **Ambiente Docker** completo com MySQL e WordPress
- 🔄 **Sincronização automatizada** entre ambiente local e produção
- 📊 **Scripts de backup** e sincronização de banco de dados
- 🚀 **Setup automatizado** para novos desenvolvedores

---

## 🚀 **Início Rápido**

### **Para Desenvolvedores (Setup Local)**

```bash
# 1. Clonar o repositório
git clone https://github.com/jeandrorc/luveeparfum-wp.git
cd luveeparfum-wp

# 2. Setup automático (recomendado)
./setup-local-db.sh

# 3. Acessar WordPress
# 🌐 http://localhost:8000
```

**📖 [Guia Completo de Desenvolvimento Local →](local-dev.md)**

### **Para Administradores (Sincronização)**

```bash
# 1. Configurar credenciais
cp sync-config.example .env.sync
# Editar .env.sync com suas configurações

# 2. Sincronizar banco de dados
./sync-simple.sh

# 3. Sincronizar uploads
./sync-uploads.sh --remote-host [SEU_SERVIDOR]
```

---

## �� **Documentação**

### **📖 Guias Principais**
- **[🚀 Desenvolvimento Local](local-dev.md)** - Setup completo para desenvolvedores
- **[🐳 Docker e Ambiente](README-docker.md)** - Configuração Docker detalhada
- **[🔄 Sincronização](README-sync.md)** - Sincronização com servidor de produção

### **📋 Guias Específicos**
- **[⏰ Dumps Horários](README-dumps-horarios.md)** - Sistema de backup automático
- **[�� Sincronização Avançada](README-sincronizacao.md)** - Sincronização detalhada
- **[�� Headers e Tema](ANALISE_HEADERS_TEMA.md)** - Análise do tema customizado

### **🔧 Scripts e Ferramentas**
- **[🗄️ Setup Local](setup-local-db.sh)** - Configuração automática do ambiente
- **[🔄 Sincronização Simples](sync-simple.sh)** - Sincronização rápida de banco
- **[📁 Sincronização de Uploads](sync-uploads.sh)** - Sincronização de mídia

---

## ��️ **Arquitetura**

### **Stack Tecnológico**
```
Frontend:    WordPress + Tema Customizado (luvee-theme)
Backend:     PHP 8.0+ + MySQL 8.0
Container:   Docker + Docker Compose
Sincronização: Scripts Shell + SSH
```

### **Serviços Docker**
- **WordPress** - Aplicação principal (porta 8000)
- **MySQL** - Banco de dados (porta 3306)
- **WP-CLI** - Interface de linha de comando WordPress

---

## ️ **Desenvolvimento**

### **Requisitos do Sistema**
- Docker Desktop 20.10+
- Git 2.30+
- 4GB+ RAM
- 2GB+ espaço livre

### **Fluxo de Trabalho**
1. **Setup Local** → `./setup-local-db.sh`
2. **Desenvolvimento** → Editar arquivos do tema
3. **Teste** → http://localhost:8000
4. **Commit** → `git add . && git commit -m "descrição"`
5. **Push** → `git push origin main`

### **Estrutura do Tema**
```
wp-content/themes/luvee-theme/
├── style.css          # Estilos principais
├── functions.php      # Funções do tema
├── index.php          # Template principal
├── header.php         # Cabeçalho
├── footer.php         # Rodapé
├── single.php         # Post único
├── page.php           # Página
├── archive.php        # Arquivo
├── search.php         # Busca
├── 404.php           # Erro 404
└── assets/            # CSS, JS, imagens
```

---

## 🔄 **Sincronização**

### **Tipos de Sincronização**
- **Banco de Dados** → `./sync-simple.sh`
- **Uploads/Mídia** → `./sync-uploads.sh`
- **Backup Automático** → Scripts cron

### **Configuração de Sincronização**
```bash
# Copiar arquivo de exemplo
cp sync-config.example .env.sync

# Editar com suas configurações
nano .env.sync
```

**⚠️ IMPORTANTE:** A sincronização do banco **SUBSTITUI** completamente o banco local!

---

##  **Docker**

### **Comandos Básicos**
```bash
# Iniciar ambiente
docker-compose up -d

# Parar ambiente
docker-compose down

# Ver logs
docker-compose logs -f

# Status dos serviços
docker-compose ps
```

### **URLs de Acesso**
- **WordPress:** http://localhost:8000
- **MySQL:** localhost:3306
- **Backup:** ./database-backups/

---

##  **Estrutura do Projeto**

```
luveeparfum-wp/
├── 📖 local-dev.md              # Guia de desenvolvimento local
├──  docker-compose.yml        # Configuração Docker
├── ️ setup-local-db.sh        # Setup automático
├── 🔄 sync-*.sh                 # Scripts de sincronização
├── 📚 README-*.md               # Documentação específica
├── 🎨 wp-content/
│   └── themes/
│       └── luvee-theme/         # Tema customizado
├── 📊 database-backups/         # Backups de banco
├── 🔧 .env                      # Variáveis de ambiente
└── 🚫 .gitignore               # Arquivos ignorados
```

---

##  **Contribuição**

### **Para Desenvolvedores**
1. **Fork** o repositório
2. **Clone** para sua máquina local
3. **Setup** ambiente com `./setup-local-db.sh`
4. **Desenvolva** suas funcionalidades
5. **Teste** localmente
6. **Commit** e **Push** suas alterações
7. **Pull Request** para o repositório principal

### **Padrões de Código**
- **PHP:** PSR-12
- **CSS:** BEM methodology
- **JavaScript:** ES6+ com comentários
- **Commits:** Conventional Commits

---

## 📞 **Suporte**

### **Canais de Ajuda**
- 📖 **Documentação:** Este README e arquivos relacionados
- 🐛 **Issues:** [GitHub Issues](https://github.com/jeandrorc/luveeparfum-wp/issues)
- 💬 **Discussões:** [GitHub Discussions](https://github.com/jeandrorc/luveeparfum-wp/discussions)

### **Problemas Comuns**
- **Porta 8000 ocupada:** Verificar `lsof -i :8000`
- **MySQL não inicia:** Verificar logs com `docker-compose logs mysql`
- **Permissões:** Executar `chmod -R 755 wp-content`

---

## 📄 **Licença**

Este projeto está sob a licença [GPL v2](LICENSE) ou posterior, conforme o WordPress.

---

## 🙏 **Agradecimentos**

- **WordPress Community** - Plataforma base
- **Docker Team** - Containerização
- **Contribuidores** - Desenvolvimento e manutenção

---

## 📊 **Status do Projeto**

- **Versão:** 1.0.0
- **Última Atualização:** Janeiro 2025
- **Status:** Em desenvolvimento ativo
- **Compatibilidade:** WordPress 6.4+

---

**⭐ Se este projeto te ajudou, considere dar uma estrela no repositório!**

---

*Desenvolvido com ❤️ pela equipe Luvee*
