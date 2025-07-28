<?php
/**
 * Elementor Widgets for Ecommerce
 * 
 * Widgets customizados para Elementor com elementos de ecommerce
 */

// Prevent direct access
if (!defined('ABSPATH')) {
  exit;
}

/**
 * Register Elementor Widgets
 */
function astra_child_register_elementor_widgets()
{
  // Check if Elementor is installed and activated
  if (!did_action('elementor/loaded')) {
    return;
  }

  // Include widget files
  require_once get_stylesheet_directory() . '/widgets/product-card-widget.php';
  require_once get_stylesheet_directory() . '/widgets/product-grid-widget.php';
  require_once get_stylesheet_directory() . '/widgets/product-search-widget.php';
  require_once get_stylesheet_directory() . '/widgets/single-product-widget.php';
  require_once get_stylesheet_directory() . '/widgets/hero-carousel-widget.php';
  require_once get_stylesheet_directory() . '/widgets/mega-menu-widget.php';

  // Register widgets
  add_action('elementor/widgets/register', 'astra_child_register_widgets');
}
add_action('init', 'astra_child_register_elementor_widgets');

/**
 * Register Widgets
 */
function astra_child_register_widgets($widgets_manager)
{
  $widgets_manager->register(new \Astra_Child_Product_Card_Widget());
  $widgets_manager->register(new \Astra_Child_Product_Grid_Widget());
  $widgets_manager->register(new \Astra_Child_Product_Search_Widget());
  $widgets_manager->register(new \Astra_Child_Single_Product_Widget());
  $widgets_manager->register(new \Astra_Child_Hero_Carousel_Widget());
  $widgets_manager->register(new \Astra_Child_Mega_Menu_Widget());
}

/**
 * Add custom category for widgets
 */
function astra_child_add_elementor_widget_categories($elements_manager)
{
  $elements_manager->add_category(
    'astra-child-ecommerce',
    [
      'title' => esc_html__('Astra Child Ecommerce', 'astra-child'),
      'icon' => 'fa fa-shopping-cart',
    ]
  );
}
add_action('elementor/elements/categories_registered', 'astra_child_add_elementor_widget_categories');

/**
 * Enqueue Elementor styles
 */
function astra_child_elementor_styles()
{
  if (class_exists('\Elementor\Plugin')) {
    wp_enqueue_style(
      'astra-child-elementor',
      get_stylesheet_directory_uri() . '/assets/css/elementor-widgets.css',
      array(),
      '1.0.0'
    );
  }
}
add_action('wp_enqueue_scripts', 'astra_child_elementor_styles');