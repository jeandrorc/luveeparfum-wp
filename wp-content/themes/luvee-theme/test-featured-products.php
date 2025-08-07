<?php
/**
 * Teste de Produtos Featured
 * Verificar se produtos featured estão funcionando corretamente
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
  <title>🧪 Teste - Produtos Featured</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

  <!-- Incluir CSS global do tema -->
  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/product-card-global.css">

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
    }

    .badge-featured {
      background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    }
  </style>
</head>

<body>

  <div class="container">
    <h1 class="text-center mb-5">🧪 Teste - Produtos Featured</h1>

    <!-- Debug Info -->
    <div class="debug-section">
      <h2>🔍 Informações de Debug</h2>

      <?php
      // Buscar produtos featured
      $featured_products = wc_get_products(array(
        'limit' => -1,
        'status' => 'publish',
        'featured' => true
      ));

      // Buscar todos os produtos
      $all_products = wc_get_products(array(
        'limit' => -1,
        'status' => 'publish'
      ));

      echo "<div class='row'>";
      echo "<div class='col-md-6'>";
      echo "<h4>📊 Estatísticas:</h4>";
      echo "<ul>";
      echo "<li><strong>Total de produtos:</strong> " . count($all_products) . "</li>";
      echo "<li><strong>Produtos featured:</strong> " . count($featured_products) . "</li>";
      echo "<li><strong>Porcentagem featured:</strong> " . (count($all_products) > 0 ? round(count($featured_products) / count($all_products) * 100, 1) : 0) . "%</li>";
      echo "</ul>";
      echo "</div>";

      echo "<div class='col-md-6'>";
      echo "<h4>🎯 Produtos Featured:</h4>";
      if (empty($featured_products)) {
        echo "<p class='text-warning'>⚠️ Nenhum produto featured encontrado!</p>";
        echo "<p><strong>Como marcar produtos como featured:</strong></p>";
        echo "<ol>";
        echo "<li>Acesse wp-admin > Produtos</li>";
        echo "<li>Edite um produto</li>";
        echo "<li>Marque '✓ Produto em destaque'</li>";
        echo "<li>Salve o produto</li>";
        echo "</ol>";
      } else {
        echo "<ul>";
        foreach ($featured_products as $product) {
          echo "<li>ID: {$product->get_id()} - {$product->get_name()}</li>";
        }
        echo "</ul>";
      }
      echo "</div>";
      echo "</div>";
      ?>
    </div>

    <!-- Teste do Product Section com Featured -->
    <div class="debug-section">
      <h2>🎴 Teste do Product Section (type = 'featured')</h2>

      <?php if (!empty($featured_products)): ?>
        <!-- Simular product-section com produtos featured -->
        <div class="row">
          <?php
          $limit = min(4, count($featured_products));
          for ($i = 0; $i < $limit; $i++):
            $product = $featured_products[$i];
            $GLOBALS['product'] = $product;
            setup_postdata($GLOBALS['post'] = get_post($product->get_id()));
            ?>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
              <div class="card product-card-modern h-100">
                <div class="product-image position-relative">
                  <?php if ($product->get_image_id()): ?>
                    <img src="<?php echo wp_get_attachment_image_url($product->get_image_id(), 'medium'); ?>"
                      class="card-img-top" alt="<?php echo esc_attr($product->get_name()); ?>">
                  <?php else: ?>
                    <img
                      src="https://via.placeholder.com/300x200/007bff/ffffff?text=<?php echo urlencode($product->get_name()); ?>"
                      class="card-img-top" alt="<?php echo esc_attr($product->get_name()); ?>">
                  <?php endif; ?>

                  <!-- Badge Featured -->
                  <div class="product-badges">
                    <span class="badge badge-featured">✨ Destaque</span>
                    <?php if ($product->is_on_sale()): ?>
                      <span class="badge badge-sale">🔥 Oferta</span>
                    <?php endif; ?>
                  </div>

                  <!-- Wishlist Button -->
                  <button class="wishlist-btn" title="Adicionar aos favoritos">
                    <i class="far fa-heart"></i>
                  </button>
                </div>

                <div class="card-body d-flex flex-column">
                  <h5 class="product-title">
                    <a href="<?php echo $product->get_permalink(); ?>">
                      <?php echo $product->get_name(); ?>
                    </a>
                  </h5>

                  <p class="product-excerpt">
                    <?php echo wp_trim_words($product->get_short_description() ?: $product->get_description(), 15); ?>
                  </p>

                  <!-- Rating -->
                  <div class="product-rating">
                    <div class="rating-stars">
                      <?php
                      $rating = $product->get_average_rating() ?: 4.2;
                      for ($star = 1; $star <= 5; $star++):
                        if ($star <= floor($rating)): ?>
                          <i class="fas fa-star text-warning"></i>
                        <?php elseif ($star <= ceil($rating)): ?>
                          <i class="fas fa-star-half-alt text-warning"></i>
                        <?php else: ?>
                          <i class="far fa-star text-muted"></i>
                        <?php endif;
                      endfor; ?>
                    </div>
                    <span class="rating-count">(<?php echo $product->get_rating_count() ?: rand(15, 50); ?>)</span>
                  </div>

                  <!-- Price -->
                  <div class="product-price">
                    <div class="price-display">
                      <?php if ($product->is_on_sale()): ?>
                        <span class="current-price">R$
                          <?php echo number_format((float) $product->get_sale_price(), 2, ',', '.'); ?></span>
                        <span class="original-price">R$
                          <?php echo number_format((float) $product->get_regular_price(), 2, ',', '.'); ?></span>
                      <?php else: ?>
                        <span class="current-price">R$
                          <?php echo number_format((float) $product->get_price(), 2, ',', '.'); ?></span>
                      <?php endif; ?>
                    </div>
                  </div>

                  <!-- Add to Cart Button -->
                  <div class="product-actions mt-3">
                    <?php if ($product->is_purchasable() && $product->is_in_stock()): ?>
                      <button type="button" class="btn btn-primary btn-sm w-100 btn-add-to-cart"
                        data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                        data-product-name="<?php echo esc_attr($product->get_name()); ?>" onclick="simulateAddToCart(this)">

                        <span class="btn-text">
                          <i class="fas fa-shopping-cart me-1"></i>
                          Adicionar ao Carrinho
                        </span>

                        <span class="btn-loading d-none">
                          <div class="spinner-border spinner-border-sm me-1"></div>
                          Adicionando...
                        </span>

                        <span class="btn-success-text d-none">
                          <i class="fas fa-check me-1"></i>
                          Adicionado!
                        </span>
                      </button>

                    <?php elseif (!$product->is_in_stock()): ?>
                      <button type="button" class="btn btn-outline-secondary btn-sm w-100" disabled>
                        <i class="fas fa-times me-1"></i>
                        Fora de Estoque
                      </button>

                    <?php else: ?>
                      <a href="<?php echo esc_url($product->get_permalink()); ?>"
                        class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-eye me-1"></i>
                        Ver Detalhes
                      </a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          <?php
          endfor;
          wp_reset_postdata();
          ?>
        </div>
      <?php else: ?>
        <div class="alert alert-warning">
          <h4>⚠️ Nenhum produto featured encontrado!</h4>
          <p>Para testar esta seção, você precisa marcar alguns produtos como "featured".</p>

          <h5>🔧 Como marcar produtos como featured:</h5>
          <ol>
            <li><strong>Via Admin:</strong> wp-admin > Produtos > Editar > ✓ Produto em destaque</li>
            <li><strong>Via Script:</strong> Use o arquivo <code>set-featured-products.php</code></li>
            <li><strong>Via WP-CLI:</strong> <code>wp post meta update PRODUCT_ID _featured yes</code></li>
          </ol>
        </div>
      <?php endif; ?>
    </div>

    <!-- Query de Teste -->
    <div class="debug-section">
      <h2>🔍 Teste da Query Featured</h2>

      <?php
      // Testar query featured
      echo "<h4>Query wc_get_products com featured=true:</h4>";

      $query_args = array(
        'limit' => 8,
        'status' => 'publish',
        'featured' => true
      );

      $query_products = wc_get_products($query_args);

      echo "<pre>";
      echo "Argumentos da query:\n";
      print_r($query_args);
      echo "\nResultados: " . count($query_products) . " produtos\n";

      if (!empty($query_products)) {
        echo "\nProdutos encontrados:\n";
        foreach ($query_products as $product) {
          echo "- ID: {$product->get_id()}, Nome: {$product->get_name()}, Featured: " . ($product->is_featured() ? 'SIM' : 'NÃO') . "\n";
        }
      }
      echo "</pre>";
      ?>
    </div>

    <!-- Como usar na product-section -->
    <div class="debug-section">
      <h2>💻 Como Usar na Product Section</h2>

      <h4>Código para usar produtos featured:</h4>
      <pre><code>&lt;?php luvee_get_template_part('product-section', null, array(
    'title' => 'Produtos em Destaque',
    'subtitle' => 'Nossa seleção especial de perfumes',
    'type' => 'featured',        &lt;-- IMPORTANTE: type = 'featured'
    'columns' => 4,
    'rows' => 2,
    'show_view_all' => true,
    'section_id' => 'featured-products'
)); ?&gt;</code></pre>

      <h4>Outros tipos disponíveis:</h4>
      <ul>
        <li><code>'type' => 'featured'</code> - Produtos marcados como featured</li>
        <li><code>'type' => 'recent'</code> - Produtos mais recentes</li>
        <li><code>'type' => 'sale'</code> - Produtos em promoção</li>
        <li><code>'type' => 'category'</code> - Produtos de categoria específica</li>
      </ul>
    </div>

    <div class="text-center mt-4">
      <a href="set-featured-products.php" class="btn btn-primary">
        🔧 Gerenciar Produtos Featured
      </a>
      <a href="<?php echo admin_url('edit.php?post_type=product'); ?>" class="btn btn-outline-primary">
        📋 Ver Produtos no Admin
      </a>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

  <script>
    // Simular add to cart para teste
    function simulateAddToCart(btn) {
      const $btn = jQuery(btn);
      const productName = $btn.data('product-name');

      // Loading state
      $btn.prop('disabled', true).addClass('loading');
      $btn.find('.btn-text').addClass('d-none');
      $btn.find('.btn-loading').removeClass('d-none');

      setTimeout(() => {
        // Success state
        $btn.removeClass('loading').addClass('success');
        $btn.find('.btn-loading').addClass('d-none');
        $btn.find('.btn-success-text').removeClass('d-none');

        // Mostrar notificação
        alert(`✅ ${productName} adicionado ao carrinho! (simulação)`);

        setTimeout(() => {
          // Reset
          $btn.removeClass('success').prop('disabled', false);
          $btn.find('.btn-success-text').addClass('d-none');
          $btn.find('.btn-text').removeClass('d-none');
        }, 2000);
      }, 1500);
    }
  </script>

</body>

</html>