<?php
/**
 * Classe para gerenciar produtos featured
 * Adiciona funcionalidades de produtos em destaque ao WooCommerce
 */

if (!defined('ABSPATH')) {
  exit;
}

class Luvee_Site_Featured_Products
{

  /**
   * Construtor da classe
   */
  public function __construct()
  {
    // Só executar se WooCommerce estiver ativo
    if (!class_exists('WooCommerce')) {
      return;
    }

    $this->init();
  }

  /**
   * Inicializar hooks e filtros
   */
  private function init()
  {
    // Meta box no admin de produtos
    add_action('add_meta_boxes', array($this, 'add_featured_meta_box'));
    add_action('save_post', array($this, 'save_featured_meta_box'));

    // Coluna na listagem de produtos
    add_filter('manage_product_posts_columns', array($this, 'add_featured_column'));
    add_action('manage_product_posts_custom_column', array($this, 'featured_column_content'), 10, 2);

    // Quick edit
    add_action('quick_edit_custom_box', array($this, 'quick_edit_featured'), 10, 2);
    add_action('wp_ajax_save_featured_quick_edit', array($this, 'save_featured_quick_edit'));

    // Bulk actions
    add_filter('bulk_actions-edit-product', array($this, 'add_bulk_actions'));
    add_filter('handle_bulk_actions-edit-product', array($this, 'handle_bulk_actions'), 10, 3);

    // Admin notices
    add_action('admin_notices', array($this, 'admin_notices'));

    // Scripts e styles do admin
    add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));

    // Badge featured no frontend
    add_action('woocommerce_before_single_product_summary', array($this, 'add_featured_badge'), 5);
    add_filter('woocommerce_get_price_html', array($this, 'add_featured_price_badge'), 10, 2);
  }

  /**
   * Adicionar meta box na edição de produtos
   */
  public function add_featured_meta_box()
  {
    add_meta_box(
      'luvee_featured_product',
      '⭐ Produto em Destaque',
      array($this, 'featured_meta_box_callback'),
      'product',
      'side',
      'high'
    );
  }

  /**
   * Callback do meta box
   */
  public function featured_meta_box_callback($post)
  {
    // Nonce para segurança
    wp_nonce_field('luvee_featured_nonce', 'luvee_featured_nonce');

    // Valor atual
    $is_featured = get_post_meta($post->ID, '_featured', true) === 'yes';

    echo '<div style="padding: 10px 0;">';
    echo '<label for="luvee_featured_checkbox" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">';
    echo '<input type="checkbox" id="luvee_featured_checkbox" name="luvee_featured" value="yes" ' . checked($is_featured, true, false) . '>';
    echo '<span>✨ Marcar como produto em destaque</span>';
    echo '</label>';
    echo '</div>';

    echo '<div style="font-size: 12px; color: #666; margin-top: 10px;">';
    echo '💡 <strong>Produtos em destaque:</strong><br>';
    echo '• Aparecem nas seções "featured" do site<br>';
    echo '• Recebem badge dourado no frontend<br>';
    echo '• São priorizados em buscas e listagens';
    echo '</div>';

    // Estatísticas
    $featured_count = $this->get_featured_count();
    echo '<div style="background: #f0f0f1; padding: 10px; margin-top: 15px; border-radius: 4px; font-size: 12px;">';
    echo '<strong>📊 Estatísticas:</strong><br>';
    echo "Total de produtos featured: <strong>{$featured_count}</strong><br>";
    echo '<small>Recomendado: 8-12 produtos para melhor performance</small>';
    echo '</div>';
  }

  /**
   * Salvar meta box
   */
  public function save_featured_meta_box($post_id)
  {
    // Verificações de segurança
    if (!isset($_POST['luvee_featured_nonce']) || !wp_verify_nonce($_POST['luvee_featured_nonce'], 'luvee_featured_nonce')) {
      return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
    }

    if (!current_user_can('edit_post', $post_id)) {
      return;
    }

    if (get_post_type($post_id) !== 'product') {
      return;
    }

    // Salvar valor
    $featured = isset($_POST['luvee_featured']) ? 'yes' : 'no';
    update_post_meta($post_id, '_featured', $featured);

    // Log da ação
    $this->log_featured_action($post_id, $featured);
  }

  /**
   * Adicionar coluna na listagem de produtos
   */
  public function add_featured_column($columns)
  {
    // Inserir coluna após a checkbox
    $new_columns = array();
    foreach ($columns as $key => $value) {
      $new_columns[$key] = $value;
      if ($key === 'cb') {
        $new_columns['luvee_featured'] = '⭐ Featured';
      }
    }
    return $new_columns;
  }

  /**
   * Conteúdo da coluna featured
   */
  public function featured_column_content($column, $post_id)
  {
    if ($column === 'luvee_featured') {
      $is_featured = get_post_meta($post_id, '_featured', true) === 'yes';

      if ($is_featured) {
        echo '<span style="color: #e7b416; font-size: 16px;" title="Produto em destaque">⭐</span>';
      } else {
        echo '<span style="color: #ddd; font-size: 16px;" title="Produto normal">☆</span>';
      }
    }
  }

  /**
   * Quick edit featured
   */
  public function quick_edit_featured($column_name, $post_type)
  {
    if ($column_name === 'luvee_featured' && $post_type === 'product') {
      echo '<fieldset class="inline-edit-col-left">';
      echo '<div class="inline-edit-col">';
      echo '<label>';
      echo '<span class="title">⭐ Featured</span>';
      echo '<select name="luvee_featured_quick">';
      echo '<option value="">— Sem alterações —</option>';
      echo '<option value="yes">✅ Sim</option>';
      echo '<option value="no">❌ Não</option>';
      echo '</select>';
      echo '</label>';
      echo '</div>';
      echo '</fieldset>';
    }
  }

  /**
   * Salvar quick edit
   */
  public function save_featured_quick_edit()
  {
    if (!current_user_can('edit_posts')) {
      wp_die();
    }

    $post_id = intval($_POST['post_id']);
    $featured = sanitize_text_field($_POST['luvee_featured_quick']);

    if ($featured && in_array($featured, ['yes', 'no'])) {
      update_post_meta($post_id, '_featured', $featured);
      $this->log_featured_action($post_id, $featured, 'quick_edit');
    }

    wp_die();
  }

  /**
   * Adicionar bulk actions
   */
  public function add_bulk_actions($actions)
  {
    $actions['luvee_set_featured'] = '⭐ Marcar como Featured';
    $actions['luvee_unset_featured'] = '☆ Desmarcar Featured';
    return $actions;
  }

  /**
   * Processar bulk actions
   */
  public function handle_bulk_actions($redirect_to, $action, $post_ids)
  {
    if ($action === 'luvee_set_featured') {
      $count = 0;
      foreach ($post_ids as $post_id) {
        if (get_post_type($post_id) === 'product') {
          update_post_meta($post_id, '_featured', 'yes');
          $count++;
        }
      }

      $redirect_to = add_query_arg('luvee_featured_bulk', $count, $redirect_to);
      $this->log_bulk_action('set_featured', $count);

    } elseif ($action === 'luvee_unset_featured') {
      $count = 0;
      foreach ($post_ids as $post_id) {
        if (get_post_type($post_id) === 'product') {
          update_post_meta($post_id, '_featured', 'no');
          $count++;
        }
      }

      $redirect_to = add_query_arg('luvee_unfeatured_bulk', $count, $redirect_to);
      $this->log_bulk_action('unset_featured', $count);
    }

    return $redirect_to;
  }

  /**
   * Admin notices
   */
  public function admin_notices()
  {
    if (isset($_GET['luvee_featured_bulk'])) {
      $count = intval($_GET['luvee_featured_bulk']);
      echo "<div class='notice notice-success is-dismissible'>";
      echo "<p>✅ <strong>{$count} produtos marcados como featured com sucesso!</strong></p>";
      echo "</div>";
    }

    if (isset($_GET['luvee_unfeatured_bulk'])) {
      $count = intval($_GET['luvee_unfeatured_bulk']);
      echo "<div class='notice notice-success is-dismissible'>";
      echo "<p>☆ <strong>{$count} produtos desmarcados como featured com sucesso!</strong></p>";
      echo "</div>";
    }
  }

  /**
   * Scripts do admin
   */
  public function admin_scripts($hook)
  {
    global $post_type;

    if ($post_type === 'product' && in_array($hook, ['edit.php', 'post.php', 'post-new.php'])) {
      wp_enqueue_script(
        'luvee-featured-admin',
        LUVEE_SITE_PLUGIN_URL . 'assets/js/featured-admin.js',
        array('jquery'),
        LUVEE_SITE_VERSION,
        true
      );

      wp_localize_script('luvee-featured-admin', 'luvee_featured_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('luvee_featured_nonce')
      ));
    }
  }

  /**
   * Badge featured no single product
   */
  public function add_featured_badge()
  {
    global $product;

    if ($product && $product->is_featured()) {
      echo '<div class="luvee-featured-badge-single">';
      echo '<span class="badge badge-featured">✨ Produto em Destaque</span>';
      echo '</div>';
    }
  }

  /**
   * Badge no preço
   */
  public function add_featured_price_badge($price, $product)
  {
    if ($product && $product->is_featured()) {
      $price .= ' <span class="luvee-featured-price-badge">⭐</span>';
    }
    return $price;
  }

  /**
   * Utilitários
   */

  /**
   * Contar produtos featured
   */
  private function get_featured_count()
  {
    $products = wc_get_products(array(
      'limit' => -1,
      'status' => 'publish',
      'featured' => true
    ));
    return count($products);
  }

  /**
   * Log de ações
   */
  private function log_featured_action($post_id, $featured, $context = 'edit')
  {
    $product = wc_get_product($post_id);
    if (!$product)
      return;

    $action = $featured === 'yes' ? 'marked' : 'unmarked';
    $message = sprintf(
      'Product "%s" (ID: %d) %s as featured via %s',
      $product->get_name(),
      $post_id,
      $action,
      $context
    );

    error_log('[Luvee Featured] ' . $message);
  }

  /**
   * Log de bulk actions
   */
  private function log_bulk_action($action, $count)
  {
    $message = sprintf(
      'Bulk action "%s" executed on %d products',
      $action,
      $count
    );

    error_log('[Luvee Featured Bulk] ' . $message);
  }

  /**
   * Métodos públicos para uso externo
   */

  /**
   * Marcar produto como featured
   */
  public static function set_product_featured($product_id, $featured = true)
  {
    $value = $featured ? 'yes' : 'no';
    return update_post_meta($product_id, '_featured', $value);
  }

  /**
   * Verificar se produto é featured
   */
  public static function is_product_featured($product_id)
  {
    return get_post_meta($product_id, '_featured', true) === 'yes';
  }

  /**
   * Obter produtos featured
   */
  public static function get_featured_products($args = array())
  {
    $default_args = array(
      'limit' => 8,
      'status' => 'publish',
      'featured' => true
    );

    $args = wp_parse_args($args, $default_args);
    return wc_get_products($args);
  }

  /**
   * Obter produtos mais vendidos (best sellers)
   */
  public static function get_best_sellers($args = array())
  {
    $default_args = array(
      'limit' => 8,
      'status' => 'publish',
      'orderby' => 'meta_value_num',
      'order' => 'DESC',
      'meta_key' => 'total_sales',
      'meta_query' => array(
        array(
          'key' => 'total_sales',
          'value' => 0,
          'compare' => '>',
          'type' => 'NUMERIC'
        )
      )
    );

    $args = wp_parse_args($args, $default_args);
    return wc_get_products($args);
  }

  /**
   * Obter estatísticas de vendas
   */
  public static function get_sales_stats()
  {
    global $wpdb;

    // Total de produtos com vendas
    $products_with_sales = $wpdb->get_var("
            SELECT COUNT(DISTINCT post_id) 
            FROM {$wpdb->postmeta} 
            WHERE meta_key = 'total_sales' 
            AND CAST(meta_value AS UNSIGNED) > 0
        ");

    // Top seller
    $top_seller = $wpdb->get_row("
            SELECT post_id, meta_value as sales
            FROM {$wpdb->postmeta} 
            WHERE meta_key = 'total_sales' 
            ORDER BY CAST(meta_value AS UNSIGNED) DESC 
            LIMIT 1
        ");

    // Total de vendas
    $total_sales = $wpdb->get_var("
            SELECT SUM(CAST(meta_value AS UNSIGNED)) 
            FROM {$wpdb->postmeta} 
            WHERE meta_key = 'total_sales'
        ");

    return array(
      'products_with_sales' => intval($products_with_sales),
      'top_seller_id' => $top_seller ? intval($top_seller->post_id) : null,
      'top_seller_sales' => $top_seller ? intval($top_seller->sales) : 0,
      'total_sales' => intval($total_sales)
    );
  }

  /**
   * Simular vendas para produtos (para teste)
   */
  public static function simulate_sales($product_id, $sales_count = null)
  {
    if (!current_user_can('manage_woocommerce')) {
      return false;
    }

    // Se não especificado, gerar número aleatório
    if ($sales_count === null) {
      $sales_count = rand(1, 50);
    }

    // Atualizar total_sales
    update_post_meta($product_id, 'total_sales', $sales_count);

    // Log da ação
    $product = wc_get_product($product_id);
    if ($product) {
      $message = sprintf(
        'Simulated %d sales for product "%s" (ID: %d)',
        $sales_count,
        $product->get_name(),
        $product_id
      );
      error_log('[Luvee Best Sellers] ' . $message);
    }

    return true;
  }
}

// Inicializar a classe
new Luvee_Site_Featured_Products();
