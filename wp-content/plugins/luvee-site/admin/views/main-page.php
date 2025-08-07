<?php
/**
 * Página principal do plugin Luvee Site
 */

if (!defined('ABSPATH')) {
  exit;
}

// Verifica permissões
if (!current_user_can('manage_options')) {
  return;
}
?>

<div class="wrap">
  <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

  <div class="luvee-admin-dashboard">
    <div class="luvee-dashboard-widgets">

      <!-- Widget de Estatísticas -->
      <div class="luvee-widget">
        <h2><?php _e('Estatísticas Gerais', 'luvee-site'); ?></h2>
        <div class="luvee-stats-grid">
          <?php
          // Conta banners
          $banners_count = wp_count_posts('luvee_banner');
          $carousels_count = wp_count_posts('luvee_carousel');
          $heroes_count = wp_count_posts('luvee_hero');
          ?>
          <div class="luvee-stat-item">
            <span class="luvee-stat-number"><?php echo $banners_count->publish; ?></span>
            <span class="luvee-stat-label"><?php _e('Banners', 'luvee-site'); ?></span>
          </div>
          <div class="luvee-stat-item">
            <span class="luvee-stat-number"><?php echo $carousels_count->publish; ?></span>
            <span class="luvee-stat-label"><?php _e('Carrosséis', 'luvee-site'); ?></span>
          </div>
          <div class="luvee-stat-item">
            <span class="luvee-stat-number"><?php echo $heroes_count->publish; ?></span>
            <span class="luvee-stat-label"><?php _e('Hero Banners', 'luvee-site'); ?></span>
          </div>
        </div>
      </div>

      <!-- Widget de Ações Rápidas -->
      <div class="luvee-widget">
        <h2><?php _e('Ações Rápidas', 'luvee-site'); ?></h2>
        <div class="luvee-quick-actions">
          <a href="<?php echo admin_url('post-new.php?post_type=luvee_banner'); ?>" class="button button-primary">
            <span class="dashicons dashicons-plus-alt"></span>
            <?php _e('Novo Banner', 'luvee-site'); ?>
          </a>
          <a href="<?php echo admin_url('post-new.php?post_type=luvee_carousel'); ?>" class="button button-primary">
            <span class="dashicons dashicons-plus-alt"></span>
            <?php _e('Novo Carrossel', 'luvee-site'); ?>
          </a>
          <a href="<?php echo admin_url('post-new.php?post_type=luvee_hero'); ?>" class="button button-primary">
            <span class="dashicons dashicons-plus-alt"></span>
            <?php _e('Novo Hero Banner', 'luvee-site'); ?>
          </a>
        </div>
      </div>

      <!-- Widget de Banners Ativos -->
      <div class="luvee-widget">
        <h2><?php _e('Banners Ativos', 'luvee-site'); ?></h2>
        <?php
        $active_banners = get_posts(array(
          'post_type' => 'luvee_banner',
          'post_status' => 'publish',
          'meta_key' => '_luvee_banner_active',
          'meta_value' => 1,
          'posts_per_page' => 5
        ));

        if ($active_banners):
          ?>
          <ul class="luvee-active-list">
            <?php foreach ($active_banners as $banner): ?>
              <li>
                <a href="<?php echo get_edit_post_link($banner->ID); ?>">
                  <?php echo esc_html($banner->post_title); ?>
                </a>
                <span class="luvee-position-tag">
                  <?php
                  $position = get_post_meta($banner->ID, '_luvee_banner_position', true);
                  echo esc_html($position);
                  ?>
                </span>
              </li>
            <?php endforeach; ?>
          </ul>
          <p>
            <a href="<?php echo admin_url('edit.php?post_type=luvee_banner'); ?>" class="button">
              <?php _e('Ver Todos os Banners', 'luvee-site'); ?>
            </a>
          </p>
        <?php else: ?>
          <p><?php _e('Nenhum banner ativo encontrado.', 'luvee-site'); ?></p>
          <p>
            <a href="<?php echo admin_url('post-new.php?post_type=luvee_banner'); ?>" class="button button-primary">
              <?php _e('Criar Primeiro Banner', 'luvee-site'); ?>
            </a>
          </p>
        <?php endif; ?>
      </div>

      <!-- Widget de Como Usar -->
      <div class="luvee-widget">
        <h2><?php _e('Como Usar', 'luvee-site'); ?></h2>
        <div class="luvee-how-to-use">
          <h4><?php _e('Shortcodes Disponíveis:', 'luvee-site'); ?></h4>
          <ul>
            <li><code>[luvee_banner id="123"]</code> - <?php _e('Exibe um banner específico', 'luvee-site'); ?></li>
            <li><code>[luvee_carousel id="123"]</code> - <?php _e('Exibe um carrossel específico', 'luvee-site'); ?>
            </li>
            <li><code>[luvee_hero id="123"]</code> - <?php _e('Exibe um hero banner específico', 'luvee-site'); ?></li>
            <li><code>[luvee_banners_by_position position="header"]</code> -
              <?php _e('Exibe banners por posição', 'luvee-site'); ?>
            </li>
          </ul>

          <h4><?php _e('Posições de Banner:', 'luvee-site'); ?></h4>
          <ul>
            <li><strong>header</strong> - <?php _e('Cabeçalho', 'luvee-site'); ?></li>
            <li><strong>sidebar</strong> - <?php _e('Barra Lateral', 'luvee-site'); ?></li>
            <li><strong>footer</strong> - <?php _e('Rodapé', 'luvee-site'); ?></li>
            <li><strong>content</strong> - <?php _e('Conteúdo', 'luvee-site'); ?></li>
          </ul>
        </div>
      </div>

    </div>
  </div>
</div>

<style>
  .luvee-admin-dashboard {
    margin-top: 20px;
  }

  .luvee-dashboard-widgets {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
  }

  .luvee-widget {
    background: #fff;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
    padding: 20px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
  }

  .luvee-widget h2 {
    margin-top: 0;
    font-size: 18px;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
    margin-bottom: 15px;
  }

  .luvee-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
    gap: 15px;
    text-align: center;
  }

  .luvee-stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .luvee-stat-number {
    font-size: 24px;
    font-weight: bold;
    color: #0073aa;
    display: block;
  }

  .luvee-stat-label {
    font-size: 12px;
    color: #666;
    text-transform: uppercase;
    margin-top: 5px;
  }

  .luvee-quick-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .luvee-quick-actions .button {
    justify-content: flex-start;
    text-align: left;
  }

  .luvee-quick-actions .dashicons {
    margin-right: 8px;
  }

  .luvee-active-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .luvee-active-list li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #eee;
  }

  .luvee-active-list li:last-child {
    border-bottom: none;
  }

  .luvee-position-tag {
    background: #f0f0f1;
    color: #646970;
    padding: 2px 8px;
    border-radius: 3px;
    font-size: 11px;
    text-transform: uppercase;
  }

  .luvee-how-to-use ul {
    padding-left: 20px;
  }

  .luvee-how-to-use code {
    background: #f1f1f1;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: monospace;
    font-size: 13px;
  }
</style>