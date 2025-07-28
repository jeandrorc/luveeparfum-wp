<?php
/**
 * WooCommerce Template
 * 
 * Template customizado para WooCommerce que integra com os componentes de ecommerce
 */

get_header(); ?>

<div class="container">
  <div class="content-area">

    <?php if (is_shop() || is_product_category() || is_product_tag()): ?>
      <!-- Hero Section for Shop -->
      <section class="shop-hero">
        <h1><?php woocommerce_page_title(); ?></h1>
        <?php woocommerce_breadcrumb(); ?>
      </section>

      <!-- Products Grid -->
      <div class="products-container">
        <?php if (woocommerce_product_loop()): ?>
          <div class="products-grid">
            <?php while (have_posts()):
              the_post(); ?>
              <?php echo astra_child_product_card(); ?>
            <?php endwhile; ?>
          </div>

          <?php woocommerce_pagination(); ?>
        <?php else: ?>
          <p><?php esc_html_e('Nenhum produto encontrado.', 'astra-child'); ?></p>
        <?php endif; ?>
      </div>

    <?php elseif (is_product()): ?>
      <!-- Single Product Page -->
      <div class="single-product-container">
        <div class="product-gallery">
          <?php woocommerce_show_product_images(); ?>
        </div>

        <div class="product-info">
          <h1><?php the_title(); ?></h1>

          <?php woocommerce_template_single_rating(); ?>

          <div class="product-price">
            <?php woocommerce_template_single_price(); ?>
          </div>

          <div class="product-description">
            <?php woocommerce_template_single_excerpt(); ?>
          </div>

          <div class="product-actions">
            <?php woocommerce_template_single_add_to_cart(); ?>

            <button class="wishlist-btn" data-product-id="<?php echo get_the_ID(); ?>">
              <i class="fas fa-heart"></i>
              Adicionar à Lista de Desejos
            </button>
          </div>

          <div class="product-meta">
            <?php woocommerce_template_single_meta(); ?>
          </div>
        </div>
      </div>

      <!-- Related Products -->
      <section class="related-products">
        <h2>Produtos Relacionados</h2>
        <div class="products-grid">
          <?php
          $related_products = wc_get_related_products(get_the_ID(), 4);
          foreach ($related_products as $product_id) {
            echo astra_child_product_card($product_id);
          }
          ?>
        </div>
      </section>

    <?php elseif (is_cart()): ?>
      <!-- Cart Page -->
      <div class="cart-container">
        <h1>Carrinho de Compras</h1>
        <?php woocommerce_content(); ?>
      </div>

    <?php elseif (is_checkout()): ?>
      <!-- Checkout Page -->
      <div class="checkout-container">
        <h1>Finalizar Compra</h1>
        <?php woocommerce_content(); ?>
      </div>

    <?php else: ?>
      <!-- Default WooCommerce Content -->
      <?php woocommerce_content(); ?>
    <?php endif; ?>

  </div>
</div>

<style>
  /* WooCommerce Integration Styles */
  .shop-hero {
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
    padding: 60px 40px;
    border-radius: 12px;
    margin-bottom: 40px;
    text-align: center;
  }

  .shop-hero h1 {
    font-size: 48px;
    font-weight: 700;
    margin-bottom: 16px;
    color: white;
  }

  .products-container {
    margin-bottom: 60px;
  }

  .products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
  }

  .single-product-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    margin-bottom: 60px;
  }

  .product-gallery {
    position: sticky;
    top: 20px;
  }

  .product-info h1 {
    font-size: 32px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 20px;
  }

  .product-price {
    font-size: 24px;
    font-weight: 700;
    color: #3498db;
    margin-bottom: 20px;
  }

  .product-description {
    margin-bottom: 30px;
    line-height: 1.6;
    color: #6c757d;
  }

  .product-actions {
    display: flex;
    gap: 16px;
    margin-bottom: 30px;
    flex-wrap: wrap;
  }

  .wishlist-btn {
    padding: 12px 24px;
    background: transparent;
    color: #3498db;
    border: 2px solid #3498db;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .wishlist-btn:hover {
    background: #3498db;
    color: white;
  }

  .product-meta {
    border-top: 1px solid #e9ecef;
    padding-top: 20px;
  }

  .related-products {
    margin-top: 60px;
  }

  .related-products h2 {
    font-size: 28px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 30px;
    text-align: center;
  }

  .cart-container,
  .checkout-container {
    max-width: 800px;
    margin: 0 auto;
  }

  .cart-container h1,
  .checkout-container h1 {
    font-size: 32px;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 30px;
    text-align: center;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .single-product-container {
      grid-template-columns: 1fr;
      gap: 30px;
    }

    .product-actions {
      flex-direction: column;
    }

    .shop-hero {
      padding: 40px 20px;
    }

    .shop-hero h1 {
      font-size: 32px;
    }
  }
</style>

<?php get_footer(); ?>