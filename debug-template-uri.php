<?php
/**
 * Debug da função get_template_directory_uri()
 */

// Incluir WordPress
require_once('./wp-config.php');
require_once('./wp-load.php');

echo "🔧 Debug das funções de template URI\n\n";

// Verificar todas as funções relacionadas
echo "📋 Informações do tema:\n";
echo "  - get_option('stylesheet'): " . get_option('stylesheet') . "\n";
echo "  - get_option('template'): " . get_option('template') . "\n";
echo "  - get_stylesheet(): " . get_stylesheet() . "\n";
echo "  - get_template(): " . get_template() . "\n";

echo "\n🌐 URLs e caminhos:\n";
echo "  - get_template_directory_uri(): " . get_template_directory_uri() . "\n";
echo "  - get_stylesheet_directory_uri(): " . get_stylesheet_directory_uri() . "\n";
echo "  - get_template_directory(): " . get_template_directory() . "\n";
echo "  - get_stylesheet_directory(): " . get_stylesheet_directory() . "\n";

echo "\n🏠 WordPress URLs:\n";
echo "  - home_url(): " . home_url() . "\n";
echo "  - site_url(): " . site_url() . "\n";
echo "  - wp_upload_dir()['baseurl']: " . wp_upload_dir()['baseurl'] . "\n";

echo "\n🔍 Arquivo JS esperado:\n";
$js_url = get_template_directory_uri() . '/assets/js/header-enhancements.js';
echo "  - URL: " . $js_url . "\n";

// Testar se o arquivo existe fisicamente
$js_path = get_template_directory() . '/assets/js/header-enhancements.js';
echo "  - Caminho físico: " . $js_path . "\n";
echo "  - Arquivo existe: " . (file_exists($js_path) ? "✅ SIM" : "❌ NÃO") . "\n";

if (file_exists($js_path)) {
    echo "  - Tamanho: " . filesize($js_path) . " bytes\n";
    echo "  - Modificado: " . date('Y-m-d H:i:s', filemtime($js_path)) . "\n";
}

// Testar URL com cURL
echo "\n🌐 Teste de acesso HTTP:\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $js_url);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$result = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "  - Status HTTP: " . $http_code . "\n";
if ($result) {
    $lines = explode("\n", $result);
    echo "  - Primeira linha: " . trim($lines[0]) . "\n";
}

// Verificar se há .htaccess interferindo
echo "\n📄 Verificações adicionais:\n";
$htaccess_path = ABSPATH . '.htaccess';
echo "  - .htaccess existe: " . (file_exists($htaccess_path) ? "SIM" : "NÃO") . "\n";

if (file_exists($htaccess_path)) {
    $htaccess_content = file_get_contents($htaccess_path);
    if (strpos($htaccess_content, 'js') !== false) {
        echo "  - .htaccess contém regras para JS: SIM\n";
    }
}

// Verificar permissões
echo "  - Permissões do diretório do tema: " . substr(sprintf('%o', fileperms(get_template_directory())), -4) . "\n";
if (file_exists($js_path)) {
    echo "  - Permissões do arquivo JS: " . substr(sprintf('%o', fileperms($js_path)), -4) . "\n";
}

echo "\n✨ Debug concluído!\n";
?>