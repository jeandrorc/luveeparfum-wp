# Luvee Site Plugin

Plugin para gerenciar banners, carrosséis e hero banners do site Luvee.

## Funcionalidades

### 🎯 **Banners**
- Criação e gerenciamento de banners
- Posicionamento flexível (cabeçalho, barra lateral, rodapé, conteúdo)
- Links personalizáveis com target configurável
- Sistema de ativação/desativação
- Datas de início e fim (agendamento)
- Rastreamento de visualizações e cliques

### 🎠 **Carrosséis**
- Criação de carrosséis com múltiplos itens
- Configurações de autoplay com velocidade personalizável
- Setas de navegação opcionais
- Pontos de navegação opcionais
- Loop infinito opcional
- Suporte a touch/swipe em dispositivos móveis
- Itens com imagem, título, descrição e link

### 🦸 **Hero Banners**
- Hero banners de destaque para páginas principais (especialmente home)
- Upload de imagem com texto alternativo (alt) para acessibilidade
- Links personalizáveis com target configurável
- Sistema de ativação/desativação
- Agendamento com data de início e fim
- **Carrossel automático**: quando há múltiplos banners ativos, vira carrossel
- Compatível com container Bootstrap (visual encaixotado)
- Autoplay configurável, setas e pontos de navegação
- Suporte a touch/swipe em dispositivos móveis

## Instalação

1. Faça upload da pasta `luvee-site` para `/wp-content/plugins/`
2. Ative o plugin através do menu 'Plugins' no WordPress
3. Use o menu 'Luvee Site' no painel administrativo para gerenciar os conteúdos

## Como Usar

### Shortcodes Disponíveis

#### Banner Específico
```php
[luvee_banner id="123"]
```

#### Banners por Posição
```php
[luvee_banners_by_position position="header" limit="3"]
```

Posições disponíveis:
- `header` - Cabeçalho
- `sidebar` - Barra Lateral  
- `footer` - Rodapé
- `content` - Conteúdo

#### Carrossel
```php
[luvee_carousel id="123"]
```

#### Hero Banner
```php
// Hero banner específico
[luvee_hero id="123"]

// Todos os hero banners ativos (carrossel automático se múltiplos)
[luvee_hero]

// Hero banner com configurações customizadas
[luvee_hero autoplay="true" autoplay_speed="3000" show_arrows="true" show_dots="true"]
```

### Uso em Templates PHP

```php
// Banner específico
echo do_shortcode('[luvee_banner id="123"]');

// Banners por posição
echo do_shortcode('[luvee_banners_by_position position="header"]');

// Carrossel
echo do_shortcode('[luvee_carousel id="123"]');

// Hero banner específico
echo do_shortcode('[luvee_hero id="123"]');

// Todos os hero banners (ideal para home)
echo do_shortcode('[luvee_hero]');
```

### Inserindo no Tema

Para inserir banners automaticamente em posições específicas do tema:

```php
// No header.php
echo do_shortcode('[luvee_banners_by_position position="header"]');

// Na home (após o header) - HERO BANNER
echo do_shortcode('[luvee_hero]');

// Na sidebar
echo do_shortcode('[luvee_banners_by_position position="sidebar"]');

// No footer.php
echo do_shortcode('[luvee_banners_by_position position="footer"]');
```

## Estrutura de Arquivos

```
luvee-site/
├── luvee-site.php          # Arquivo principal do plugin
├── admin/                  # Arquivos administrativos
│   ├── class-admin.php     # Classe principal do admin
│   └── views/              # Views do admin
│       ├── main-page.php   # Página principal
│       └── settings-page.php # Página de configurações
├── assets/                 # Recursos estáticos
│   ├── css/               # Estilos CSS
│   ├── js/                # Scripts JavaScript
│   └── images/            # Imagens do plugin
├── includes/              # Classes principais
│   ├── class-post-types.php # Gerenciamento de post types
│   └── class-shortcodes.php # Gerenciamento de shortcodes
└── templates/             # Templates frontend
```

## Recursos Técnicos

### Post Types Criados
- `luvee_banner` - Banners
- `luvee_carousel` - Carrosséis  
- `luvee_hero` - Hero Banners

### Tabelas do Banco de Dados
- `wp_luvee_banner_stats` - Estatísticas de visualizações e cliques

### Meta Fields

#### Banners
- `_luvee_banner_link_url` - URL do link
- `_luvee_banner_link_target` - Target do link (_self/_blank)
- `_luvee_banner_position` - Posição do banner
- `_luvee_banner_active` - Status ativo/inativo
- `_luvee_banner_start_date` - Data de início
- `_luvee_banner_end_date` - Data de fim

#### Carrosséis
- `_luvee_carousel_autoplay` - Autoplay ativo/inativo
- `_luvee_carousel_autoplay_speed` - Velocidade do autoplay (ms)
- `_luvee_carousel_show_arrows` - Mostrar setas
- `_luvee_carousel_show_dots` - Mostrar pontos
- `_luvee_carousel_infinite` - Loop infinito
- `_luvee_carousel_items` - Array com itens do carrossel

#### Hero Banners
- `_luvee_hero_image_url` - URL da imagem
- `_luvee_hero_image_alt` - Texto alternativo da imagem
- `_luvee_hero_link_url` - URL do link
- `_luvee_hero_link_target` - Target do link (_self/_blank)
- `_luvee_hero_active` - Status ativo/inativo
- `_luvee_hero_start_date` - Data de início
- `_luvee_hero_end_date` - Data de validade

## Configurações

Acesse **Luvee Site > Configurações** para:
- Habilitar/desabilitar analytics
- Definir tamanho padrão das imagens
- Configurar duração do cache
- Limpar estatísticas
- Recriar tabelas do banco

## Estatísticas

O plugin rastreia automaticamente:
- Número de visualizações de cada banner/carrossel/hero
- Número de cliques em cada elemento
- Dados organizados por dia

## Requisitos

- WordPress 5.0+
- PHP 7.4+
- MySQL 5.6+ ou MariaDB 10.1+

## Suporte

Para suporte técnico, entre em contato com a equipe Luvee.

## Changelog

### Versão 1.0.0
- Lançamento inicial
- Gerenciamento completo de banners
- Sistema de carrosséis
- Hero banners
- Sistema de estatísticas
- Interface administrativa completa
