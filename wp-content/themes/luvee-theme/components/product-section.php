<?php
/**
 * Product Section Component - Versão Avançada com Grid Customizável e Carrossel
 */

if (!function_exists('WC')) {
  return;
}

// Parâmetros básicos
$section_title = $args['title'] ?? 'Produtos em Destaque';
$section_subtitle = $args['subtitle'] ?? '';
$product_type = $args['type'] ?? 'featured'; // featured, recent, sale, category
$category_slug = $args['category'] ?? '';
$show_view_all = $args['show_view_all'] ?? true;
$section_id = $args['section_id'] ?? 'products-' . sanitize_title($section_title);

// Parâmetros de grid customizável
$display_mode = $args['display_mode'] ?? 'grid'; // 'grid' ou 'carousel'
$columns = $args['columns'] ?? 4; // Colunas por linha
$rows = $args['rows'] ?? 2; // Número de linhas (para grid)
$limit = $args['limit'] ?? ($columns * $rows); // Total de produtos

// Parâmetros do carrossel
$carousel_autoplay = $args['carousel_autoplay'] ?? true;
$carousel_speed = $args['carousel_speed'] ?? 3000; // ms
$carousel_arrows = $args['carousel_arrows'] ?? true;
$carousel_dots = $args['carousel_dots'] ?? true;
$carousel_responsive = $args['carousel_responsive'] ?? true;

// Query args baseado no tipo
$query_args = array(
  'limit' => $limit,
  'status' => 'publish',
);

switch ($product_type) {
  case 'recent':
    $query_args['orderby'] = 'date';
    $query_args['order'] = 'DESC';
    break;
  case 'sale':
    $query_args['on_sale'] = true;
    break;
  case 'category':
    if ($category_slug) {
      $query_args['category'] = array($category_slug);
    }
    break;
  case 'best_sellers':
    // Query específica para produtos mais vendidos
    $query_args['orderby'] = 'meta_value_num';
    $query_args['order'] = 'DESC';
    $query_args['meta_key'] = 'total_sales';
    $query_args['meta_query'] = array(
      array(
        'key' => 'total_sales',
        'value' => 0,
        'compare' => '>',
        'type' => 'NUMERIC'
      )
    );
    break;
  case 'featured':
  default:
    $query_args['featured'] = true;
    break;
}

$products = wc_get_products($query_args);

// Fallback para best_sellers se não houver produtos com vendas
if ($product_type === 'best_sellers' && empty($products)) {
  // Tentar buscar produtos recentes como alternativa
  $fallback_args = array(
    'limit' => $limit,
    'status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC'
  );
  $products = wc_get_products($fallback_args);

  // Log do fallback
  
}
?>

<section id="<?php echo esc_attr($section_id); ?>" class="product-section py-section">
  <div class="container-fluid container-xxl">

    <!-- Section Header -->
    <div class="row mb-5">
      <div class="col-12">
        <div class="section-header text-center">
          <h2 class="section-title mb-3" data-aos="fade-up">
            <?php echo esc_html($section_title); ?>
          </h2>

          <?php if ($section_subtitle): ?>
            <p class="section-subtitle lead text-muted-custom mx-auto mb-0" style="max-width: 600px;" data-aos="fade-up"
              data-aos-delay="100">
              <?php echo esc_html($section_subtitle); ?>
            </p>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Products Display -->
    <?php if (!empty($products)): ?>

      <?php if ($display_mode === 'carousel'): ?>
        <!-- Carousel Mode -->
        <div class="products-carousel-container" data-aos="fade-up" data-aos-delay="200">
          <div class="products-carousel" id="<?php echo esc_attr($section_id); ?>-carousel"
            data-autoplay="<?php echo $carousel_autoplay ? 'true' : 'false'; ?>"
            data-speed="<?php echo esc_attr($carousel_speed); ?>"
            data-arrows="<?php echo $carousel_arrows ? 'true' : 'false'; ?>"
            data-dots="<?php echo $carousel_dots ? 'true' : 'false'; ?>" data-columns="<?php echo esc_attr($columns); ?>">

            <?php
            global $woocommerce_loop;
            $woocommerce_loop['columns'] = $columns;
            $woocommerce_loop['is_shortcode'] = true;
            $woocommerce_loop['display_mode'] = 'carousel';

            foreach ($products as $index => $product):
              $GLOBALS['product'] = $product;
              setup_postdata($GLOBALS['post'] = get_post($product->get_id()));
              ?>

              <div class="carousel-item">
                <?php wc_get_template_part('content', 'product'); ?>
              </div>

              <?php
            endforeach;
            wp_reset_postdata();
            ?>
          </div>
        </div>

      <?php else: ?>
        <!-- Grid Mode -->
        <div class="products-grid-container" data-aos="fade-up" data-aos-delay="200">
          <div class="products-grid-flexbox" id="<?php echo esc_attr($section_id); ?>-grid"
            data-columns="<?php echo esc_attr($columns); ?>" data-rows="<?php echo esc_attr($rows); ?>">

            <?php
            global $woocommerce_loop;
            $woocommerce_loop['columns'] = $columns;
            $woocommerce_loop['rows'] = $rows;
            $woocommerce_loop['is_shortcode'] = true;
            $woocommerce_loop['display_mode'] = 'grid-flexbox';

            foreach ($products as $index => $product):
              $GLOBALS['product'] = $product;
              setup_postdata($GLOBALS['post'] = get_post($product->get_id()));

              // Calcular delay baseado na posição
              $row = floor($index / $columns);
              $col = $index % $columns;
              $delay = ($row * 100) + ($col * 50);
              ?>

              <div class="product-grid-item" data-aos="zoom-in" data-aos-delay="<?php echo $delay; ?>">
                <?php wc_get_template_part('content', 'product'); ?>
              </div>

              <?php
            endforeach;
            wp_reset_postdata();
            ?>
          </div>
        </div>

        <!-- CSS Dinâmico para Grid Flexbox -->
        <style>
          #<?php echo esc_attr($section_id);

          ?>  -grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 0;
          padding: 0;
        }




  

                     
             
             
          #<?php echo esc_attr($section_id);

          ?>-grid .product-grid-item {
          flex: 0 0 calc((100% - <?php echo ($columns - 1) * 20; ?> px) / <?php echo $columns; ?>);
            max-width: calc((100% - <?php echo ($columns - 1) * 20; ?> px) / <?php echo $columns; ?>);
            margin: 0;
          }

          #<?php echo esc_attr($section_id);

          ?>-grid .product-grid-item .product {
            margin-bottom: 0;
          width: 100%;
  
  
               }
  
          /* Responsivo Tablet */
        @media (max-width: 1199.98px) {

            <?php if ($columns > 4): ?> #<?php echo esc_attr($section_id);

                  ?>-grid .product-grid-item {
              flex: 0 0 calc((100% - 60px) / 4);
                max-width: calc((100% - 60px) / 4);
            }
  

         
            <?php endif;
            ?>
        }
  
        @media (max-width: 991.98px) {
  
          <?php if ($columns > 3): ?> #<?php echo esc_attr($section_id);

                ?>-grid .product-grid-item {
              flex: 0 0 calc((100% - 40px) / 3);
                max-width: calc((100% - 4
              0px) / 3);
              }
  
            <?php endif;
          ?>
          }

          /* Responsivo Mobile */
        @media (max-width: 767.98px) {
  
            <?php if ($columns > 2): ?> #<?php echo esc_attr($section_id);

                  ?>-grid .product-grid-item {
              flex: 0 0 calc((100% - 20px) / 2);
                max-width: calc((100% - 20px) / 2);
              }
  
            <?php endif;
            ?>
        }

        @media (max-width: 575.98px) {
          #<?php echo esc_attr($section_id);

          ?>-grid {
            gap: 15px;
          }

          #<?php echo esc_attr($section_id);

          ?>-grid .product-grid-item {
            flex: 0 0 100%;
            max-width: 100%;
          }
        }
        </style>

      <?php endif; ?>

      <!-- View All Button -->
      <?php if ($show_view_all): ?>
        <div class="row mt-5">
          <div class="col-12 text-center" data-aos="fade-up" data-aos-delay="300">
            <a href="<?php echo get_permalink(get_option('woocommerce_shop_page_id')); ?>"
              class="btn btn-outline-primary btn-lg">
              <i class="fas fa-th me-2"></i>
              Ver Todos os Produtos
            </a>
          </div>
        </div>
      <?php endif; ?>

    <?php else: ?>
      <!-- No products message -->
      <div class="row">
        <div class="col-12">
          <div class="no-products text-center py-5">
            <i class="fas fa-box-open fa-4x text-muted-custom mb-3"></i>
            <h4 class="text-muted-custom">Nenhum produto encontrado</h4>
            <p class="text-muted-custom">
              Não há produtos disponíveis nesta seção no momento.
            </p>
            <a href="<?php echo get_permalink(get_option('woocommerce_shop_page_id')); ?>" class="btn btn-primary">
              Explorar Catálogo
            </a>
          </div>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>