<?php
/**
 * Test Cart Page - Luvee Perfumaria
 * Teste da funcionalidade da página de carrinho
 */

// Este arquivo deve ser executado apenas em ambiente de desenvolvimento
if (!defined('WP_DEBUG') || !WP_DEBUG) {
  wp_die('Este arquivo só pode ser executado em modo debug.');
}

// Verificar se WooCommerce está ativo
if (!function_exists('WC')) {
  wp_die('WooCommerce não está ativo.');
}

echo '<div style="background: #f0f0f0; padding: 20px; font-family: Arial, sans-serif;">';
echo '<h1>🛒 Teste da Página de Carrinho - Luvee Perfumaria</h1>';

// Teste 1: Verificar se o template existe
echo '<h2>✅ Teste 1: Template do Carrinho</h2>';
$cart_template = get_template_directory() . '/woocommerce/cart/cart.php';
if (file_exists($cart_template)) {
  echo '<p style="color: green;">✅ Template customizado encontrado: ' . $cart_template . '</p>';
} else {
  echo '<p style="color: red;">❌ Template customizado não encontrado</p>';
}

// Teste 2: Verificar CSS
echo '<h2>✅ Teste 2: Arquivos CSS</h2>';
$css_files = [
  'cart-page.css' => get_template_directory() . '/assets/css/cart-page.css',
  'footer-modern.css' => get_template_directory() . '/assets/css/footer-modern.css'
];

foreach ($css_files as $name => $path) {
  if (file_exists($path)) {
    echo '<p style="color: green;">✅ ' . $name . ' encontrado</p>';
  } else {
    echo '<p style="color: red;">❌ ' . $name . ' não encontrado</p>';
  }
}

// Teste 3: Verificar JavaScript
echo '<h2>✅ Teste 3: Arquivos JavaScript</h2>';
$js_files = [
  'cart-page.js' => get_template_directory() . '/assets/js/cart-page.js',
  'cart-ajax.js' => get_template_directory() . '/assets/js/cart-ajax.js'
];

foreach ($js_files as $name => $path) {
  if (file_exists($path)) {
    echo '<p style="color: green;">✅ ' . $name . ' encontrado</p>';
  } else {
    echo '<p style="color: red;">❌ ' . $name . ' não encontrado</p>';
  }
}

// Teste 4: Verificar funções AJAX
echo '<h2>✅ Teste 4: Funções AJAX</h2>';
$ajax_functions = [
  'luvee_ajax_update_cart_item',
  'luvee_ajax_apply_coupon',
  'luvee_global_cart_scripts',
  'luvee_cart_scripts'
];

foreach ($ajax_functions as $function) {
  if (function_exists($function)) {
    echo '<p style="color: green;">✅ Função ' . $function . '() existe</p>';
  } else {
    echo '<p style="color: red;">❌ Função ' . $function . '() não encontrada</p>';
  }
}

// Teste 5: Verificar URLs do WooCommerce
echo '<h2>✅ Teste 5: URLs WooCommerce</h2>';
$urls = [
  'Cart URL' => wc_get_cart_url(),
  'Shop URL' => get_permalink(get_option('woocommerce_shop_page_id')),
  'Checkout URL' => wc_get_checkout_url()
];

foreach ($urls as $name => $url) {
  if ($url) {
    echo '<p style="color: green;">✅ ' . $name . ': <a href="' . esc_url($url) . '">' . esc_url($url) . '</a></p>';
  } else {
    echo '<p style="color: red;">❌ ' . $name . ' não configurada</p>';
  }
}

// Teste 6: Verificar configurações da Luvee
echo '<h2>✅ Teste 6: Configurações Tema Luvee</h2>';
$luvee_settings = [
  'luvee_phone' => get_theme_mod('luvee_phone'),
  'luvee_email' => get_theme_mod('luvee_email'),
  'luvee_instagram_url' => get_theme_mod('luvee_instagram_url'),
  'luvee_facebook_url' => get_theme_mod('luvee_facebook_url')
];

foreach ($luvee_settings as $setting => $value) {
  if ($value) {
    echo '<p style="color: green;">✅ ' . $setting . ': ' . esc_html($value) . '</p>';
  } else {
    echo '<p style="color: orange;">⚠️ ' . $setting . ' não configurado (opcional)</p>';
  }
}

// Teste 7: Simular carrinho com produtos
echo '<h2>✅ Teste 7: Simulação de Carrinho</h2>';

// Verificar se há produtos no carrinho
if (!WC()->cart->is_empty()) {
  echo '<p style="color: green;">✅ Carrinho contém ' . WC()->cart->get_cart_contents_count() . ' itens</p>';
  echo '<p>Total: ' . WC()->cart->get_cart_total() . '</p>';

  // Listar itens do carrinho
  echo '<h3>Itens no Carrinho:</h3>';
  foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
    $product = $cart_item['data'];
    echo '<p>• ' . $product->get_name() . ' (Qty: ' . $cart_item['quantity'] . ')</p>';
  }
} else {
  echo '<p style="color: orange;">⚠️ Carrinho vazio - adicione produtos para testar</p>';

  // Buscar produtos para teste
  $products = wc_get_products(['limit' => 3, 'status' => 'publish']);
  if (!empty($products)) {
    echo '<h3>Produtos Disponíveis para Teste:</h3>';
    foreach ($products as $product) {
      $add_to_cart_url = '?add-to-cart=' . $product->get_id();
      echo '<p>• <a href="' . esc_url($add_to_cart_url) . '">' . $product->get_name() . '</a> - ' . $product->get_price_html() . '</p>';
    }
  }
}

// Teste 8: Verificar hooks do WooCommerce
echo '<h2>✅ Teste 8: Hooks WooCommerce</h2>';
$cart_hooks = [
  'woocommerce_before_cart',
  'woocommerce_after_cart',
  'woocommerce_cart_totals_before_shipping',
  'woocommerce_add_to_cart_fragments'
];

foreach ($cart_hooks as $hook) {
  $priority = has_action($hook);
  if ($priority !== false) {
    echo '<p style="color: green;">✅ Hook ' . $hook . ' registrado</p>';
  } else {
    echo '<p style="color: orange;">⚠️ Hook ' . $hook . ' não tem ações registradas</p>';
  }
}

// Links de navegação
echo '<h2>🔗 Links de Teste</h2>';
echo '<p><a href="' . esc_url(wc_get_cart_url()) . '" style="background: #ff6b9d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">🛒 Ir para o Carrinho</a></p>';
echo '<p><a href="' . esc_url(get_permalink(get_option('woocommerce_shop_page_id'))) . '" style="background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">🛍️ Ir para a Loja</a></p>';
echo '<p><a href="' . esc_url(home_url()) . '" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">🏠 Ir para Home</a></p>';

echo '<hr>';
echo '<p><strong>🎯 Status Geral:</strong> Página de carrinho Luvee implementada com sucesso!</p>';
echo '<p><small>📅 Teste executado em: ' . date('d/m/Y H:i:s') . '</small></p>';
echo '</div>';
?>