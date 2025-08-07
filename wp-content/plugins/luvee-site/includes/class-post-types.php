<?php
/**
 * Classe para gerenciar post types personalizados
 */

if (!defined('ABSPATH')) {
    exit;
}

class Luvee_Site_Post_Types {
    
    /**
     * Registra todos os post types personalizados
     */
    public function register() {
        $this->registerBanners();
        $this->registerCarousels();
        $this->registerHeroBanners();
        
        // Adiciona meta boxes após registrar os post types
        add_action('add_meta_boxes', array($this, 'addMetaBoxes'));
        add_action('save_post', array($this, 'savePostMeta'));
    }
    
    /**
     * Registra post type para Banners
     */
    private function registerBanners() {
        $labels = array(
            'name'               => __('Banners', 'luvee-site'),
            'singular_name'      => __('Banner', 'luvee-site'),
            'menu_name'          => __('Banners', 'luvee-site'),
            'add_new'            => __('Adicionar Novo', 'luvee-site'),
            'add_new_item'       => __('Adicionar Novo Banner', 'luvee-site'),
            'edit_item'          => __('Editar Banner', 'luvee-site'),
            'new_item'           => __('Novo Banner', 'luvee-site'),
            'view_item'          => __('Ver Banner', 'luvee-site'),
            'search_items'       => __('Buscar Banners', 'luvee-site'),
            'not_found'          => __('Nenhum banner encontrado', 'luvee-site'),
            'not_found_in_trash' => __('Nenhum banner encontrado na lixeira', 'luvee-site'),
        );
        
        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'query_var'           => true,
            'rewrite'             => array('slug' => 'banner'),
            'capability_type'     => 'post',
            'has_archive'         => false,
            'hierarchical'        => false,
            'menu_position'       => null,
            'supports'            => array('title', 'editor', 'thumbnail'),
            'show_in_rest'        => true,
        );
        
        register_post_type('luvee_banner', $args);
    }
    
    /**
     * Registra post type para Carrosséis
     */
    private function registerCarousels() {
        $labels = array(
            'name'               => __('Carrosséis', 'luvee-site'),
            'singular_name'      => __('Carrossel', 'luvee-site'),
            'menu_name'          => __('Carrosséis', 'luvee-site'),
            'add_new'            => __('Adicionar Novo', 'luvee-site'),
            'add_new_item'       => __('Adicionar Novo Carrossel', 'luvee-site'),
            'edit_item'          => __('Editar Carrossel', 'luvee-site'),
            'new_item'           => __('Novo Carrossel', 'luvee-site'),
            'view_item'          => __('Ver Carrossel', 'luvee-site'),
            'search_items'       => __('Buscar Carrosséis', 'luvee-site'),
            'not_found'          => __('Nenhum carrossel encontrado', 'luvee-site'),
            'not_found_in_trash' => __('Nenhum carrossel encontrado na lixeira', 'luvee-site'),
        );
        
        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'query_var'           => true,
            'rewrite'             => array('slug' => 'carrossel'),
            'capability_type'     => 'post',
            'has_archive'         => false,
            'hierarchical'        => false,
            'menu_position'       => null,
            'supports'            => array('title', 'editor'),
            'show_in_rest'        => true,
        );
        
        register_post_type('luvee_carousel', $args);
    }
    
    /**
     * Registra post type para Hero Banners
     */
    private function registerHeroBanners() {
        $labels = array(
            'name'               => __('Hero Banners', 'luvee-site'),
            'singular_name'      => __('Hero Banner', 'luvee-site'),
            'menu_name'          => __('Hero Banners', 'luvee-site'),
            'add_new'            => __('Adicionar Novo', 'luvee-site'),
            'add_new_item'       => __('Adicionar Novo Hero Banner', 'luvee-site'),
            'edit_item'          => __('Editar Hero Banner', 'luvee-site'),
            'new_item'           => __('Novo Hero Banner', 'luvee-site'),
            'view_item'          => __('Ver Hero Banner', 'luvee-site'),
            'search_items'       => __('Buscar Hero Banners', 'luvee-site'),
            'not_found'          => __('Nenhum hero banner encontrado', 'luvee-site'),
            'not_found_in_trash' => __('Nenhum hero banner encontrado na lixeira', 'luvee-site'),
        );
        
        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'query_var'           => true,
            'rewrite'             => array('slug' => 'hero-banner'),
            'capability_type'     => 'post',
            'has_archive'         => false,
            'hierarchical'        => false,
            'menu_position'       => null,
            'supports'            => array('title', 'editor', 'thumbnail'),
            'show_in_rest'        => true,
        );
        
        register_post_type('luvee_hero', $args);
    }
    
    /**
     * Adiciona meta boxes para todos os post types
     */
    public function addMetaBoxes() {
        // Meta box para banners
        add_meta_box(
            'luvee_banner_settings',
            __('Configurações do Banner', 'luvee-site'),
            array($this, 'bannerMetaBox'),
            'luvee_banner',
            'normal',
            'high'
        );
        
        // Meta box para carrosséis
        add_meta_box(
            'luvee_carousel_settings',
            __('Configurações do Carrossel', 'luvee-site'),
            array($this, 'carouselMetaBox'),
            'luvee_carousel',
            'normal',
            'high'
        );
        
        // Meta box para hero banners
        add_meta_box(
            'luvee_hero_settings',
            __('Configurações do Hero Banner', 'luvee-site'),
            array($this, 'heroMetaBox'),
            'luvee_hero',
            'normal',
            'high'
        );
    }
    
    /**
     * Meta box para banners
     */
    public function bannerMetaBox($post) {
        wp_nonce_field('luvee_meta_box', 'luvee_meta_box_nonce');
        
        $link_url = get_post_meta($post->ID, '_luvee_banner_link_url', true);
        $link_target = get_post_meta($post->ID, '_luvee_banner_link_target', true);
        $position = get_post_meta($post->ID, '_luvee_banner_position', true);
        $active = get_post_meta($post->ID, '_luvee_banner_active', true);
        
        ?>
<table class="form-table">
  <tr>
    <th><label for="luvee_banner_link_url"><?php _e('URL do Link', 'luvee-site'); ?></label></th>
    <td><input type="url" id="luvee_banner_link_url" name="luvee_banner_link_url"
        value="<?php echo esc_attr($link_url); ?>" class="regular-text" /></td>
  </tr>
  <tr>
    <th><label for="luvee_banner_link_target"><?php _e('Abrir Link', 'luvee-site'); ?></label></th>
    <td>
      <select id="luvee_banner_link_target" name="luvee_banner_link_target">
        <option value="_self" <?php selected($link_target, '_self'); ?>><?php _e('Na mesma janela', 'luvee-site'); ?>
        </option>
        <option value="_blank" <?php selected($link_target, '_blank'); ?>><?php _e('Em nova janela', 'luvee-site'); ?>
        </option>
      </select>
    </td>
  </tr>
  <tr>
    <th><label for="luvee_banner_position"><?php _e('Posição', 'luvee-site'); ?></label></th>
    <td>
      <select id="luvee_banner_position" name="luvee_banner_position">
        <option value="header" <?php selected($position, 'header'); ?>><?php _e('Cabeçalho', 'luvee-site'); ?></option>
        <option value="sidebar" <?php selected($position, 'sidebar'); ?>><?php _e('Barra Lateral', 'luvee-site'); ?>
        </option>
        <option value="footer" <?php selected($position, 'footer'); ?>><?php _e('Rodapé', 'luvee-site'); ?></option>
        <option value="content" <?php selected($position, 'content'); ?>><?php _e('Conteúdo', 'luvee-site'); ?></option>
      </select>
    </td>
  </tr>
  <tr>
    <th><label for="luvee_banner_active"><?php _e('Status', 'luvee-site'); ?></label></th>
    <td>
      <label>
        <input type="checkbox" id="luvee_banner_active" name="luvee_banner_active" value="1"
          <?php checked($active, 1); ?> />
        <?php _e('Banner ativo', 'luvee-site'); ?>
      </label>
    </td>
  </tr>
</table>
<?php
    }
    
    /**
     * Meta box para carrosséis
     */
    public function carouselMetaBox($post) {
        wp_nonce_field('luvee_meta_box', 'luvee_meta_box_nonce');
        
        $autoplay = get_post_meta($post->ID, '_luvee_carousel_autoplay', true);
        $autoplay_speed = get_post_meta($post->ID, '_luvee_carousel_autoplay_speed', true) ?: 3000;
        $show_arrows = get_post_meta($post->ID, '_luvee_carousel_show_arrows', true);
        $show_dots = get_post_meta($post->ID, '_luvee_carousel_show_dots', true);
        $items = get_post_meta($post->ID, '_luvee_carousel_items', true) ?: array();
        
        ?>
<h4><?php _e('Configurações', 'luvee-site'); ?></h4>
<table class="form-table">
  <tr>
    <th><label for="luvee_carousel_autoplay"><?php _e('Autoplay', 'luvee-site'); ?></label></th>
    <td>
      <label>
        <input type="checkbox" id="luvee_carousel_autoplay" name="luvee_carousel_autoplay" value="1"
          <?php checked($autoplay, 1); ?> />
        <?php _e('Ativar autoplay', 'luvee-site'); ?>
      </label>
    </td>
  </tr>
  <tr>
    <th><label for="luvee_carousel_autoplay_speed"><?php _e('Velocidade (ms)', 'luvee-site'); ?></label></th>
    <td><input type="number" id="luvee_carousel_autoplay_speed" name="luvee_carousel_autoplay_speed"
        value="<?php echo esc_attr($autoplay_speed); ?>" min="1000" step="500" /></td>
  </tr>
  <tr>
    <th><label for="luvee_carousel_show_arrows"><?php _e('Mostrar Setas', 'luvee-site'); ?></label></th>
    <td>
      <label>
        <input type="checkbox" id="luvee_carousel_show_arrows" name="luvee_carousel_show_arrows" value="1"
          <?php checked($show_arrows, 1); ?> />
        <?php _e('Mostrar setas de navegação', 'luvee-site'); ?>
      </label>
    </td>
  </tr>
  <tr>
    <th><label for="luvee_carousel_show_dots"><?php _e('Mostrar Pontos', 'luvee-site'); ?></label></th>
    <td>
      <label>
        <input type="checkbox" id="luvee_carousel_show_dots" name="luvee_carousel_show_dots" value="1"
          <?php checked($show_dots, 1); ?> />
        <?php _e('Mostrar pontos de navegação', 'luvee-site'); ?>
      </label>
    </td>
  </tr>
</table>

<h4><?php _e('Itens do Carrossel', 'luvee-site'); ?></h4>
<div id="carousel-items-container">
  <?php
            if (!empty($items)) {
                foreach ($items as $index => $item) {
                    $this->renderCarouselItem($index, $item);
                }
            }
            ?>
</div>
<p>
  <button type="button" id="add-carousel-item" class="button"><?php _e('Adicionar Item', 'luvee-site'); ?></button>
</p>

<script type="text/template" id="carousel-item-template">
  <?php $this->renderCarouselItem('{{INDEX}}', array()); ?>
        </script>
<?php
    }
    
    /**
     * Renderiza um item do carrossel
     */
    private function renderCarouselItem($index, $item) {
        $image_url = isset($item['image_url']) ? $item['image_url'] : '';
        $title = isset($item['title']) ? $item['title'] : '';
        $link_url = isset($item['link_url']) ? $item['link_url'] : '';
        
        ?>
<div class="carousel-item-wrapper" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 10px;">
  <h4><?php _e('Item', 'luvee-site'); ?> #<span
      class="item-number"><?php echo is_numeric($index) ? ($index + 1) : 1; ?></span></h4>
  <table class="form-table">
    <tr>
      <th><label><?php _e('Imagem URL', 'luvee-site'); ?></label></th>
      <td><input type="text" name="carousel_items[<?php echo $index; ?>][image_url]"
          value="<?php echo esc_attr($image_url); ?>" class="regular-text" /></td>
    </tr>
    <tr>
      <th><label><?php _e('Título', 'luvee-site'); ?></label></th>
      <td><input type="text" name="carousel_items[<?php echo $index; ?>][title]" value="<?php echo esc_attr($title); ?>"
          class="regular-text" /></td>
    </tr>
    <tr>
      <th><label><?php _e('URL do Link', 'luvee-site'); ?></label></th>
      <td><input type="url" name="carousel_items[<?php echo $index; ?>][link_url]"
          value="<?php echo esc_attr($link_url); ?>" class="regular-text" /></td>
    </tr>
  </table>
  <button type="button" class="button remove-carousel-item"><?php _e('Remover Item', 'luvee-site'); ?></button>
</div>
<?php
    }
    
    /**
     * Meta box para hero banners
     */
    public function heroMetaBox($post) {
        wp_nonce_field('luvee_meta_box', 'luvee_meta_box_nonce');
        
        $image_url = get_post_meta($post->ID, '_luvee_hero_image_url', true);
        $image_alt = get_post_meta($post->ID, '_luvee_hero_image_alt', true);
        $link_url = get_post_meta($post->ID, '_luvee_hero_link_url', true);
        $link_target = get_post_meta($post->ID, '_luvee_hero_link_target', true);
        $active = get_post_meta($post->ID, '_luvee_hero_active', true);
        $start_date = get_post_meta($post->ID, '_luvee_hero_start_date', true);
        $end_date = get_post_meta($post->ID, '_luvee_hero_end_date', true);
        
        ?>
        <h4><?php _e('Configurações da Imagem', 'luvee-site'); ?></h4>
        <table class="form-table">
            <tr>
                <th><label for="luvee_hero_image_url"><?php _e('URL da Imagem', 'luvee-site'); ?></label></th>
                <td>
                    <input type="url" id="luvee_hero_image_url" name="luvee_hero_image_url" value="<?php echo esc_attr($image_url); ?>" class="regular-text" />
                    <button type="button" class="button select-hero-image"><?php _e('Selecionar Imagem', 'luvee-site'); ?></button>
                    <p class="description"><?php _e('Recomendado: 1200x400px para melhor qualidade.', 'luvee-site'); ?></p>
                    <?php if ($image_url): ?>
                        <div class="image-preview" style="margin-top: 10px;">
                            <img src="<?php echo esc_url($image_url); ?>" style="max-width: 300px; height: auto; border: 1px solid #ddd; border-radius: 4px;" />
                        </div>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th><label for="luvee_hero_image_alt"><?php _e('Texto Alternativo (Alt)', 'luvee-site'); ?></label></th>
                <td>
                    <input type="text" id="luvee_hero_image_alt" name="luvee_hero_image_alt" value="<?php echo esc_attr($image_alt); ?>" class="regular-text" />
                    <p class="description"><?php _e('Importante para acessibilidade e SEO.', 'luvee-site'); ?></p>
                </td>
            </tr>
        </table>
        
        <h4><?php _e('Configurações do Link', 'luvee-site'); ?></h4>
        <table class="form-table">
            <tr>
                <th><label for="luvee_hero_link_url"><?php _e('URL do Link', 'luvee-site'); ?></label></th>
                <td>
                    <input type="url" id="luvee_hero_link_url" name="luvee_hero_link_url" value="<?php echo esc_attr($link_url); ?>" class="regular-text" />
                    <p class="description"><?php _e('URL para onde o banner vai direcionar quando clicado (opcional).', 'luvee-site'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="luvee_hero_link_target"><?php _e('Abrir Link', 'luvee-site'); ?></label></th>
                <td>
                    <select id="luvee_hero_link_target" name="luvee_hero_link_target">
                        <option value="_self" <?php selected($link_target, '_self'); ?>><?php _e('Na mesma janela', 'luvee-site'); ?></option>
                        <option value="_blank" <?php selected($link_target, '_blank'); ?>><?php _e('Em nova janela', 'luvee-site'); ?></option>
                    </select>
                </td>
            </tr>
        </table>
        
        <h4><?php _e('Configurações de Exibição', 'luvee-site'); ?></h4>
        <table class="form-table">
            <tr>
                <th><label for="luvee_hero_active"><?php _e('Status', 'luvee-site'); ?></label></th>
                <td>
                    <label>
                        <input type="checkbox" id="luvee_hero_active" name="luvee_hero_active" value="1" <?php checked($active, 1); ?> />
                        <?php _e('Hero banner ativo', 'luvee-site'); ?>
                    </label>
                    <p class="description"><?php _e('Desmarque para desativar temporariamente este banner.', 'luvee-site'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="luvee_hero_start_date"><?php _e('Data de Início', 'luvee-site'); ?></label></th>
                <td>
                    <input type="datetime-local" id="luvee_hero_start_date" name="luvee_hero_start_date" value="<?php echo esc_attr($start_date); ?>" />
                    <p class="description"><?php _e('Data e hora para começar a exibir o banner (opcional).', 'luvee-site'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="luvee_hero_end_date"><?php _e('Data de Validade', 'luvee-site'); ?></label></th>
                <td>
                    <input type="datetime-local" id="luvee_hero_end_date" name="luvee_hero_end_date" value="<?php echo esc_attr($end_date); ?>" />
                    <p class="description"><?php _e('Data e hora para parar de exibir o banner (opcional).', 'luvee-site'); ?></p>
                </td>
            </tr>
        </table>
        
        <script>
        jQuery(document).ready(function($) {
            $('.select-hero-image').on('click', function(e) {
                e.preventDefault();
                
                var button = $(this);
                var inputField = button.siblings('#luvee_hero_image_url');
                var preview = button.siblings('.image-preview');
                
                var mediaUploader = wp.media({
                    title: 'Selecionar Imagem do Hero Banner',
                    button: {
                        text: 'Usar esta imagem'
                    },
                    multiple: false
                });
                
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    
                    inputField.val(attachment.url);
                    
                    if (preview.length) {
                        preview.html('<img src="' + attachment.url + '" style="max-width: 300px; height: auto; border: 1px solid #ddd; border-radius: 4px;" />');
                    } else {
                        inputField.after('<div class="image-preview" style="margin-top: 10px;"><img src="' + attachment.url + '" style="max-width: 300px; height: auto; border: 1px solid #ddd; border-radius: 4px;" /></div>');
                    }
                    
                    // Auto-preenche o alt se estiver vazio
                    if (!$('#luvee_hero_image_alt').val() && attachment.alt) {
                        $('#luvee_hero_image_alt').val(attachment.alt);
                    }
                });
                
                mediaUploader.open();
            });
        });
        </script>
        <?php
    }
    
    /**
     * Salva meta dados dos posts
     */
    public function savePostMeta($post_id) {
        // Verifica nonce
        if (!isset($_POST['luvee_meta_box_nonce']) || !wp_verify_nonce($_POST['luvee_meta_box_nonce'], 'luvee_meta_box')) {
            return;
        }
        
        // Verifica autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Verifica permissões
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        $post_type = get_post_type($post_id);
        
        // Salva dados do banner
        if ($post_type === 'luvee_banner') {
            $this->saveBannerMeta($post_id);
        }
        
        // Salva dados do carrossel
        if ($post_type === 'luvee_carousel') {
            $this->saveCarouselMeta($post_id);
        }
        
        // Salva dados do hero banner
        if ($post_type === 'luvee_hero') {
            $this->saveHeroMeta($post_id);
        }
    }
    
    /**
     * Salva meta dados específicos do banner
     */
    private function saveBannerMeta($post_id) {
        $fields = array(
            'luvee_banner_link_url' => '_luvee_banner_link_url',
            'luvee_banner_link_target' => '_luvee_banner_link_target',
            'luvee_banner_position' => '_luvee_banner_position',
        );
        
        foreach ($fields as $field => $meta_key) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$field]));
            }
        }
        
        // Campo checkbox
        $active = isset($_POST['luvee_banner_active']) ? 1 : 0;
        update_post_meta($post_id, '_luvee_banner_active', $active);
    }
    
    /**
     * Salva meta dados específicos do carrossel
     */
    private function saveCarouselMeta($post_id) {
        // Configurações do carrossel
        $autoplay = isset($_POST['luvee_carousel_autoplay']) ? 1 : 0;
        update_post_meta($post_id, '_luvee_carousel_autoplay', $autoplay);
        
        $autoplay_speed = isset($_POST['luvee_carousel_autoplay_speed']) ? intval($_POST['luvee_carousel_autoplay_speed']) : 3000;
        update_post_meta($post_id, '_luvee_carousel_autoplay_speed', $autoplay_speed);
        
        $show_arrows = isset($_POST['luvee_carousel_show_arrows']) ? 1 : 0;
        update_post_meta($post_id, '_luvee_carousel_show_arrows', $show_arrows);
        
        $show_dots = isset($_POST['luvee_carousel_show_dots']) ? 1 : 0;
        update_post_meta($post_id, '_luvee_carousel_show_dots', $show_dots);
        
        // Itens do carrossel
        if (isset($_POST['carousel_items']) && is_array($_POST['carousel_items'])) {
            $items = array();
            foreach ($_POST['carousel_items'] as $item) {
                if (!empty($item['image_url']) || !empty($item['title'])) {
                    $items[] = array(
                        'image_url' => sanitize_url($item['image_url']),
                        'title' => sanitize_text_field($item['title']),
                        'link_url' => sanitize_url($item['link_url']),
                    );
                }
            }
            update_post_meta($post_id, '_luvee_carousel_items', $items);
        }
    }
    
    /**
     * Salva meta dados específicos do hero banner
     */
    private function saveHeroMeta($post_id) {
        $fields = array(
            'luvee_hero_image_url' => '_luvee_hero_image_url',
            'luvee_hero_image_alt' => '_luvee_hero_image_alt',
            'luvee_hero_link_url' => '_luvee_hero_link_url',
            'luvee_hero_link_target' => '_luvee_hero_link_target',
            'luvee_hero_start_date' => '_luvee_hero_start_date',
            'luvee_hero_end_date' => '_luvee_hero_end_date',
        );
        
        foreach ($fields as $field => $meta_key) {
            if (isset($_POST[$field])) {
                if (strpos($field, '_date') !== false) {
                    // Para campos de data, sanitiza de forma diferente
                    $date_value = sanitize_text_field($_POST[$field]);
                    update_post_meta($post_id, $meta_key, $date_value);
                } elseif (strpos($field, '_url') !== false) {
                    // Para URLs, usa sanitize_url
                    update_post_meta($post_id, $meta_key, sanitize_url($_POST[$field]));
                } else {
                    // Para outros campos, usa sanitize_text_field
                    update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$field]));
                }
            } else {
                // Remove meta se o campo não foi enviado
                delete_post_meta($post_id, $meta_key);
            }
        }
        
        // Campo checkbox
        $active = isset($_POST['luvee_hero_active']) ? 1 : 0;
        update_post_meta($post_id, '_luvee_hero_active', $active);
    }
}