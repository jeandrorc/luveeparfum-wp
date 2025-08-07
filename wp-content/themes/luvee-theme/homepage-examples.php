<?php
/**
 * Exemplos Práticos para Homepage
 * Como usar a product-section avançada na página inicial
 */
?>

<!-- Hero Section - Grid 2×1 (Produtos destaque principais) -->
<?php luvee_get_template_part('product-section', null, array(
  'title' => 'Produtos em Destaque',
  'subtitle' => 'Conheça nossa seleção especial de perfumes exclusivos',
  'display_mode' => 'grid',
  'type' => 'featured',
  'columns' => 2,
  'rows' => 1,
  'show_view_all' => false,
  'section_id' => 'hero-products'
)); ?>

<!-- Seção Principal - Grid 4×2 (Catálogo balanceado) -->
<?php luvee_get_template_part('product-section', null, array(
  'title' => 'Mais Vendidos',
  'subtitle' => 'Os perfumes preferidos dos nossos clientes',
  'display_mode' => 'grid',
  'type' => 'featured',
  'columns' => 4,
  'rows' => 2,
  'show_view_all' => true,
  'section_id' => 'bestsellers'
)); ?>

<!-- Carrossel de Novidades -->
<?php luvee_get_template_part('product-section', null, array(
  'title' => 'Novidades',
  'subtitle' => 'Confira os últimos lançamentos em perfumaria',
  'display_mode' => 'carousel',
  'type' => 'recent',
  'columns' => 4,
  'limit' => 12, // 3 slides de 4 produtos
  'carousel_autoplay' => true,
  'carousel_speed' => 4000,
  'carousel_arrows' => true,
  'carousel_dots' => true,
  'show_view_all' => true,
  'section_id' => 'new-arrivals'
)); ?>

<!-- Ofertas - Carrossel rápido -->
<?php luvee_get_template_part('product-section', null, array(
  'title' => 'Ofertas Especiais',
  'subtitle' => 'Promoções por tempo limitado',
  'display_mode' => 'carousel',
  'type' => 'sale',
  'columns' => 3,
  'limit' => 9, // 3 slides de 3 produtos
  'carousel_autoplay' => true,
  'carousel_speed' => 2500,
  'carousel_arrows' => false,
  'carousel_dots' => true,
  'show_view_all' => true,
  'section_id' => 'special-offers'
)); ?>

<!-- Showcase do Mês - Carrossel 1×1 -->
<?php luvee_get_template_part('product-section', null, array(
  'title' => 'Perfume do Mês',
  'subtitle' => 'Nossa seleção especial mensal',
  'display_mode' => 'carousel',
  'type' => 'featured',
  'columns' => 1,
  'limit' => 3, // 3 slides de 1 produto
  'carousel_autoplay' => true,
  'carousel_speed' => 6000,
  'carousel_arrows' => true,
  'carousel_dots' => true,
  'show_view_all' => false,
  'section_id' => 'perfume-of-month'
)); ?>

<!-- Categorias - Grid 6×1 (Linha horizontal) -->
<?php luvee_get_template_part('product-section', null, array(
  'title' => 'Explore por Categoria',
  'subtitle' => 'Encontre o perfume perfeito para cada ocasião',
  'display_mode' => 'grid',
  'type' => 'recent',
  'columns' => 6,
  'rows' => 1,
  'show_view_all' => true,
  'section_id' => 'categories-showcase'
)); ?>