<?php
/**
 * WooCommerce Support
 * 
 * Arquivo com hooks e filtros para melhor integração com WooCommerce
 */

// Prevent direct access
if (!defined('ABSPATH')) {
  exit;
}

/**
 * Add WooCommerce support
 */
function astra_child_woocommerce_support()
{
  add_theme_support('woocommerce');
  add_theme_support('wc-product-gallery-zoom');
  add_theme_support('wc-product-gallery-lightbox');
  add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'astra_child_woocommerce_support');

/**
 * Customize WooCommerce product loop
 */
function astra_child_woocommerce_product_loop_start()
{
  echo '<div class="products-grid">';
}
add_action('woocommerce_before_shop_loop', 'astra_child_woocommerce_product_loop_start');

function astra_child_woocommerce_product_loop_end()
{
  echo '</div>';
}
add_action('woocommerce_after_shop_loop', 'astra_child_woocommerce_product_loop_end');

/**
 * Remove default WooCommerce styles
 */
function astra_child_remove_woocommerce_styles()
{
  if (class_exists('woocommerce')) {
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');
  }
}
add_action('wp_enqueue_scripts', 'astra_child_remove_woocommerce_styles');

/**
 * Customize WooCommerce breadcrumbs
 */
function astra_child_woocommerce_breadcrumb_defaults($args)
{
  $args['delimiter'] = '<i class="fas fa-chevron-right"></i>';
  $args['wrap_before'] = '<nav class="woocommerce-breadcrumb">';
  $args['wrap_after'] = '</nav>';
  return $args;
}
add_filter('woocommerce_breadcrumb_defaults', 'astra_child_woocommerce_breadcrumb_defaults');

/**
 * Customize WooCommerce pagination
 */
function astra_child_woocommerce_pagination_args($args)
{
  $args['prev_text'] = '<i class="fas fa-chevron-left"></i> Anterior';
  $args['next_text'] = 'Próximo <i class="fas fa-chevron-right"></i>';
  return $args;
}
add_filter('woocommerce_pagination_args', 'astra_child_woocommerce_pagination_args');

/**
 * Add custom fields to WooCommerce products
 */
function astra_child_woocommerce_product_custom_fields()
{
  global $post;

  echo '<div class="product-custom-fields">';

  // Badge field
  $badge = get_post_meta($post->ID, '_product_badge', true);
  if ($badge) {
    echo '<div class="product-badge">' . esc_html($badge) . '</div>';
  }

  // SKU field
  $sku = get_post_meta($post->ID, '_product_sku', true);
  if ($sku) {
    echo '<div class="product-sku">SKU: ' . esc_html($sku) . '</div>';
  }

  echo '</div>';
}
add_action('woocommerce_single_product_summary', 'astra_child_woocommerce_product_custom_fields', 25);

/**
 * Customize WooCommerce product price display
 */
function astra_child_woocommerce_price_html($price, $product)
{
  $regular_price = $product->get_regular_price();
  $sale_price = $product->get_sale_price();

  if ($sale_price && $regular_price > $sale_price) {
    $percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
    $price .= '<span class="discount-badge">-' . $percentage . '%</span>';
  }

  return $price;
}
add_filter('woocommerce_get_price_html', 'astra_child_woocommerce_price_html', 10, 2);

/**
 * Add wishlist functionality to WooCommerce
 */
function astra_child_woocommerce_wishlist_button()
{
  global $product;

  if (!$product)
    return;

  $user_id = get_current_user_id();
  $wishlist = get_user_meta($user_id, '_wishlist', true);
  $is_in_wishlist = is_array($wishlist) && in_array($product->get_id(), $wishlist);

  $class = $is_in_wishlist ? 'wishlist-btn active' : 'wishlist-btn';
  $text = $is_in_wishlist ? 'Remover da Lista' : 'Adicionar à Lista';

  echo '<button class="' . $class . '" data-product-id="' . $product->get_id() . '">';
  echo '<i class="fas fa-heart"></i>';
  echo '<span>' . $text . '</span>';
  echo '</button>';
}
add_action('woocommerce_after_add_to_cart_button', 'astra_child_woocommerce_wishlist_button');

/**
 * Customize WooCommerce cart page
 */
function astra_child_woocommerce_cart_customization()
{
  // Add continue shopping button
  echo '<div class="cart-actions">';
  echo '<a href="' . wc_get_page_permalink('shop') . '" class="button continue-shopping">';
  echo '<i class="fas fa-arrow-left"></i> Continuar Comprando';
  echo '</a>';
  echo '</div>';
}
add_action('woocommerce_cart_collaterals', 'astra_child_woocommerce_cart_customization');

/**
 * Customize WooCommerce checkout page
 */
function astra_child_woocommerce_checkout_customization()
{
  // Add order summary styling
  echo '<style>
        .woocommerce-checkout-review-order {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .woocommerce-checkout-review-order-table {
            margin-bottom: 0;
        }
    </style>';
}
add_action('woocommerce_checkout_before_order_review', 'astra_child_woocommerce_checkout_customization');

/**
 * Add custom CSS for WooCommerce integration
 */
function astra_child_woocommerce_styles()
{
  if (class_exists('woocommerce')) {
    ?>
    <style>
      /* WooCommerce Integration Styles */
      .woocommerce .products {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
      }

      .woocommerce .product {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
      }

      .woocommerce .product:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
      }

      .woocommerce .product .woocommerce-loop-product__title {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        margin: 16px;
      }

      .woocommerce .product .price {
        font-size: 18px;
        font-weight: 700;
        color: #3498db;
        margin: 16px;
      }

      .woocommerce .product .price del {
        color: #6c757d;
        font-size: 14px;
      }

      .woocommerce .product .price ins {
        color: #27ae60;
        text-decoration: none;
      }

      .discount-badge {
        background: #27ae60;
        color: white;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        margin-left: 8px;
      }

      .product-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: #ff4757;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        z-index: 2;
      }

      .product-sku {
        color: #6c757d;
        font-size: 12px;
        margin: 8px 16px;
      }

      .wishlist-btn {
        padding: 10px 16px;
        background: transparent;
        color: #3498db;
        border: 2px solid #3498db;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin: 16px;
      }

      .wishlist-btn:hover,
      .wishlist-btn.active {
        background: #3498db;
        color: white;
      }

      .wishlist-btn.active {
        border-color: #ff4757;
        background: #ff4757;
      }

      .cart-actions {
        margin-top: 20px;
        text-align: center;
      }

      .continue-shopping {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: #3498db;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 600;
        transition: background 0.2s ease;
      }

      .continue-shopping:hover {
        background: #2980b9;
        color: white;
      }

      /* Responsive */
      @media (max-width: 768px) {
        .woocommerce .products {
          grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
          gap: 20px;
        }
      }
    </style>
    <?php
  }
}
add_action('wp_head', 'astra_child_woocommerce_styles');

/**
 * Customize WooCommerce order received page
 */
function astra_child_woocommerce_order_received_customization()
{
  echo '<div class="order-received-success">';
  echo '<i class="fas fa-check-circle"></i>';
  echo '<h2>Pedido Recebido!</h2>';
  echo '<p>Obrigado por sua compra. Você receberá um e-mail de confirmação em breve.</p>';
  echo '</div>';
}
add_action('woocommerce_before_thankyou', 'astra_child_woocommerce_order_received_customization');

/**
 * Add custom styles for order received page
 */
function astra_child_order_received_styles()
{
  if (is_wc_endpoint_url('order-received')) {
    ?>
    <style>
      .order-received-success {
        text-align: center;
        padding: 40px;
        background: #f8f9fa;
        border-radius: 12px;
        margin-bottom: 30px;
      }

      .order-received-success i {
        font-size: 64px;
        color: #27ae60;
        margin-bottom: 20px;
      }

      .order-received-success h2 {
        color: #2c3e50;
        margin-bottom: 16px;
      }

      .order-received-success p {
        color: #6c757d;
        font-size: 18px;
      }
    </style>
    <?php
  }
}
add_action('wp_head', 'astra_child_order_received_styles');