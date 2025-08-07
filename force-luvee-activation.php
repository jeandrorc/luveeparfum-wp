<?php
/**
 * Script para forçar ativação completa do tema Luvee
 */

// Incluir WordPress
require_once('./wp-config.php');
require_once('./wp-load.php');

echo "🔧 Forçando ativação completa do tema Luvee...\n";

// Verificar tema atual
$current_theme = get_option('stylesheet');
$current_template = get_option('template');
echo "📋 Tema atual (stylesheet): " . $current_theme . "\n";
echo "📋 Template atual: " . $current_template . "\n";

// Forçar ativação do tema Luvee
update_option('stylesheet', 'luvee-theme');
update_option('template', 'luvee-theme');

// Limpar todas as opções de cache
delete_option('stylesheet');
delete_option('template');
update_option('stylesheet', 'luvee-theme');
update_option('template', 'luvee-theme');

// Verificar novamente
$new_theme = get_option('stylesheet');
$new_template = get_option('template');
echo "✅ Novo tema (stylesheet): " . $new_theme . "\n";
echo "✅ Novo template: " . $new_template . "\n";

// Limpar cache do WordPress
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
    echo "🗑️ Cache WP limpo\n";
}

// Limpar cache de objetos
if (function_exists('wp_cache_delete')) {
    wp_cache_delete('alloptions', 'options');
    echo "🗑️ Cache de opções limpo\n";
}

// Verificar se arquivos existem
$theme_path = get_theme_root() . '/luvee-theme';
$js_path = $theme_path . '/assets/js';
$header_js = $js_path . '/header-enhancements.js';
$heroicons_js = $js_path . '/heroicons-simple.js';

echo "📁 Caminho do tema: " . $theme_path . "\n";
echo "📁 Caminho JS: " . $js_path . "\n";
echo "📄 header-enhancements.js existe: " . (file_exists($header_js) ? "✅ SIM" : "❌ NÃO") . "\n";
echo "📄 heroicons-simple.js existe: " . (file_exists($heroicons_js) ? "✅ SIM" : "❌ NÃO") . "\n";

// Mostrar URL que deveria ser carregada
$theme_uri = get_template_directory_uri();
echo "🌐 URL do tema: " . $theme_uri . "\n";
echo "🌐 URL JS esperada: " . $theme_uri . "/assets/js/header-enhancements.js\n";

echo "🎉 Processo concluído! Recarregue a página com Ctrl+F5.\n";
?>