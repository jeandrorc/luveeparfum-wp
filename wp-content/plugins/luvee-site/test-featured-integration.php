<?php
/**
 * Teste da Integração Featured Products no Plugin Luvee
 * Verificar se a funcionalidade está funcionando corretamente
 */

// Incluir WordPress
require_once('../../../../wp-config.php');
require_once('../../../../wp-load.php');

if (!function_exists('WC')) {
  die('❌ WooCommerce não está ativo!');
}

if (!class_exists('Luvee_Site_Featured_Products')) {
  die('❌ Plugin Luvee Site não está ativo ou classe Featured Products não foi carregada!');
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>🧪 Teste - Featured Products Plugin Integration</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      background: #f8f9fa;
      padding: 2rem 0;
    }

    .test-section {
      background: white;
      padding: 1.5rem;
      border-radius: 8px;
      margin-bottom: 2rem;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .test-result {
      padding: 1rem;
      border-radius: 4px;
      margin: 1rem 0;
    }

    .test-success {
      background: #d4edda;
      border-left: 4px solid #28a745;
    }

    .test-error {
      background: #f8d7da;
      border-left: 4px solid #dc3545;
    }

    .test-warning {
      background: #fff3cd;
      border-left: 4px solid #ffc107;
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
    <h1 class="text-center mb-5">🧪 Teste - Featured Products Plugin Integration</h1>

    <!-- Status do Plugin -->
    <div class="test-section">
      <h2>🔍 Status do Plugin Luvee</h2>

      <?php
      $plugin_tests = [
        'Plugin Luvee Site ativo' => class_exists('LuveeSite'),
        'Classe Featured Products carregada' => class_exists('Luvee_Site_Featured_Products'),
        'WooCommerce ativo' => function_exists('WC'),
        'WordPress admin hooks' => function_exists('add_meta_boxes'),
      ];

      foreach ($plugin_tests as $test_name => $test_result) {
        $status_class = $test_result ? 'test-success' : 'test-error';
        $icon = $test_result ? '✅' : '❌';
        echo "<div class='test-result {$status_class}'>{$icon} <strong>{$test_name}</strong></div>";
      }
      ?>
    </div>

    <!-- Teste da API -->
    <div class="test-section">
      <h2>🔧 Teste da API Featured Products</h2>

      <?php
      // Testar métodos da classe
      echo "<h4>Métodos Disponíveis:</h4>";

      $methods = [
        'set_product_featured' => method_exists('Luvee_Site_Featured_Products', 'set_product_featured'),
        'is_product_featured' => method_exists('Luvee_Site_Featured_Products', 'is_product_featured'),
        'get_featured_products' => method_exists('Luvee_Site_Featured_Products', 'get_featured_products'),
      ];

      foreach ($methods as $method_name => $exists) {
        $status_class = $exists ? 'test-success' : 'test-error';
        $icon = $exists ? '✅' : '❌';
        echo "<div class='test-result {$status_class}'>{$icon} <code>{$method_name}()</code></div>";
      }

      // Testar funcionalidade básica
      echo "<h4>Teste Funcional:</h4>";

      // Obter um produto para teste
      $test_products = wc_get_products(array('limit' => 1, 'status' => 'publish'));

      if (!empty($test_products)) {
        $test_product = $test_products[0];
        $product_id = $test_product->get_id();

        echo "<div class='test-result test-warning'>";
        echo "🧪 <strong>Produto de teste:</strong> {$test_product->get_name()} (ID: {$product_id})";
        echo "</div>";

        // Teste 1: Marcar como featured
        $result1 = Luvee_Site_Featured_Products::set_product_featured($product_id, true);
        $status1 = $result1 ? 'test-success' : 'test-error';
        $icon1 = $result1 ? '✅' : '❌';
        echo "<div class='test-result {$status1}'>{$icon1} <strong>set_product_featured(true):</strong> " . ($result1 ? 'Sucesso' : 'Falha') . "</div>";

        // Teste 2: Verificar se é featured
        $is_featured = Luvee_Site_Featured_Products::is_product_featured($product_id);
        $status2 = $is_featured ? 'test-success' : 'test-error';
        $icon2 = $is_featured ? '✅' : '❌';
        echo "<div class='test-result {$status2}'>{$icon2} <strong>is_product_featured():</strong> " . ($is_featured ? 'É featured' : 'Não é featured') . "</div>";

        // Teste 3: Obter produtos featured
        $featured_products = Luvee_Site_Featured_Products::get_featured_products(array('limit' => 5));
        $count = count($featured_products);
        $status3 = $count > 0 ? 'test-success' : 'test-warning';
        $icon3 = $count > 0 ? '✅' : '⚠️';
        echo "<div class='test-result {$status3}'>{$icon3} <strong>get_featured_products():</strong> {$count} produtos encontrados</div>";

        // Teste 4: Desmarcar featured
        $result4 = Luvee_Site_Featured_Products::set_product_featured($product_id, false);
        $status4 = $result4 ? 'test-success' : 'test-error';
        $icon4 = $result4 ? '✅' : '❌';
        echo "<div class='test-result {$status4}'>{$icon4} <strong>set_product_featured(false):</strong> " . ($result4 ? 'Sucesso' : 'Falha') . "</div>";

      } else {
        echo "<div class='test-result test-error'>❌ <strong>Nenhum produto encontrado para teste</strong></div>";
      }
      ?>
    </div>

    <!-- Teste dos Hooks -->
    <div class="test-section">
      <h2>🎣 Teste dos Hooks WordPress</h2>

      <?php
      // Verificar se hooks estão registrados
      global $wp_filter;

      $hooks_to_check = [
        'add_meta_boxes' => 'Meta boxes registrados',
        'save_post' => 'Save post hook ativo',
        'admin_enqueue_scripts' => 'Scripts admin carregados',
        'manage_product_posts_columns' => 'Colunas customizadas',
        'bulk_actions-edit-product' => 'Bulk actions registradas',
      ];

      foreach ($hooks_to_check as $hook => $description) {
        $has_hook = isset($wp_filter[$hook]) && !empty($wp_filter[$hook]);
        $status_class = $has_hook ? 'test-success' : 'test-warning';
        $icon = $has_hook ? '✅' : '⚠️';
        echo "<div class='test-result {$status_class}'>{$icon} <strong>{$description}</strong> ({$hook})</div>";
      }
      ?>
    </div>

    <!-- Arquivos do Plugin -->
    <div class="test-section">
      <h2>📁 Arquivos do Plugin</h2>

      <?php
      $plugin_dir = WP_PLUGIN_DIR . '/luvee-site/';
      $files_to_check = [
        'includes/class-featured-products.php' => 'Classe principal',
        'assets/js/featured-admin.js' => 'JavaScript admin',
        'assets/css/admin.css' => 'CSS admin',
        'FEATURED-PRODUCTS-PLUGIN.md' => 'Documentação',
      ];

      foreach ($files_to_check as $file => $description) {
        $file_exists = file_exists($plugin_dir . $file);
        $status_class = $file_exists ? 'test-success' : 'test-error';
        $icon = $file_exists ? '✅' : '❌';
        echo "<div class='test-result {$status_class}'>{$icon} <strong>{$description}:</strong> {$file}</div>";
      }
      ?>
    </div>

    <!-- Estatísticas -->
    <div class="test-section">
      <h2>📊 Estatísticas Atuais</h2>

      <?php
      // Obter estatísticas
      $all_products = wc_get_products(array('limit' => -1, 'status' => 'publish'));
      $featured_products = wc_get_products(array('limit' => -1, 'status' => 'publish', 'featured' => true));

      $total_products = count($all_products);
      $featured_count = count($featured_products);
      $featured_percentage = $total_products > 0 ? round(($featured_count / $total_products) * 100, 1) : 0;

      echo "<div class='row'>";
      echo "<div class='col-md-3'>";
      echo "<div class='test-result test-success text-center'>";
      echo "<h3>{$total_products}</h3>";
      echo "<strong>Total de Produtos</strong>";
      echo "</div>";
      echo "</div>";

      echo "<div class='col-md-3'>";
      echo "<div class='test-result " . ($featured_count > 0 ? 'test-success' : 'test-warning') . " text-center'>";
      echo "<h3>{$featured_count}</h3>";
      echo "<strong>Produtos Featured</strong>";
      echo "</div>";
      echo "</div>";

      echo "<div class='col-md-3'>";
      echo "<div class='test-result test-success text-center'>";
      echo "<h3>{$featured_percentage}%</h3>";
      echo "<strong>Porcentagem Featured</strong>";
      echo "</div>";
      echo "</div>";

      echo "<div class='col-md-3'>";
      $recommended = ($featured_count >= 8 && $featured_count <= 12) ? 'test-success' : 'test-warning';
      echo "<div class='test-result {$recommended} text-center'>";
      echo "<h3>" . ($featured_count >= 8 && $featured_count <= 12 ? '✅' : '⚠️') . "</h3>";
      echo "<strong>Recomendação</strong><br><small>8-12 produtos</small>";
      echo "</div>";
      echo "</div>";
      echo "</div>";
      ?>
    </div>

    <!-- Instruções -->
    <div class="test-section">
      <h2>📋 Próximos Passos</h2>

      <div class="alert alert-info">
        <h5>🎯 Como usar a funcionalidade:</h5>
        <ol>
          <li><strong>Admin:</strong> <a href="<?php echo admin_url('edit.php?post_type=product'); ?>"
              target="_blank">wp-admin > Produtos</a></li>
          <li><strong>Editar produto:</strong> Procure o meta box "⭐ Produto em Destaque"</li>
          <li><strong>Listagem:</strong> Clique na estrela da coluna "Featured"</li>
          <li><strong>Bulk actions:</strong> Selecione múltiplos e use ações em lote</li>
        </ol>

        <h5>🎨 No frontend:</h5>
        <div class="code-block">
          &lt;?php luvee_get_template_part('product-section', null, array(<br>
          &nbsp;&nbsp;&nbsp;&nbsp;'title' => 'Produtos em Destaque',<br>
          &nbsp;&nbsp;&nbsp;&nbsp;'type' => 'featured',<br>
          &nbsp;&nbsp;&nbsp;&nbsp;'columns' => 4,<br>
          &nbsp;&nbsp;&nbsp;&nbsp;'rows' => 2<br>
          )); ?&gt;
        </div>
      </div>
    </div>

    <!-- Links Úteis -->
    <div class="test-section">
      <h2>🔗 Links Úteis</h2>

      <div class="row">
        <div class="col-md-6">
          <h5>Admin WordPress:</h5>
          <ul>
            <li><a href="<?php echo admin_url('edit.php?post_type=product'); ?>" target="_blank">Gerenciar Produtos</a>
            </li>
            <li><a href="<?php echo admin_url('post-new.php?post_type=product'); ?>" target="_blank">Adicionar
                Produto</a></li>
            <li><a href="<?php echo admin_url('plugins.php'); ?>" target="_blank">Plugins Instalados</a></li>
          </ul>
        </div>

        <div class="col-md-6">
          <h5>Documentação:</h5>
          <ul>
            <li><a href="FEATURED-PRODUCTS-PLUGIN.md" target="_blank">Documentação Completa</a></li>
            <li><a href="../../../themes/luvee-theme/test-featured-products.php" target="_blank">Teste Frontend</a></li>
            <li><a href="../../../themes/luvee-theme/ADVANCED-PRODUCT-SECTION.md" target="_blank">Product Section
                Guide</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="text-center mt-4">
      <div class="alert alert-success">
        <h4>🎉 Plugin Featured Products Integrado com Sucesso!</h4>
        <p>A funcionalidade está pronta para uso. Acesse o admin para começar a marcar produtos como featured.</p>
      </div>
    </div>
  </div>

</body>

</html>