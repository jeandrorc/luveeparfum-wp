<?php
/**
 * Teste de Formatação de Preços
 * Para verificar se o problema do HTML no mini-cart foi corrigido
 */

// Simular alguns valores problemáticos
$test_prices = [
  '<span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">&#82;&#36;</span>&nbsp;399,90</bdi></span>',
  'R$ 199,90',
  '299,90',
  '<span class="amount">R$ 159,90</span>',
  '<bdi>R$ 259,90</bdi>',
  '',
  null
];

echo "<h2>🧪 Teste de Formatação de Preços</h2>\n";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
echo "<tr><th>Entrada</th><th>Saída Limpa</th><th>Status</th></tr>\n";

foreach ($test_prices as $price) {
  $cleaned = luvee_format_price_clean($price);
  $status = (strpos($cleaned, '<') === false && strpos($cleaned, '&') === false) ? '✅ OK' : '❌ ERRO';

  echo "<tr>\n";
  echo "<td>" . htmlspecialchars($price ?: 'NULL') . "</td>\n";
  echo "<td>" . htmlspecialchars($cleaned) . "</td>\n";
  echo "<td>" . $status . "</td>\n";
  echo "</tr>\n";
}

echo "</table>\n";

// Teste com WooCommerce se disponível
if (function_exists('WC') && WC()->cart) {
  echo "<h3>📊 Teste com WooCommerce Ativo</h3>\n";
  echo "<p><strong>Total do Carrinho:</strong> " . luvee_get_cart_total_clean() . "</p>\n";
  echo "<p><strong>Contagem de Itens:</strong> " . luvee_get_cart_count() . "</p>\n";
  echo "<p><strong>Carrinho tem itens:</strong> " . (luvee_cart_has_items() ? 'Sim' : 'Não') . "</p>\n";
} else {
  echo "<p>⚠️ WooCommerce não está ativo ou carrinho indisponível</p>\n";
}

echo "<h3>✅ Resultado do Teste</h3>\n";
echo "<p>Se todos os valores da coluna 'Saída Limpa' estão sem HTML/entidades, o problema foi corrigido!</p>\n";
?>