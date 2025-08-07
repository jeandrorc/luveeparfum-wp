# Luvée Theme - Guia de Uso

## 🎨 Visão Geral

O tema Luvée é um tema WordPress moderno e refinado, desenvolvido especificamente para sites de beleza e estética. Utiliza Bootstrap 5, fonte Poppins e uma paleta de cores elegante focada na experiência do usuário.

## 🚀 Tecnologias Utilizadas

- **Bootstrap 5.3.3** - Framework CSS responsivo
- **Google Fonts Poppins** - Tipografia moderna (9 pesos)
- **Font Awesome 6.5.0** - Biblioteca de ícones
- **WooCommerce** - Compatibilidade total para e-commerce
- **CSS Variables** - Customização avançada de cores

## 🎨 Paleta de Cores

```css
/* Cores Primárias */
--luvee-primary: #d4869c;         /* Rosa elegante */
--luvee-primary-light: #e8b4c8;   /* Rosa claro */
--luvee-primary-dark: #b56b82;    /* Rosa escuro */

/* Cores Secundárias */
--luvee-secondary: #f4e4e6;       /* Rosa nude */
--luvee-accent: #daa520;          /* Dourado elegante */

/* Neutros */
--luvee-cream: #faf9f7;           /* Branco cremoso */
--luvee-charcoal: #4a453f;        /* Cinza elegante */
```

## 📁 Estrutura do Tema

```
wp-content/themes/luvee-theme/
├── style.css              # CSS principal com customizações
├── functions.php           # Funções do tema
├── header.php             # Cabeçalho básico
├── footer.php             # Rodapé básico
├── index.php              # Template padrão
├── front-page.php         # Página inicial
├── assets/
│   └── js/
│       └── theme.js       # JavaScript do tema
└── components/
    ├── header.php         # Header com Bootstrap
    ├── footer.php         # Footer com Bootstrap
    ├── hero.php           # Seção hero
    ├── product-section.php # Seção de produtos
    └── newsletter.php     # Newsletter
```

## 🔧 Componentes Disponíveis

### Header Component
```php
<?php luvee_get_template_part('header'); ?>
```

### Hero Section
```php
<?php 
luvee_get_template_part('hero', null, array(
    'title' => 'Seu Título',
    'subtitle' => 'Seu Subtítulo',
    'button_text' => 'Botão',
    'button_link' => '#',
    'style' => 'split', // ou 'default'
)); 
?>
```

### Product Section
```php
<?php 
luvee_get_template_part('product-section', null, array(
    'title' => 'Produtos em Destaque',
    'limit' => 8,
    'columns' => 4,
    'type' => 'featured', // featured, recent, sale, category
)); 
?>
```

### Newsletter
```php
<?php 
luvee_get_template_part('newsletter', null, array(
    'title' => 'Newsletter',
    'subtitle' => 'Receba novidades',
    'style' => 'elegant', // default, minimal, elegant
)); 
?>
```

## 🎛️ Customização

### Cores CSS
Para alterar as cores, modifique as variáveis CSS no `style.css`:

```css
:root {
  --luvee-primary: #sua-cor;
  --luvee-secondary: #sua-cor;
}
```

### Classes Bootstrap Customizadas
- `.btn-primary` - Botão com gradiente
- `.card` - Cards com sombra e hover
- `.hero` - Seção hero com background
- `.newsletter` - Newsletter com gradiente

### Classes Utilitárias Personalizadas
- `.py-section` - Padding vertical das seções
- `.text-primary-custom` - Cor primária do tema
- `.bg-cream` - Background cremoso
- `.text-muted-custom` - Texto cinza elegante

## 📱 Responsividade

O tema é mobile-first e utiliza o sistema de grid do Bootstrap:

- **xs**: < 576px (Mobile)
- **sm**: ≥ 576px (Mobile grande)
- **md**: ≥ 768px (Tablet)
- **lg**: ≥ 992px (Desktop)
- **xl**: ≥ 1200px (Desktop grande)
- **xxl**: ≥ 1400px (Desktop extra grande)

## 🛒 WooCommerce

### Funcionalidades Incluídas
- Header com carrinho e contador
- Busca de produtos
- Grid de produtos responsivo
- Cards de produtos com badges
- Links para conta e checkout

### Hooks Utilizados
- `woocommerce` theme support
- `wc-product-gallery-zoom`
- `wc-product-gallery-lightbox`
- `wc-product-gallery-slider`

## ⚙️ Funções Disponíveis

### luvee_get_template_part()
Carrega componentes com parâmetros:
```php
luvee_get_template_part('component-name', null, $args);
```

### Widgets Areas
- `sidebar-1` - Sidebar principal
- `footer-1`, `footer-2`, `footer-3` - Colunas do footer

## 🎯 Menus

### Localizações Disponíveis
- `primary` - Menu principal (header)
- `footer` - Menu do rodapé

### Configuração
1. Vá em **Aparência → Menus**
2. Crie um novo menu
3. Atribua à localização desejada

## 📈 Performance

### Otimizações Incluídas
- Carregamento condicional de scripts
- CSS minificado e otimizado
- Imagens responsivas
- Lazy loading (com AOS)
- Cache-friendly

### CDNs Utilizados
- Bootstrap (jsDelivr)
- Font Awesome (Cloudflare)
- Google Fonts

## 🔍 SEO

### Recursos Incluídos
- Suporte ao `title-tag`
- Meta tags otimizadas
- Estrutura semântica HTML5
- Schema.org markup (produtos)
- URLs amigáveis

## 🎨 Próximos Passos

1. **Customizer Integration** - Adicionar opções no Customizer
2. **AOS Animations** - Implementar animações on scroll
3. **Advanced JavaScript** - Interações mais complexas
4. **Page Builder Support** - Suporte a Elementor/Gutenberg
5. **Multilingual** - Suporte a tradução
6. **PWA Features** - Progressive Web App

## 🆘 Suporte

Para dúvidas sobre customização ou problemas técnicos:
- Verifique a documentação do Bootstrap 5
- Consulte a documentação do WooCommerce
- Teste sempre em ambiente de desenvolvimento

---

**Versão:** 2.0.0  
**Última atualização:** Janeiro 2025