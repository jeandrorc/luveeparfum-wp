<?php
/**
 * Debug das Queries - Featured vs Best Sellers
 * Verificar porque estão retornando os mesmos produtos
 */

// Incluir WordPress
require_once('../../../wp-config.php');
require_once('../../../wp-load.php');

if (!function_exists('WC')) {
  die('❌ WooCommerce não está ativo!');
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>🔍 Debug - Featured vs Best Sellers</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
  body {
    background: #f8f9fa;
    padding: 2rem 0;
  }

  .debug-section {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .product-item {
    border: 1px solid #ddd;
    padding: 1rem;
    margin: 0.5rem 0;
    border-radius: 4px;
  }

  .featured {
    border-left: 4px solid #28a745;
  }

  .best-seller {
    border-left: 4px solid #ff6b9d;
  }

  .code-block {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 4px;
    font-family: monospace;
    font-size: 0.9rem;
  }
  </style>
</head>

<body>

  <div class="container">
    <h1 class="text-center mb-5">🔍 Debug - Featured vs Best Sellers</h1>

    <!-- Status dos Dados -->
    <div class="debug-section">
      <h2>📊 Status dos Dados</h2>

      <?php
      // Verificar produtos featured
      $featured_products = wc_get_products(array(
        'limit' => -1,
        'status' => 'publish',
        'featured' => true
      ));

      // Verificar produtos com vendas
      global $wpdb;
      $products_with_sales = $wpdb->get_results("
            SELECT post_id, meta_value as sales 
            FROM {$wpdb->postmeta} 
            WHERE meta_key = 'total_sales' 
            AND CAST(meta_value AS UNSIGNED) > 0
            ORDER BY CAST(meta_value AS UNSIGNED) DESC
        ");

      echo "<div class='row'>";
      echo "<div class='col-md-4'>";
      echo "<div class='alert alert-info text-center'>";
      echo "<h3>" . count($featured_products) . "</h3>";
      echo "<strong>Produtos Featured</strong>";
      echo "</div>";
      echo "</div>";

      echo "<div class='col-md-4'>";
      echo "<div class='alert " . (count($products_with_sales) > 0 ? 'alert-success' : 'alert-warning') . " text-center'>";
      echo "<h3>" . count($products_with_sales) . "</h3>";
      echo "<strong>Produtos com Vendas</strong>";
      echo "</div>";
      echo "</div>";

      echo "<div class='col-md-4'>";
      $overlap = 0;
      if (!empty($products_with_sales)) {
        $sales_ids = array_column($products_with_sales, 'post_id');
        $featured_ids = array_map(function ($p) {
          return $p->get_id(); }, $featured_products);
        $overlap = count(array_intersect($featured_ids, $sales_ids));
      }
      echo "<div class='alert alert-warning text-center'>";
      echo "<h3>{$overlap}</h3>";
      echo "<strong>Sobreposição</strong>";
      echo "</div>";
      echo "</div>";
      echo "</div>";
      ?>
    </div>

    <!-- Query Featured Products -->
    <div class="debug-section">
      <h2>⭐ Query Featured Products</h2>

      <div class="code-block">
        $query_args = array(<br>
        &nbsp;&nbsp;&nbsp;&nbsp;'limit' => 8,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;'status' => 'publish',<br>
        &nbsp;&nbsp;&nbsp;&nbsp;'featured' => true<br>
        );
      </div>

      <?php
      $featured_query = array(
        'limit' => 8,
        'status' => 'publish',
        'featured' => true
      );

      $featured_results = wc_get_products($featured_query);

      echo "<h4>📋 Resultados (" . count($featured_results) . " produtos):</h4>";

      if (!empty($featured_results)) {
        foreach ($featured_results as $product) {
          $sales = get_post_meta($product->get_id(), 'total_sales', true) ?: 0;
          echo "<div class='product-item featured'>";
          echo "<strong>{$product->get_name()}</strong> (ID: {$product->get_id()})<br>";
          echo "<small>Vendas: {$sales} | Featured: " . ($product->is_featured() ? 'Sim' : 'Não') . "</small>";
          echo "</div>";
        }
      } else {
        echo "<div class='alert alert-warning'>⚠️ Nenhum produto featured encontrado</div>";
      }
      ?>
    </div>

    <!-- Query Best Sellers -->
    <div class="debug-section">
      <h2>🏆 Query Best Sellers</h2>

      <div class="code-block">
        $query_args = array(<br>
        &nbsp;&nbsp;&nbsp;&nbsp;'limit' => 8,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;'status' => 'publish',<br>
        &nbsp;&nbsp;&nbsp;&nbsp;'orderby' => 'meta_value_num',<br>
        &nbsp;&nbsp;&nbsp;&nbsp;'order' => 'DESC',<br>
        &nbsp;&nbsp;&nbsp;&nbsp;'meta_key' => 'total_sales',<br>
        &nbsp;&nbsp;&nbsp;&nbsp;'meta_query' => array(<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;array(<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'key' => 'total_sales',<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'value' => 0,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'compare' => '>',<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'type' => 'NUMERIC'<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)<br>
        &nbsp;&nbsp;&nbsp;&nbsp;)<br>
        );
      </div>

      <?php
      $best_sellers_query = array(
        'limit' => 8,
        'status' => 'publish',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'meta_key' => 'total_sales',
        'meta_query' => array(
          array(
            'key' => 'total_sales',
            'value' => 0,
            'compare' => '>',
            'type' => 'NUMERIC'
          )
        )
      );

      $best_sellers_results = wc_get_products($best_sellers_query);

      echo "<h4>📋 Resultados (" . count($best_sellers_results) . " produtos):</h4>";

      if (!empty($best_sellers_results)) {
        foreach ($best_sellers_results as $product) {
          $sales = get_post_meta($product->get_id(), 'total_sales', true) ?: 0;
          echo "<div class='product-item best-seller'>";
          echo "<strong>{$product->get_name()}</strong> (ID: {$product->get_id()})<br>";
          echo "<small>Vendas: {$sales} | Featured: " . ($product->is_featured() ? 'Sim' : 'Não') . "</small>";
          echo "</div>";
        }
      } else {
        echo "<div class='alert alert-danger'>❌ <strong>Nenhum produto best seller encontrado!</strong></div>";
        echo "<div class='alert alert-info'>";
        echo "<h5>🔧 Como resolver:</h5>";
        echo "<ol>";
        echo "<li>Alguns produtos precisam ter vendas registradas (meta_key: total_sales)</li>";
        echo "<li>Use a função simulate_sales() para criar dados de teste</li>";
        echo "<li>Ou aguarde vendas reais serem processadas pelo WooCommerce</li>";
        echo "</ol>";
        echo "</div>";
      }
      ?>
    </div>

    <!-- SQL Debug -->
    <div class="debug-section">
      <h2>🔍 SQL Debug - Produtos com Vendas</h2>

      <?php
      if (!empty($products_with_sales)) {
        echo "<div class='table-responsive'>";
        echo "<table class='table table-sm'>";
        echo "<thead><tr><th>ID</th><th>Nome</th><th>Vendas</th><th>Featured</th></tr></thead>";
        echo "<tbody>";

        foreach ($products_with_sales as $row) {
          $product = wc_get_product($row->post_id);
          if ($product) {
            $featured = $product->is_featured() ? '⭐ Sim' : 'Não';
            echo "<tr>";
            echo "<td>{$row->post_id}</td>";
            echo "<td>" . esc_html($product->get_name()) . "</td>";
            echo "<td><strong>{$row->sales}</strong></td>";
            echo "<td>{$featured}</td>";
            echo "</tr>";
          }
        }
        echo "</tbody></table>";
        echo "</div>";
      } else {
        echo "<div class='alert alert-warning'>";
        echo "<h5>⚠️ Nenhum produto com vendas encontrado!</h5>";
        echo "<p>Isso explica porque best_sellers está retornando os mesmos produtos que featured.</p>";
        echo "</div>";
      }
      ?>
    </div>

    <!-- Simular Dados -->
    <?php if (count($products_with_sales) === 0): ?>
    <div class="debug-section">
      <h2>🎭 Simular Dados de Vendas</h2>

      <div class="alert alert-info">
        <h5>💡 Nenhum dado de vendas encontrado!</h5>
        <p>Vou criar alguns dados de vendas para que a funcionalidade best_sellers funcione corretamente.</p>
      </div>

      <?php
        // Obter alguns produtos para simular vendas
        $all_products = wc_get_products(array('limit' => 10, 'status' => 'publish'));

        if (!empty($all_products) && class_exists('Luvee_Site_Featured_Products')) {
          echo "<h4>🎲 Criando dados de vendas:</h4>";

          foreach ($all_products as $index => $product) {
            // Criar diferentes números de vendas
            $sales_count = [5, 12, 8, 25, 3, 18, 30, 7, 15, 22][$index] ?? rand(1, 30);

            // Simular vendas
            $result = Luvee_Site_Featured_Products::simulate_sales($product->get_id(), $sales_count);

            $status = $result ? 'text-success' : 'text-danger';
            $icon = $result ? '✅' : '❌';

            echo "<div class='product-item'>";
            echo "<span class='{$status}'>{$icon}</span> ";
            echo "<strong>{$product->get_name()}</strong> ";
            echo "<small>(ID: {$product->get_id()}) - {$sales_count} vendas simuladas</small>";
            echo "</div>";
          }

          echo "<div class='alert alert-success mt-3'>";
          echo "<h5>✅ Dados criados com sucesso!</h5>";
          echo "<p>Agora a seção best_sellers deve mostrar produtos diferentes da seção featured.</p>";
          echo "<a href='#' onclick='location.reload()' class='btn btn-primary'>🔄 Recarregar para Ver Resultado</a>";
          echo "</div>";
        }
        ?>
    </div>
    <?php endif; ?>

    <!-- Debug Product Section Switch -->
    <div class="debug-section">
      <h2>🔧 Debug Product Section Switch</h2>

      <p>Vamos simular o switch do <code>product-section.php</code>:</p>

      <?php
      // Simular o switch para featured
      echo "<h4>⭐ Caso 'featured':</h4>";
      $featured_switch_args = array(
        'limit' => 8,
        'status' => 'publish',
        'featured' => true
      );
      echo "<pre class='code-block'>" . print_r($featured_switch_args, true) . "</pre>";

      // Simular o switch para best_sellers
      echo "<h4>🏆 Caso 'best_sellers':</h4>";
      $best_sellers_switch_args = array(
        'limit' => 8,
        'status' => 'publish',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'meta_key' => 'total_sales',
        'meta_query' => array(
          array(
            'key' => 'total_sales',
            'value' => 0,
            'compare' => '>',
            'type' => 'NUMERIC'
          )
        )
      );
      echo "<pre class='code-block'>" . print_r($best_sellers_switch_args, true) . "</pre>";

      // Verificar se há diferença
      $featured_test = wc_get_products($featured_switch_args);
      $best_sellers_test = wc_get_products($best_sellers_switch_args);

      $featured_ids = array_map(function ($p) {
        return $p->get_id(); }, $featured_test);
      $best_sellers_ids = array_map(function ($p) {
        return $p->get_id(); }, $best_sellers_test);

      $different = array_diff($featured_ids, $best_sellers_ids) || array_diff($best_sellers_ids, $featured_ids);

      if ($different) {
        echo "<div class='alert alert-success'>";
        echo "<h5>✅ Queries estão retornando produtos diferentes!</h5>";
        echo "<p>Featured: " . implode(', ', $featured_ids) . "</p>";
        echo "<p>Best Sellers: " . implode(', ', $best_sellers_ids) . "</p>";
        echo "</div>";
      } else {
        echo "<div class='alert alert-warning'>";
        echo "<h5>⚠️ Queries estão retornando os mesmos produtos</h5>";
        echo "<p>IDs iguais: " . implode(', ', $featured_ids) . "</p>";
        echo "</div>";
      }
      ?>
    </div>

    <div class="text-center mt-4">
      <div class="btn-group">
        <a href="../front-page.php" class="btn btn-primary" target="_blank">🏠 Ver Homepage</a>
        <a href="../../../plugins/luvee-site/test-best-sellers.php" class="btn btn-outline-primary" target="_blank">🧪
          Teste API</a>
      </div>
    </div>
  </div>

</body>

</html>