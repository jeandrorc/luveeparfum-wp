<?php
/**
 * Teste Rápido de Correção
 * Para verificar se o problema do zero foi resolvido
 */

require_once dirname(__FILE__) . '/inc/cart-helpers.php';

echo "<h2>🧪 Teste Rápido de Formatação</h2>";

// Simulações de valores que podem vir do WooCommerce
$test_cases = [
  '<span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">R$</span>&nbsp;199,90</bdi></span>',
  '<span class="amount">R$ 259,90</span>',
  'R$ 399,90',
  '19990', // centavos
  '0',
  '',
  null,
  false
];

echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>Entrada</th><th>Saída</th><th>Status</th></tr>";

foreach ($test_cases as $test) {
  $result = luvee_format_price_clean($test);
  $status = ($result === 'R$ 0,00') ? '⚠️ Zero' : '✅ OK';

  echo "<tr>";
  echo "<td>" . htmlspecialchars(var_export($test, true)) . "</td>";
  echo "<td><strong>" . htmlspecialchars($result) . "</strong></td>";
  echo "<td>" . $status . "</td>";
  echo "</tr>";
}

echo "</table>";

echo "<h3>📋 Análise:</h3>";
echo "<p>Se há valores diferentes de 'R$ 0,00' para entradas válidas, a função está funcionando.</p>";

if (function_exists('WC') && WC()->cart) {
  echo "<h3>🛒 Teste Real do Carrinho:</h3>";
  $cart_total = WC()->cart->get_cart_total();
  echo "Total bruto do WooCommerce: " . htmlspecialchars($cart_total) . "<br>";
  echo "Total formatado: <strong>" . luvee_format_price_clean($cart_total) . "</strong><br>";
  echo "Contagem de itens: " . WC()->cart->get_cart_contents_count() . "<br>";
} else {
  echo "<p>⚠️ WooCommerce não disponível para teste real</p>";
}
?>