<?php
/**
 * Plugin Name: Luvee Site
 * Plugin URI: https://luvee.com.br
 * Description: Plugin para gerenciar banners, carrosséis, hero banners e produtos featured do site Luvee
 * Version: 1.0.0
 * Author: Luvee Team
 * Text Domain: luvee-site
 * Domain Path: /languages
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Define constantes do plugin
define('LUVEE_SITE_VERSION', '1.0.0');
define('LUVEE_SITE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('LUVEE_SITE_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Classe principal do plugin Luvee Site
 */
class LuveeSite
{

    /**
     * Instância única da classe
     */
    private static $instance = null;

    /**
     * Retorna a instância única da classe
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Construtor privado para implementar singleton
     */
    private function __construct()
    {
        $this->init();
    }

    /**
     * Inicializa o plugin
     */
    private function init()
    {
        // Hook de ativação
        register_activation_hook(__FILE__, array($this, 'activate'));

        // Hook de desativação
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));

        // Carrega arquivos necessários
        $this->loadFiles();

        // Inicializa hooks
        add_action('init', array($this, 'initPlugin'));
        add_action('admin_menu', array($this, 'addAdminMenu'));
        add_action('admin_enqueue_scripts', array($this, 'adminScripts'));
        add_action('wp_enqueue_scripts', array($this, 'frontendScripts'));

        // Registra post types personalizados
        add_action('init', array($this, 'registerPostTypes'));

        // Adiciona shortcodes
        add_action('init', array($this, 'registerShortcodes'));
    }

    /**
     * Carrega arquivos necessários
     */
    private function loadFiles()
    {
        require_once LUVEE_SITE_PLUGIN_DIR . 'includes/class-post-types.php';
        require_once LUVEE_SITE_PLUGIN_DIR . 'includes/class-shortcodes.php';
        require_once LUVEE_SITE_PLUGIN_DIR . 'includes/class-featured-products.php';
        require_once LUVEE_SITE_PLUGIN_DIR . 'admin/class-admin.php';
    }

    /**
     * Inicializa o plugin
     */
    public function initPlugin()
    {
        load_plugin_textdomain('luvee-site', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    /**
     * Ativação do plugin
     */
    public function activate()
    {
        // Cria tabelas necessárias
        $this->createTables();

        // Registra post types
        $this->registerPostTypes();

        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Desativação do plugin
     */
    public function deactivate()
    {
        flush_rewrite_rules();
    }

    /**
     * Cria tabelas necessárias no banco de dados
     */
    private function createTables()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        // Tabela para estatísticas de banners
        $table_name = $wpdb->prefix . 'luvee_banner_stats';

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
    }

    /**
     * Registra post types personalizados
     */
    public function registerPostTypes()
    {
        $postTypes = new Luvee_Site_Post_Types();
        $postTypes->register();
    }

    /**
     * Registra shortcodes
     */
    public function registerShortcodes()
    {
        $shortcodes = new Luvee_Site_Shortcodes();
        $shortcodes->register();
    }

    /**
     * Adiciona menu no painel administrativo
     */
    public function addAdminMenu()
    {
        add_menu_page(
            __('Luvee Site', 'luvee-site'),
            __('Luvee Site', 'luvee-site'),
            'manage_options',
            'luvee-site',
            array($this, 'adminPage'),
            'dashicons-images-alt2',
            30
        );

        add_submenu_page(
            'luvee-site',
            __('Banners', 'luvee-site'),
            __('Banners', 'luvee-site'),
            'manage_options',
            'edit.php?post_type=luvee_banner'
        );

        add_submenu_page(
            'luvee-site',
            __('Carrosséis', 'luvee-site'),
            __('Carrosséis', 'luvee-site'),
            'manage_options',
            'edit.php?post_type=luvee_carousel'
        );

        add_submenu_page(
            'luvee-site',
            __('Hero Banners', 'luvee-site'),
            __('Hero Banners', 'luvee-site'),
            'manage_options',
            'edit.php?post_type=luvee_hero'
        );

        add_submenu_page(
            'luvee-site',
            __('Configurações', 'luvee-site'),
            __('Configurações', 'luvee-site'),
            'manage_options',
            'luvee-site-settings',
            array($this, 'settingsPage')
        );
    }

    /**
     * Página principal do admin
     */
    public function adminPage()
    {
        include LUVEE_SITE_PLUGIN_DIR . 'admin/views/main-page.php';
    }

    /**
     * Página de configurações
     */
    public function settingsPage()
    {
        include LUVEE_SITE_PLUGIN_DIR . 'admin/views/settings-page.php';
    }

    /**
     * Carrega scripts do admin
     */
    public function adminScripts($hook)
    {
        // Carrega apenas nas páginas do plugin
        if (
            strpos($hook, 'luvee-site') !== false ||
            (isset($_GET['post_type']) && strpos($_GET['post_type'], 'luvee_') === 0)
        ) {

            wp_enqueue_style(
                'luvee-site-admin',
                LUVEE_SITE_PLUGIN_URL . 'assets/css/admin.css',
                array(),
                LUVEE_SITE_VERSION
            );

            wp_enqueue_script(
                'luvee-site-admin',
                LUVEE_SITE_PLUGIN_URL . 'assets/js/admin.js',
                array('jquery'),
                LUVEE_SITE_VERSION,
                true
            );

            // Localização para AJAX
            wp_localize_script('luvee-site-admin', 'luvee_site_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('luvee_site_nonce')
            ));
        }
    }

    /**
     * Carrega scripts do frontend
     */
    public function frontendScripts()
    {
        wp_enqueue_style(
            'luvee-site-frontend',
            LUVEE_SITE_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            LUVEE_SITE_VERSION
        );

        wp_enqueue_script(
            'luvee-site-frontend',
            LUVEE_SITE_PLUGIN_URL . 'assets/js/frontend.js',
            array('jquery'),
            LUVEE_SITE_VERSION,
            true
        );

        // Localização para AJAX frontend
        wp_localize_script('luvee-site-frontend', 'luvee_site_frontend', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('luvee_site_frontend_nonce')
        ));
    }
}

// Inicializa o plugin
LuveeSite::getInstance();
