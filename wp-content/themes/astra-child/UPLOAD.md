# 🚀 Guia de Upload para o Servidor

## 📋 Opções de Upload

### Opção 1: Script Automático (Recomendado)

```bash
# Navegar para o diretório do projeto
cd /Users/jeandrocouto/Workspace/Luvee/wordpress

# Executar script de upload
./wp-content/themes/astra-child/upload-theme.sh
```

### Opção 2: Upload Simples

```bash
# Navegar para o diretório do projeto
cd /Users/jeandrocouto/Workspace/Luvee/wordpress

# Executar upload simples
./wp-content/themes/astra-child/upload-simple.sh
```

### Opção 3: Comando Manual

```bash
# Navegar para o diretório do projeto
cd /Users/jeandrocouto/Workspace/Luvee/wordpress

# Upload manual
scp -r wp-content/themes/astra-child luvee-hostgator:~/public_html/wp-content/themes/
```

### Opção 4: Upload Individual de Arquivos

```bash
# Upload de arquivos específicos
scp wp-content/themes/astra-child/functions.php luvee-hostgator:~/public_html/wp-content/themes/astra-child/
scp wp-content/themes/astra-child/style.css luvee-hostgator:~/public_html/wp-content/themes/astra-child/
scp -r wp-content/themes/astra-child/widgets luvee-hostgator:~/public_html/wp-content/themes/astra-child/
scp -r wp-content/themes/astra-child/assets luvee-hostgator:~/public_html/wp-content/themes/astra-child/
```

## 🔧 Verificação do Upload

### Verificar se os arquivos foram enviados:

```bash
# Conectar via SSH
ssh luvee-hostgator

# Verificar estrutura do tema
ls -la ~/public_html/wp-content/themes/astra-child/

# Verificar widgets
ls -la ~/public_html/wp-content/themes/astra-child/widgets/

# Verificar assets
ls -la ~/public_html/wp-content/themes/astra-child/assets/
```

### Verificar permissões:

```bash
# Definir permissões corretas
ssh luvee-hostgator "chmod -R 755 ~/public_html/wp-content/themes/astra-child/"
ssh luvee-hostgator "chmod 644 ~/public_html/wp-content/themes/astra-child/*.php"
ssh luvee-hostgator "chmod 644 ~/public_html/wp-content/themes/astra-child/*.css"
```

## 📁 Estrutura de Arquivos Enviados

```
astra-child/
├── style.css                    # Estilos principais
├── functions.php                # Funções do tema
├── elementor-widgets.php        # Registro dos widgets
├── shortcodes.php              # Shortcodes
├── woocommerce-support.php     # Suporte WooCommerce
├── woocommerce.php            # Template WooCommerce
├── template-ecommerce.php      # Template demo
├── README.md                   # Documentação
├── ELEMENTOS.md               # Guia dos elementos
├── INSTALACAO.md              # Guia de instalação
├── upload-theme.sh            # Script de upload
├── upload-simple.sh           # Upload simples
├── widgets/                   # Widgets do Elementor
│   ├── product-card-widget.php
│   ├── product-grid-widget.php
│   ├── product-search-widget.php
│   ├── single-product-widget.php
│   ├── hero-carousel-widget.php
│   └── mega-menu-widget.php
└── assets/                    # Assets
    ├── css/
    │   └── elementor-widgets.css
    └── js/
        └── components.js
```

## 🎯 Pós-Upload

### 1. Ativar o Tema

1. Acesse o **WordPress Admin**
2. Vá em **Aparência** → **Temas**
3. Procure por **"Astra Child - Ecommerce"**
4. Clique em **"Ativar"**

### 2. Verificar Widgets do Elementor

1. Vá para **Páginas** → **Editar com Elementor**
2. Clique no ícone **+**
3. Procure por **"Astra Child Ecommerce"**
4. Teste os widgets disponíveis

### 3. Testar Shortcodes

1. Crie uma nova página
2. Adicione shortcodes:
   ```php
   [product_grid posts_per_page="6"]
   [product_search]
   [hero_carousel]
   ```

### 4. Configurar Produtos

1. Vá para **Posts** → **Adicionar Novo**
2. Adicione imagem destacada
3. Preencha os campos de produto:
   - **Preço (R$)**
   - **Preço Antigo (R$)**
   - **SKU**
   - **Badge**

## 🔍 Troubleshooting

### Erro de Conexão SSH

```bash
# Testar conexão
ssh luvee-hostgator "echo 'Conexão OK'"

# Se der erro, verificar:
# 1. Chave SSH configurada
# 2. Host correto
# 3. Usuário correto
```

### Arquivos não aparecem

```bash
# Verificar se foram enviados
ssh luvee-hostgator "ls -la ~/public_html/wp-content/themes/astra-child/"

# Se não existir, criar diretório
ssh luvee-hostgator "mkdir -p ~/public_html/wp-content/themes/astra-child/"
```

### Permissões incorretas

```bash
# Corrigir permissões
ssh luvee-hostgator "chmod -R 755 ~/public_html/wp-content/themes/astra-child/"
ssh luvee-hostgator "chown -R $(whoami):$(whoami) ~/public_html/wp-content/themes/astra-child/"
```

### Widgets não aparecem no Elementor

1. Verificar se o Elementor está ativo
2. Limpar cache do Elementor
3. Verificar se o tema está ativo
4. Recarregar a página

## 🚀 Comandos Rápidos

### Upload Completo
```bash
cd /Users/jeandrocouto/Workspace/Luvee/wordpress && ./wp-content/themes/astra-child/upload-theme.sh
```

### Upload Simples
```bash
cd /Users/jeandrocouto/Workspace/Luvee/wordpress && ./wp-content/themes/astra-child/upload-simple.sh
```

### Upload Manual
```bash
cd /Users/jeandrocouto/Workspace/Luvee/wordpress && scp -r wp-content/themes/astra-child luvee-hostgator:~/public_html/wp-content/themes/
```

### Verificar Upload
```bash
ssh luvee-hostgator "ls -la ~/public_html/wp-content/themes/astra-child/"
```

---

**Dica:** Use o script `upload-theme.sh` para uploads completos com backup automático! 🎯 