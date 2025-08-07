<?php
/**
 * Debug do sistema de enqueue de scripts
 */

// Incluir WordPress
require_once('./wp-config.php');
require_once('./wp-load.php');

echo "🔧 Debug do sistema de enqueue\n\n";

// Simular o processo de enqueue
do_action('wp_enqueue_scripts');

// Verificar scripts globais
global $wp_scripts;

echo "📋 Scripts registrados relacionados ao Luvee:\n";
if ($wp_scripts && $wp_scripts->registered) {
    foreach ($wp_scripts->registered as $handle => $script) {
        if (strpos($handle, 'luvee') !== false || strpos($script->src, 'luvee') !== false) {
            echo "  ✅ Handle: " . $handle . "\n";
            echo "     📁 Src: " . $script->src . "\n";
            echo "     📦 Deps: " . implode(', ', $script->deps) . "\n";
            echo "     🔢 Ver: " . $script->ver . "\n";
            echo "     📍 Footer: " . ($script->extra['group'] ?? 'header') . "\n";
            echo "\n";
        }
    }
}

echo "📋 Scripts enfileirados relacionados ao Luvee:\n";
if ($wp_scripts && $wp_scripts->queue) {
    foreach ($wp_scripts->queue as $handle) {
        if (strpos($handle, 'luvee') !== false) {
            echo "  🎯 " . $handle . "\n";
        }
    }
    
    if (empty(array_filter($wp_scripts->queue, function($h) { return strpos($h, 'luvee') !== false; }))) {
        echo "  ❌ Nenhum script Luvee na fila!\n";
    }
} else {
    echo "  ❌ Fila de scripts vazia!\n";
}

// Verificar dependências
echo "\n🔗 Verificando dependências:\n";
$deps_to_check = ['jquery', 'bootstrap-bundle'];
foreach ($deps_to_check as $dep) {
    if ($wp_scripts && isset($wp_scripts->registered[$dep])) {
        echo "  ✅ " . $dep . " está registrado\n";
        if (in_array($dep, $wp_scripts->queue)) {
            echo "     🎯 E está na fila\n";
        } else {
            echo "     ⚠️  Mas NÃO está na fila\n";
        }
    } else {
        echo "  ❌ " . $dep . " NÃO está registrado\n";
    }
}

// Verificar hooks
echo "\n🪝 Verificando hooks:\n";
$priority = has_action('wp_enqueue_scripts', 'luvee_enqueue_scripts');
if ($priority !== false) {
    echo "  ✅ Hook wp_enqueue_scripts está registrado (prioridade: $priority)\n";
} else {
    echo "  ❌ Hook wp_enqueue_scripts NÃO está registrado!\n";
}

// Verificar se a função existe
echo "\n🔧 Verificando função:\n";
if (function_exists('luvee_enqueue_scripts')) {
    echo "  ✅ Função luvee_enqueue_scripts existe\n";
    
    // Testar execução da função
    echo "  🧪 Testando execução da função...\n";
    ob_start();
    try {
        luvee_enqueue_scripts();
        echo "  ✅ Função executada sem erros\n";
    } catch (Exception $e) {
        echo "  ❌ Erro na execução: " . $e->getMessage() . "\n";
    }
    $output = ob_get_clean();
    if ($output) {
        echo "  📄 Output: " . $output . "\n";
    }
} else {
    echo "  ❌ Função luvee_enqueue_scripts NÃO existe!\n";
}

// Verificar tema ativo
echo "\n🎨 Verificando tema:\n";
$current_theme = wp_get_theme();
echo "  📝 Nome: " . $current_theme->get('Name') . "\n";
echo "  📁 Diretório: " . $current_theme->get_stylesheet() . "\n";
echo "  🔢 Versão: " . $current_theme->get('Version') . "\n";

// Verificar se o functions.php foi carregado
echo "\n📄 Verificando carregamento do functions.php:\n";
$functions_path = get_template_directory() . '/functions.php';
echo "  📁 Caminho: " . $functions_path . "\n";
echo "  ✅ Existe: " . (file_exists($functions_path) ? "SIM" : "NÃO") . "\n";

if (file_exists($functions_path)) {
    $content = file_get_contents($functions_path);
    if (strpos($content, 'luvee_enqueue_scripts') !== false) {
        echo "  ✅ Contém função luvee_enqueue_scripts\n";
    } else {
        echo "  ❌ NÃO contém função luvee_enqueue_scripts\n";
    }
    
    if (strpos($content, "add_action('wp_enqueue_scripts'") !== false) {
        echo "  ✅ Contém hook wp_enqueue_scripts\n";
    } else {
        echo "  ❌ NÃO contém hook wp_enqueue_scripts\n";
    }
}

echo "\n✨ Debug do enqueue concluído!\n";
?>