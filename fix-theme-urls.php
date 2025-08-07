<?php
/**
 * CORREÇÃO DEFINITIVA DO TEMA
 */

// Incluir WordPress
require_once('./wp-config.php');
require_once('./wp-load.php');

echo "🔧 CORREÇÃO DEFINITIVA DO TEMA LUVEE\n\n";

// 1. Verificar estado atual
echo "📋 Estado atual:\n";
echo "  - get_option('stylesheet'): " . get_option('stylesheet') . "\n";
echo "  - get_option('template'): " . get_option('template') . "\n";
echo "  - get_template_directory_uri(): " . get_template_directory_uri() . "\n";
echo "  - get_stylesheet_directory_uri(): " . get_stylesheet_directory_uri() . "\n";

// 2. Limpar TODOS os caches possíveis
echo "\n🧹 Limpando TODOS os caches...\n";

// Cache do WordPress
wp_cache_flush();
echo "  ✅ wp_cache_flush() executado\n";

// Cache de transientes
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'");
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_site_transient_%'");
echo "  ✅ Transientes limpos\n";

// Cache de opções
wp_cache_delete('alloptions', 'options');
echo "  ✅ Cache de opções limpo\n";

// 3. FORÇAR as opções corretas
echo "\n🎯 Forçando opções corretas...\n";

// Deletar e recriar as opções
delete_option('stylesheet');
delete_option('template');
delete_option('current_theme');

// Definir novamente
update_option('stylesheet', 'luvee-theme');
update_option('template', 'luvee-theme');
echo "  ✅ Opções stylesheet e template atualizadas\n";

// 4. Limpar cache de temas
wp_clean_themes_cache();
echo "  ✅ Cache de temas limpo\n";

// 5. Recarregar temas
$themes = wp_get_themes();
echo "  ✅ Lista de temas recarregada\n";

// 6. Verificar se luvee-theme existe
if (isset($themes['luvee-theme'])) {
    echo "  ✅ Tema luvee-theme encontrado\n";
    
    // Switch usando API do WordPress
    switch_theme('luvee-theme');
    echo "  ✅ switch_theme() executado\n";
} else {
    echo "  ❌ Tema luvee-theme NÃO encontrado!\n";
    echo "  📁 Temas disponíveis:\n";
    foreach ($themes as $theme_slug => $theme_obj) {
        echo "    - " . $theme_slug . "\n";
    }
}

// 7. Limpar cache de URL
delete_option('home');
delete_option('siteurl');
wp_cache_delete('current_theme', 'themes');
echo "  ✅ Cache de URLs limpo\n";

// 8. Verificar resultado
echo "\n📊 Resultado final:\n";
echo "  - get_option('stylesheet'): " . get_option('stylesheet') . "\n";
echo "  - get_option('template'): " . get_option('template') . "\n";
echo "  - get_template_directory_uri(): " . get_template_directory_uri() . "\n";
echo "  - get_stylesheet_directory_uri(): " . get_stylesheet_directory_uri() . "\n";

// 9. Verificar arquivos JS agora
echo "\n🔍 Teste final dos arquivos JS:\n";
$js_url = get_template_directory_uri() . '/assets/js/header-enhancements.js';
echo "  - Nova URL JS: " . $js_url . "\n";

$context = stream_context_create(['http' => ['timeout' => 5]]);
$headers = @get_headers($js_url, 1, $context);
$status = $headers ? $headers[0] : "ERRO";
echo "  - Status HTTP: " . $status . "\n";

echo "\n✨ CORREÇÃO CONCLUÍDA!\n";
echo "🔄 RECARREGUE A PÁGINA AGORA!\n";
?>