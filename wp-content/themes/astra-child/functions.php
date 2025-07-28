<?php
/**
 * Astra Child Theme Functions
 * 
 * @package Astra Child
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
  exit;
}

/**
 * Enqueue parent and child theme styles
 */
function astra_child_enqueue_styles()
{
  // Parent theme style
  wp_enqueue_style('astra-parent-style', get_template_directory_uri() . '/style.css');

  // Child theme style
  wp_enqueue_style('astra-child-style', get_stylesheet_directory_uri() . '/style.css', array('astra-parent-style'));

  // Custom JavaScript for components
  wp_enqueue_script('astra-child-script', get_stylesheet_directory_uri() . '/assets/js/components.js', array('jquery'), '1.0.0', true);

  // Localize script for AJAX
  wp_localize_script('astra-child-script', 'astra_child_ajax', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('astra_child_nonce')
  ));
}
add_action('wp_enqueue_scripts', 'astra_child_enqueue_styles');

/**
 * Register custom post types for ecommerce
 */
function astra_child_register_post_types()
{
  // Register Product Category taxonomy if WooCommerce is not active
  if (!class_exists('WooCommerce')) {
    register_taxonomy('product_category', 'post', array(
      'labels' => array(
        'name' => 'Categorias de Produtos',
        'singular_name' => 'Categoria de Produto',
        'menu_name' => 'Categorias',
        'all_items' => 'Todas as Categorias',
        'edit_item' => 'Editar Categoria',
        'update_item' => 'Atualizar Categoria',
        'add_new_item' => 'Adicionar Nova Categoria',
        'new_item_name' => 'Nome da Nova Categoria',
        'search_items' => 'Buscar Categorias',
        'popular_items' => 'Categorias Populares',
        'separate_items_with_commas' => 'Separe as categorias com vírgulas',
        'add_or_remove_items' => 'Adicionar ou remover categorias',
        'choose_from_most_used' => 'Escolher das mais usadas',
        'not_found' => 'Nenhuma categoria encontrada',
      ),
      'hierarchical' => true,
      'show_ui' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'rewrite' => array('slug' => 'categoria-produto'),
    ));
  }
}
add_action('init', 'astra_child_register_post_types');

/**
 * Add custom meta boxes for product information
 */
function astra_child_add_product_meta_boxes()
{
  add_meta_box(
    'product_info',
    'Informações do Produto',
    'astra_child_product_meta_box_callback',
    'post',
    'normal',
    'high'
  );
}
add_action('add_meta_boxes', 'astra_child_add_product_meta_boxes');

/**
 * Product meta box callback
 */
function astra_child_product_meta_box_callback($post)
{
  wp_nonce_field('astra_child_product_meta_box', 'astra_child_product_meta_box_nonce');

  $product_price = get_post_meta($post->ID, '_product_price', true);
  $product_old_price = get_post_meta($post->ID, '_product_old_price', true);
  $product_sku = get_post_meta($post->ID, '_product_sku', true);
  $product_badge = get_post_meta($post->ID, '_product_badge', true);

  ?>
  <table class="form-table">
    <tr>
      <th><label for="product_price">Preço (R$)</label></th>
      <td><input type="number" step="0.01" id="product_price" name="product_price"
          value="<?php echo esc_attr($product_price); ?>" class="regular-text" /></td>
    </tr>
    <tr>
      <th><label for="product_old_price">Preço Antigo (R$)</label></th>
      <td><input type="number" step="0.01" id="product_old_price" name="product_old_price"
          value="<?php echo esc_attr($product_old_price); ?>" class="regular-text" /></td>
    </tr>
    <tr>
      <th><label for="product_sku">SKU</label></th>
      <td><input type="text" id="product_sku" name="product_sku" value="<?php echo esc_attr($product_sku); ?>"
          class="regular-text" /></td>
    </tr>
    <tr>
      <th><label for="product_badge">Badge (Promoção, Novo, etc.)</label></th>
      <td><input type="text" id="product_badge" name="product_badge" value="<?php echo esc_attr($product_badge); ?>"
          class="regular-text" /></td>
    </tr>
  </table>
  <?php
}

/**
 * Save product meta data
 */
function astra_child_save_product_meta($post_id)
{
  if (
    !isset($_POST['astra_child_product_meta_box_nonce']) ||
    !wp_verify_nonce($_POST['astra_child_product_meta_box_nonce'], 'astra_child_product_meta_box')
  ) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  $fields = array('product_price', 'product_old_price', 'product_sku', 'product_badge');

  foreach ($fields as $field) {
    if (isset($_POST[$field])) {
      update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
    }
  }
}
add_action('save_post', 'astra_child_save_product_meta');

/**
 * Product Card Component
 */
function astra_child_product_card($post_id = null)
{
  if (!$post_id) {
    $post_id = get_the_ID();
  }

  $product_price = get_post_meta($post_id, '_product_price', true);
  $product_old_price = get_post_meta($post_id, '_product_old_price', true);
  $product_sku = get_post_meta($post_id, '_product_sku', true);
  $product_badge = get_post_meta($post_id, '_product_badge', true);
  $product_categories = get_the_terms($post_id, 'product_category');

  $discount_percentage = '';
  if ($product_old_price && $product_price) {
    $discount = (($product_old_price - $product_price) / $product_old_price) * 100;
    $discount_percentage = round($discount);
  }

  ob_start();
  ?>
  <div class="product-card">
    <div class="product-card__image">
      <?php if (has_post_thumbnail($post_id)): ?>
        <?php echo get_the_post_thumbnail($post_id, 'medium'); ?>
      <?php else: ?>
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/placeholder-product.jpg"
          alt="<?php echo get_the_title($post_id); ?>">
      <?php endif; ?>

      <?php if ($product_badge): ?>
        <div class="product-card__badge"><?php echo esc_html($product_badge); ?></div>
      <?php endif; ?>
    </div>

    <div class="product-card__content">
      <?php if ($product_categories && !is_wp_error($product_categories)): ?>
        <div class="product-card__category"><?php echo esc_html($product_categories[0]->name); ?></div>
      <?php endif; ?>

      <h3 class="product-card__title">
        <a href="<?php echo get_permalink($post_id); ?>"><?php echo get_the_title($post_id); ?></a>
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
        <a href="<?php echo get_permalink($post_id); ?>" class="product-card__btn product-card__btn--primary">
          Ver Produto
        </a>
        <button class="product-card__wishlist" data-product-id="<?php echo $post_id; ?>">
          <i class="fas fa-heart"></i>
        </button>
      </div>
    </div>
  </div>
  <?php
  return ob_get_clean();
}

/**
 * Hero Carousel Component
 */
function astra_child_hero_carousel($slides = array())
{
  if (empty($slides)) {
    // Default slides
    $slides = array(
      array(
        'image' => get_stylesheet_directory_uri() . '/assets/images/hero-1.jpg',
        'title' => 'Novos Produtos Chegaram',
        'subtitle' => 'Descubra nossa coleção mais recente com descontos especiais',
        'button_text' => 'Comprar Agora',
        'button_url' => '#'
      ),
      array(
        'image' => get_stylesheet_directory_uri() . '/assets/images/hero-2.jpg',
        'title' => 'Ofertas Imperdíveis',
        'subtitle' => 'Até 50% de desconto em produtos selecionados',
        'button_text' => 'Ver Ofertas',
        'button_url' => '#'
      )
    );
  }

  ob_start();
  ?>
  <div class="hero-carousel">
    <div class="hero-carousel__container">
      <?php foreach ($slides as $index => $slide): ?>
        <div class="hero-carousel__slide">
          <img src="<?php echo esc_url($slide['image']); ?>" alt="<?php echo esc_attr($slide['title']); ?>"
            class="hero-carousel__image">
          <div class="hero-carousel__overlay"></div>
          <div class="hero-carousel__content">
            <h2 class="hero-carousel__title"><?php echo esc_html($slide['title']); ?></h2>
            <p class="hero-carousel__subtitle"><?php echo esc_html($slide['subtitle']); ?></p>
            <a href="<?php echo esc_url($slide['button_url']); ?>" class="hero-carousel__btn">
              <?php echo esc_html($slide['button_text']); ?>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <button class="hero-carousel__nav hero-carousel__nav--prev">
      <i class="fas fa-chevron-left"></i>
    </button>
    <button class="hero-carousel__nav hero-carousel__nav--next">
      <i class="fas fa-chevron-right"></i>
    </button>

    <div class="hero-carousel__dots">
      <?php for ($i = 0; $i < count($slides); $i++): ?>
        <button class="hero-carousel__dot<?php echo $i === 0 ? ' active' : ''; ?>" data-slide="<?php echo $i; ?>"></button>
      <?php endfor; ?>
    </div>
  </div>
  <?php
  return ob_get_clean();
}

/**
 * Mega Menu Component
 */
function astra_child_mega_menu($menu_items = array())
{
  if (empty($menu_items)) {
    $menu_items = array(
      'categorias' => array(
        'title' => 'Categorias',
        'items' => array(
          'eletronicos' => 'Eletrônicos',
          'moda' => 'Moda',
          'casa' => 'Casa e Decoração',
          'esporte' => 'Esporte',
          'livros' => 'Livros',
          'brinquedos' => 'Brinquedos'
        )
      ),
      'marcas' => array(
        'title' => 'Marcas',
        'items' => array(
          'apple' => 'Apple',
          'samsung' => 'Samsung',
          'nike' => 'Nike',
          'adidas' => 'Adidas'
        )
      ),
      'ofertas' => array(
        'title' => 'Ofertas',
        'items' => array(
          'promocoes' => 'Promoções',
          'lancamentos' => 'Lançamentos',
          'mais-vendidos' => 'Mais Vendidos'
        )
      )
    );
  }

  ob_start();
  ?>
  <div class="mega-menu">
    <div class="mega-menu__container">
      <div class="mega-menu__categories">
        <?php foreach ($menu_items as $key => $section): ?>
          <div class="mega-menu__category" data-category="<?php echo $key; ?>">
            <?php echo esc_html($section['title']); ?>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="mega-menu__content">
        <?php foreach ($menu_items as $key => $section): ?>
          <div class="mega-menu__section" data-category="<?php echo $key; ?>">
            <h4 class="mega-menu__section-title"><?php echo esc_html($section['title']); ?></h4>
            <div class="mega-menu__links">
              <?php foreach ($section['items'] as $slug => $title): ?>
                <a href="<?php echo esc_url(home_url('/categoria/' . $slug)); ?>" class="mega-menu__link">
                  <?php echo esc_html($title); ?>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php
  return ob_get_clean();
}

/**
 * Footer Component
 */
function astra_child_footer()
{
  ob_start();
  ?>
  <footer class="site-footer">
    <div class="footer__container">
      <div class="footer__grid">
        <div class="footer__section">
          <h3>Sobre Nós</h3>
          <div class="footer__links">
            <a href="#" class="footer__link">Nossa História</a>
            <a href="#" class="footer__link">Missão e Valores</a>
            <a href="#" class="footer__link">Política de Privacidade</a>
            <a href="#" class="footer__link">Termos de Uso</a>
          </div>
        </div>

        <div class="footer__section">
          <h3>Atendimento</h3>
          <div class="footer__links">
            <a href="#" class="footer__link">Central de Ajuda</a>
            <a href="#" class="footer__link">Fale Conosco</a>
            <a href="#" class="footer__link">Trocas e Devoluções</a>
            <a href="#" class="footer__link">FAQ</a>
          </div>
        </div>

        <div class="footer__section">
          <h3>Minha Conta</h3>
          <div class="footer__links">
            <a href="#" class="footer__link">Meus Pedidos</a>
            <a href="#" class="footer__link">Lista de Desejos</a>
            <a href="#" class="footer__link">Endereços</a>
            <a href="#" class="footer__link">Configurações</a>
          </div>
        </div>

        <div class="footer__section">
          <div class="footer__newsletter">
            <h4>Newsletter</h4>
            <p>Receba ofertas exclusivas e novidades em primeira mão!</p>
            <form class="footer__newsletter-form">
              <input type="email" placeholder="Seu e-mail" class="footer__newsletter-input" required>
              <button type="submit" class="footer__newsletter-btn">Inscrever</button>
            </form>
          </div>

          <div class="footer__social">
            <a href="#" class="footer__social-link">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="footer__social-link">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="footer__social-link">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="footer__social-link">
              <i class="fab fa-youtube"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="footer__bottom">
        <p>&copy; <?php echo date('Y'); ?> Luvee. Todos os direitos reservados.</p>
      </div>
    </div>
  </footer>
  <?php
  return ob_get_clean();
}

/**
 * AJAX handler for wishlist
 */
function astra_child_wishlist_handler()
{
  check_ajax_referer('astra_child_nonce', 'nonce');

  $product_id = intval($_POST['product_id']);
  $user_id = get_current_user_id();

  if (!$user_id) {
    wp_die('Usuário não logado');
  }

  $wishlist = get_user_meta($user_id, '_wishlist', true);
  if (!is_array($wishlist)) {
    $wishlist = array();
  }

  if (in_array($product_id, $wishlist)) {
    $wishlist = array_diff($wishlist, array($product_id));
    $action = 'removed';
  } else {
    $wishlist[] = $product_id;
    $action = 'added';
  }

  update_user_meta($user_id, '_wishlist', $wishlist);

  wp_send_json_success(array(
    'action' => $action,
    'count' => count($wishlist)
  ));
}
add_action('wp_ajax_astra_child_wishlist', 'astra_child_wishlist_handler');
add_action('wp_ajax_nopriv_astra_child_wishlist', 'astra_child_wishlist_handler');

/**
 * Add Font Awesome for icons
 */
function astra_child_enqueue_fontawesome()
{
  wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
}
add_action('wp_enqueue_scripts', 'astra_child_enqueue_fontawesome');

/**
 * Create assets directory and files
 */
function astra_child_create_assets()
{
  $assets_dir = get_stylesheet_directory() . '/assets';
  $js_dir = $assets_dir . '/js';
  $css_dir = $assets_dir . '/css';
  $images_dir = $assets_dir . '/images';

  // Create directories if they don't exist
  if (!file_exists($assets_dir)) {
    wp_mkdir_p($assets_dir);
  }
  if (!file_exists($js_dir)) {
    wp_mkdir_p($js_dir);
  }
  if (!file_exists($css_dir)) {
    wp_mkdir_p($css_dir);
  }
  if (!file_exists($images_dir)) {
    wp_mkdir_p($images_dir);
  }
}
add_action('after_switch_theme', 'astra_child_create_assets');

/**
 * Include WooCommerce support
 */
if (class_exists('WooCommerce')) {
  require_once get_stylesheet_directory() . '/woocommerce-support.php';
}

/**
 * Include Elementor widgets
 */
require_once get_stylesheet_directory() . '/elementor-widgets.php';

/**
 * Include Shortcodes
 */
require_once get_stylesheet_directory() . '/shortcodes.php';