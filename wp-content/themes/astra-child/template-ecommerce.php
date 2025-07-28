<?php
/**
 * Template Name: Ecommerce Components Demo
 * 
 * Template para demonstrar os componentes de ecommerce customizados
 */

get_header(); ?>

<div class="container">
  <div class="content-area">

    <!-- Hero Carousel Section -->
    <section class="hero-section">
      <h2>Hero Carousel</h2>
      <?php echo astra_child_hero_carousel(); ?>
    </section>

    <!-- Product Grid Section -->
    <section class="products-section">
      <h2>Produtos em Destaque</h2>
      <div class="products-grid">
        <?php
        // Query para buscar posts com informações de produto
        $products_query = new WP_Query(array(
          'post_type' => 'post',
          'posts_per_page' => 8,
          'meta_query' => array(
            array(
              'key' => '_product_price',
              'compare' => 'EXISTS'
            )
          )
        ));

        if ($products_query->have_posts()):
          while ($products_query->have_posts()):
            $products_query->the_post();
            echo astra_child_product_card();
          endwhile;
          wp_reset_postdata();
        else:
          // Produtos de exemplo se não houver posts
          for ($i = 1; $i <= 6; $i++):
            ?>
        <div class="product-card">
          <div class="product-card__image">
            <img src="https://via.placeholder.com/300x300/3498db/ffffff?text=Produto+<?php echo $i; ?>"
              alt="Produto <?php echo $i; ?>">
            <?php if ($i % 3 === 0): ?>
            <div class="product-card__badge">Promoção</div>
            <?php endif; ?>
          </div>

          <div class="product-card__content">
            <div class="product-card__category">Categoria <?php echo $i; ?></div>
            <h3 class="product-card__title">
              <a href="#">Produto Exemplo <?php echo $i; ?></a>
            </h3>

            <div class="product-card__price">
              <span class="product-card__current-price">R$
                <?php echo number_format(rand(50, 500), 2, ',', '.'); ?></span>
              <?php if ($i % 2 === 0): ?>
              <span class="product-card__old-price">R$ <?php echo number_format(rand(600, 800), 2, ',', '.'); ?></span>
              <span class="product-card__discount">-<?php echo rand(10, 30); ?>%</span>
              <?php endif; ?>
            </div>

            <div class="product-card__actions">
              <a href="#" class="product-card__btn product-card__btn--primary">
                Ver Produto
              </a>
              <button class="product-card__wishlist" data-product-id="<?php echo $i; ?>">
                <i class="fas fa-heart"></i>
              </button>
            </div>
          </div>
        </div>
        <?php
          endfor;
        endif;
        ?>
      </div>
    </section>

    <!-- Mega Menu Demo Section -->
    <section class="mega-menu-demo">
      <h2>Mega Menu Demo</h2>
      <div class="mega-menu-demo-container">
        <div class="mega-menu-demo-trigger">
          <button class="mega-menu-trigger-btn">Abrir Mega Menu</button>
        </div>
        <?php echo astra_child_mega_menu(); ?>
      </div>
    </section>

    <!-- Categories Section -->
    <section class="categories-section">
      <h2>Categorias Populares</h2>
      <div class="categories-grid">
        <?php
        $categories = array(
          array('name' => 'Eletrônicos', 'icon' => 'fas fa-mobile-alt', 'count' => '150+'),
          array('name' => 'Moda', 'icon' => 'fas fa-tshirt', 'count' => '200+'),
          array('name' => 'Casa e Decoração', 'icon' => 'fas fa-home', 'count' => '100+'),
          array('name' => 'Esporte', 'icon' => 'fas fa-dumbbell', 'count' => '80+'),
          array('name' => 'Livros', 'icon' => 'fas fa-book', 'count' => '120+'),
          array('name' => 'Brinquedos', 'icon' => 'fas fa-gamepad', 'count' => '90+')
        );

        foreach ($categories as $category):
          ?>
        <div class="category-card">
          <div class="category-card__icon">
            <i class="<?php echo $category['icon']; ?>"></i>
          </div>
          <h3 class="category-card__title"><?php echo $category['name']; ?></h3>
          <p class="category-card__count"><?php echo $category['count']; ?> produtos</p>
          <a href="#" class="category-card__link">Ver Produtos</a>
        </div>
        <?php
        endforeach;
        ?>
      </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter-section">
      <div class="newsletter-content">
        <h2>Receba Ofertas Exclusivas</h2>
        <p>Inscreva-se em nossa newsletter e receba descontos especiais e novidades em primeira mão!</p>
        <form class="newsletter-form">
          <input type="email" placeholder="Seu melhor e-mail" class="newsletter-input" required>
          <button type="submit" class="newsletter-btn">Inscrever</button>
        </form>
      </div>
    </section>

  </div>
</div>

<style>
/* Additional styles for the demo template */
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.content-area {
  padding: 40px 0;
}

section {
  margin-bottom: 60px;
}

section h2 {
  font-size: 32px;
  font-weight: 700;
  color: #2c3e50;
  margin-bottom: 30px;
  text-align: center;
}

/* Products Grid */
.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 30px;
  margin-bottom: 40px;
}

/* Categories Grid */
.categories-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 30px;
}

.category-card {
  background: white;
  border-radius: 12px;
  padding: 30px 20px;
  text-align: center;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
}

.category-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

.category-card__icon {
  font-size: 48px;
  color: #3498db;
  margin-bottom: 20px;
}

.category-card__title {
  font-size: 18px;
  font-weight: 600;
  color: #2c3e50;
  margin-bottom: 10px;
}

.category-card__count {
  color: #6c757d;
  margin-bottom: 20px;
}

.category-card__link {
  display: inline-block;
  padding: 10px 20px;
  background: #3498db;
  color: white;
  text-decoration: none;
  border-radius: 6px;
  font-weight: 600;
  transition: background 0.2s ease;
}

.category-card__link:hover {
  background: #2980b9;
  color: white;
}

/* Newsletter Section */
.newsletter-section {
  background: linear-gradient(135deg, #3498db, #2980b9);
  color: white;
  padding: 60px 40px;
  border-radius: 12px;
  text-align: center;
}

.newsletter-content h2 {
  color: white;
  margin-bottom: 16px;
}

.newsletter-content p {
  font-size: 18px;
  margin-bottom: 30px;
  opacity: 0.9;
}

.newsletter-form {
  display: flex;
  max-width: 500px;
  margin: 0 auto;
  gap: 10px;
}

.newsletter-input {
  flex: 1;
  padding: 15px 20px;
  border: none;
  border-radius: 6px;
  font-size: 16px;
}

.newsletter-btn {
  padding: 15px 30px;
  background: #27ae60;
  color: white;
  border: none;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s ease;
}

.newsletter-btn:hover {
  background: #219a52;
}

/* Mega Menu Demo */
.mega-menu-demo-container {
  position: relative;
  background: #f8f9fa;
  padding: 40px;
  border-radius: 12px;
}

.mega-menu-demo-trigger {
  text-align: center;
  margin-bottom: 20px;
}

.mega-menu-trigger-btn {
  padding: 12px 24px;
  background: #3498db;
  color: white;
  border: none;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s ease;
}

.mega-menu-trigger-btn:hover {
  background: #2980b9;
}

/* Responsive */
@media (max-width: 768px) {
  .products-grid {
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
  }

  .categories-grid {
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
  }

  .newsletter-form {
    flex-direction: column;
  }

  .newsletter-section {
    padding: 40px 20px;
  }
}
</style>

<script>
// Demo functionality for mega menu trigger
document.addEventListener('DOMContentLoaded', function() {
  const triggerBtn = document.querySelector('.mega-menu-trigger-btn');
  const megaMenu = document.querySelector('.mega-menu');

  if (triggerBtn && megaMenu) {
    triggerBtn.addEventListener('click', function() {
      megaMenu.classList.toggle('active');
    });

    // Close mega menu when clicking outside
    document.addEventListener('click', function(e) {
      if (!megaMenu.contains(e.target) && !triggerBtn.contains(e.target)) {
        megaMenu.classList.remove('active');
      }
    });
  }
});
</script>

<?php get_footer(); ?>