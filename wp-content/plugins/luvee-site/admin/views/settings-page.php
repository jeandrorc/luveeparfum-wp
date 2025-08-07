<?php
/**
 * Página de configurações do plugin Luvee Site
 */

if (!defined('ABSPATH')) {
  exit;
}

// Verifica permissões
if (!current_user_can('manage_options')) {
  return;
}

// Processa salvamento das configurações
if (isset($_POST['submit'])) {
  check_admin_referer('luvee_site_settings_nonce');

  $options = array();
  $options['enable_analytics'] = isset($_POST['enable_analytics']) ? 1 : 0;
  $options['default_image_size'] = sanitize_text_field($_POST['default_image_size']);
  $options['cache_duration'] = intval($_POST['cache_duration']);

  update_option('luvee_site_options', $options);

  echo '<div class="notice notice-success is-dismissible"><p>' . __('Configurações salvas com sucesso!', 'luvee-site') . '</p></div>';
}

$options = get_option('luvee_site_options', array());
$enable_analytics = isset($options['enable_analytics']) ? $options['enable_analytics'] : 1;
$default_image_size = isset($options['default_image_size']) ? $options['default_image_size'] : 'full';
$cache_duration = isset($options['cache_duration']) ? $options['cache_duration'] : 12;
?>

<div class="wrap">
  <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

  <form method="post" action="">
    <?php wp_nonce_field('luvee_site_settings_nonce'); ?>

    <table class="form-table">
      <tbody>

        <tr>
          <th scope="row">
            <label for="enable_analytics"><?php _e('Habilitar Analytics', 'luvee-site'); ?></label>
          </th>
          <td>
            <label>
              <input type="checkbox" id="enable_analytics" name="enable_analytics" value="1" <?php checked($enable_analytics, 1); ?> />
              <?php _e('Habilitar rastreamento de visualizações e cliques', 'luvee-site'); ?>
            </label>
            <p class="description">
              <?php _e('Quando habilitado, o plugin irá rastrear quantas vezes os banners foram visualizados e clicados.', 'luvee-site'); ?>
            </p>
          </td>
        </tr>

        <tr>
          <th scope="row">
            <label for="default_image_size"><?php _e('Tamanho Padrão das Imagens', 'luvee-site'); ?></label>
          </th>
          <td>
            <select id="default_image_size" name="default_image_size">
              <?php
              $image_sizes = get_intermediate_image_sizes();
              $image_sizes[] = 'full';

              foreach ($image_sizes as $size) {
                echo '<option value="' . esc_attr($size) . '" ' . selected($default_image_size, $size, false) . '>';
                echo esc_html($size);
                echo '</option>';
              }
              ?>
            </select>
            <p class="description">
              <?php _e('Tamanho padrão das imagens nos banners e carrosséis quando nenhum tamanho específico for definido.', 'luvee-site'); ?>
            </p>
          </td>
        </tr>

        <tr>
          <th scope="row">
            <label for="cache_duration"><?php _e('Duração do Cache (horas)', 'luvee-site'); ?></label>
          </th>
          <td>
            <input type="number" id="cache_duration" name="cache_duration"
              value="<?php echo esc_attr($cache_duration); ?>" min="1" max="168" class="small-text" />
            <p class="description">
              <?php _e('Por quantas horas os dados dos banners devem ser mantidos em cache. Máximo 168 horas (1 semana).', 'luvee-site'); ?>
            </p>
          </td>
        </tr>

      </tbody>
    </table>

    <?php submit_button(); ?>
  </form>

  <hr />

  <h2><?php _e('Informações do Sistema', 'luvee-site'); ?></h2>
  <table class="widefat">
    <thead>
      <tr>
        <th><?php _e('Configuração', 'luvee-site'); ?></th>
        <th><?php _e('Valor', 'luvee-site'); ?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?php _e('Versão do Plugin', 'luvee-site'); ?></td>
        <td><?php echo LUVEE_SITE_VERSION; ?></td>
      </tr>
      <tr>
        <td><?php _e('Versão do WordPress', 'luvee-site'); ?></td>
        <td><?php echo get_bloginfo('version'); ?></td>
      </tr>
      <tr>
        <td><?php _e('Versão do PHP', 'luvee-site'); ?></td>
        <td><?php echo PHP_VERSION; ?></td>
      </tr>
      <tr>
        <td><?php _e('Total de Banners', 'luvee-site'); ?></td>
        <td>
          <?php
          $banners_count = wp_count_posts('luvee_banner');
          echo $banners_count->publish . ' ' . __('publicados', 'luvee-site');
          if ($banners_count->draft > 0) {
            echo ', ' . $banners_count->draft . ' ' . __('rascunhos', 'luvee-site');
          }
          ?>
        </td>
      </tr>
      <tr>
        <td><?php _e('Total de Carrosséis', 'luvee-site'); ?></td>
        <td>
          <?php
          $carousels_count = wp_count_posts('luvee_carousel');
          echo $carousels_count->publish . ' ' . __('publicados', 'luvee-site');
          if ($carousels_count->draft > 0) {
            echo ', ' . $carousels_count->draft . ' ' . __('rascunhos', 'luvee-site');
          }
          ?>
        </td>
      </tr>
      <tr>
        <td><?php _e('Total de Hero Banners', 'luvee-site'); ?></td>
        <td>
          <?php
          $heroes_count = wp_count_posts('luvee_hero');
          echo $heroes_count->publish . ' ' . __('publicados', 'luvee-site');
          if ($heroes_count->draft > 0) {
            echo ', ' . $heroes_count->draft . ' ' . __('rascunhos', 'luvee-site');
          }
          ?>
        </td>
      </tr>
    </tbody>
  </table>

  <hr />

  <h2><?php _e('Ferramentas', 'luvee-site'); ?></h2>
  <p><?php _e('Use estas ferramentas para manutenção e limpeza do plugin.', 'luvee-site'); ?></p>

  <table class="form-table">
    <tbody>
      <tr>
        <th scope="row"><?php _e('Limpar Estatísticas', 'luvee-site'); ?></th>
        <td>
          <button type="button" class="button"
            onclick="if(confirm('<?php _e('Tem certeza? Esta ação não pode ser desfeita.', 'luvee-site'); ?>')) { window.location.href='<?php echo wp_nonce_url(admin_url('admin.php?page=luvee-site-settings&action=clear_stats'), 'clear_stats_nonce'); ?>'; }"><?php _e('Limpar Todas as Estatísticas', 'luvee-site'); ?></button>
          <p class="description">
            <?php _e('Remove todas as estatísticas de visualizações e cliques. Esta ação não pode ser desfeita.', 'luvee-site'); ?>
          </p>
        </td>
      </tr>
      <tr>
        <th scope="row"><?php _e('Recriar Tabelas', 'luvee-site'); ?></th>
        <td>
          <button type="button" class="button"
            onclick="if(confirm('<?php _e('Tem certeza? Esta ação irá recriar as tabelas do banco de dados.', 'luvee-site'); ?>')) { window.location.href='<?php echo wp_nonce_url(admin_url('admin.php?page=luvee-site-settings&action=recreate_tables'), 'recreate_tables_nonce'); ?>'; }"><?php _e('Recriar Tabelas do Banco', 'luvee-site'); ?></button>
          <p class="description">
            <?php _e('Recria as tabelas do banco de dados do plugin. Use apenas se houver problemas com o banco.', 'luvee-site'); ?>
          </p>
        </td>
      </tr>
    </tbody>
  </table>
</div>

<?php
// Processa ações das ferramentas
if (isset($_GET['action'])) {
  global $wpdb;

  if ($_GET['action'] === 'clear_stats' && wp_verify_nonce($_GET['_wpnonce'], 'clear_stats_nonce')) {
    $table_name = $wpdb->prefix . 'luvee_banner_stats';
    $wpdb->query("TRUNCATE TABLE $table_name");
    echo '<div class="notice notice-success is-dismissible"><p>' . __('Estatísticas limpas com sucesso!', 'luvee-site') . '</p></div>';
  }

  if ($_GET['action'] === 'recreate_tables' && wp_verify_nonce($_GET['_wpnonce'], 'recreate_tables_nonce')) {
    // Recria tabela de estatísticas
    $table_name = $wpdb->prefix . 'luvee_banner_stats';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            banner_id bigint(20) NOT NULL,
            banner_type varchar(50) NOT NULL,
            views bigint(20) DEFAULT 0,
            clicks bigint(20) DEFAULT 0,
            date_created datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY banner_id (banner_id),
            KEY banner_type (banner_type)
        ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    echo '<div class="notice notice-success is-dismissible"><p>' . __('Tabelas recriadas com sucesso!', 'luvee-site') . '</p></div>';
  }
}
?>