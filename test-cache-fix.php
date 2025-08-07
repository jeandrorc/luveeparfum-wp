<?php
/**
 * Teste rápido para verificar se o cache.php está funcionando
 */

echo "=== TESTE DE CACHE.PHP ===\n";

// Verificar se o arquivo existe
$cache_file = __DIR__ . '/wp-includes/cache.php';
if (file_exists($cache_file)) {
    echo "✅ cache.php encontrado: $cache_file\n";
    
    // Verificar tamanho
    $size = filesize($cache_file);
    echo "📏 Tamanho: " . number_format($size) . " bytes\n";
    
    // Verificar se é legível
    if (is_readable($cache_file)) {
        echo "✅ Arquivo é legível\n";
        
        // Tentar incluir o arquivo
        try {
            require_once $cache_file;
            echo "✅ cache.php carregado com sucesso\n";
            
            // Verificar se as funções de cache estão disponíveis
            if (function_exists('wp_cache_get')) {
                echo "✅ Função wp_cache_get disponível\n";
            } else {
                echo "⚠️ Função wp_cache_get não encontrada\n";
            }
            
            if (function_exists('wp_cache_set')) {
                echo "✅ Função wp_cache_set disponível\n";
            } else {
                echo "⚠️ Função wp_cache_set não encontrada\n";
            }
            
        } catch (Exception $e) {
            echo "❌ Erro ao carregar cache.php: " . $e->getMessage() . "\n";
        }
    } else {
        echo "❌ Arquivo não é legível\n";
    }
} else {
    echo "❌ cache.php não encontrado em: $cache_file\n";
}

echo "\n=== VERIFICAÇÃO DO SISTEMA ===\n";
echo "🕐 Timestamp: " . date('Y-m-d H:i:s') . "\n";
echo "🖥️ PHP Version: " . PHP_VERSION . "\n";
echo "📁 Diretório atual: " . __DIR__ . "\n";

echo "\n=== TESTE CONCLUÍDO ===\n";
