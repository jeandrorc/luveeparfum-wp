<?php
/**
 * Classe para gerenciar shortcodes
 */

if (!defined('ABSPATH')) {
    exit;
}

class Luvee_Site_Shortcodes
{

    /**
     * Registra todos os shortcodes
     */
    public function register()
    {
        add_shortcode('luvee_banner', array($this, 'displayBanner'));
        add_shortcode('luvee_carousel', array($this, 'displayCarousel'));
        add_shortcode('luvee_hero', array($this, 'displayHero'));
        add_shortcode('luvee_banners_by_position', array($this, 'displayBannersByPosition'));
    }

    /**
     * Shortcode para exibir um banner específico
     * Uso: [luvee_banner id="123"]
     */
    public function displayBanner($atts)
    {
        $atts = shortcode_atts(array(
            'id' => 0,
        ), $atts, 'luvee_banner');

        if (!$atts['id']) {
            return '';
        }

        $banner = get_post($atts['id']);
        if (!$banner || $banner->post_type !== 'luvee_banner') {
            return '';
        }

        // Verifica se o banner está ativo
        $is_active = get_post_meta($banner->ID, '_luvee_banner_active', true);
        if (!$is_active) {
            return '';
        }

        // Verifica datas de início e fim
        $start_date = get_post_meta($banner->ID, '_luvee_banner_start_date', true);
        $end_date = get_post_meta($banner->ID, '_luvee_banner_end_date', true);
        $current_date = date('Y-m-d');

        if ($start_date && $current_date < $start_date) {
            return '';
        }

        if ($end_date && $current_date > $end_date) {
            return '';
        }

        // Incrementa visualizações
        $this->incrementBannerViews($banner->ID, 'banner');

        return $this->renderBanner($banner);
    }

    /**
     * Shortcode para exibir banners por posição
     * Uso: [luvee_banners_by_position position="header" limit="3"]
     */
    public function displayBannersByPosition($atts)
    {
        $atts = shortcode_atts(array(
            'position' => 'header',
            'limit' => -1,
        ), $atts, 'luvee_banners_by_position');

        $args = array(
            'post_type' => 'luvee_banner',
            'post_status' => 'publish',
            'posts_per_page' => intval($atts['limit']),
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_luvee_banner_position',
                    'value' => $atts['position'],
                    'compare' => '='
                ),
                array(
                    'key' => '_luvee_banner_active',
                    'value' => 1,
                    'compare' => '='
                )
            )
        );

        $banners = get_posts($args);

        if (empty($banners)) {
            return '';
        }

        $output = '<div class="luvee-banners-container position-' . esc_attr($atts['position']) . '">';

        foreach ($banners as $banner) {
            // Verifica datas
            $start_date = get_post_meta($banner->ID, '_luvee_banner_start_date', true);
            $end_date = get_post_meta($banner->ID, '_luvee_banner_end_date', true);
            $current_date = date('Y-m-d');

            if ($start_date && $current_date < $start_date) {
                continue;
            }

            if ($end_date && $current_date > $end_date) {
                continue;
            }

            // Incrementa visualizações
            $this->incrementBannerViews($banner->ID, 'banner');

            $output .= $this->renderBanner($banner);
        }

        $output .= '</div>';

        return $output;
    }

    /**
     * Shortcode para exibir um carrossel
     * Uso: [luvee_carousel id="123"]
     */
    public function displayCarousel($atts)
    {
        $atts = shortcode_atts(array(
            'id' => 0,
        ), $atts, 'luvee_carousel');

        if (!$atts['id']) {
            return '';
        }

        $carousel = get_post($atts['id']);
        if (!$carousel || $carousel->post_type !== 'luvee_carousel') {
            return '';
        }

        // Incrementa visualizações
        $this->incrementBannerViews($carousel->ID, 'carousel');

        return $this->renderCarousel($carousel);
    }

    /**
     * Shortcode para exibir hero banner(s)
     * Uso: [luvee_hero] ou [luvee_hero id="123"]
     */
    public function displayHero($atts)
    {
        $atts = shortcode_atts(array(
            'id' => 0,
            'autoplay' => 'true',
            'autoplay_speed' => '5000',
            'show_arrows' => 'true',
            'show_dots' => 'true'
        ), $atts, 'luvee_hero');

        // Se um ID específico foi fornecido, exibe apenas esse hero banner
        if (!empty($atts['id'])) {
            return $this->displaySingleHero($atts['id']);
        }

        // Busca todos os hero banners ativos
        $hero_banners = $this->getActiveHeroBanners();

        if (empty($hero_banners)) {
            return '';
        }

        // Se há apenas um banner, exibe sem carrossel
        if (count($hero_banners) === 1) {
            return $this->displaySingleHero($hero_banners[0]->ID);
        }

        // Se há múltiplos banners, exibe como carrossel
        return $this->renderHeroCarousel($hero_banners, $atts);
    }

    /**
     * Exibe um único hero banner
     */
    private function displaySingleHero($hero_id)
    {
        $hero = get_post($hero_id);
        if (!$hero || $hero->post_type !== 'luvee_hero') {
            return '';
        }

        // Verifica se o hero está ativo
        $is_active = get_post_meta($hero->ID, '_luvee_hero_active', true);
        if (!$is_active) {
            return '';
        }

        // Verifica datas
        if (!$this->isHeroActive($hero->ID)) {
            return '';
        }

        // Incrementa visualizações
        $this->incrementBannerViews($hero->ID, 'hero');

        return $this->renderSingleHero($hero);
    }

    /**
     * Busca hero banners ativos respeitando datas
     */
    private function getActiveHeroBanners()
    {
        $args = array(
            'post_type' => 'luvee_hero',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_luvee_hero_active',
                    'value' => '1',
                    'compare' => '='
                )
            ),
            'orderby' => 'menu_order',
            'order' => 'ASC'
        );

        $banners = get_posts($args);
        $active_banners = array();

        foreach ($banners as $banner) {
            if ($this->isHeroActive($banner->ID)) {
                $active_banners[] = $banner;
            }
        }

        return $active_banners;
    }

    /**
     * Verifica se um hero banner está ativo considerando datas
     */
    private function isHeroActive($hero_id)
    {
        $current_time = current_time('Y-m-d H:i:s');

        $start_date = get_post_meta($hero_id, '_luvee_hero_start_date', true);
        $end_date = get_post_meta($hero_id, '_luvee_hero_end_date', true);

        // Verifica data de início
        if (!empty($start_date) && $current_time < $start_date) {
            return false;
        }

        // Verifica data de fim
        if (!empty($end_date) && $current_time > $end_date) {
            return false;
        }

        return true;
    }

    /**
     * Renderiza um banner
     */
    private function renderBanner($banner)
    {
        $link_url = get_post_meta($banner->ID, '_luvee_banner_link_url', true);
        $link_target = get_post_meta($banner->ID, '_luvee_banner_link_target', true) ?: '_self';
        $position = get_post_meta($banner->ID, '_luvee_banner_position', true);

        $image = get_the_post_thumbnail($banner->ID, 'full', array(
            'class' => 'luvee-banner-image',
            'alt' => $banner->post_title
        ));

        if (!$image) {
            return '';
        }

        $output = '<div class="luvee-banner banner-position-' . esc_attr($position) . '" data-banner-id="' . $banner->ID . '">';

        if ($link_url) {
            $output .= '<a href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '" onclick="luveeTrackBannerClick(' . $banner->ID . ', \'banner\')">';
        }

        $output .= $image;

        if ($banner->post_content) {
            $output .= '<div class="luvee-banner-content">';
            $output .= '<h3 class="luvee-banner-title">' . esc_html($banner->post_title) . '</h3>';
            $output .= '<div class="luvee-banner-description">' . wp_kses_post($banner->post_content) . '</div>';
            $output .= '</div>';
        }

        if ($link_url) {
            $output .= '</a>';
        }

        $output .= '</div>';

        return $output;
    }

    /**
     * Renderiza um carrossel
     */
    private function renderCarousel($carousel)
    {
        $carousel_items = get_post_meta($carousel->ID, '_luvee_carousel_items', true);

        if (empty($carousel_items)) {
            return '';
        }

        // Configurações do carrossel
        $autoplay = get_post_meta($carousel->ID, '_luvee_carousel_autoplay', true);
        $autoplay_speed = get_post_meta($carousel->ID, '_luvee_carousel_autoplay_speed', true) ?: 3000;
        $show_arrows = get_post_meta($carousel->ID, '_luvee_carousel_show_arrows', true);
        $show_dots = get_post_meta($carousel->ID, '_luvee_carousel_show_dots', true);
        $infinite = get_post_meta($carousel->ID, '_luvee_carousel_infinite', true);

        $carousel_id = 'luvee-carousel-' . $carousel->ID;

        $output = '<div class="luvee-carousel-container" data-carousel-id="' . $carousel->ID . '">';
        $output .= '<div id="' . $carousel_id . '" class="luvee-carousel" ';
        $output .= 'data-autoplay="' . ($autoplay ? 'true' : 'false') . '" ';
        $output .= 'data-autoplay-speed="' . esc_attr($autoplay_speed) . '" ';
        $output .= 'data-show-arrows="' . ($show_arrows ? 'true' : 'false') . '" ';
        $output .= 'data-show-dots="' . ($show_dots ? 'true' : 'false') . '" ';
        $output .= 'data-infinite="' . ($infinite ? 'true' : 'false') . '">';

        foreach ($carousel_items as $index => $item) {
            $output .= '<div class="luvee-carousel-item">';

            if ($item['link_url']) {
                $target = isset($item['link_target']) ? $item['link_target'] : '_self';
                $output .= '<a href="' . esc_url($item['link_url']) . '" target="' . esc_attr($target) . '" onclick="luveeTrackBannerClick(' . $carousel->ID . ', \'carousel\')">';
            }

            if ($item['image_url']) {
                $output .= '<img src="' . esc_url($item['image_url']) . '" alt="' . esc_attr($item['title']) . '" class="luvee-carousel-image" />';
            }

            if ($item['title'] || $item['description']) {
                $output .= '<div class="luvee-carousel-content">';
                if ($item['title']) {
                    $output .= '<h3 class="luvee-carousel-title">' . esc_html($item['title']) . '</h3>';
                }
                if ($item['description']) {
                    $output .= '<p class="luvee-carousel-description">' . esc_html($item['description']) . '</p>';
                }
                $output .= '</div>';
            }

            if ($item['link_url']) {
                $output .= '</a>';
            }

            $output .= '</div>';
        }

        $output .= '</div>';
        $output .= '</div>';

        return $output;
    }

    /**
     * Renderiza um único hero banner
     */
    private function renderSingleHero($hero)
    {
        $image_url = get_post_meta($hero->ID, '_luvee_hero_image_url', true);
        $image_alt = get_post_meta($hero->ID, '_luvee_hero_image_alt', true);
        $link_url = get_post_meta($hero->ID, '_luvee_hero_link_url', true);
        $link_target = get_post_meta($hero->ID, '_luvee_hero_link_target', true) ?: '_self';

        if (empty($image_url)) {
            return '';
        }

        $output = '<div class="luvee-hero-container">';
        $output .= '<div class="container">';

        if (!empty($link_url)) {
            $target_attr = ($link_target === '_blank') ? ' target="_blank" rel="noopener"' : '';
            $output .= '<a href="' . esc_url($link_url) . '"' . $target_attr . ' class="luvee-hero-banner-link" onclick="luveeTrackBannerClick(' . $hero->ID . ', \'hero\')">';
        }

        $output .= '<div class="luvee-hero-banner">';
        $output .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" class="luvee-hero-image" />';

        if (!empty($hero->post_title) || !empty($hero->post_content)) {
            $output .= '<div class="luvee-hero-overlay">';
            $output .= '<div class="luvee-hero-content">';
            if (!empty($hero->post_title)) {
                $output .= '<h2 class="luvee-hero-title">' . esc_html($hero->post_title) . '</h2>';
            }
            if (!empty($hero->post_content)) {
                $output .= '<div class="luvee-hero-description">' . wp_kses_post($hero->post_content) . '</div>';
            }
            $output .= '</div>';
            $output .= '</div>';
        }

        $output .= '</div>';

        if (!empty($link_url)) {
            $output .= '</a>';
        }

        $output .= '</div>';
        $output .= '</div>';

        return $output;
    }

    /**
     * Renderiza carrossel de hero banners
     */
    private function renderHeroCarousel($banners, $atts)
    {
        $carousel_id = 'luvee-hero-carousel-' . uniqid();

        $output = '<div class="luvee-hero-container">';
        $output .= '<div class="container">';
        $output .= '<div class="luvee-hero-carousel-wrapper">';
        $output .= '<div id="' . $carousel_id . '" class="luvee-hero-carousel" 
            data-autoplay="' . esc_attr($atts['autoplay']) . '" 
            data-autoplay-speed="' . esc_attr($atts['autoplay_speed']) . '"
            data-show-arrows="' . esc_attr($atts['show_arrows']) . '"
            data-show-dots="' . esc_attr($atts['show_dots']) . '">';

        foreach ($banners as $index => $banner) {
            $image_url = get_post_meta($banner->ID, '_luvee_hero_image_url', true);
            $image_alt = get_post_meta($banner->ID, '_luvee_hero_image_alt', true);
            $link_url = get_post_meta($banner->ID, '_luvee_hero_link_url', true);
            $link_target = get_post_meta($banner->ID, '_luvee_hero_link_target', true) ?: '_self';

            if (empty($image_url))
                continue;

            $active_class = ($index === 0) ? ' active' : '';

            $output .= '<div class="luvee-hero-slide' . $active_class . '">';

            if (!empty($link_url)) {
                $target_attr = ($link_target === '_blank') ? ' target="_blank" rel="noopener"' : '';
                $output .= '<a href="' . esc_url($link_url) . '"' . $target_attr . ' class="luvee-hero-banner-link" onclick="luveeTrackBannerClick(' . $banner->ID . ', \'hero\')">';
            }

            $output .= '<div class="luvee-hero-banner">';
            $output .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" class="luvee-hero-image" />';

            if (!empty($banner->post_title) || !empty($banner->post_content)) {
                $output .= '<div class="luvee-hero-overlay">';
                $output .= '<div class="luvee-hero-content">';
                if (!empty($banner->post_title)) {
                    $output .= '<h2 class="luvee-hero-title">' . esc_html($banner->post_title) . '</h2>';
                }
                if (!empty($banner->post_content)) {
                    $output .= '<div class="luvee-hero-description">' . wp_kses_post($banner->post_content) . '</div>';
                }
                $output .= '</div>';
                $output .= '</div>';
            }

            $output .= '</div>';

            if (!empty($link_url)) {
                $output .= '</a>';
            }

            $output .= '</div>';

            // Incrementa visualizações
            $this->incrementBannerViews($banner->ID, 'hero');
        }

        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';

        return $output;
    }

    /**
     * Incrementa visualizações
     */
    private function incrementBannerViews($banner_id, $banner_type)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'luvee_banner_stats';
        $today = date('Y-m-d');

        // Verifica se já existe registro para hoje
        $existing = $wpdb->get_row($wpdb->prepare(
            "SELECT id, views FROM $table_name WHERE banner_id = %d AND banner_type = %s AND DATE(date_created) = %s",
            $banner_id,
            $banner_type,
            $today
        ));

        if ($existing) {
            // Atualiza views
            $wpdb->update(
                $table_name,
                array('views' => $existing->views + 1),
                array('id' => $existing->id)
            );
        } else {
            // Insere novo registro
            $wpdb->insert(
                $table_name,
                array(
                    'banner_id' => $banner_id,
                    'banner_type' => $banner_type,
                    'views' => 1,
                    'clicks' => 0,
                    'date_created' => current_time('mysql')
                )
            );
        }
    }

    /**
     * Incrementa cliques (via AJAX)
     */
    public static function trackBannerClick()
    {
        if (!wp_verify_nonce($_POST['nonce'], 'luvee_site_frontend_nonce')) {
            wp_die('Security check failed');
        }

        $banner_id = intval($_POST['banner_id']);
        $banner_type = sanitize_text_field($_POST['banner_type']);

        if (!$banner_id || !$banner_type) {
            wp_die('Invalid data');
        }

        global $wpdb;

        $table_name = $wpdb->prefix . 'luvee_banner_stats';
        $today = date('Y-m-d');

        // Verifica se já existe registro para hoje
        $existing = $wpdb->get_row($wpdb->prepare(
            "SELECT id, clicks FROM $table_name WHERE banner_id = %d AND banner_type = %s AND DATE(date_created) = %s",
            $banner_id,
            $banner_type,
            $today
        ));

        if ($existing) {
            // Atualiza clicks
            $wpdb->update(
                $table_name,
                array('clicks' => $existing->clicks + 1),
                array('id' => $existing->id)
            );
        } else {
            // Insere novo registro
            $wpdb->insert(
                $table_name,
                array(
                    'banner_id' => $banner_id,
                    'banner_type' => $banner_type,
                    'views' => 0,
                    'clicks' => 1,
                    'date_created' => current_time('mysql')
                )
            );
        }

        wp_die('success');
    }
}

// Registra AJAX para tracking de cliques
add_action('wp_ajax_luvee_track_banner_click', array('Luvee_Site_Shortcodes', 'trackBannerClick'));
add_action('wp_ajax_nopriv_luvee_track_banner_click', array('Luvee_Site_Shortcodes', 'trackBannerClick'));
