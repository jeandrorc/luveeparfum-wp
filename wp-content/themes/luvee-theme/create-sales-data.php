<?php
/**
 * Criar Dados de Vendas para Best Sellers
 * Script para simular vendas e diferenciar de featured
 */

// Incluir WordPress
require_once('../../../wp-config.php');
require_once('../../../wp-load.php');

if (!function_exists('WC')) {
  die('❌ WooCommerce não está ativo!');
}

if (!class_exists('Luvee_Site_Featured_Products')) {
  die('❌ Plugin Luvee Site não está ativo!');
}

// Verificar permissões
if (!current_user_can('manage_woocommerce')) {
  die('❌ Sem permissão para gerenciar produtos!');
}

?>
<!DOCTYPE html>
<html>

<head>
  <title>🎭 Criar Dados de Vendas</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
      padding: 2rem 0;
    }

    .result-section {
      background: white;
      padding: 1.5rem;
      border-radius: 8px;
      margin-bottom: 2rem;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>

<body>

  <div class="container">
    <h1 class="text-center mb-5">🎭 Criar Dados de Vendas</h1>

    <div class="result-section">
      <h2>📊 Criando Dados de Vendas Realistas</h2>

      <?php
      // Obter todos os produtos
      $all_products = wc_get_products(array(
        'limit' => -1,
        'status' => 'publish'
      ));

      if (empty($all_products)) {
        echo "<div class='alert alert-warning'>⚠️ Nenhum produto encontrado!</div>";
        exit;
      }

      // Dados de vendas realistas
      $sales_patterns = [
        'high_sellers' => [45, 52, 38, 41, 48],  // Top sellers
        'medium_sellers' => [15, 22, 18, 25, 12, 20, 16], // Médios
        'low_sellers' => [3, 7, 5, 8, 4, 6, 9, 2] // Baixos
      ];

      $created_count = 0;
      $products_processed = 0;

      echo "<h4>🎲 Simulando vendas para produtos:</h4>";
      echo "<div class='row'>";

      foreach ($all_products as $index => $product) {
        if ($products_processed >= 20)
          break; // Limite para não sobrecarregar
      
        // Determinar categoria de vendas
        if ($index < 5) {
          $sales = $sales_patterns['high_sellers'][$index] ?? rand(35, 55);
          $category = 'high';
          $badge_class = 'bg-success';
        } elseif ($index < 12) {
          $sales_index = $index - 5;
          $sales = $sales_patterns['medium_sellers'][$sales_index] ?? rand(10, 30);
          $category = 'medium';
          $badge_class = 'bg-warning';
        } else {
          $sales_index = $index - 12;
          $sales = $sales_patterns['low_sellers'][$sales_index] ?? rand(1, 15);
          $category = 'low';
          $badge_class = 'bg-secondary';
        }

        // Simular vendas
        $result = Luvee_Site_Featured_Products::simulate_sales($product->get_id(), $sales);

        if ($result) {
          $created_count++;

          echo "<div class='col-md-6 mb-3'>";
          echo "<div class='card'>";
          echo "<div class='card-body'>";
          echo "<h6 class='card-title'>" . esc_html($product->get_name()) . "</h6>";
          echo "<p class='card-text'>";
          echo "<small class='text-muted'>ID: {$product->get_id()}</small><br>";
          echo "<span class='badge {$badge_class}'>{$sales} vendas</span> ";
          echo "<span class='badge bg-info'>{$category}</span>";
          if ($product->is_featured()) {
            echo " <span class='badge bg-primary'>⭐ Featured</span>";
          }
          echo "</p>";
          echo "</div>";
          echo "</div>";
          echo "</div>";
        }

        $products_processed++;
      }

      echo "</div>";

      echo "<div class='alert alert-success mt-4'>";
      echo "<h5>✅ Dados criados com sucesso!</h5>";
      echo "<p><strong>{$created_count}</strong> produtos agora têm dados de vendas simulados.</p>";
      echo "</div>";

      // Verificar estatísticas
      $stats = Luvee_Site_Featured_Products::get_sales_stats();

      echo "<div class='row mt-4'>";
      echo "<div class='col-md-3'>";
      echo "<div class='card text-center'>";
      echo "<div class='card-body'>";
      echo "<h3>{$stats['products_with_sales']}</h3>";
      echo "<p>Produtos com Vendas</p>";
      echo "</div>";
      echo "</div>";
      echo "</div>";

      echo "<div class='col-md-3'>";
      echo "<div class='card text-center'>";
      echo "<div class='card-body'>";
      echo "<h3>" . number_format($stats['total_sales']) . "</h3>";
      echo "<p>Total de Vendas</p>";
      echo "</div>";
      echo "</div>";
      echo "</div>";

      echo "<div class='col-md-3'>";
      echo "<div class='card text-center'>";
      echo "<div class='card-body'>";
      echo "<h3>{$stats['top_seller_sales']}</h3>";
      echo "<p>Top Seller</p>";
      echo "</div>";
      echo "</div>";
      echo "</div>";

      echo "<div class='col-md-3'>";
      $top_product = $stats['top_seller_id'] ? wc_get_product($stats['top_seller_id']) : null;
      echo "<div class='card text-center'>";
      echo "<div class='card-body'>";
      echo "<h6>" . ($top_product ? $top_product->get_name() : 'N/A') . "</h6>";
      echo "<p><small>Produto Mais Vendido</small></p>";
      echo "</div>";
      echo "</div>";
      echo "</div>";
      echo "</div>";
      ?>
    </div>

    <div class="result-section">
      <h2>🧪 Testar Queries</h2>

      <?php
      // Testar featured vs best_sellers
      $featured_products = wc_get_products(array(
        'limit' => 4,
        'status' => 'publish',
        'featured' => true
      ));

      $best_sellers = Luvee_Site_Featured_Products::get_best_sellers(array(
        'limit' => 4
      ));

      echo "<div class='row'>";

      // Featured
      echo "<div class='col-md-6'>";
      echo "<h4>⭐ Featured Products</h4>";
      if (!empty($featured_products)) {
        foreach ($featured_products as $product) {
          $sales = get_post_meta($product->get_id(), 'total_sales', true) ?: 0;
          echo "<div class='card mb-2'>";
          echo "<div class='card-body py-2'>";
          echo "<small><strong>{$product->get_name()}</strong><br>";
          echo "ID: {$product->get_id()} | Vendas: {$sales}</small>";
          echo "</div>";
          echo "</div>";
        }
      } else {
        echo "<div class='alert alert-warning'>Nenhum produto featured</div>";
      }
      echo "</div>";

      // Best Sellers
      echo "<div class='col-md-6'>";
      echo "<h4>🏆 Best Sellers</h4>";
      if (!empty($best_sellers)) {
        foreach ($best_sellers as $product) {
          $sales = get_post_meta($product->get_id(), 'total_sales', true) ?: 0;
          echo "<div class='card mb-2'>";
          echo "<div class='card-body py-2'>";
          echo "<small><strong>{$product->get_name()}</strong><br>";
          echo "ID: {$product->get_id()} | Vendas: {$sales}</small>";
          echo "</div>";
          echo "</div>";
        }
      } else {
        echo "<div class='alert alert-warning'>Nenhum best seller</div>";
      }
      echo "</div>";
      echo "</div>";

      // Verificar se são diferentes
      $featured_ids = array_map(function ($p) {
        return $p->get_id(); }, $featured_products);
      $best_sellers_ids = array_map(function ($p) {
        return $p->get_id(); }, $best_sellers);

      $are_different = !empty(array_diff($featured_ids, $best_sellers_ids)) || !empty(array_diff($best_sellers_ids, $featured_ids));

      if ($are_different) {
        echo "<div class='alert alert-success mt-3'>";
        echo "<h5>✅ Queries estão retornando produtos diferentes!</h5>";
        echo "<p>Agora as seções na homepage devem mostrar produtos distintos.</p>";
        echo "</div>";
      } else {
        echo "<div class='alert alert-warning mt-3'>";
        echo "<h5>⚠️ Ainda retornando produtos iguais</h5>";
        echo "<p>Pode ser necessário marcar mais produtos como featured ou aguardar cache.</p>";
        echo "</div>";
      }
      ?>
    </div>

    <div class="text-center">
      <div class="btn-group">
        <a href="../front-page.php" class="btn btn-primary" target="_blank">🏠 Ver Homepage</a>
        <a href="debug-queries.php" class="btn btn-outline-primary" target="_blank">🔍 Debug Queries</a>
        <a href="../../../plugins/luvee-site/test-best-sellers.php" class="btn btn-outline-secondary" target="_blank">🧪
          Teste API</a>
      </div>

      <div class="alert alert-info mt-3">
        <h5>📋 Próximos Passos:</h5>
        <ol class="text-start">
          <li>Acesse a <strong>Homepage</strong> para ver as seções</li>
          <li>Verifique se "Destaque" e "Mais Vendidos" mostram produtos diferentes</li>
          <li>Use o <strong>Debug Queries</strong> para análise detalhada</li>
          <li>Configure produtos como Featured no admin se necessário</li>
        </ol>
      </div>
    </div>
  </div>

</body>

</html>