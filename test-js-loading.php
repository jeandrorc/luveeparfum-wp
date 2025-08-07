<?php
/**
 * Script para debugar carregamento de JS
 */

// Incluir WordPress
require_once('./wp-config.php');
require_once('./wp-load.php');

echo "🔧 Debug do carregamento de JavaScript\n";

// Verificar tema ativo
$theme = get_option('stylesheet');
echo "📋 Tema ativo: " . $theme . "\n";

// Verificar URLs
$theme_uri = get_template_directory_uri();
$theme_path = get_template_directory();

echo "🌐 URL do tema: " . $theme_uri . "\n";
echo "📁 Caminho do tema: " . $theme_path . "\n";

// Verificar arquivos
$js_files = [
    'header-enhancements.js',
    'heroicons-simple.js',
    'theme.js'
];

foreach ($js_files as $file) {
    $file_path = $theme_path . '/assets/js/' . $file;
    $file_url = $theme_uri . '/assets/js/' . $file;
    
    echo "\n📄 Arquivo: " . $file . "\n";
    echo "   📁 Caminho: " . $file_path . "\n";
    echo "   🌐 URL: " . $file_url . "\n";
    echo "   ✅ Existe: " . (file_exists($file_path) ? "SIM" : "NÃO") . "\n";
    echo "   📊 Tamanho: " . (file_exists($file_path) ? filesize($file_path) . " bytes" : "N/A") . "\n";
    
    // Testar URL
    $context = stream_context_create(['http' => ['timeout' => 5]]);
    $headers = @get_headers($file_url, 1, $context);
    $status = $headers ? $headers[0] : "ERRO";
    echo "   🌐 Status HTTP: " . $status . "\n";
}

// Listar scripts enfileirados
echo "\n🎯 Scripts enfileirados:\n";
global $wp_scripts;
if ($wp_scripts) {
    foreach ($wp_scripts->registered as $handle => $script) {
        if (strpos($script->src, 'luvee') !== false) {
            echo "   - " . $handle . ": " . $script->src . "\n";
        }
    }
}
?>