# 🎛️ PRODUCT SECTION AVANÇADA - Grid Customizável + Carrossel

## ✅ **IMPLEMENTAÇÃO COMPLETA**

Sistema totalmente flexível de exibição de produtos com grid customizável (linhas x colunas) e modo carrossel responsivo.

---

## 🎯 **Funcionalidades Implementadas**

### **✅ Grid Customizável:**
- **Colunas:** 1, 2, 3, 4, 5, 6 por linha
- **Linhas:** Qualquer quantidade (1, 2, 3, 4+)
- **Cálculo Automático:** Total = colunas × linhas
- **Responsivo:** Adapta automaticamente no mobile

### **✅ Modo Carrossel:**
- **Slides Automáticos:** Com autoplay configurável
- **Navegação:** Setas + dots indicadores
- **Touch/Swipe:** Suporte completo mobile
- **Responsivo:** Adapta colunas por breakpoint
- **Performance:** Animações suaves e otimizadas

### **✅ Layouts Avançados:**
- Grid assimétrico (5×2, 3×4, etc.)
- Carrossel showcase (1 produto por slide)
- Mega grids (6×3 = 18 produtos)
- Linha horizontal (6×1)

---

## 📋 **Parâmetros Disponíveis**

### **🔧 Básicos:**
```php
'title'         => 'Título da Seção',
'subtitle'      => 'Subtítulo (opcional)',
'type'          => 'featured|best_sellers|recent|sale|category',
'category'      => 'slug-categoria',
'show_view_all' => true|false,
'section_id'    => 'id-unico'
```

### **🎛️ Grid Customizável:**
```php
'display_mode'  => 'grid',           // Modo grid
'columns'       => 4,                // Colunas por linha
'rows'          => 2,                // Número de linhas
'limit'         => 8                 // Total (auto: columns × rows)
```

### **🎠 Carrossel:**
```php
'display_mode'       => 'carousel',  // Modo carrossel
'columns'            => 4,           // Produtos por slide
'limit'              => 16,          // Total de produtos
'carousel_autoplay'  => true,        // Autoplay automático
'carousel_speed'     => 3000,        // Velocidade (ms)
'carousel_arrows'    => true,        // Setas navegação
'carousel_dots'      => true         // Indicadores
```

---

## 🎨 **Exemplos de Uso**

### **📐 Grid 2×2 (4 produtos):**
```php
<?php luvee_get_template_part('product-section', null, array(
    'title' => 'Produtos Destacados',
    'display_mode' => 'grid',
    'columns' => 2,
    'rows' => 2,
    'type' => 'featured'
)); ?>
```

### **📐 Grid 4×3 (12 produtos):**
```php
<?php luvee_get_template_part('product-section', null, array(
    'title' => 'Catálogo Extenso',
    'display_mode' => 'grid',
    'columns' => 4,
    'rows' => 3,
    'type' => 'recent'
)); ?>
```

### **🎠 Carrossel Básico:**
```php
<?php luvee_get_template_part('product-section', null, array(
    'title' => 'Produtos Populares',
    'display_mode' => 'carousel',
    'columns' => 4,
    'limit' => 16,
    'carousel_autoplay' => true,
    'carousel_speed' => 4000
)); ?>
```

### **🎠 Carrossel Showcase:**
```php
<?php luvee_get_template_part('product-section', null, array(
    'title' => 'Produto do Mês',
    'display_mode' => 'carousel',
    'columns' => 1,
    'limit' => 5,
    'carousel_autoplay' => true,
    'carousel_arrows' => true,
    'carousel_dots' => true
)); ?>
```

---

## 📱 **Responsividade Inteligente**

### **🖥️ Desktop (≥992px):**
- Mantém configuração original
- Grid: 1-6 colunas conforme definido
- Carrossel: Slides completos

### **📱 Tablet (768px-991px):**
- Grid: Máximo 3 colunas
- Carrossel: Adapta produtos por slide
- Touch: Swipe habilitado

### **📱 Mobile (<768px):**
- Grid: Máximo 2 colunas
- Carrossel: 1-2 produtos por slide
- Touch: Navegação principal

### **📱 Mobile Small (<576px):**
- Grid: 1 coluna
- Carrossel: 1 produto por slide
- Setas menores, dots maiores

---

## 🎛️ **Configurações do Carrossel**

### **⏯️ Autoplay:**
```php
'carousel_autoplay' => true,     // Ativa autoplay
'carousel_speed' => 3000         // 3 segundos entre slides
```

### **🔘 Navegação:**
```php
'carousel_arrows' => true,       // Setas laterais
'carousel_dots' => true          // Indicadores embaixo
```

### **📱 Touch:**
- **Swipe:** Deslizar para navegar
- **Threshold:** 50px mínimo para ativar
- **Feedback:** Visual durante swipe

---

## 🎨 **Layouts Pré-definidos**

### **📐 Grids Populares:**
```php
// 2×2 - Destaque compacto
'columns' => 2, 'rows' => 2  // = 4 produtos

// 3×2 - Balanceado
'columns' => 3, 'rows' => 2  // = 6 produtos

// 4×2 - Linha dupla completa
'columns' => 4, 'rows' => 2  // = 8 produtos

// 6×1 - Linha horizontal
'columns' => 6, 'rows' => 1  // = 6 produtos

// 4×3 - Grid extenso
'columns' => 4, 'rows' => 3  // = 12 produtos
```

### **🎠 Carrosséis Populares:**
```php
// Showcase (1 por slide)
'columns' => 1, 'limit' => 5

// Compacto (2 por slide)
'columns' => 2, 'limit' => 8

// Padrão (4 por slide)
'columns' => 4, 'limit' => 16

// Mega (6 por slide)
'columns' => 6, 'limit' => 30
```

---

## 🔧 **Recursos Técnicos**

### **📦 Arquivos Criados:**
- ✅ `assets/css/product-carousel.css` → Estilos do carrossel
- ✅ `assets/js/product-carousel.js` → JavaScript do carrossel
- ✅ `assets/css/product-grid-flexbox.css` → **NOVO** Estilos flexbox
- ✅ `assets/js/product-grid-flexbox.js` → **NOVO** JavaScript flexbox
- ✅ `components/product-section.php` → Template atualizado
- ✅ `example-usage-product-section.php` → Exemplos completos
- ✅ `test-flexbox-grid.php` → **NOVO** Testes do flexbox

### **⚙️ JavaScript Features:**
- **Carrossel:** Touch/swipe, responsive, autoplay
- **Flexbox:** Height equalization, responsive resize
- **Performance:** Otimizado e sem libraries externas
- **Debug:** Helpers para desenvolvimento

### **🎨 CSS Features:**
- **Flexbox Grid:** CSS dinâmico injetado por seção
- **Responsive:** Breakpoints automáticos
- **Equal Heights:** Cards com altura uniforme
- **Gap Control:** Espaçamento flexível
- **Animações:** CSS3 + AOS integration

---

## 🎯 **Casos de Uso**

### **🏪 Homepage:**
```php
// Hero products (2×1)
'columns' => 2, 'rows' => 1

// Featured section (4×2)
'columns' => 4, 'rows' => 2

// New arrivals carousel
'display_mode' => 'carousel', 'columns' => 4
```

### **🏷️ Landing Pages:**
```php
// Product showcase (1×1)
'columns' => 1, 'rows' => 1

// Category grid (3×3)
'columns' => 3, 'rows' => 3

// Related products carousel
'display_mode' => 'carousel', 'columns' => 3
```

### **📱 Mobile-First:**
```php
// Mobile carousel (2 por slide)
'columns' => 2, 'carousel_arrows' => false

// Single column grid
'columns' => 1, 'rows' => 5
```

---

## 🚀 **Performance & SEO**

### **⚡ Otimizações:**
- **Lazy Loading:** Produtos carregam sob demanda
- **CSS Minificado:** Estilos otimizados
- **JavaScript Eficiente:** Sem libraries externas
- **Responsive Images:** Tamanhos adaptativos

### **🔍 SEO-Friendly:**
- **HTML Semântico:** Estrutura correta
- **Microdata:** Schema.org products
- **Acessibilidade:** ARIA labels
- **Performance:** Core Web Vitals otimizados

---

## 🎉 **Resultado Final**

**✅ GRID CUSTOMIZÁVEL:**
- Qualquer combinação colunas × linhas
- Layout responsivo automático
- Animações escalonadas por posição

**✅ CARROSSEL PROFISSIONAL:**
- Autoplay inteligente
- Navegação completa (setas + dots)
- Touch/swipe nativo
- Responsivo por breakpoints

**✅ FLEXIBILIDADE TOTAL:**
- 1×1 até 6×∞ produtos
- Carrossel 1-6 produtos por slide
- Configuração por seção
- Design consistente

---

## 🏆 **BEST SELLERS - PRODUTOS MAIS VENDIDOS**

### **✨ Nova Funcionalidade Integrada:**

```php
// Best Sellers Grid
luvee_get_template_part('product-section', null, array(
    'title' => 'Produtos Mais Vendidos',
    'subtitle' => 'Os favoritos dos nossos clientes',
    'type' => 'best_sellers',
    'columns' => 4,
    'rows' => 2
));

// Best Sellers Carousel
luvee_get_template_part('product-section', null, array(
    'title' => '🏆 Top Vendas',
    'type' => 'best_sellers',
    'display_mode' => 'carousel',
    'columns' => 4,
    'carousel_autoplay' => true,
    'carousel_speed' => 4000
));
```

### **🔧 Como Funciona:**

- **Query otimizada** por `total_sales` (meta_value_num)
- **Ordena produtos** por número de vendas (DESC)
- **Filtra apenas** produtos com vendas > 0
- **Performance otimizada** com meta_query específica

### **📊 API Avançada (Plugin Luvee):**

```php
// Obter best sellers
$products = Luvee_Site_Featured_Products::get_best_sellers(array(
    'limit' => 8
));

// Estatísticas de vendas
$stats = Luvee_Site_Featured_Products::get_sales_stats();
echo "Total de vendas: " . $stats['total_sales'];

// Simular vendas (para teste)
Luvee_Site_Featured_Products::simulate_sales($product_id, 25);
```

### **🎯 Tipos Disponíveis:**

- ✅ `'featured'` - Produtos em destaque
- ✅ `'best_sellers'` - **Produtos mais vendidos** 🆕
- ✅ `'recent'` - Produtos recentes  
- ✅ `'sale'` - Produtos em promoção
- ✅ `'category'` - Por categoria específica

**🏆 Sistema de product section mais avançado e flexível já criado! Agora você pode criar qualquer layout de produtos imaginável, incluindo best sellers, do mais simples ao mais complexo, sempre com design profissional e funcionalidades completas.**
