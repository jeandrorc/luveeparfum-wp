<?php
/**
 * Teste dos Botões Adicionar ao Carrinho
 * Verificar funcionamento dos novos botões no content-product
 */
?>

<!DOCTYPE html>
<html>

<head>
  <title>Teste - Botões Add to Cart</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

  <style>
    /* Simulação dos estilos do botão */
    .btn-add-to-cart {
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      border: none;
      background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
      box-shadow: 0 2px 8px rgba(0, 123, 255, 0.2);
    }

    .btn-add-to-cart:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
      background: linear-gradient(135deg, #0056b3 0%, #003d82 100%);
    }

    .btn-add-to-cart.loading {
      pointer-events: none;
    }

    .btn-add-to-cart.success {
      background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
      animation: pulse 0.6s ease-in-out;
    }

    .btn-add-to-cart.error {
      background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
      animation: shake 0.5s ease-in-out;
    }

    @keyframes pulse {
      0% {
        transform: scale(1);
      }

      50% {
        transform: scale(1.05);
      }

      100% {
        transform: scale(1);
      }
    }

    @keyframes shake {

      0%,
      100% {
        transform: translateX(0);
      }

      25% {
        transform: translateX(-2px);
      }

      75% {
        transform: translateX(2px);
      }
    }

    .product-card {
      max-width: 300px;
      margin: 20px;
    }
  </style>
</head>

<body class="bg-light">

  <div class="container py-5">
    <h1 class="text-center mb-5">🛒 Teste - Botões Add to Cart</h1>

    <div class="row justify-content-center">

      <!-- Produto 1 - Em Estoque -->
      <div class="col-auto">
        <div class="card product-card h-100 shadow-sm">
          <img src="https://via.placeholder.com/300x200/007bff/ffffff?text=Produto+1" class="card-img-top">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Perfume Elegance</h5>
            <p class="text-muted small">Fragrância sofisticada</p>

            <div class="mt-auto">
              <div class="mb-3">
                <span class="fw-bold text-dark fs-5">R$ 89,90</span>
              </div>

              <!-- Botão Add to Cart -->
              <button type="button" class="btn btn-primary btn-sm w-100 btn-add-to-cart position-relative"
                data-product-id="123" data-product-name="Perfume Elegance" onclick="simulateAddToCart(this)">

                <span class="btn-text">
                  <i class="fas fa-shopping-cart me-1"></i>
                  Adicionar ao Carrinho
                </span>

                <span class="btn-loading d-none">
                  <div class="spinner-border spinner-border-sm me-1" role="status">
                    <span class="visually-hidden">Carregando...</span>
                  </div>
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

      <!-- Produto 2 - Fora de Estoque -->
      <div class="col-auto">
        <div class="card product-card h-100 shadow-sm">
          <img src="https://via.placeholder.com/300x200/6c757d/ffffff?text=Produto+2" class="card-img-top">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Perfume Premium</h5>
            <p class="text-muted small">Edição limitada</p>

            <div class="mt-auto">
              <div class="mb-3">
                <span class="fw-bold text-dark fs-5">R$ 129,90</span>
              </div>

              <!-- Botão Fora de Estoque -->
              <button type="button" class="btn btn-outline-secondary btn-sm w-100" disabled>
                <i class="fas fa-times me-1"></i>
                Fora de Estoque
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Produto 3 - Ver Detalhes -->
      <div class="col-auto">
        <div class="card product-card h-100 shadow-sm">
          <img src="https://via.placeholder.com/300x200/28a745/ffffff?text=Produto+3" class="card-img-top">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Perfume Especial</h5>
            <p class="text-muted small">Produto customizável</p>

            <div class="mt-auto">
              <div class="mb-3">
                <span class="fw-bold text-dark fs-5">R$ 199,90</span>
              </div>

              <!-- Botão Ver Detalhes -->
              <a href="#" class="btn btn-outline-primary btn-sm w-100">
                <i class="fas fa-eye me-1"></i>
                Ver Detalhes
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- Testes de Estado -->
    <div class="row justify-content-center mt-5">
      <div class="col-md-8">
        <h3 class="text-center mb-4">🧪 Testes de Estados</h3>

        <div class="d-flex gap-3 flex-wrap justify-content-center">
          <button class="btn btn-primary btn-add-to-cart" onclick="testSuccess(this)">
            <span class="btn-text">
              <i class="fas fa-shopping-cart me-1"></i>
              Testar Sucesso
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

          <button class="btn btn-primary btn-add-to-cart" onclick="testError(this)">
            <span class="btn-text">
              <i class="fas fa-shopping-cart me-1"></i>
              Testar Erro
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

          <button class="btn btn-primary btn-add-to-cart" onclick="testConnection(this)">
            <span class="btn-text">
              <i class="fas fa-shopping-cart me-1"></i>
              Testar Conexão
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

    <div class="alert alert-info mt-5">
      <h5>📋 Estados do Botão:</h5>
      <ul class="mb-0">
        <li><strong>Normal:</strong> Azul com gradiente</li>
        <li><strong>Loading:</strong> Spinner + "Adicionando..."</li>
        <li><strong>Success:</strong> Verde + "Adicionado!" + animação pulse</li>
        <li><strong>Error:</strong> Vermelho + "Erro" + animação shake</li>
        <li><strong>Connection:</strong> Vermelho + "Sem Conexão"</li>
      </ul>
    </div>
  </div>

  <script>
    // Simular funcionamento real do addToCart
    function simulateAddToCart(btn) {
      const $btn = jQuery(btn);

      // Estado de loading
      $btn.prop('disabled', true).addClass('loading');
      $btn.find('.btn-text').addClass('d-none');
      $btn.find('.btn-loading').removeClass('d-none');

      // Simular delay de AJAX
      setTimeout(() => {
        // Estado de sucesso
        $btn.removeClass('loading').addClass('success');
        $btn.find('.btn-loading').addClass('d-none');
        $btn.find('.btn-success-text').removeClass('d-none');

        // Reset após 2 segundos
        setTimeout(() => {
          $btn.removeClass('success').prop('disabled', false);
          $btn.find('.btn-success-text').addClass('d-none');
          $btn.find('.btn-text').removeClass('d-none');
        }, 2000);
      }, 1500);
    }

    function testSuccess(btn) {
      const $btn = jQuery(btn);
      $btn.addClass('loading');
      $btn.find('.btn-text').addClass('d-none');
      $btn.find('.btn-loading').removeClass('d-none');

      setTimeout(() => {
        $btn.removeClass('loading').addClass('success');
        $btn.find('.btn-loading').addClass('d-none');
        $btn.find('.btn-success-text').removeClass('d-none');

        setTimeout(() => {
          $btn.removeClass('success');
          $btn.find('.btn-success-text').addClass('d-none');
          $btn.find('.btn-text').removeClass('d-none');
        }, 2000);
      }, 1000);
    }

    function testError(btn) {
      const $btn = jQuery(btn);
      $btn.addClass('loading');
      $btn.find('.btn-text').addClass('d-none');
      $btn.find('.btn-loading').removeClass('d-none');

      setTimeout(() => {
        $btn.removeClass('loading').addClass('error');
        $btn.find('.btn-loading').addClass('d-none');
        $btn.find('.btn-text').removeClass('d-none').html('<i class="fas fa-times me-1"></i>Erro');

        setTimeout(() => {
          $btn.removeClass('error');
          $btn.find('.btn-text').html('<i class="fas fa-shopping-cart me-1"></i>Testar Erro');
        }, 2000);
      }, 1000);
    }

    function testConnection(btn) {
      const $btn = jQuery(btn);
      $btn.addClass('loading');
      $btn.find('.btn-text').addClass('d-none');
      $btn.find('.btn-loading').removeClass('d-none');

      setTimeout(() => {
        $btn.removeClass('loading').addClass('error');
        $btn.find('.btn-loading').addClass('d-none');
        $btn.find('.btn-text').removeClass('d-none').html('<i class="fas fa-wifi me-1"></i>Sem Conexão');

        setTimeout(() => {
          $btn.removeClass('error');
          $btn.find('.btn-text').html('<i class="fas fa-shopping-cart me-1"></i>Testar Conexão');
        }, 2000);
      }, 1000);
    }
  </script>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>