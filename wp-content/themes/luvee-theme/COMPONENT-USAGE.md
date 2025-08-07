# Luvée Theme - Guia de Uso dos Componentes

## 🎯 Como Usar os Componentes

### Função Principal
```php
luvee_get_template_part($slug, $name, $args);
```

**Parâmetros:**
- `$slug`: Nome do componente (ex: 'hero', 'product-section')
- `$name`: Variação do componente (opcional)
- `$args`: Array com parâmetros para o componente

---

## 📋 Lista de Componentes Disponíveis

### 🎯 Hero Section

#### Uso Básico:
```php
<?php luvee_get_template_part('hero'); ?>
```

#### Com Parâmetros:
```php
<?php 
luvee_get_template_part('hero', null, array(
    'title' => 'Seu Título Aqui',
    'subtitle' => 'Subtítulo descritivo',
    'button_text' => 'Botão Principal',
    'button_link' => '/sua-pagina',
    'style' => 'split', // 'default' ou 'split'
    'image' => 'url-da-imagem.jpg'
)); 
?>
```

#### Parâmetros Disponíveis:
- `title` - Título principal (HTML permitido)
- `subtitle` - Subtítulo/descrição
- `button_text` - Texto do botão
- `button_link` - URL do botão
- `style` - 'default' (centralizado) ou 'split' (duas colunas)
- `image` - URL da imagem (para style split)
- `features` - Array de características (ver exemplo completo)

---

### 🛍️ Product Section

#### Uso Básico:
```php
<?php luvee_get_template_part('product-section'); ?>
```

#### Com Parâmetros:
```php
<?php 
luvee_get_template_part('product-section', null, array(
    'title' => 'Produtos em Destaque',
    'subtitle' => 'Descrição da seção',
    'limit' => 8,
    'columns' => 4,
    'type' => 'featured'
)); 
?>
```

#### Parâmetros Disponíveis:
- `title` - Título da seção
- `subtitle` - Subtítulo/descrição
- `limit` - Número de produtos (padrão: 8)
- `columns` - Colunas no grid (2, 3, 4, 6)
- `type` - Tipo de produtos:
  - `featured` - Produtos em destaque
  - `recent` - Produtos recentes
  - `sale` - Produtos em promoção
  - `category` - Por categoria (requer `category` param)
- `category` - Slug da categoria (para type='category')
- `show_view_all` - Mostrar botão "Ver todos" (true/false)

---

### 📧 Newsletter

#### Uso Básico:
```php
<?php luvee_get_template_part('newsletter'); ?>
```

#### Com Parâmetros:
```php
<?php 
luvee_get_template_part('newsletter', null, array(
    'title' => 'Assine nossa Newsletter',
    'subtitle' => 'Receba ofertas exclusivas',
    'style' => 'elegant',
    'show_benefits' => true
)); 
?>
```

#### Parâmetros Disponíveis:
- `title` - Título da newsletter
- `subtitle` - Descrição
- `button_text` - Texto do botão (padrão: "Assinar Newsletter")
- `style` - Estilos disponíveis:
  - `default` - Gradiente rosa padrão
  - `elegant` - Gradiente animado
  - `minimal` - Cores suaves
- `show_benefits` - Mostrar benefícios visuais (true/false)

---

### 📋 Header

#### Uso:
```php
<?php luvee_get_template_part('header'); ?>
```

**Nota:** O header é automaticamente incluído via `get_header()`. Este componente é usado internamente.

---

### 🦶 Footer

#### Uso:
```php
<?php luvee_get_template_part('footer'); ?>
```

**Nota:** O footer é automaticamente incluído via `get_footer()`. Este componente é usado internamente.

---

## 🎨 Exemplos Completos

### Página de Produto com Hero Split
```php
<?php 
// Hero com imagem do produto
luvee_get_template_part('hero', null, array(
    'title' => 'Novo <span class="text-primary-custom">Perfume Francês</span>',
    'subtitle' => 'Fragrância exclusiva importada diretamente de Paris',
    'button_text' => 'Ver Detalhes',
    'button_link' => '/produto/perfume-frances',
    'style' => 'split',
    'image' => '/wp-content/uploads/produto-hero.jpg'
)); 

// Produtos relacionados
luvee_get_template_part('product-section', null, array(
    'title' => 'Produtos Relacionados',
    'limit' => 4,
    'columns' => 4,
    'type' => 'category',
    'category' => 'perfumes',
    'show_view_all' => false
)); 
?>
```

### Landing Page de Categoria
```php
<?php 
// Hero centralizado
luvee_get_template_part('hero', null, array(
    'title' => 'Coleção <span class="text-primary-custom">Skin Care</span>',
    'subtitle' => 'Cuide da sua pele com os melhores produtos do mercado',
    'button_text' => 'Ver Produtos',
    'button_link' => '/categoria/skin-care',
    'features' => array(
        array(
            'icon' => 'fas fa-leaf',
            'title' => 'Ingredientes Naturais',
            'description' => 'Produtos com componentes orgânicos'
        ),
        array(
            'icon' => 'fas fa-shield-alt',
            'title' => 'Dermatologicamente Testado',
            'description' => 'Segurança comprovada cientificamente'
        ),
        array(
            'icon' => 'fas fa-heart',
            'title' => 'Resultados Visíveis',
            'description' => 'Melhora perceptível em 30 dias'
        )
    )
)); 

// Produtos da categoria
luvee_get_template_part('product-section', null, array(
    'title' => 'Produtos de Skin Care',
    'limit' => 12,
    'columns' => 4,
    'type' => 'category',
    'category' => 'skin-care'
)); 

// Newsletter
luvee_get_template_part('newsletter', null, array(
    'title' => 'Dicas de Skin Care',
    'subtitle' => 'Receba rotinas e dicas personalizadas',
    'style' => 'minimal'
)); 
?>
```

---

## 🔧 Customização Avançada

### CSS Classes Utilitárias
```css
/* Espaçamentos */
.py-section         /* Padding vertical das seções */
.py-section-sm      /* Padding menor */
.py-section-lg      /* Padding maior */

/* Cores */
.text-primary-custom    /* Cor primária do tema */
.text-accent           /* Cor dourada */
.bg-cream             /* Background cremoso */
.bg-primary-gradient  /* Gradiente primário */

/* Tipografia */
.h1, .h2, .h3, .h4, .h5, .h6  /* Headers customizados */
```

### Variáveis CSS Disponíveis
```css
/* Modifique no style.css */
:root {
  --luvee-primary: #d4869c;      /* Rosa elegante */
  --luvee-secondary: #f4e4e6;    /* Rosa nude */
  --luvee-accent: #daa520;       /* Dourado */
  --luvee-charcoal: #4a453f;     /* Cinza escuro */
}
```

---

## 📱 Página de Demonstração

Acesse `/luvee-theme/` para ver todos os componentes em ação com diferentes variações e exemplos de uso.

---

**Última atualização:** Janeiro 2025