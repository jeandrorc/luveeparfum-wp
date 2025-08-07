<?php
/**
 * Exemplos de Uso da Product Section Avançada
 * Grid customizável + Carrossel
 */
?>

<!-- ========== EXEMPLOS DE GRID CUSTOMIZÁVEL ========== -->

<!-- Grid 2x2 (2 colunas x 2 linhas = 4 produtos) -->
<?php luvee_get_template_part('product-section', null, array(
  'title' => 'Produtos em Destaque',
  'subtitle' => 'Seleção especial em grid 2x2',
  'display_mode' => 'grid',
  'type' => 'featured',
  'columns' => 2,
  'rows' => 2,
  'show_view_all' => true,
  'section_id' => 'featured-2x2'
)); ?>

<!-- Grid 3x2 (3 colunas x 2 linhas = 6 produtos) -->
<?php luvee_get_template_part('product-section', null, array(
  'title' => 'Ofertas Especiais',
  'subtitle' => 'Promoções em grid balanceado',
  'display_mode' => 'grid',
  'type' => 'sale',
  'columns' => 3,
  'rows' => 2,
  'show_view_all' => true,
  'section_id' => 'sale-3x2'
)); ?>

<!-- Grid 4x3 (4 colunas x 3 linhas = 12 produtos) -->
<?php luvee_get_template_part('product-section', null, array(
  'title' => 'Catálogo Completo',
  'subtitle' => 'Grade extensa de produtos',
  'display_mode' => 'grid',
  'type' => 'recent',
  'columns' => 4,
  'rows' => 3,
  'show_view_all' => true,
  'section_id' => 'catalog-4x3'
)); ?>

<!-- Grid 6x1 (6 colunas x 1 linha = 6 produtos) -->
<?php luvee_get_template_part('product-section', null, array(
  'title' => 'Lançamentos',
  'subtitle' => 'Linha horizontal de novidades',
  'display_mode' => 'grid',
  'type' => 'recent',
  'columns' => 6,
  'rows' => 1,
  'show_view_all' => false,
  'section_id' => 'launches-6x1'
)); ?>

<!-- ========== EXEMPLOS DE CARROSSEL ========== -->

<!-- Carrossel Básico (4 produtos por slide) -->
<?php luvee_get_template_part('product-section', null, array(
  'title' => 'Produtos Populares',
  'subtitle' => 'Navegue pelos favoritos dos clientes',
  'display_mode' => 'carousel',
  'type' => 'featured',
  'columns' => 4,
  'limit' => 16, // 4 slides de 4 produtos cada
  'carousel_autoplay' => true,
  'carousel_speed' => 4000,
  'carousel_arrows' => true,
  'carousel_dots' => true,
  'show_view_all' => true,
  'section_id' => 'popular-carousel'
)); ?>

<!-- Carrossel Compacto (3 produtos por slide) -->
<?php luvee_get_template_part('product-section', null, array(
  'title' => 'Perfumes Premium',
  'subtitle' => 'Linha exclusiva de alta qualidade',
  'display_mode' => 'carousel',
  'type' => 'category',
  'category' => 'premium',
  'columns' => 3,
  'limit' => 12, // 4 slides de 3 produtos cada
  'carousel_autoplay' => false,
  'carousel_arrows' => true,
  'carousel_dots' => false,
  'show_view_all' => true,
  'section_id' => 'premium-carousel'
)); ?>

<!-- Carrossel Mobile-First (2 produtos por slide) -->
<?php luvee_get_template_part('product-section', null, array(
  'title' => 'Ofertas Relâmpago',
  'subtitle' => 'Promoções por tempo limitado',
  'display_mode' => 'carousel',
  'type' => 'sale',
  'columns' => 2,
  'limit' => 8, // 4 slides de 2 produtos cada
  'carousel_autoplay' => true,
  'carousel_speed' => 2000,
  'carousel_arrows' => false,
  'carousel_dots' => true,
  'show_view_all' => false,
  'section_id' => 'flash-deals'
)); ?>

<!-- Carrossel Showcase (1 produto por slide) -->
<?php luvee_get_template_part('product-section', null, array(
  'title' => 'Produto do Mês',
  'subtitle' => 'Destaque especial mensal',
  'display_mode' => 'carousel',
  'type' => 'featured',
  'columns' => 1,
  'limit' => 5, // 5 slides de 1 produto cada
  'carousel_autoplay' => true,
  'carousel_speed' => 5000,
  'carousel_arrows' => true,
  'carousel_dots' => true,
  'show_view_all' => false,
  'section_id' => 'product-of-month'
)); ?>

<!-- ========== EXEMPLOS AVANÇADOS ========== -->

<!-- Grid Assimétrico (5 colunas x 2 linhas) -->
<?php luvee_get_template_part('product-section', null, array(
  'title' => 'Coleção Especial',
  'subtitle' => 'Layout único para produtos exclusivos',
  'display_mode' => 'grid',
  'type' => 'category',
  'category' => 'colecao-especial',
  'columns' => 5,
  'rows' => 2,
  'show_view_all' => true,
  'section_id' => 'special-collection'
)); ?>

<!-- Carrossel com Muitos Produtos (6 por slide) -->
<?php luvee_get_template_part('product-section', null, array(
  'title' => 'Mega Catálogo',
  'subtitle' => 'Navegue por toda nossa coleção',
  'display_mode' => 'carousel',
  'type' => 'recent',
  'columns' => 6,
  'limit' => 30, // 5 slides de 6 produtos cada
  'carousel_autoplay' => false,
  'carousel_speed' => 3000,
  'carousel_arrows' => true,
  'carousel_dots' => true,
  'show_view_all' => true,
  'section_id' => 'mega-catalog'
)); ?>

<?php
/**
 * PARÂMETROS DISPONÍVEIS:
 * 
 * title         - Título da seção
 * subtitle      - Subtítulo (opcional)
 * type          - Tipo de produtos: 'featured', 'recent', 'sale', 'category'
 * category      - Slug da categoria (apenas para type='category')
 * columns       - Número de colunas: 2, 3, 4, 6
 * limit         - Quantidade de produtos a exibir
 * show_view_all - true/false para mostrar botão "Ver Todos"
 * section_id    - ID único da seção para CSS/JS
 * 
 * BENEFÍCIOS DA INTEGRAÇÃO:
 * ✅ Cards padronizados com content-product.php
 * ✅ Sistema de carrinho AJAX funcionando
 * ✅ Design moderno e responsivo
 * ✅ Animações AOS incluídas
 * ✅ Badges e wishlist integrados
 * ✅ Rating e preços formatados
 * ✅ Hover effects profissionais
 */
?>