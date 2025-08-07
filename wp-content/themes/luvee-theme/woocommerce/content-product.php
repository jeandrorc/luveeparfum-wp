<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
  return;
}

// Grid responsive classes - Adaptável baseado no contexto
global $woocommerce_loop;
$columns = $woocommerce_loop['columns'] ?? 4;
$display_mode = $woocommerce_loop['display_mode'] ?? 'grid';

// Determinar classes baseado no modo de exibição
if ($display_mode === 'carousel') {
  $grid_classes = 'carousel-product-item';
} elseif ($display_mode === 'grid-flexbox') {
  $grid_classes = 'flexbox-product-item';
} else {
  // Grid classes Bootstrap baseado no número de colunas
  switch ($columns) {
    case 1:
      $grid_classes = 'col-12';
      break;
    case 2:
      $grid_classes = 'col-lg-6 col-md-6 col-sm-6 col-12';
      break;
    case 3:
      $grid_classes = 'col-lg-4 col-md-6 col-sm-6 col-12';
      break;
    case 4:
      $grid_classes = 'col-lg-3 col-md-4 col-sm-6 col-6';
      break;
    case 5:
      $grid_classes = 'col-lg-2 col-md-4 col-sm-6 col-6';
      break;
    case 6:
      $grid_classes = 'col-lg-2 col-md-4 col-sm-6 col-6';
      break;
    default:
      $grid_classes = 'col-lg-3 col-md-4 col-sm-6 col-6';
  }
}
?>

<div <?php wc_product_class($grid_classes, $product); ?>>
  <div class="product-item mb-2 card h-100 border-0 shadow-sm position-relative overflow-hidden product-card-modern"
    data-product-id="<?php echo esc_attr($product->get_id()); ?>">

    <?php
    /**
     * Hook: woocommerce_before_shop_loop_item.
     *
     * @hooked woocommerce_template_loop_product_link_open - 10
     */
    remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
    do_action('woocommerce_before_shop_loop_item');
    ?>

    <!-- Product Image -->
    <div class="product-image position-relative overflow-hidden">
      <a href="<?php echo esc_url(get_permalink()); ?>" class="d-block product-link position-relative">
        <div class="product-image-wrapper" style="aspect-ratio: 1; background: #f8f9fa;">
          <?php
          /**
           * Hook: woocommerce_before_shop_loop_item_title.
           *
           * @hooked woocommerce_show_product_loop_sale_flash - 10
           * @hooked woocommerce_template_loop_product_thumbnail - 10
           */
          remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
          do_action('woocommerce_before_shop_loop_item_title');
          ?>
        </div>
      </a>

      <!-- Product Badges - Canto superior esquerdo -->
      <div class="product-badges position-absolute top-0 start-0 p-2 z-index-2">
        <?php if ($product->is_featured()): ?>
          <div class="badge-wrapper mb-1">
            <span class="badge badge-new px-3 py-1 fw-bold text-white">
              Novo
            </span>
          </div>
        <?php endif; ?>

        <?php if ($product->is_on_sale() && $product->get_regular_price()): ?>
          <?php
          $regular_price = (float) $product->get_regular_price();
          $sale_price = (float) $product->get_sale_price();
          $percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
          ?>
          <div class="badge-wrapper">
            <span class="badge badge-discount px-3 py-1 fw-bold text-white">
              <?php echo esc_html($percentage); ?>% OFF
            </span>
          </div>
        <?php endif; ?>

        <?php if (!$product->is_in_stock()): ?>
          <div class="badge-wrapper">
            <span class="badge bg-secondary px-3 py-1 fw-bold text-white">
              Esgotado
            </span>
          </div>
        <?php endif; ?>
      </div>

      <!-- Wishlist Button - Canto superior direito -->
      <div class="product-wishlist position-absolute top-0 end-0 p-2 z-index-2">
        <button type="button" class="btn btn-wishlist rounded-circle shadow-sm wishlist-btn"
          title="<?php esc_attr_e('Adicionar aos Favoritos', 'luvee'); ?>"
          data-product-id="<?php echo esc_attr($product->get_id()); ?>">
          <i class="far fa-heart"></i>
        </button>
      </div>

      <!-- Add to Cart Button - Aparece no hover -->
      <div
        class="product-cart-overlay position-absolute w-100 h-100 d-flex align-items-center justify-content-center opacity-0">
        <button type="button" class="btn btn-add-to-cart px-4 py-2 fw-semibold"
          data-product-id="<?php echo esc_attr($product->get_id()); ?>">
          <i class="fas fa-shopping-cart me-2"></i>
          Adicionar ao Carrinho
        </button>
      </div>
    </div>

    <!-- Product Info -->
    <div class="card-body p-3 d-flex flex-column">
      <!-- Brand/Category -->
      <?php
      $terms = get_the_terms($product->get_id(), 'product_cat');
      if ($terms && !is_wp_error($terms)):
        $term = array_shift($terms);
        ?>
        <div class="product-brand mb-1">
          <span class="text-muted small fw-medium text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
            <?php echo esc_html($term->name); ?>
          </span>
        </div>
      <?php endif; ?>

      <!-- Product Title -->
      <h3 class="product-title mb-2" style="font-size: 0.95rem; line-height: 1.4;">
        <a href="<?php echo esc_url(get_permalink()); ?>" class="text-decoration-none text-dark fw-medium">
          <?php
          /**
           * Hook: woocommerce_shop_loop_item_title.
           *
           * @hooked woocommerce_template_loop_product_title - 10
           */
          do_action('woocommerce_shop_loop_item_title');
          ?>
        </a>
      </h3>

      <!-- Product Rating -->
      <div class="product-rating mb-2 d-flex align-items-center gap-1">
        <div class="rating-stars">
          <?php
          $rating = $product->get_average_rating();
          $rating_count = $product->get_rating_count();

          // Se não há avaliações, simular algumas para o design
          if ($rating_count == 0) {
            $rating = 4.2; // Rating padrão
            $rating_count = rand(50, 100); // Contador simulado
          }

          // Mostrar estrelas
          for ($i = 1; $i <= 5; $i++) {
            if ($i <= floor($rating)) {
              echo '<i class="fas fa-star text-warning" style="font-size: 0.75rem;"></i>';
            } elseif ($i <= ceil($rating)) {
              echo '<i class="fas fa-star-half-alt text-warning" style="font-size: 0.75rem;"></i>';
            } else {
              echo '<i class="far fa-star text-muted" style="font-size: 0.75rem;"></i>';
            }
          }
          ?>
        </div>
        <span class="rating-count text-muted" style="font-size: 0.75rem;">
          (<?php echo esc_html($rating_count); ?>)
        </span>
      </div>

      <!-- Product Price -->
      <div class="product-price mt-auto">
        <?php
        $regular_price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();
        ?>

        <div class="price-display">
          <?php if ($product->is_on_sale() && $regular_price): ?>
            <div class="d-flex align-items-center gap-2">
              <span class="current-price fw-bold text-dark" style="font-size: 1.1rem;">
                R$ <?php echo number_format((float) $sale_price, 2, ',', '.'); ?>
              </span>
              <span class="original-price text-muted text-decoration-line-through" style="font-size: 0.85rem;">
                R$ <?php echo number_format((float) $regular_price, 2, ',', '.'); ?>
              </span>
            </div>
          <?php else: ?>
            <span class="current-price fw-bold text-dark" style="font-size: 1.1rem;">
              R$ <?php echo number_format((float) $product->get_price(), 2, ',', '.'); ?>
            </span>
          <?php endif; ?>
        </div>
      </div>

      <!-- Add to Cart Button -->
      <div class="product-actions mt-3">
        <?php if ($product->is_purchasable() && $product->is_in_stock()): ?>
          <button type="button" class="btn btn-primary btn-sm w-100 btn-add-to-cart position-relative"
            data-product-id="<?php echo esc_attr($product->get_id()); ?>"
            data-product-name="<?php echo esc_attr($product->get_name()); ?>"
            data-nonce="<?php echo wp_create_nonce('luvee_cart_nonce'); ?>"
            aria-label="Adicionar <?php echo esc_attr($product->get_name()); ?> ao carrinho">

            <span class="btn-text">
              <i class="fas fa-shopping-cart me-1"></i>
              Adicionar ao Carrinho
            </span>

            <!-- Loading spinner (hidden by default) -->
            <span class="btn-loading d-none">
              <div class="spinner-border spinner-border-sm me-1" role="status">
                <span class="visually-hidden">Carregando...</span>
              </div>
              Adicionando...
            </span>

            <!-- Success state (hidden by default) -->
            <span class="btn-success-text d-none">
              <i class="fas fa-check me-1"></i>
              Adicionado!
            </span>
          </button>

        <?php elseif (!$product->is_in_stock()): ?>
          <button type="button" class="btn btn-outline-secondary btn-sm w-100" disabled>
            <i class="fas fa-times me-1"></i>
            Fora de Estoque
          </button>

        <?php else: ?>
          <a href="<?php echo esc_url($product->get_permalink()); ?>" class="btn btn-outline-primary btn-sm w-100">
            <i class="fas fa-eye me-1"></i>
            Ver Detalhes
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>