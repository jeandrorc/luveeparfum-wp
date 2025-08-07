<?php
/**
 * Debug do Carrinho WooCommerce
 * Arquivo temporário para verificar o estado do carrinho
 */

// Simular WordPress apenas para debug
if (!function_exists('WC')) {
  echo "❌ WooCommerce não está ativo ou não pode ser carregado<br>";
  exit;
}

echo "<h2>🔍 Debug do Carrinho WooCommerce</h2>";

// Verificar se WooCommerce está funcionando
echo "<h3>📊 Status WooCommerce:</h3>";
echo "WC() disponível: " . (function_exists('WC') ? '✅ Sim' : '❌ Não') . "<br>";
echo "WC()->cart disponível: " . (WC()->cart ? '✅ Sim' : '❌ Não') . "<br>";

if (!WC()->cart) {
  echo "❌ Carrinho não está disponível. Pode ser necessário inicializar a sessão.<br>";
  exit;
}

// Informações do carrinho
echo "<h3>🛒 Informações do Carrinho:</h3>";
echo "Contagem de itens: " . WC()->cart->get_cart_contents_count() . "<br>";
echo "Total bruto: " . WC()->cart->get_cart_total() . "<br>";
echo "Total limpo: " . luvee_format_price_clean(WC()->cart->get_cart_total()) . "<br>";
echo "Subtotal bruto: " . WC()->cart->get_cart_subtotal() . "<br>";
echo "Carrinho vazio: " . (WC()->cart->is_empty() ? 'Sim' : 'Não') . "<br>";

// Itens do carrinho
echo "<h3>📦 Itens no Carrinho:</h3>";
if (WC()->cart->is_empty()) {
  echo "Carrinho está vazio.<br>";
} else {
  foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
    $product = $cart_item['data'];
    echo "- " . $product->get_name() . " (Qty: " . $cart_item['quantity'] . ")<br>";
    echo "  Preço bruto: " . WC()->cart->get_product_price($product) . "<br>";
    echo "  Preço limpo: " . luvee_format_price_clean(WC()->cart->get_product_price($product)) . "<br>";
  }
}

// Teste da função de formatação
echo "<h3>🧪 Teste de Formatação:</h3>";
$test_values = [
  '<span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">R$</span>&nbsp;199,90</bdi></span>',
  'R$ 299,90',
  ''
];

foreach ($test_values as $test) {
  echo "Entrada: " . htmlspecialchars($test) . "<br>";
  echo "Saída: " . luvee_format_price_clean($test) . "<br><br>";
}

// Simular adição ao carrinho para teste
echo "<h3>🔧 Teste de Adição (se houver produtos):</h3>";
$products = wc_get_products(['limit' => 1]);
if (!empty($products)) {
  $product = $products[0];
  echo "Produto encontrado: " . $product->get_name() . "<br>";
  echo "ID: " . $product->get_id() . "<br>";
  echo "Preço: " . $product->get_price() . "<br>";

  // Tentar adicionar (apenas para debug)
  // WC()->cart->add_to_cart($product->get_id(), 1);
  echo "Para testar, descomente a linha de add_to_cart acima.<br>";
} else {
  echo "Nenhum produto encontrado para teste.<br>";
}

echo "<hr>";
echo "<small>Este arquivo deve ser removido após o debug.</small>";
?>