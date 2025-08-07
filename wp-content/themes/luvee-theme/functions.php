<?php
/**
 * Luvée Theme Functions - Versão com Bootstrap 5 e Design Refinado
 */

// Prevenir acesso direto
if (!defined('ABSPATH')) {
  exit;
}

// Incluir helpers do carrinho
require_once get_template_directory() . '/inc/cart-helpers.php';

/**
 * Setup do Tema
 */
function luvee_theme_setup()
{
  // Suporte a recursos do WordPress
  add_theme_support('post-thumbnails');
  add_theme_support('title-tag');
  add_theme_support('custom-logo');
  add_theme_support('html5', array(
    'search-form',
    'comment-form',
    'comment-list',
    'gallery',
    'caption',
  ));

  // Suporte ao WooCommerce
  add_theme_support('woocommerce');
  add_theme_support('wc-product-gallery-zoom');
  add_theme_support('wc-product-gallery-lightbox');
  add_theme_support('wc-product-gallery-slider');

  // Registrar menus
  register_nav_menus(array(
    'primary' => __('Menu Principal', 'luvee-theme'),
    'footer' => __('Menu Rodapé', 'luvee-theme'),
  ));

  // Suporte a tamanhos de imagem customizados
  add_image_size('luvee-hero', 1920, 800, true);
  add_image_size('luvee-product', 400, 400, true);
  add_image_size('luvee-thumbnail', 300, 300, true);
}
add_action('after_setup_theme', 'luvee_theme_setup');

/**
 * Enqueue Scripts e Styles
 */
function luvee_enqueue_scripts()
{
  // Debug
  error_log('🔧 Luvee: Enqueue scripts executado');

  // FORÇAR JQUERY NO FRONTEND
  wp_enqueue_script('jquery');
  // Google Fonts - Poppins
  wp_enqueue_style(
    'luvee-google-fonts',
    'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
    array(),
    null
  );

  // Font Awesome (mantido para compatibilidade com plugins)
  wp_enqueue_style(
    'font-awesome',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css',
    array(),
    '6.5.0'
  );

  // Heroicons - Sistema customizado implementado via JavaScript
  // Ícones modernos e performáticos carregados dinamicamente

  // Bootstrap 5 CSS
  wp_enqueue_style(
    'bootstrap',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
    array(),
    '5.3.3'
  );

  // CSS Principal (customizado)
  wp_enqueue_style(
    'luvee-style',
    get_stylesheet_uri(),
    array('bootstrap', 'luvee-google-fonts', 'font-awesome'),
    wp_get_theme()->get('Version')
  );

  // Product Card Global Styles (disponível em todas as páginas)
  wp_enqueue_style(
    'luvee-product-card-global',
    get_template_directory_uri() . '/assets/css/product-card-global.css',
    array('luvee-style'),
    wp_get_theme()->get('Version')
  );

  // Bootstrap 5 JavaScript
  wp_enqueue_script(
    'bootstrap-bundle',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
    array(),
    '5.3.3',
    true
  );

  // Tema JavaScript (quando necessário)
  wp_enqueue_script(
    'luvee-script',
    get_template_directory_uri() . '/assets/js/theme.js',
    array('bootstrap-bundle'),
    wp_get_theme()->get('Version'),
    true
  );

  // Header Enhancements com debug
  $header_js_url = get_template_directory_uri() . '/assets/js/header-enhancements.js';
  $heroicons_js_url = get_template_directory_uri() . '/assets/js/heroicons-simple.js';

  error_log('🔧 Luvee: Enfileirando ' . $header_js_url);
  error_log('🔧 Luvee: Enfileirando ' . $heroicons_js_url);

  // IMPORTANTE: Garantir que jQuery está carregado
  wp_enqueue_script('jquery');

  wp_enqueue_script(
    'luvee-test-simple',
    get_template_directory_uri() . '/assets/js/test-simple.js',
    array(),
    time(),
    true
  );

  wp_enqueue_script(
    'luvee-jquery-test',
    get_template_directory_uri() . '/assets/js/jquery-test.js',
    array('jquery'),
    time(),
    true
  );

  wp_enqueue_script(
    'luvee-heroicons-simple',
    $heroicons_js_url,
    array('jquery'),
    wp_get_theme()->get('Version') . '.' . time(),
    true
  );

  wp_enqueue_script(
    'luvee-header-enhancements',
    $header_js_url,
    array('jquery', 'luvee-heroicons-simple'),
    wp_get_theme()->get('Version') . '.' . time(),
    true
  );

  // Debug info SEMPRE ativo para diagnosticar
  wp_add_inline_script('luvee-header-enhancements', '
    console.log("🎯 Tema ativo: ' . get_option('stylesheet') . '");
    console.log("📁 URL base: ' . get_template_directory_uri() . '");
    console.log("⏰ Timestamp: ' . time() . '");
    console.log("🔧 Script timestamp: ' . time() . '");
  ');

  // Localização para JavaScript
  wp_localize_script('luvee-script', 'luvee_ajax', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('luvee_nonce'),
    'home_url' => home_url(),
  ));

  // Scripts básicos WordPress
  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
}
add_action('wp_enqueue_scripts', 'luvee_enqueue_scripts');

/**
 * Função para incluir componentes
 */
function luvee_get_template_part($slug, $name = null, $args = array())
{
  $templates = array();

  if ($name) {
    $templates[] = "components/{$slug}-{$name}.php";
  }
  $templates[] = "components/{$slug}.php";

  // Tornar $args disponível no template
  if (!empty($args)) {
    extract($args);
  }

  $located = locate_template($templates);

  if ($located) {
    include $located;
  }
}

/**
 * Personalizar excerpt
 */
function luvee_excerpt_length($length)
{
  return 25;
}
add_filter('excerpt_length', 'luvee_excerpt_length');

function luvee_excerpt_more($more)
{
  return '...';
}
add_filter('excerpt_more', 'luvee_excerpt_more');

/**
 * Remover versão do WordPress
 */
remove_action('wp_head', 'wp_generator');

/**
 * Limpar wp_head
 */
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_shortlink_wp_head');

/**
 * Widgets Areas
 */
function luvee_widgets_init()
{
  register_sidebar(array(
    'name' => __('Sidebar Principal', 'luvee-theme'),
    'id' => 'sidebar-1',
    'description' => __('Widgets para a sidebar principal', 'luvee-theme'),
    'before_widget' => '<div id="%1$s" class="widget %2$s mb-4">',
    'after_widget' => '</div>',
    'before_title' => '<h3 class="widget-title h5 mb-3">',
    'after_title' => '</h3>',
  ));

  register_sidebar(array(
    'name' => __('Footer 1', 'luvee-theme'),
    'id' => 'footer-1',
    'description' => __('Primeira coluna do rodapé', 'luvee-theme'),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h4 class="widget-title h6 mb-3">',
    'after_title' => '</h4>',
  ));

  register_sidebar(array(
    'name' => __('Footer 2', 'luvee-theme'),
    'id' => 'footer-2',
    'description' => __('Segunda coluna do rodapé', 'luvee-theme'),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h4 class="widget-title h6 mb-3">',
    'after_title' => '</h4>',
  ));

  register_sidebar(array(
    'name' => __('Footer 3', 'luvee-theme'),
    'id' => 'footer-3',
    'description' => __('Terceira coluna do rodapé', 'luvee-theme'),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h4 class="widget-title h6 mb-3">',
    'after_title' => '</h4>',
  ));
}
add_action('widgets_init', 'luvee_widgets_init');

/**
 * Adicionar classes Bootstrap aos menus
 */
function luvee_nav_menu_css_class($classes, $item, $args)
{
  if (isset($args->add_li_class)) {
    $classes[] = $args->add_li_class;
  }
  return $classes;
}
add_filter('nav_menu_css_class', 'luvee_nav_menu_css_class', 10, 3);

function luvee_nav_menu_link_attributes($atts, $item, $args)
{
  if (isset($args->add_link_class)) {
    $atts['class'] = $args->add_link_class;
  }
  return $atts;
}
add_filter('nav_menu_link_attributes', 'luvee_nav_menu_link_attributes', 10, 3);

/**
 * Criar diretório JS se não existir
 */
function luvee_create_js_directory()
{
  $js_dir = get_template_directory() . '/assets/js';
  if (!file_exists($js_dir)) {
    wp_mkdir_p($js_dir);
  }
}
add_action('init', 'luvee_create_js_directory');

/**
 * Shop Page Functions
 */

/**
 * Get product price range for filter slider
 */
function luvee_get_product_price_range()
{
  global $wpdb;

  // Buscar preços diretamente do banco de dados
  $prices = $wpdb->get_row("
    SELECT 
      MIN(CAST(meta_value AS DECIMAL(10,2))) as min_price,
      MAX(CAST(meta_value AS DECIMAL(10,2))) as max_price
    FROM {$wpdb->postmeta} pm
    LEFT JOIN {$wpdb->posts} p ON pm.post_id = p.ID
    WHERE p.post_type = 'product' 
    AND p.post_status = 'publish'
    AND pm.meta_key = '_price'
    AND pm.meta_value != ''
  ");

  if (!$prices) {
    return array(
      'min' => 0,
      'max' => 1000
    );
  }

  return array(
    'min' => floor($prices->min_price ?: 0),
    'max' => ceil($prices->max_price ?: 1000)
  );
}

/**
 * Add shop filters to WooCommerce query
 */
function luvee_shop_filters_query($q)
{
  if (!is_admin() && $q->is_main_query()) {
    if (is_shop() || is_product_category() || is_product_tag()) {
      $meta_query = array();
      $tax_query = array();

      // Price filter
      if (isset($_GET['min_price']) && !empty($_GET['min_price'])) {
        $meta_query[] = array(
          'key' => '_price',
          'value' => floatval($_GET['min_price']),
          'compare' => '>=',
          'type' => 'NUMERIC'
        );
      }

      if (isset($_GET['max_price']) && !empty($_GET['max_price'])) {
        $meta_query[] = array(
          'key' => '_price',
          'value' => floatval($_GET['max_price']),
          'compare' => '<=',
          'type' => 'NUMERIC'
        );
      }

      // Category filter
      if (isset($_GET['product_cat']) && !empty($_GET['product_cat'])) {
        $tax_query[] = array(
          'taxonomy' => 'product_cat',
          'field' => 'slug',
          'terms' => sanitize_text_field($_GET['product_cat'])
        );
      }

      // Tag filter
      if (isset($_GET['product_tag']) && !empty($_GET['product_tag'])) {
        $tax_query[] = array(
          'taxonomy' => 'product_tag',
          'field' => 'slug',
          'terms' => sanitize_text_field($_GET['product_tag'])
        );
      }

      // Rating filter
      if (isset($_GET['rating_filter']) && !empty($_GET['rating_filter'])) {
        $meta_query[] = array(
          'key' => '_wc_average_rating',
          'value' => intval($_GET['rating_filter']),
          'compare' => '>=',
          'type' => 'NUMERIC'
        );
      }

      // On sale filter
      if (isset($_GET['on_sale']) && $_GET['on_sale'] == '1') {
        $q->set('meta_query', array(
          array(
            'relation' => 'OR',
            array(
              'key' => '_sale_price',
              'value' => 0,
              'compare' => '>',
              'type' => 'NUMERIC'
            ),
            array(
              'key' => '_min_variation_sale_price',
              'value' => 0,
              'compare' => '>',
              'type' => 'NUMERIC'
            ),
          )
        ));
      }

      // In stock filter
      if (isset($_GET['in_stock']) && $_GET['in_stock'] == '1') {
        $meta_query[] = array(
          'key' => '_stock_status',
          'value' => 'instock',
          'compare' => '='
        );
      }

      // Featured filter
      if (isset($_GET['featured']) && $_GET['featured'] == '1') {
        $tax_query[] = array(
          'taxonomy' => 'product_visibility',
          'field' => 'name',
          'terms' => 'featured'
        );
      }

      if (!empty($meta_query)) {
        $q->set('meta_query', $meta_query);
      }

      if (!empty($tax_query)) {
        $q->set('tax_query', $tax_query);
      }
    }
  }
}
add_action('pre_get_posts', 'luvee_shop_filters_query');

/**
 * Cart AJAX Scripts - Global (todas as páginas)
 */
function luvee_cart_scripts()
{
  // Cart AJAX JavaScript
  wp_enqueue_script(
    'luvee-cart-ajax',
    get_template_directory_uri() . '/assets/js/cart-ajax.js',
    array('jquery'),
    wp_get_theme()->get('Version'),
    true
  );

  // Cart AJAX Localization
  wp_localize_script('luvee-cart-ajax', 'luvee_cart_ajax', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('luvee_cart_nonce'),
    'cart_url' => wc_get_cart_url(),
    'checkout_url' => wc_get_checkout_url()
  ));

  // Mini-cart CSS
  wp_enqueue_style(
    'luvee-mini-cart',
    get_template_directory_uri() . '/assets/css/mini-cart.css',
    array(),
    wp_get_theme()->get('Version')
  );

  // Product Carousel CSS
  wp_enqueue_style(
    'luvee-product-carousel',
    get_template_directory_uri() . '/assets/css/product-carousel.css',
    array(),
    wp_get_theme()->get('Version')
  );

  // Product Grid Flexbox CSS (clean version)
  wp_enqueue_style(
    'luvee-product-grid-flexbox',
    get_template_directory_uri() . '/assets/css/product-grid-flexbox-clean.css',
    array('luvee-product-card-global'),
    wp_get_theme()->get('Version')
  );

  // Product Carousel JavaScript
  wp_enqueue_script(
    'luvee-product-carousel',
    get_template_directory_uri() . '/assets/js/product-carousel.js',
    array('jquery'),
    wp_get_theme()->get('Version'),
    true
  );

  // Product Grid Flexbox JavaScript
  wp_enqueue_script(
    'luvee-product-grid-flexbox',
    get_template_directory_uri() . '/assets/js/product-grid-flexbox.js',
    array('jquery'),
    wp_get_theme()->get('Version'),
    true
  );
}
add_action('wp_enqueue_scripts', 'luvee_cart_scripts');

/**
 * Enqueue shop scripts and styles
 */
function luvee_shop_scripts()
{
  if (is_shop() || is_product_category() || is_product_tag()) {
    wp_enqueue_script(
      'luvee-shop-filters',
      get_template_directory_uri() . '/assets/js/shop-filters.js',
      array('jquery', 'luvee-heroicons-simple', 'luvee-header-enhancements', 'luvee-cart-ajax'),
      wp_get_theme()->get('Version'),
      true
    );

    wp_localize_script('luvee-shop-filters', 'luvee_shop_ajax', array(
      'ajax_url' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('luvee_shop_filters_nonce'),
      'shop_url' => get_permalink(wc_get_page_id('shop'))
    ));

    wp_enqueue_style(
      'luvee-shop-styles',
      get_template_directory_uri() . '/assets/css/shop.css',
      array(),
      wp_get_theme()->get('Version')
    );

    // Estilos modernos da página de produtos - PRIORIDADE ALTA
    wp_enqueue_style(
      'luvee-shop-modern',
      get_template_directory_uri() . '/assets/css/shop-modern.css',
      array(), // Remove dependências para carregar primeiro
      wp_get_theme()->get('Version') . '.' . time() // Force refresh
    );

    // Adicionar CSS inline para garantir fundo claro
    wp_add_inline_style('luvee-shop-modern', '
      html, body, body.woocommerce, body.woocommerce-page {
        background: #fefefe !important;
        background-color: #fefefe !important;
      }
      .shop-page-wrapper {
        background: linear-gradient(135deg, #fefefe 0%, #f8f9fa 100%) !important;
      }
    ');

    // Garantir que os heroicons funcionem na página de produtos
    wp_add_inline_script('luvee-shop-filters', '
      // Reforçar inicialização dos heroicons na página de produtos
      jQuery(document).ready(function($) {
        console.log("🛍️ Página de produtos: inicializando heroicons...");
        
        // Aguardar um pouco para garantir que tudo carregou
        setTimeout(function() {
          if (typeof window.simpleHeroicons === "function") {
            window.simpleHeroicons();
            console.log("✅ Heroicons reaplicados na página de produtos");
          }
          
          if (typeof initHeroicons === "function") {
            initHeroicons();
            console.log("✅ initHeroicons executado na página de produtos");
          }
        }, 500);
        
        // Backup adicional
        setTimeout(function() {
          if (typeof window.simpleHeroicons === "function") {
            window.simpleHeroicons();
          }
        }, 2000);
      });
    ');
  }
}
add_action('wp_enqueue_scripts', 'luvee_shop_scripts');

/**
 * AJAX handler for shop filters
 */
function luvee_ajax_shop_filters()
{
  check_ajax_referer('luvee_shop_filters_nonce', 'nonce');

  // Get filter parameters
  $filters = array(
    'min_price' => isset($_POST['min_price']) ? floatval($_POST['min_price']) : '',
    'max_price' => isset($_POST['max_price']) ? floatval($_POST['max_price']) : '',
    'product_cat' => isset($_POST['product_cat']) ? sanitize_text_field($_POST['product_cat']) : '',
    'product_tag' => isset($_POST['product_tag']) ? sanitize_text_field($_POST['product_tag']) : '',
    'rating_filter' => isset($_POST['rating_filter']) ? intval($_POST['rating_filter']) : '',
    'on_sale' => isset($_POST['on_sale']) ? 1 : 0,
    'in_stock' => isset($_POST['in_stock']) ? 1 : 0,
    'featured' => isset($_POST['featured']) ? 1 : 0,
    'orderby' => isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : 'menu_order',
    'paged' => isset($_POST['paged']) ? intval($_POST['paged']) : 1,
  );

  // Build query args
  $args = array(
    'post_type' => 'product',
    'post_status' => 'publish',
    'posts_per_page' => get_option('posts_per_page'),
    'paged' => $filters['paged'],
    'orderby' => $filters['orderby'],
    'meta_query' => array(),
    'tax_query' => array()
  );

  // Apply filters (similar to the pre_get_posts function)
  // ... (filter logic here)

  $query = new WP_Query($args);

  ob_start();

  if ($query->have_posts()) {
    echo '<div class="row" data-columns="6">';
    while ($query->have_posts()) {
      $query->the_post();
      wc_get_template_part('content', 'product');
    }
    echo '</div>';

    // Pagination
    echo '<div class="shop-pagination">';
    echo paginate_links(array(
      'total' => $query->max_num_pages,
      'current' => $filters['paged'],
      'format' => '?paged=%#%',
      'prev_text' => '&laquo; ' . __('Anterior', 'luvee'),
      'next_text' => __('Próximo', 'luvee') . ' &raquo;',
    ));
    echo '</div>';
  } else {
    echo '<div class="no-products-found text-center py-5">';
    echo '<h3>' . __('Nenhum produto encontrado', 'luvee') . '</h3>';
    echo '<p>' . __('Tente ajustar seus filtros ou navegar por outras categorias.', 'luvee') . '</p>';
    echo '</div>';
  }

  $content = ob_get_clean();
  wp_reset_postdata();

  wp_send_json_success(array(
    'content' => $content,
    'found_posts' => $query->found_posts,
    'max_pages' => $query->max_num_pages
  ));
}
add_action('wp_ajax_luvee_shop_filters', 'luvee_ajax_shop_filters');
add_action('wp_ajax_nopriv_luvee_shop_filters', 'luvee_ajax_shop_filters');

/**
 * AJAX Handlers for Cart Functionality
 */

/**
 * Add product to cart via AJAX
 */
function luvee_ajax_add_to_cart()
{
  check_ajax_referer('luvee_cart_nonce', 'nonce');

  // Verificar se WooCommerce está disponível
  if (!function_exists('WC') || !WC()->cart) {
    wp_send_json_error(array('message' => 'WooCommerce não disponível'));
    return;
  }

  $product_id = intval($_POST['product_id']);
  $quantity = intval($_POST['quantity']) ?: 1;
  $variation_id = intval($_POST['variation_id']) ?: 0;
  $variation = isset($_POST['variation']) ? $_POST['variation'] : array();

  if (!$product_id) {
    wp_send_json_error(array('message' => 'Produto não encontrado'));
  }

  // Clear any previous notices
  wc_clear_notices();

  // Add to cart
  $added = false;
  if ($variation_id) {
    $added = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
  } else {
    $added = WC()->cart->add_to_cart($product_id, $quantity);
  }

  if ($added) {
    $product = wc_get_product($product_id);
    $product_name = $product ? $product->get_name() : 'Produto';

    wp_send_json_success(array(
      'message' => sprintf('%s adicionado ao carrinho!', $product_name),
      'cart_count' => WC()->cart->get_cart_contents_count(),
      'cart_total' => luvee_format_price_clean(WC()->cart->get_cart_total()),
      'fragments' => apply_filters('woocommerce_add_to_cart_fragments', array())
    ));
  } else {
    $notices = wc_get_notices('error');
    $error_message = !empty($notices) ? $notices[0]['notice'] : 'Erro ao adicionar produto ao carrinho';
    wc_clear_notices();

    wp_send_json_error(array('message' => $error_message));
  }
}
add_action('wp_ajax_luvee_add_to_cart', 'luvee_ajax_add_to_cart');
add_action('wp_ajax_nopriv_luvee_add_to_cart', 'luvee_ajax_add_to_cart');

/**
 * Update cart item quantity via AJAX
 */
function luvee_ajax_update_cart_item()
{
  check_ajax_referer('luvee_cart_nonce', 'nonce');

  $cart_key = sanitize_text_field($_POST['cart_key']);
  $quantity = intval($_POST['quantity']);

  if (!$cart_key) {
    wp_send_json_error(array('message' => 'Item não encontrado'));
  }

  $updated = WC()->cart->set_quantity($cart_key, $quantity);

  if ($updated) {
    wp_send_json_success(array(
      'message' => 'Carrinho atualizado',
      'cart_count' => WC()->cart->get_cart_contents_count(),
      'cart_total' => luvee_format_price_clean(WC()->cart->get_cart_total()),
      'fragments' => apply_filters('woocommerce_add_to_cart_fragments', array())
    ));
  } else {
    wp_send_json_error(array('message' => 'Erro ao atualizar carrinho'));
  }
}
add_action('wp_ajax_luvee_update_cart_item', 'luvee_ajax_update_cart_item');
add_action('wp_ajax_nopriv_luvee_update_cart_item', 'luvee_ajax_update_cart_item');

/**
 * Remove cart item via AJAX
 */
function luvee_ajax_remove_cart_item()
{
  check_ajax_referer('luvee_cart_nonce', 'nonce');

  $cart_key = sanitize_text_field($_POST['cart_key']);

  if (!$cart_key) {
    wp_send_json_error(array('message' => 'Item não encontrado'));
  }

  $removed = WC()->cart->remove_cart_item($cart_key);

  if ($removed) {
    wp_send_json_success(array(
      'message' => 'Item removido do carrinho',
      'cart_count' => WC()->cart->get_cart_contents_count(),
      'cart_total' => luvee_format_price_clean(WC()->cart->get_cart_total()),
      'fragments' => apply_filters('woocommerce_add_to_cart_fragments', array())
    ));
  } else {
    wp_send_json_error(array('message' => 'Erro ao remover item'));
  }
}
add_action('wp_ajax_luvee_remove_cart_item', 'luvee_ajax_remove_cart_item');
add_action('wp_ajax_nopriv_luvee_remove_cart_item', 'luvee_ajax_remove_cart_item');

/**
 * Get mini cart content via AJAX
 */
function luvee_ajax_get_mini_cart()
{
  check_ajax_referer('luvee_cart_nonce', 'nonce');

  // Verificar se WooCommerce está disponível
  if (!function_exists('WC') || !WC()->cart) {
    wp_send_json_error(array('message' => 'WooCommerce não disponível'));
    return;
  }

  // Garantir que o carrinho está inicializado
  WC()->cart->maybe_set_cart_cookies();

  ob_start();

  if (WC()->cart->is_empty()) {
    echo '<div class="mini-cart-empty">';
    echo '<div class="mini-cart-empty-icon"><i class="fas fa-shopping-bag"></i></div>';
    echo '<h4>Seu carrinho está vazio</h4>';
    echo '<p>Adicione produtos incríveis para começar suas compras!</p>';
    echo '<a href="' . get_permalink(wc_get_page_id('shop')) . '" class="btn btn-primary">Continuar Comprando</a>';
    echo '</div>';
  } else {
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
      $product = $cart_item['data'];
      $product_id = $cart_item['product_id'];
      $quantity = $cart_item['quantity'];

      if (!$product || !$product->exists()) {
        continue;
      }

      $product_name = $product->get_name();
      $product_price = luvee_format_price_clean(WC()->cart->get_product_price($product));
      $product_permalink = $product->is_visible() ? $product->get_permalink($cart_item) : '';
      $thumbnail = get_the_post_thumbnail($product_id, 'thumbnail');

      if (!$thumbnail) {
        $thumbnail = wc_placeholder_img('thumbnail');
      }

      echo '<div class="mini-cart-item" data-key="' . esc_attr($cart_item_key) . '">';

      // Product image
      echo '<div class="mini-cart-item-image">';
      if ($product_permalink) {
        echo '<a href="' . esc_url($product_permalink) . '">' . $thumbnail . '</a>';
      } else {
        echo $thumbnail;
      }
      echo '</div>';

      // Product info
      echo '<div class="mini-cart-item-info">';

      // Product name
      echo '<h6 class="mini-cart-item-name">';
      if ($product_permalink) {
        echo '<a href="' . esc_url($product_permalink) . '">' . esc_html($product_name) . '</a>';
      } else {
        echo esc_html($product_name);
      }
      echo '</h6>';

      // Product price
      echo '<div class="mini-cart-item-price">' . $product_price . '</div>';

      // Quantity controls
      echo '<div class="mini-cart-quantity-controls">';
      echo '<input type="number" class="mini-cart-quantity" value="' . esc_attr($quantity) . '" min="1" data-cart-key="' . esc_attr($cart_item_key) . '">';
      echo '<button type="button" class="mini-cart-remove" data-cart-key="' . esc_attr($cart_item_key) . '" title="Remover item">';
      echo '<i class="fas fa-trash-alt"></i>';
      echo '</button>';
      echo '</div>';

      echo '</div>'; // .mini-cart-item-info
      echo '</div>'; // .mini-cart-item
    }
  }

  $content = ob_get_clean();

  wp_send_json_success(array(
    'content' => $content,
    'count' => WC()->cart->get_cart_contents_count(),
    'total' => luvee_format_price_clean(WC()->cart->get_cart_total()),
    'subtotal' => luvee_format_price_clean(WC()->cart->get_cart_subtotal())
  ));
}
add_action('wp_ajax_luvee_get_mini_cart', 'luvee_ajax_get_mini_cart');
add_action('wp_ajax_nopriv_luvee_get_mini_cart', 'luvee_ajax_get_mini_cart');

/**
 * WooCommerce Cart Fragments for AJAX Updates
 */
function luvee_add_to_cart_fragments($fragments)
{
  $cart_count = WC()->cart->get_cart_contents_count();

  // Fragment for cart count
  ob_start();
  ?>
  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark cart-count"
    <?php echo ($cart_count > 0) ? '' : 'style="display: none;"'; ?>>
    <?php echo $cart_count; ?>
    <span class="visually-hidden">itens no carrinho</span>
  </span>
  <?php
  $fragments['.cart-count'] = ob_get_clean();

  // Fragment for cart total
  ob_start();
  echo luvee_format_price_clean(WC()->cart->get_cart_total());
  $fragments['.cart-total'] = ob_get_clean();

  return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'luvee_add_to_cart_fragments');

/**
 * Ensure cart fragments are updated on cart page
 */
function luvee_cart_updated_fragments($fragments)
{
  return luvee_add_to_cart_fragments($fragments);
}
add_filter('woocommerce_cart_fragments', 'luvee_cart_updated_fragments');

/**
 * Enable WooCommerce AJAX cart support
 */
function luvee_woocommerce_support()
{
  add_theme_support('wc-product-gallery-zoom');
  add_theme_support('wc-product-gallery-lightbox');
  add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'luvee_woocommerce_support');

/**
 * Custom body classes for cart functionality
 */
function luvee_cart_body_classes($classes)
{
  if (WC()->cart->get_cart_contents_count() > 0) {
    $classes[] = 'has-cart-items';
  } else {
    $classes[] = 'cart-empty';
  }
  return $classes;
}
add_filter('body_class', 'luvee_cart_body_classes');