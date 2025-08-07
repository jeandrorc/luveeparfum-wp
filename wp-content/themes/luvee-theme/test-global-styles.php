<?php
/**
 * Teste de Estilos Globais
 * Verificar se os estilos do content-product estão carregando globalmente
 */
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Teste - Estilos Globais</title>

  <?php
  // Simular carregamento do WordPress
  wp_head();
  ?>

  <style>
    body {
      background: #f8f9fa;
      padding: 2rem 0;
    }

    .test-section {
      margin-bottom: 3rem;
    }

    .debug-info {
      background: #fff;
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 2rem;
    }
  </style>
</head>

<body <?php body_class(); ?>>

  <div class="container">
    <h1 class="text-center mb-5">🧪 Teste - Estilos Globais do Product Card</h1>

    <!-- Debug Info -->
    <div class="debug-info">
      <h3>🔍 CSS Debug Info</h3>
      <div id="css-debug-info">
        <p><strong>Carregando informações...</strong></p>
      </div>
    </div>

    <!-- Teste do Botão Add to Cart -->
    <div class="test-section">
      <h2>🛒 Teste do Botão "Adicionar ao Carrinho"</h2>
      <div class="row">
        <div class="col-md-3">
          <div class="card product-card-modern">
            <div class="card-body">
              <h5 class="product-title">Produto Teste</h5>
              <p class="product-excerpt text-muted">Descrição do produto</p>
              <div class="product-price">
                <span class="current-price">R$ 99,90</span>
              </div>
              <div class="product-actions mt-3">
                <button type="button" class="btn btn-primary btn-sm w-100 btn-add-to-cart">
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
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Teste dos Badges -->
    <div class="test-section">
      <h2>🏷️ Teste dos Badges</h2>
      <div class="d-flex gap-3">
        <span class="badge badge-sale">Oferta</span>
        <span class="badge badge-new">Novo</span>
        <span class="badge badge-featured">Destaque</span>
      </div>
    </div>

    <!-- Teste do Product Card -->
    <div class="test-section">
      <h2>🎴 Teste do Product Card Completo</h2>
      <div class="row">
        <div class="col-md-4">
          <div class="card product-card-modern h-100">
            <div class="product-image">
              <img src="https://via.placeholder.com/300x200/007bff/ffffff?text=Produto+Global" class="card-img-top"
                alt="Produto">

              <!-- Badges -->
              <div class="product-badges">
                <span class="badge badge-sale">-20%</span>
              </div>

              <!-- Wishlist -->
              <button class="wishlist-btn" title="Adicionar aos favoritos">
                <i class="far fa-heart"></i>
              </button>
            </div>

            <div class="card-body d-flex flex-column">
              <h5 class="product-title">
                <a href="#">Perfume Global Test</a>
              </h5>

              <p class="product-excerpt">
                Teste de produto com estilos globais aplicados
              </p>

              <!-- Rating -->
              <div class="product-rating">
                <div class="rating-stars">
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star text-warning"></i>
                  <i class="far fa-star text-muted"></i>
                </div>
                <span class="rating-count">(42)</span>
              </div>

              <!-- Price -->
              <div class="product-price">
                <div class="price-display">
                  <span class="current-price">R$ 79,90</span>
                  <span class="original-price">R$ 99,90</span>
                </div>
              </div>

              <!-- Actions -->
              <div class="product-actions mt-3">
                <button type="button" class="btn btn-primary btn-sm w-100 btn-add-to-cart"
                  onclick="testButtonStates(this)">
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
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Teste de Responsividade -->
    <div class="test-section">
      <h2>📱 Teste Mobile (redimensione a janela)</h2>
      <p>Os estilos devem se adaptar automaticamente para mobile.</p>
    </div>

    <!-- Resultados do Teste -->
    <div class="test-section">
      <h2>✅ Checklist de Verificação</h2>
      <div id="test-results">
        <div class="alert alert-info">
          <h5>🔍 Verificações Automáticas:</h5>
          <ul id="auto-checks">
            <li>Carregando...</li>
          </ul>
        </div>

        <div class="alert alert-warning">
          <h5>👀 Verificações Visuais:</h5>
          <ul>
            <li>Botão tem gradiente azul?</li>
            <li>Hover effect funciona no botão?</li>
            <li>Card tem border-radius e shadow?</li>
            <li>Badges têm gradientes coloridos?</li>
            <li>Wishlist button está posicionado corretamente?</li>
            <li>Preços estão formatados corretamente?</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Função para testar estados do botão
    function testButtonStates(btn) {
      const $btn = jQuery(btn);

      // Loading state
      $btn.addClass('loading');
      $btn.find('.btn-text').addClass('d-none');
      $btn.find('.btn-loading').removeClass('d-none');

      setTimeout(() => {
        // Success state
        $btn.removeClass('loading').addClass('success');
        $btn.find('.btn-loading').addClass('d-none');
        $btn.find('.btn-success-text').removeClass('d-none');

        setTimeout(() => {
          // Reset
          $btn.removeClass('success');
          $btn.find('.btn-success-text').addClass('d-none');
          $btn.find('.btn-text').removeClass('d-none');
        }, 2000);
      }, 1500);
    }

    // Debug automático
    document.addEventListener('DOMContentLoaded', function () {
      // Verificar CSS carregados
      const cssFiles = Array.from(document.styleSheets)
        .map(sheet => sheet.href)
        .filter(href => href && href.includes('luvee'));

      const debugInfo = document.getElementById('css-debug-info');
      const autoChecks = document.getElementById('auto-checks');

      // Mostrar CSS carregados
      debugInfo.innerHTML = `
        <p><strong>CSS do Luvee carregados:</strong></p>
        <ul>
            ${cssFiles.map(css => `<li>${css.split('/').pop()}</li>`).join('')}
        </ul>
    `;

      // Verificações automáticas
      const checks = [
        {
          test: () => cssFiles.some(css => css.includes('product-card-global')),
          text: 'CSS Global carregado'
        },
        {
          test: () => getComputedStyle(document.querySelector('.btn-add-to-cart')).background.includes('linear-gradient'),
          text: 'Botão tem gradiente'
        },
        {
          test: () => getComputedStyle(document.querySelector('.product-card-modern')).borderRadius !== '0px',
          text: 'Card tem border-radius'
        },
        {
          test: () => getComputedStyle(document.querySelector('.badge-sale')).background.includes('linear-gradient'),
          text: 'Badge sale tem gradiente'
        }
      ];

      autoChecks.innerHTML = checks.map(check => {
        const passed = check.test();
        return `<li style="color: ${passed ? 'green' : 'red'}">
            ${passed ? '✅' : '❌'} ${check.text}
        </li>`;
      }).join('');
    });
  </script>

  <?php wp_footer(); ?>
</body>

</html>