<?php
/**
 * Shortcodes for Ecommerce Elements
 * 
 * Shortcodes para usar os elementos de ecommerce em qualquer lugar
 */

// Prevent direct access
if (!defined('ABSPATH')) {
  exit;
}

/**
 * Product Card Shortcode
 * Usage: [product_card id="123"]
 */
function astra_child_product_card_shortcode($atts)
{
  $atts = shortcode_atts(array(
    'id' => get_the_ID(),
    'show_badge' => 'yes',
    'show_category' => 'yes',
    'show_wishlist' => 'yes',
  ), $atts);

  $product_id = intval($atts['id']);

  if (!$product_id) {
    return '<p>' . esc_html__('No product selected', 'astra-child') . '</p>';
  }

  ob_start();

  // Get product data
  $product_price = get_post_meta($product_id, '_product_price', true);
  $product_old_price = get_post_meta($product_id, '_product_old_price', true);
  $product_badge = get_post_meta($product_id, '_product_badge', true);
  $product_categories = get_the_terms($product_id, 'product_category');

  // Calculate discount
  $discount_percentage = '';
  if ($product_old_price && $product_price) {
    $discount = (($product_old_price - $product_price) / $product_old_price) * 100;
    $discount_percentage = round($discount);
  }
  ?>

  <div class="product-card">
    <div class="product-card__image">
      <?php if (has_post_thumbnail($product_id)): ?>
        <?php echo get_the_post_thumbnail($product_id, 'medium'); ?>
      <?php else: ?>
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/placeholder-product.jpg"
          alt="<?php echo get_the_title($product_id); ?>">
      <?php endif; ?>

      <?php if ($product_badge && $atts['show_badge'] === 'yes'): ?>
        <div class="product-card__badge"><?php echo esc_html($product_badge); ?></div>
      <?php endif; ?>
    </div>

    <div class="product-card__content">
      <?php if ($product_categories && !is_wp_error($product_categories) && $atts['show_category'] === 'yes'): ?>
        <div class="product-card__category"><?php echo esc_html($product_categories[0]->name); ?></div>
      <?php endif; ?>

      <h3 class="product-card__title">
        <a href="<?php echo get_permalink($product_id); ?>"><?php echo get_the_title($product_id); ?></a>
      </h3>

      <div class="product-card__price">
        <?php if ($product_price): ?>
          <span class="product-card__current-price">R$ <?php echo number_format($product_price, 2, ',', '.'); ?></span>
        <?php endif; ?>

        <?php if ($product_old_price && $product_old_price > $product_price): ?>
          <span class="product-card__old-price">R$ <?php echo number_format($product_old_price, 2, ',', '.'); ?></span>
        <?php endif; ?>

        <?php if ($discount_percentage): ?>
          <span class="product-card__discount">-<?php echo $discount_percentage; ?>%</span>
        <?php endif; ?>
      </div>

      <div class="product-card__actions">
        <a href="<?php echo get_permalink($product_id); ?>" class="product-card__btn product-card__btn--primary">
          Ver Produto
        </a>
        <?php if ($atts['show_wishlist'] === 'yes'): ?>
          <button class="product-card__wishlist" data-product-id="<?php echo $product_id; ?>">
            <i class="fas fa-heart"></i>
          </button>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <?php
  return ob_get_clean();
}
add_shortcode('product_card', 'astra_child_product_card_shortcode');

/**
 * Product Grid Shortcode
 * Usage: [product_grid posts_per_page="8" category="eletronicos"]
 */
function astra_child_product_grid_shortcode($atts)
{
  $atts = shortcode_atts(array(
    'posts_per_page' => 8,
    'category' => '',
    'orderby' => 'date',
    'order' => 'DESC',
    'show_pagination' => 'no',
    'columns' => 3,
  ), $atts);

  // Build query args
  $args = [
    'post_type' => 'post',
    'posts_per_page' => intval($atts['posts_per_page']),
    'orderby' => $atts['orderby'],
    'order' => $atts['order'],
    'meta_query' => [
      [
        'key' => '_product_price',
        'compare' => 'EXISTS',
      ],
    ],
  ];

  // Add category filter
  if (!empty($atts['category'])) {
    $args['tax_query'] = [
      [
        'taxonomy' => 'product_category',
        'field' => 'slug',
        'terms' => $atts['category'],
      ],
    ];
  }

  $products_query = new WP_Query($args);

  ob_start();

  if ($products_query->have_posts()):
    ?>
    <div class="products-grid" style="grid-template-columns: repeat(<?php echo intval($atts['columns']); ?>, 1fr);">
      <?php while ($products_query->have_posts()):
        $products_query->the_post(); ?>
        <?php echo astra_child_product_card(); ?>
      <?php endwhile; ?>
    </div>

    <?php if ($atts['show_pagination'] === 'yes'): ?>
      <div class="products-pagination">
        <?php
        echo paginate_links([
          'total' => $products_query->max_num_pages,
          'current' => max(1, get_query_var('paged')),
          'prev_text' => '<i class="fas fa-chevron-left"></i> Anterior',
          'next_text' => 'Próximo <i class="fas fa-chevron-right"></i>',
        ]);
        ?>
      </div>
    <?php endif; ?>

    <?php
    wp_reset_postdata();
  else:
    ?>
    <p><?php esc_html_e('Nenhum produto encontrado.', 'astra-child'); ?></p>
    <?php
  endif;

  return ob_get_clean();
}
add_shortcode('product_grid', 'astra_child_product_grid_shortcode');

/**
 * Product Search Shortcode
 * Usage: [product_search]
 */
function astra_child_product_search_shortcode($atts)
{
  $atts = shortcode_atts(array(
    'placeholder' => 'Buscar produtos...',
    'button_text' => 'Buscar',
    'show_categories' => 'yes',
    'show_price_range' => 'yes',
  ), $atts);

  ob_start();
  ?>

  <div class="product-search">
    <form class="search-form" method="get" action="<?php echo esc_url(home_url('/')); ?>">
      <div class="search-inputs">
        <div class="search-field">
          <input type="text" name="s" placeholder="<?php echo esc_attr($atts['placeholder']); ?>"
            value="<?php echo get_search_query(); ?>" class="search-input">
          <input type="hidden" name="post_type" value="post">
        </div>

        <?php if ($atts['show_categories'] === 'yes'): ?>
          <div class="search-field">
            <select name="product_category" class="search-select">
              <option value=""><?php esc_html_e('Todas as Categorias', 'astra-child'); ?></option>
              <?php
              $categories = get_terms([
                'taxonomy' => 'product_category',
                'hide_empty' => false,
              ]);

              if (!is_wp_error($categories)) {
                foreach ($categories as $category) {
                  $selected = isset($_GET['product_category']) && $_GET['product_category'] == $category->slug ? 'selected' : '';
                  echo '<option value="' . esc_attr($category->slug) . '" ' . $selected . '>' . esc_html($category->name) . '</option>';
                }
              }
              ?>
            </select>
          </div>
        <?php endif; ?>

        <?php if ($atts['show_price_range'] === 'yes'): ?>
          <div class="search-field">
            <select name="price_range" class="search-select">
              <option value=""><?php esc_html_e('Qualquer Preço', 'astra-child'); ?></option>
              <option value="0-50" <?php selected(isset($_GET['price_range']) ? $_GET['price_range'] : '', '0-50'); ?>>
                <?php esc_html_e('Até R$ 50', 'astra-child'); ?>
              </option>
              <option value="50-100" <?php selected(isset($_GET['price_range']) ? $_GET['price_range'] : '', '50-100'); ?>>
                <?php esc_html_e('R$ 50 - R$ 100', 'astra-child'); ?>
              </option>
              <option value="100-200" <?php selected(isset($_GET['price_range']) ? $_GET['price_range'] : '', '100-200'); ?>>
                <?php esc_html_e('R$ 100 - R$ 200', 'astra-child'); ?>
              </option>
              <option value="200+" <?php selected(isset($_GET['price_range']) ? $_GET['price_range'] : '', '200+'); ?>>
                <?php esc_html_e('Acima de R$ 200', 'astra-child'); ?>
              </option>
            </select>
          </div>
        <?php endif; ?>

        <button type="submit" class="search-button">
          <i class="fas fa-search"></i>
          <?php echo esc_html($atts['button_text']); ?>
        </button>
      </div>
    </form>
  </div>

  <?php
  return ob_get_clean();
}
add_shortcode('product_search', 'astra_child_product_search_shortcode');

/**
 * Single Product Shortcode
 * Usage: [single_product id="123"]
 */
function astra_child_single_product_shortcode($atts)
{
  $atts = shortcode_atts(array(
    'id' => get_the_ID(),
    'show_gallery' => 'yes',
    'show_related' => 'yes',
    'related_count' => 4,
  ), $atts);

  $product_id = intval($atts['id']);

  if (!$product_id) {
    return '<p>' . esc_html__('No product selected', 'astra-child') . '</p>';
  }

  ob_start();

  // Get product data
  $product_price = get_post_meta($product_id, '_product_price', true);
  $product_old_price = get_post_meta($product_id, '_product_old_price', true);
  $product_sku = get_post_meta($product_id, '_product_sku', true);
  $product_badge = get_post_meta($product_id, '_product_badge', true);
  $product_categories = get_the_terms($product_id, 'product_category');

  // Calculate discount
  $discount_percentage = '';
  if ($product_old_price && $product_price) {
    $discount = (($product_old_price - $product_price) / $product_old_price) * 100;
    $discount_percentage = round($discount);
  }
  ?>

  <div class="single-product-container">
    <div class="product-gallery">
      <?php if ($atts['show_gallery'] === 'yes'): ?>
        <div class="product-images">
          <?php if (has_post_thumbnail($product_id)): ?>
            <div class="main-image">
              <?php echo get_the_post_thumbnail($product_id, 'large'); ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="product-info">
      <?php if ($product_badge): ?>
        <div class="product-badge"><?php echo esc_html($product_badge); ?></div>
      <?php endif; ?>

      <h1 class="product-title"><?php echo get_the_title($product_id); ?></h1>

      <?php if ($product_categories && !is_wp_error($product_categories)): ?>
        <div class="product-category">
          <?php esc_html_e('Categoria:', 'astra-child'); ?>
          <a href="<?php echo get_term_link($product_categories[0]); ?>">
            <?php echo esc_html($product_categories[0]->name); ?>
          </a>
        </div>
      <?php endif; ?>

      <div class="product-price">
        <?php if ($product_price): ?>
          <span class="current-price">R$ <?php echo number_format($product_price, 2, ',', '.'); ?></span>
        <?php endif; ?>

        <?php if ($product_old_price && $product_old_price > $product_price): ?>
          <span class="old-price">R$ <?php echo number_format($product_old_price, 2, ',', '.'); ?></span>
        <?php endif; ?>

        <?php if ($discount_percentage): ?>
          <span class="discount-badge">-<?php echo $discount_percentage; ?>%</span>
        <?php endif; ?>
      </div>

      <?php if ($product_sku): ?>
        <div class="product-sku">
          <strong><?php esc_html_e('SKU:', 'astra-child'); ?></strong> <?php echo esc_html($product_sku); ?>
        </div>
      <?php endif; ?>

      <div class="product-description">
        <?php echo get_the_excerpt($product_id); ?>
      </div>

      <div class="product-actions">
        <button class="add-to-cart-btn">
          <i class="fas fa-shopping-cart"></i>
          <?php esc_html_e('Adicionar ao Carrinho', 'astra-child'); ?>
        </button>

        <button class="wishlist-btn" data-product-id="<?php echo $product_id; ?>">
          <i class="fas fa-heart"></i>
          <?php esc_html_e('Adicionar à Lista de Desejos', 'astra-child'); ?>
        </button>
      </div>
    </div>
  </div>

  <?php if ($atts['show_related'] === 'yes'): ?>
    <div class="related-products">
      <h3><?php esc_html_e('Produtos Relacionados', 'astra-child'); ?></h3>
      <div class="products-grid">
        <?php
        $related_args = [
          'post_type' => 'post',
          'posts_per_page' => intval($atts['related_count']),
          'post__not_in' => [$product_id],
          'meta_query' => [
            [
              'key' => '_product_price',
              'compare' => 'EXISTS',
            ],
          ],
        ];

        if ($product_categories && !is_wp_error($product_categories)) {
          $related_args['tax_query'] = [
            [
              'taxonomy' => 'product_category',
              'field' => 'term_id',
              'terms' => $product_categories[0]->term_id,
            ],
          ];
        }

        $related_products = new WP_Query($related_args);

        if ($related_products->have_posts()):
          while ($related_products->have_posts()):
            $related_products->the_post();
            echo astra_child_product_card();
          endwhile;
          wp_reset_postdata();
        endif;
        ?>
      </div>
    </div>
  <?php endif; ?>

  <?php
  return ob_get_clean();
}
add_shortcode('single_product', 'astra_child_single_product_shortcode');

/**
 * Hero Carousel Shortcode
 * Usage: [hero_carousel]
 */
function astra_child_hero_carousel_shortcode($atts)
{
  $atts = shortcode_atts(array(
    'autoplay' => 'yes',
    'speed' => 5000,
    'show_navigation' => 'yes',
    'show_dots' => 'yes',
  ), $atts);

  ob_start();
  ?>

  <div class="hero-carousel" data-autoplay="<?php echo $atts['autoplay'] === 'yes' ? 'true' : 'false'; ?>"
    data-speed="<?php echo intval($atts['speed']); ?>">

    <div class="hero-carousel__container">
      <div class="hero-carousel__slide">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hero-1.jpg" alt="Slide 1"
          class="hero-carousel__image">
        <div class="hero-carousel__overlay"></div>
        <div class="hero-carousel__content">
          <h2 class="hero-carousel__title">Novos Produtos Chegaram</h2>
          <p class="hero-carousel__subtitle">Descubra nossa coleção mais recente com descontos especiais</p>
          <a href="#" class="hero-carousel__btn">Comprar Agora</a>
        </div>
      </div>

      <div class="hero-carousel__slide">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hero-2.jpg" alt="Slide 2"
          class="hero-carousel__image">
        <div class="hero-carousel__overlay"></div>
        <div class="hero-carousel__content">
          <h2 class="hero-carousel__title">Ofertas Imperdíveis</h2>
          <p class="hero-carousel__subtitle">Até 50% de desconto em produtos selecionados</p>
          <a href="#" class="hero-carousel__btn">Ver Ofertas</a>
        </div>
      </div>
    </div>

    <?php if ($atts['show_navigation'] === 'yes'): ?>
      <button class="hero-carousel__nav hero-carousel__nav--prev">
        <i class="fas fa-chevron-left"></i>
      </button>
      <button class="hero-carousel__nav hero-carousel__nav--next">
        <i class="fas fa-chevron-right"></i>
      </button>
    <?php endif; ?>

    <?php if ($atts['show_dots'] === 'yes'): ?>
      <div class="hero-carousel__dots">
        <button class="hero-carousel__dot active" data-slide="0"></button>
        <button class="hero-carousel__dot" data-slide="1"></button>
      </div>
    <?php endif; ?>
  </div>

  <?php
  return ob_get_clean();
}
add_shortcode('hero_carousel', 'astra_child_hero_carousel_shortcode');