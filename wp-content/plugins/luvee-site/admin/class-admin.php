<?php
/**
 * Classe para funcionalidades administrativas
 */

if (!defined('ABSPATH')) {
  exit;
}

class Luvee_Site_Admin
{

  public function __construct()
  {
    add_action('admin_init', array($this, 'registerSettings'));
    add_action('admin_notices', array($this, 'adminNotices'));

    // Adiciona colunas personalizadas nas listagens
    add_filter('manage_luvee_banner_posts_columns', array($this, 'bannerColumns'));
    add_action('manage_luvee_banner_posts_custom_column', array($this, 'bannerColumnContent'), 10, 2);

    add_filter('manage_luvee_carousel_posts_columns', array($this, 'carouselColumns'));
    add_action('manage_luvee_carousel_posts_custom_column', array($this, 'carouselColumnContent'), 10, 2);

    add_filter('manage_luvee_hero_posts_columns', array($this, 'heroColumns'));
    add_action('manage_luvee_hero_posts_custom_column', array($this, 'heroColumnContent'), 10, 2);

    // Adiciona filtros
    add_action('restrict_manage_posts', array($this, 'addPostFilters'));
    add_filter('parse_query', array($this, 'filterPosts'));
  }

  /**
   * Registra configurações do plugin
   */
  public function registerSettings()
  {
    register_setting('luvee_site_settings', 'luvee_site_options');

    add_settings_section(
      'luvee_site_general',
      __('Configurações Gerais', 'luvee-site'),
      array($this, 'generalSectionCallback'),
      'luvee_site_settings'
    );

    add_settings_field(
      'enable_analytics',
      __('Habilitar Analytics', 'luvee-site'),
      array($this, 'enableAnalyticsCallback'),
      'luvee_site_settings',
      'luvee_site_general'
    );

    add_settings_field(
      'default_image_size',
      __('Tamanho Padrão das Imagens', 'luvee-site'),
      array($this, 'defaultImageSizeCallback'),
      'luvee_site_settings',
      'luvee_site_general'
    );
  }

  /**
   * Callback da seção geral
   */
  public function generalSectionCallback()
  {
    echo '<p>' . __('Configure as opções gerais do plugin Luvee Site.', 'luvee-site') . '</p>';
  }

  /**
   * Callback para habilitar analytics
   */
  public function enableAnalyticsCallback()
  {
    $options = get_option('luvee_site_options');
    $checked = isset($options['enable_analytics']) ? $options['enable_analytics'] : 0;

    echo '<label>';
    echo '<input type="checkbox" name="luvee_site_options[enable_analytics]" value="1" ' . checked(1, $checked, false) . ' />';
    echo ' ' . __('Habilitar rastreamento de visualizações e cliques', 'luvee-site');
    echo '</label>';
  }

  /**
   * Callback para tamanho padrão das imagens
   */
  public function defaultImageSizeCallback()
  {
    $options = get_option('luvee_site_options');
    $size = isset($options['default_image_size']) ? $options['default_image_size'] : 'full';

    $image_sizes = get_intermediate_image_sizes();
    $image_sizes[] = 'full';

    echo '<select name="luvee_site_options[default_image_size]">';
    foreach ($image_sizes as $image_size) {
      echo '<option value="' . esc_attr($image_size) . '" ' . selected($size, $image_size, false) . '>';
      echo esc_html($image_size);
      echo '</option>';
    }
    echo '</select>';
    echo '<p class="description">' . __('Tamanho padrão das imagens nos banners e carrosséis.', 'luvee-site') . '</p>';
  }

  /**
   * Avisos administrativos
   */
  public function adminNotices()
  {
    if (isset($_GET['page']) && $_GET['page'] === 'luvee-site-settings' && isset($_GET['settings-updated'])) {
      echo '<div class="notice notice-success is-dismissible">';
      echo '<p>' . __('Configurações salvas com sucesso!', 'luvee-site') . '</p>';
      echo '</div>';
    }
  }

  /**
   * Colunas personalizadas para banners
   */
  public function bannerColumns($columns)
  {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['banner_image'] = __('Imagem', 'luvee-site');
    $new_columns['banner_position'] = __('Posição', 'luvee-site');
    $new_columns['banner_status'] = __('Status', 'luvee-site');
    $new_columns['banner_link'] = __('Link', 'luvee-site');
    $new_columns['banner_stats'] = __('Estatísticas', 'luvee-site');
    $new_columns['date'] = $columns['date'];

    return $new_columns;
  }

  /**
   * Conteúdo das colunas dos banners
   */
  public function bannerColumnContent($column, $post_id)
  {
    switch ($column) {
      case 'banner_image':
        $thumbnail = get_the_post_thumbnail($post_id, array(80, 60));
        echo $thumbnail ? $thumbnail : '<span class="dashicons dashicons-format-image" style="font-size: 40px; color: #ccc;"></span>';
        break;

      case 'banner_position':
        $position = get_post_meta($post_id, '_luvee_banner_position', true);
        $positions = array(
          'header' => __('Cabeçalho', 'luvee-site'),
          'sidebar' => __('Barra Lateral', 'luvee-site'),
          'footer' => __('Rodapé', 'luvee-site'),
          'content' => __('Conteúdo', 'luvee-site'),
        );
        echo isset($positions[$position]) ? $positions[$position] : '-';
        break;

      case 'banner_status':
        $is_active = get_post_meta($post_id, '_luvee_banner_active', true);
        $start_date = get_post_meta($post_id, '_luvee_banner_start_date', true);
        $end_date = get_post_meta($post_id, '_luvee_banner_end_date', true);
        $current_date = date('Y-m-d');

        $status = 'inactive';
        $status_text = __('Inativo', 'luvee-site');

        if ($is_active) {
          if ($start_date && $current_date < $start_date) {
            $status = 'scheduled';
            $status_text = __('Agendado', 'luvee-site');
          } elseif ($end_date && $current_date > $end_date) {
            $status = 'expired';
            $status_text = __('Expirado', 'luvee-site');
          } else {
            $status = 'active';
            $status_text = __('Ativo', 'luvee-site');
          }
        }

        echo '<span class="banner-status status-' . $status . '">' . $status_text . '</span>';
        break;

      case 'banner_link':
        $link_url = get_post_meta($post_id, '_luvee_banner_link_url', true);
        if ($link_url) {
          echo '<a href="' . esc_url($link_url) . '" target="_blank" title="' . esc_attr($link_url) . '">';
          echo '<span class="dashicons dashicons-external"></span>';
          echo '</a>';
        } else {
          echo '-';
        }
        break;

      case 'banner_stats':
        $stats = $this->getBannerStats($post_id, 'banner');
        echo '<strong>' . $stats['views'] . '</strong> ' . __('visualizações', 'luvee-site') . '<br>';
        echo '<strong>' . $stats['clicks'] . '</strong> ' . __('cliques', 'luvee-site');
        break;
    }
  }

  /**
   * Colunas personalizadas para carrosséis
   */
  public function carouselColumns($columns)
  {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['carousel_items'] = __('Itens', 'luvee-site');
    $new_columns['carousel_settings'] = __('Configurações', 'luvee-site');
    $new_columns['carousel_stats'] = __('Estatísticas', 'luvee-site');
    $new_columns['date'] = $columns['date'];

    return $new_columns;
  }

  /**
   * Conteúdo das colunas dos carrosséis
   */
  public function carouselColumnContent($column, $post_id)
  {
    switch ($column) {
      case 'carousel_items':
        $items = get_post_meta($post_id, '_luvee_carousel_items', true);
        $count = is_array($items) ? count($items) : 0;
        echo '<strong>' . $count . '</strong> ' . ($count == 1 ? __('item', 'luvee-site') : __('itens', 'luvee-site'));
        break;

      case 'carousel_settings':
        $autoplay = get_post_meta($post_id, '_luvee_carousel_autoplay', true);
        $show_arrows = get_post_meta($post_id, '_luvee_carousel_show_arrows', true);
        $show_dots = get_post_meta($post_id, '_luvee_carousel_show_dots', true);
        $infinite = get_post_meta($post_id, '_luvee_carousel_infinite', true);

        $settings = array();
        if ($autoplay)
          $settings[] = __('Autoplay', 'luvee-site');
        if ($show_arrows)
          $settings[] = __('Setas', 'luvee-site');
        if ($show_dots)
          $settings[] = __('Pontos', 'luvee-site');
        if ($infinite)
          $settings[] = __('Infinito', 'luvee-site');

        echo !empty($settings) ? implode(', ', $settings) : '-';
        break;

      case 'carousel_stats':
        $stats = $this->getBannerStats($post_id, 'carousel');
        echo '<strong>' . $stats['views'] . '</strong> ' . __('visualizações', 'luvee-site') . '<br>';
        echo '<strong>' . $stats['clicks'] . '</strong> ' . __('cliques', 'luvee-site');
        break;
    }
  }

  /**
   * Colunas personalizadas para hero banners
   */
  public function heroColumns($columns)
  {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['hero_image'] = __('Imagem', 'luvee-site');
    $new_columns['hero_status'] = __('Status', 'luvee-site');
    $new_columns['hero_button'] = __('Botão', 'luvee-site');
    $new_columns['hero_stats'] = __('Estatísticas', 'luvee-site');
    $new_columns['date'] = $columns['date'];

    return $new_columns;
  }

  /**
   * Conteúdo das colunas dos hero banners
   */
  public function heroColumnContent($column, $post_id)
  {
    switch ($column) {
      case 'hero_image':
        $thumbnail = get_the_post_thumbnail($post_id, array(80, 60));
        echo $thumbnail ? $thumbnail : '<span class="dashicons dashicons-format-image" style="font-size: 40px; color: #ccc;"></span>';
        break;

      case 'hero_status':
        $is_active = get_post_meta($post_id, '_luvee_hero_active', true);
        echo $is_active ? '<span class="banner-status status-active">' . __('Ativo', 'luvee-site') . '</span>' : '<span class="banner-status status-inactive">' . __('Inativo', 'luvee-site') . '</span>';
        break;

      case 'hero_button':
        $button_text = get_post_meta($post_id, '_luvee_hero_button_text', true);
        $button_url = get_post_meta($post_id, '_luvee_hero_button_url', true);

        if ($button_text && $button_url) {
          echo '<strong>' . esc_html($button_text) . '</strong><br>';
          echo '<a href="' . esc_url($button_url) . '" target="_blank" title="' . esc_attr($button_url) . '">';
          echo '<span class="dashicons dashicons-external"></span>';
          echo '</a>';
        } else {
          echo '-';
        }
        break;

      case 'hero_stats':
        $stats = $this->getBannerStats($post_id, 'hero');
        echo '<strong>' . $stats['views'] . '</strong> ' . __('visualizações', 'luvee-site') . '<br>';
        echo '<strong>' . $stats['clicks'] . '</strong> ' . __('cliques', 'luvee-site');
        break;
    }
  }

  /**
   * Adiciona filtros nas listagens
   */
  public function addPostFilters()
  {
    global $typenow;

    if ($typenow === 'luvee_banner') {
      // Filtro por posição
      $positions = array(
        'header' => __('Cabeçalho', 'luvee-site'),
        'sidebar' => __('Barra Lateral', 'luvee-site'),
        'footer' => __('Rodapé', 'luvee-site'),
        'content' => __('Conteúdo', 'luvee-site'),
      );

      $selected = isset($_GET['banner_position']) ? $_GET['banner_position'] : '';

      echo '<select name="banner_position">';
      echo '<option value="">' . __('Todas as posições', 'luvee-site') . '</option>';
      foreach ($positions as $value => $label) {
        echo '<option value="' . esc_attr($value) . '" ' . selected($selected, $value, false) . '>' . esc_html($label) . '</option>';
      }
      echo '</select>';

      // Filtro por status
      $statuses = array(
        'active' => __('Ativo', 'luvee-site'),
        'inactive' => __('Inativo', 'luvee-site'),
      );

      $selected_status = isset($_GET['banner_status']) ? $_GET['banner_status'] : '';

      echo '<select name="banner_status">';
      echo '<option value="">' . __('Todos os status', 'luvee-site') . '</option>';
      foreach ($statuses as $value => $label) {
        echo '<option value="' . esc_attr($value) . '" ' . selected($selected_status, $value, false) . '>' . esc_html($label) . '</option>';
      }
      echo '</select>';
    }
  }

  /**
   * Filtra posts baseado nos filtros
   */
  public function filterPosts($query)
  {
    global $pagenow, $typenow;

    if ($pagenow === 'edit.php' && $typenow === 'luvee_banner' && isset($_GET['banner_position']) && $_GET['banner_position'] !== '') {
      $query->query_vars['meta_key'] = '_luvee_banner_position';
      $query->query_vars['meta_value'] = sanitize_text_field($_GET['banner_position']);
    }

    if ($pagenow === 'edit.php' && $typenow === 'luvee_banner' && isset($_GET['banner_status']) && $_GET['banner_status'] !== '') {
      $status = sanitize_text_field($_GET['banner_status']);
      $meta_value = ($status === 'active') ? 1 : 0;

      $query->query_vars['meta_key'] = '_luvee_banner_active';
      $query->query_vars['meta_value'] = $meta_value;
    }
  }

  /**
   * Obtém estatísticas de um banner
   */
  private function getBannerStats($banner_id, $banner_type)
  {
    global $wpdb;

    $table_name = $wpdb->prefix . 'luvee_banner_stats';

    $stats = $wpdb->get_row($wpdb->prepare(
      "SELECT SUM(views) as total_views, SUM(clicks) as total_clicks 
             FROM $table_name 
             WHERE banner_id = %d AND banner_type = %s",
      $banner_id,
      $banner_type
    ));

    return array(
      'views' => $stats ? intval($stats->total_views) : 0,
      'clicks' => $stats ? intval($stats->total_clicks) : 0,
    );
  }
}

// Inicializa a classe admin
new Luvee_Site_Admin();