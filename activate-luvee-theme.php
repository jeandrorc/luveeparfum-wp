<?php
/**
 * Script para ativar o tema Luvee
 */

// Incluir WordPress
require_once('./wp-config.php');
require_once('./wp-load.php');

echo "🔧 Ativando tema Luvee...\n";

// Verificar tema atual
$current_theme = get_option('stylesheet');
echo "📋 Tema atual: " . $current_theme . "\n";

// Verificar se o tema Luvee existe
$luvee_theme_path = get_theme_root() . '/luvee-theme';
if (!is_dir($luvee_theme_path)) {
    echo "❌ Tema Luvee não encontrado em: " . $luvee_theme_path . "\n";
    exit(1);
}

echo "✅ Tema Luvee encontrado em: " . $luvee_theme_path . "\n";

// Ativar o tema Luvee
$result1 = update_option('stylesheet', 'luvee-theme');
$result2 = update_option('template', 'luvee-theme');

if ($result1 || $result2 || get_option('stylesheet') === 'luvee-theme') {
    echo "✅ Tema Luvee ativado com sucesso!\n";
    
    // Verificar novamente
    $new_theme = get_option('stylesheet');
    echo "📋 Novo tema ativo: " . $new_theme . "\n";
    
    // Limpar cache se necessário
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
        echo "🗑️ Cache limpo\n";
    }
    
} else {
    echo "❌ Erro ao ativar tema Luvee\n";
    exit(1);
}

echo "🎉 Processo concluído! Recarregue a página.\n";
?>