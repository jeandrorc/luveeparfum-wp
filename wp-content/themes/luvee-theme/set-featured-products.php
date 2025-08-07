<?php
/**
 * Script para marcar produtos como Featured
 * Exemplos de como usar programaticamente
 */

// Incluir WordPress
require_once('../../../wp-config.php');
require_once('../../../wp-load.php');

// Verificar se WooCommerce está ativo
if (!function_exists('WC')) {
  die('❌ WooCommerce não está ativo!');
}

echo "<h1>🎯 Gerenciar Produtos Featured</h1>";

/**
 * MÉTODO 1: Marcar produto como featured pelo ID
 */
function set_product_featured($product_id, $featured = true)
{
  $product = wc_get_product($product_id);

  if (!$product) {
    return false;
  }

  // Marcar como featured
  $product->set_featured($featured);
  $product->save();

  return true;
}

/**
 * MÉTODO 2: Marcar múltiplos produtos como featured
 */
function set_multiple_products_featured($product_ids)
{
  $results = [];

  foreach ($product_ids as $product_id) {
    $success = set_product_featured($product_id, true);
    $results[$product_id] = $success;

    if ($success) {
      echo "✅ Produto ID $product_id marcado como featured<br>";
    } else {
      echo "❌ Erro ao marcar produto ID $product_id<br>";
    }
  }

  return $results;
}

/**
 * MÉTODO 3: Marcar produtos por categoria como featured
 */
function set_category_products_featured($category_slug, $limit = 5)
{
  $args = array(
    'limit' => $limit,
    'status' => 'publish',
    'category' => array($category_slug)
  );

  $products = wc_get_products($args);
  $count = 0;

  foreach ($products as $product) {
    $product->set_featured(true);
    $product->save();
    $count++;

    echo "✅ Produto '{$product->get_name()}' marcado como featured<br>";
  }

  return $count;
}

/**
 * MÉTODO 4: Desmarcar todos os produtos featured
 */
function unset_all_featured_products()
{
  $args = array(
    'limit' => -1,
    'status' => 'publish',
    'featured' => true
  );

  $products = wc_get_products($args);
  $count = 0;

  foreach ($products as $product) {
    $product->set_featured(false);
    $product->save();
    $count++;

    echo "❌ Produto '{$product->get_name()}' desmarcado como featured<br>";
  }

  return $count;
}

/**
 * MÉTODO 5: Listar produtos featured atuais
 */
function list_featured_products()
{
  $args = array(
    'limit' => -1,
    'status' => 'publish',
    'featured' => true
  );

  $products = wc_get_products($args);

  echo "<h2>📋 Produtos Featured Atuais (" . count($products) . "):</h2>";

  if (empty($products)) {
    echo "<p>Nenhum produto featured encontrado.</p>";
    return;
  }

  echo "<ul>";
  foreach ($products as $product) {
    echo "<li>ID: {$product->get_id()} - {$product->get_name()} - R$ {$product->get_price()}</li>";
  }
  echo "</ul>";
}

// ========== EXECUTAR EXEMPLOS ==========

echo "<h2>🔍 Produtos Featured Atuais:</h2>";
list_featured_products();

echo "<hr>";

// Exemplo 1: Marcar produtos específicos por ID
echo "<h2>📝 Exemplo 1: Marcar Produtos por ID</h2>";
echo "<p>Para marcar produtos específicos, descomente e ajuste os IDs:</p>";
echo "<code>";
echo "// \$product_ids = [123, 456, 789]; // Substitua pelos IDs reais<br>";
echo "// set_multiple_products_featured(\$product_ids);";
echo "</code>";

/*
// Descomente para usar:
$product_ids = [123, 456, 789]; // Substitua pelos IDs reais dos seus produtos
set_multiple_products_featured($product_ids);
*/

echo "<hr>";

// Exemplo 2: Marcar produtos de uma categoria
echo "<h2>📝 Exemplo 2: Marcar Produtos por Categoria</h2>";
echo "<p>Para marcar produtos de uma categoria específica:</p>";
echo "<code>";
echo "// set_category_products_featured('perfumes-masculinos', 3);<br>";
echo "// set_category_products_featured('promocoes', 5);";
echo "</code>";

/*
// Descomente para usar:
set_category_products_featured('perfumes-masculinos', 3);
*/

echo "<hr>";

// Exemplo 3: Funções úteis
echo "<h2>🔧 Funções Úteis Disponíveis:</h2>";
echo "<ul>";
echo "<li><code>set_product_featured(\$product_id, true/false)</code> - Marcar/desmarcar produto específico</li>";
echo "<li><code>set_multiple_products_featured([\$id1, \$id2])</code> - Marcar múltiplos produtos</li>";
echo "<li><code>set_category_products_featured('categoria-slug', \$limit)</code> - Marcar produtos de categoria</li>";
echo "<li><code>unset_all_featured_products()</code> - Desmarcar todos os featured</li>";
echo "<li><code>list_featured_products()</code> - Listar produtos featured</li>";
echo "</ul>";

echo "<hr>";

// Exemplo 4: Verificar se produto é featured
echo "<h2>❓ Como Verificar se Produto é Featured:</h2>";
echo "<code>";
echo "\$product = wc_get_product(\$product_id);<br>";
echo "if (\$product && \$product->is_featured()) {<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;echo 'Produto é featured!';<br>";
echo "}";
echo "</code>";

echo "<hr>";

// Botões de ação rápida
echo "<h2>⚡ Ações Rápidas:</h2>";
echo "<p><strong>⚠️ Cuidado:</strong> As ações abaixo afetam o banco de dados!</p>";

if (isset($_GET['action'])) {
  switch ($_GET['action']) {
    case 'list':
      echo "<h3>📋 Listando produtos featured...</h3>";
      list_featured_products();
      break;

    case 'unset_all':
      echo "<h3>❌ Desmarcando todos os produtos featured...</h3>";
      $count = unset_all_featured_products();
      echo "<p>✅ {$count} produtos desmarcados!</p>";
      break;
  }
}

echo "<div style='margin: 20px 0;'>";
echo "<a href='?action=list' style='background: #007cba; color: white; padding: 10px 15px; text-decoration: none; margin-right: 10px;'>📋 Listar Featured</a>";
echo "<a href='?action=unset_all' style='background: #dc3232; color: white; padding: 10px 15px; text-decoration: none;' onclick='return confirm(\"Tem certeza que deseja desmarcar TODOS os produtos featured?\")'>❌ Desmarcar Todos</a>";
echo "</div>";

echo "<hr>";

echo "<h2>💡 Dicas Importantes:</h2>";
echo "<ul>";
echo "<li>Produtos featured aparecem na query <code>type => 'featured'</code></li>";
echo "<li>Use no máximo 8-12 produtos featured para performance</li>";
echo "<li>Produtos featured são exibidos nas seções 'Produtos em Destaque'</li>";
echo "<li>A marcação é armazenada como meta <code>_featured</code> no banco</li>";
echo "</ul>";

?>

<style>
  body {
    font-family: Arial, sans-serif;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
  }

  h1,
  h2 {
    color: #333;
  }

  code {
    background: #f1f1f1;
    padding: 2px 5px;
    border-radius: 3px;
  }

  hr {
    margin: 30px 0;
    border: 1px solid #ddd;
  }

  ul li {
    margin-bottom: 5px;
  }
</style>