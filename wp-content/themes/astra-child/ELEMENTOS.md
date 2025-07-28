# 🛍️ Elementos de Ecommerce para Elementor e Shortcodes

Documentação completa dos elementos customizados para Elementor e shortcodes disponíveis no tema Astra Child.

## 📋 Índice

1. [Widgets do Elementor](#widgets-do-elementor)
2. [Shortcodes](#shortcodes)
3. [Como Usar](#como-usar)
4. [Exemplos](#exemplos)
5. [Personalização](#personalização)

## 🎯 Widgets do Elementor

### 1. Product Card Widget
**Categoria:** Astra Child Ecommerce  
**Ícone:** 🛍️

**Controles:**
- **Product ID** - ID do produto (deixe vazio para usar o produto atual)
- **Show Badge** - Mostrar badge de promoção
- **Show Category** - Mostrar categoria do produto
- **Show Wishlist** - Mostrar botão de lista de desejos

**Estilos:**
- **Title Typography** - Tipografia do título
- **Title Color** - Cor do título
- **Price Color** - Cor do preço
- **Button Color** - Cor do botão

### 2. Product Grid Widget
**Categoria:** Astra Child Ecommerce  
**Ícone:** 📦

**Controles:**
- **Products Per Page** - Produtos por página (1-50)
- **Order By** - Ordenar por (Data, Título, Menu Order, Aleatório)
- **Order** - Ordem (Crescente/Decrescente)
- **Category** - Filtrar por categoria
- **Columns** - Número de colunas (responsivo)
- **Show Pagination** - Mostrar paginação
- **Show Badge/Category/Wishlist** - Elementos visuais

**Estilos:**
- **Grid Gap** - Espaçamento entre produtos

### 3. Product Search Widget
**Categoria:** Astra Child Ecommerce  
**Ícone:** 🔍

**Controles:**
- **Placeholder** - Texto do campo de busca
- **Button Text** - Texto do botão
- **Show Categories** - Mostrar filtro de categorias
- **Show Price Range** - Mostrar filtro de preço

**Estilos:**
- **Search Background** - Cor de fundo
- **Button Background** - Cor do botão

### 4. Single Product Widget
**Categoria:** Astra Child Ecommerce  
**Ícone:** 🛍️

**Controles:**
- **Product ID** - ID do produto
- **Show Gallery** - Mostrar galeria de imagens
- **Show Related** - Mostrar produtos relacionados
- **Related Count** - Quantidade de produtos relacionados

**Estilos:**
- **Gallery Width** - Largura da galeria (30%-70%)

### 5. Hero Carousel Widget
**Categoria:** Astra Child Ecommerce  
**Ícone:** 🎠

**Controles:**
- **Slides** - Repeater com campos:
  - Slide Image
  - Slide Title
  - Slide Subtitle
  - Button Text
  - Button URL
- **Autoplay** - Reprodução automática
- **Autoplay Speed** - Velocidade (1-10s)
- **Show Navigation** - Mostrar setas
- **Show Dots** - Mostrar pontos

**Estilos:**
- **Title Color** - Cor do título
- **Subtitle Color** - Cor do subtítulo
- **Button Color** - Cor do botão

### 6. Mega Menu Widget
**Categoria:** Astra Child Ecommerce  
**Ícone:** 🧭

**Controles:**
- **Menu Sections** - Repeater com:
  - Category Title
  - Menu Items (repeater aninhado)

**Estilos:**
- **Menu Background** - Cor de fundo
- **Category Color** - Cor das categorias
- **Link Color** - Cor dos links

## 📝 Shortcodes

### 1. Product Card
```php
[product_card id="123" show_badge="yes" show_category="yes" show_wishlist="yes"]
```

**Parâmetros:**
- `id` - ID do produto (opcional)
- `show_badge` - Mostrar badge (yes/no)
- `show_category` - Mostrar categoria (yes/no)
- `show_wishlist` - Mostrar wishlist (yes/no)

### 2. Product Grid
```php
[product_grid posts_per_page="8" category="eletronicos" orderby="date" order="DESC" show_pagination="yes" columns="3"]
```

**Parâmetros:**
- `posts_per_page` - Produtos por página
- `category` - Slug da categoria
- `orderby` - Ordenar por (date, title, menu_order, rand)
- `order` - Ordem (DESC/ASC)
- `show_pagination` - Mostrar paginação (yes/no)
- `columns` - Número de colunas

### 3. Product Search
```php
[product_search placeholder="Buscar produtos..." button_text="Buscar" show_categories="yes" show_price_range="yes"]
```

**Parâmetros:**
- `placeholder` - Texto do campo
- `button_text` - Texto do botão
- `show_categories` - Mostrar categorias (yes/no)
- `show_price_range` - Mostrar faixa de preço (yes/no)

### 4. Single Product
```php
[single_product id="123" show_gallery="yes" show_related="yes" related_count="4"]
```

**Parâmetros:**
- `id` - ID do produto
- `show_gallery` - Mostrar galeria (yes/no)
- `show_related` - Mostrar relacionados (yes/no)
- `related_count` - Quantidade de relacionados

### 5. Hero Carousel
```php
[hero_carousel autoplay="yes" speed="5000" show_navigation="yes" show_dots="yes"]
```

**Parâmetros:**
- `autoplay` - Reprodução automática (yes/no)
- `speed` - Velocidade em ms
- `show_navigation` - Mostrar navegação (yes/no)
- `show_dots` - Mostrar pontos (yes/no)

## 🚀 Como Usar

### No Elementor

1. **Abrir o Editor:**
   - Vá para **Páginas** → **Editar com Elementor**

2. **Adicionar Widget:**
   - Clique no ícone **+** 
   - Procure por **"Astra Child Ecommerce"**
   - Arraste o widget desejado

3. **Configurar:**
   - Use o painel lateral para configurar
   - Teste as opções de estilo
   - Visualize em tempo real

### Com Shortcodes

1. **Em Páginas/Posts:**
   - Use o editor de texto
   - Cole o shortcode desejado
   - Ajuste os parâmetros

2. **Em Templates:**
   - Use em arquivos PHP
   - Exemplo: `<?php echo do_shortcode('[product_grid]'); ?>`

3. **Em Widgets:**
   - Use o widget "Shortcode"
   - Cole o shortcode

## 📖 Exemplos

### Exemplo 1: Página de Produtos
```php
[hero_carousel]

[product_search]

[product_grid posts_per_page="12" columns="4" show_pagination="yes"]
```

### Exemplo 2: Página de Categoria
```php
[product_grid category="eletronicos" posts_per_page="8" columns="3"]

[single_product id="456"]
```

### Exemplo 3: Página Inicial
```php
[hero_carousel autoplay="yes" speed="4000"]

[product_grid posts_per_page="6" columns="3" orderby="rand"]

[product_search show_categories="yes" show_price_range="yes"]
```

### Exemplo 4: Widget Personalizado
```php
[product_card id="789" show_badge="yes" show_wishlist="yes"]
```

## 🎨 Personalização

### CSS Customizado

Adicione no **Personalizar** → **CSS Adicional**:

```css
/* Personalizar Product Card */
.product-card {
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

/* Personalizar Hero Carousel */
.hero-carousel__title {
    font-size: 56px;
    font-weight: 800;
}

/* Personalizar Search */
.product-search {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
```

### JavaScript Customizado

Adicione no **Personalizar** → **JavaScript Adicional**:

```javascript
// Personalizar comportamento dos widgets
jQuery(document).ready(function($) {
    // Exemplo: Adicionar animação personalizada
    $('.product-card').hover(
        function() {
            $(this).addClass('animated pulse');
        },
        function() {
            $(this).removeClass('animated pulse');
        }
    );
});
```

## 🔧 Configuração Avançada

### Meta Fields Necessários

Para produtos funcionarem corretamente, configure:

1. **Preço:** `_product_price`
2. **Preço Antigo:** `_product_old_price`
3. **SKU:** `_product_sku`
4. **Badge:** `_product_badge`

### Taxonomia

- **Nome:** `product_category`
- **Slug:** `categoria-produto`
- **Hierárquica:** Sim

### Hooks Disponíveis

```php
// Filtrar produtos
add_filter('astra_child_product_query_args', function($args) {
    // Modificar query
    return $args;
});

// Personalizar card
add_filter('astra_child_product_card_html', function($html, $product_id) {
    // Modificar HTML
    return $html;
}, 10, 2);
```

## 📱 Responsividade

Todos os elementos são totalmente responsivos:

- **Mobile:** 1 coluna
- **Tablet:** 2 colunas  
- **Desktop:** 3-4 colunas

## 🎯 Melhores Práticas

1. **Performance:**
   - Use cache para grids grandes
   - Otimize imagens
   - Lazy loading

2. **UX:**
   - Mantenha consistência visual
   - Use cores da marca
   - Teste em diferentes dispositivos

3. **SEO:**
   - Use títulos descritivos
   - Adicione alt text nas imagens
   - Estrutura semântica

## 🐛 Troubleshooting

### Widgets não aparecem
- Verifique se o Elementor está ativo
- Limpe o cache
- Verifique conflitos de plugins

### Shortcodes não funcionam
- Verifique se o tema está ativo
- Confirme sintaxe correta
- Teste em modo debug

### Estilos não aplicam
- Verifique especificidade CSS
- Use `!important` se necessário
- Confirme carregamento dos arquivos

## 📞 Suporte

Para dúvidas ou problemas:

1. Verifique esta documentação
2. Teste em ambiente limpo
3. Consulte os logs de erro
4. Entre em contato com o suporte

---

**Desenvolvido para Luvee** 🚀 