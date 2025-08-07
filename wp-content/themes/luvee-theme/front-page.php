<?php
/**
 * Template da Página Inicial - Versão Bootstrap 5 Refinada
 */

get_header(); ?>

<main id="main-content" class="site-main">

  <!-- Hero Banner Section - Plugin Luvee Site -->
  <?php
  // Exibe todos os hero banners ativos (carrossel automático se múltiplos)
  echo do_shortcode('[luvee_hero]');
  ?>

  <!-- Features Section (mantendo os recursos destacados) -->
  <section class="features-section py-4 bg-light">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
          <div class="feature-item text-center p-3">
            <div class="feature-icon mb-3">
              <i class="fas fa-shipping-fast text-primary" style="font-size: 2.5rem;"></i>
            </div>
            <h5 class="feature-title mb-2">Entrega Rápida</h5>
            <p class="feature-description text-muted mb-0">Frete grátis para todo o Brasil em compras acima
              de R$ 199</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
          <div class="feature-item text-center p-3">
            <div class="feature-icon mb-3">
              <i class="fas fa-medal text-primary" style="font-size: 2.5rem;"></i>
            </div>
            <h5 class="feature-title mb-2">Produtos Premium</h5>
            <p class="feature-description text-muted mb-0">Marcas reconhecidas mundialmente e produtos de
              alta qualidade</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
          <div class="feature-item text-center p-3">
            <div class="feature-icon mb-3">
              <i class="fas fa-heart text-primary" style="font-size: 2.5rem;"></i>
            </div>
            <h5 class="feature-title mb-2">Atendimento Especializado</h5>
            <p class="feature-description text-muted mb-0">Consultoria em beleza personalizada para cada
              cliente</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Featured Products Section -->
  <?php if (function_exists('WC')): ?>
  <?php
    luvee_get_template_part('product-section', null, array(
      'title' => 'Produtos em Destaque',
      'subtitle' => 'Os queridinhos das nossas clientes, selecionados especialmente para você',
      'limit' => 8,
      'columns' => 4,
      'type' => 'featured',
      'section_id' => 'featured-products'
    ));
    ?>
  <?php endif; ?>

  <!-- About Section -->
  <section class="about-section py-section bg-light-custom">
    <div class="container-fluid container-xxl">
      <div class="row align-items-center">
        <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
          <div class="about-image">
            <img
              src="https://images.unsplash.com/photo-1541643600914-78b084683601?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
              alt="Curadoria de Perfumes Luvee" class="img-fluid rounded-lg shadow-lg">
          </div>
        </div>
        <div class="col-lg-6" data-aos="fade-left">
          <div class="about-content ps-lg-4">
            <h2 class="mb-4">Curadoria Exclusiva de Perfumes</h2>
            <p class="lead mb-4">
              Nossa paixão é encontrar as fragrâncias mais especiais do mundo e oferecê-las com um
              atendimento personalizado que transforma cada escolha em uma experiência única e inesquecível.
            </p>
            <p class="mb-4">
              Trabalhamos com uma <strong>curadoria rigorosa</strong>, selecionando apenas perfumes de
              <strong>qualidade excepcional</strong> de marcas renomadas internacionais. Cada fragrância
              em nossa coleção passa por critérios de autenticidade, longevidade e sofisticação.
            </p>

            <!-- Diferenciais -->
            <div class="row g-3 mb-4">
              <div class="col-12">
                <div class="d-flex align-items-center mb-3">
                  <div class="feature-icon me-3">
                    <i class="fas fa-gem text-primary-custom"></i>
                  </div>
                  <div>
                    <h6 class="mb-1">Curadoria Especializada</h6>
                    <small class="text-muted-custom">Seleção criteriosa de fragrâncias únicas e autênticas</small>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="d-flex align-items-center mb-3">
                  <div class="feature-icon me-3">
                    <i class="fas fa-certificate text-primary-custom"></i>
                  </div>
                  <div>
                    <h6 class="mb-1">Qualidade Garantida</h6>
                    <small class="text-muted-custom">Produtos originais importados diretamente das marcas</small>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="d-flex align-items-center mb-3">
                  <div class="feature-icon me-3">
                    <i class="fas fa-user-tie text-primary-custom"></i>
                  </div>
                  <div>
                    <h6 class="mb-1">Atendimento Personalizado</h6>
                    <small class="text-muted-custom">Consultoria especializada para encontrar sua fragrância
                      ideal</small>
                  </div>
                </div>
              </div>
            </div>

            <div class="row g-4 mb-4">
              <div class="col-6">
                <div class="stat-item text-center">
                  <h3 class="text-primary-custom mb-1">200+</h3>
                  <small class="text-muted-custom">Fragrâncias exclusivas</small>
                </div>
              </div>
              <div class="col-6">
                <div class="stat-item text-center">
                  <h3 class="text-primary-custom mb-1">100%</h3>
                  <small class="text-muted-custom">Produtos originais</small>
                </div>
              </div>
            </div>

            <a href="<?php echo home_url('/sobre'); ?>" class="btn btn-outline-primary">
              Descubra nossa curadoria
              <i class="fas fa-arrow-right ms-2"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Best Sellers Section -->
  <?php if (function_exists('WC')): ?>
  <?php
    luvee_get_template_part('product-section', null, array(
      'title' => 'Mais Vendidos',
      'subtitle' => 'Os produtos que conquistaram o coração das nossas clientes',
      'limit' => 8,
      'columns' => 4,
      'type' => 'best_sellers',
      'section_id' => 'best-sellers'
    ));
    ?>
  <?php endif; ?>

  <!-- Testimonials Section -->
  <section class="testimonials-section py-section bg-cream">
    <div class="container-fluid container-xxl">
      <div class="row mb-5">
        <div class="col-12 text-center">
          <h2 class="mb-3" data-aos="fade-up">O que nossas clientes dizem</h2>
          <p class="lead text-muted-custom mx-auto" style="max-width: 600px;" data-aos="fade-up" data-aos-delay="100">
            Depoimentos reais de quem já transformou sua rotina de beleza conosco
          </p>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
          <div class="testimonial-card card h-100 border-0 shadow">
            <div class="card-body text-center p-4">
              <div class="testimonial-avatar mb-3">
                <img
                  src="https://images.unsplash.com/photo-1494790108755-2616b612b566?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&h=150&q=80"
                  alt="Maria Silva" class="rounded-circle" width="80" height="80">
              </div>
              <blockquote class="mb-3">
                <p class="mb-0">"Produtos de qualidade excepcional e atendimento personalizado.
                  Recomendo de olhos fechados!"</p>
              </blockquote>
              <div class="testimonial-rating mb-2">
                <i class="fas fa-star text-warning"></i>
                <i class="fas fa-star text-warning"></i>
                <i class="fas fa-star text-warning"></i>
                <i class="fas fa-star text-warning"></i>
                <i class="fas fa-star text-warning"></i>
              </div>
              <cite class="text-muted-custom">
                <strong>Maria Silva</strong><br>
                <small>Cliente desde 2020</small>
              </cite>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
          <div class="testimonial-card card h-100 border-0 shadow">
            <div class="card-body text-center p-4">
              <div class="testimonial-avatar mb-3">
                <img
                  src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&h=150&q=80"
                  alt="Ana Costa" class="rounded-circle" width="80" height="80">
              </div>
              <blockquote class="mb-3">
                <p class="mb-0">"Entrega super rápida e produtos originais. Minha pele nunca esteve tão
                  bonita!"</p>
              </blockquote>
              <div class="testimonial-rating mb-2">
                <i class="fas fa-star text-warning"></i>
                <i class="fas fa-star text-warning"></i>
                <i class="fas fa-star text-warning"></i>
                <i class="fas fa-star text-warning"></i>
                <i class="fas fa-star text-warning"></i>
              </div>
              <cite class="text-muted-custom">
                <strong>Ana Costa</strong><br>
                <small>Cliente desde 2019</small>
              </cite>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
          <div class="testimonial-card card h-100 border-0 shadow">
            <div class="card-body text-center p-4">
              <div class="testimonial-avatar mb-3">
                <img
                  src="https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&h=150&q=80"
                  alt="Julia Santos" class="rounded-circle" width="80" height="80">
              </div>
              <blockquote class="mb-3">
                <p class="mb-0">"Atendimento impecável e produtos que realmente funcionam. Virei cliente
                  fiel!"</p>
              </blockquote>
              <div class="testimonial-rating mb-2">
                <i class="fas fa-star text-warning"></i>
                <i class="fas fa-star text-warning"></i>
                <i class="fas fa-star text-warning"></i>
                <i class="fas fa-star text-warning"></i>
                <i class="fas fa-star text-warning"></i>
              </div>
              <cite class="text-muted-custom">
                <strong>Julia Santos</strong><br>
                <small>Cliente desde 2021</small>
              </cite>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Categories Section -->
  <?php if (function_exists('WC')): ?>
  <section class="categories-section py-section">
    <div class="container-fluid container-xxl">
      <div class="row mb-5">
        <div class="col-12 text-center">
          <h2 class="mb-3" data-aos="fade-up">Explore por categoria</h2>
          <p class="lead text-muted-custom mx-auto" style="max-width: 600px;" data-aos="fade-up" data-aos-delay="100">
            Encontre exatamente o que você procura navegando pelas nossas categorias
          </p>
        </div>
      </div>

      <div class="row g-4">
        <?php
          $categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'number' => 6,
            'parent' => 0
          ));

          if ($categories && !is_wp_error($categories)):
            foreach ($categories as $index => $category):
              $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
              $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&h=300&q=80';
              ?>
        <div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="<?php echo 100 * $index; ?>">
          <div class="category-card position-relative overflow-hidden rounded-lg">
            <a href="<?php echo get_term_link($category); ?>" class="d-block text-decoration-none">
              <div class="category-image">
                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($category->name); ?>"
                  class="img-fluid">
              </div>
              <div
                class="category-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                <div class="category-content text-center text-white">
                  <h4 class="category-title mb-2"><?php echo esc_html($category->name); ?></h4>
                  <p class="category-count mb-3"><?php echo $category->count; ?> produtos</p>
                  <span class="btn btn-outline-light">Ver categoria</span>
                </div>
              </div>
            </a>
          </div>
        </div>
        <?php
            endforeach;
          endif;
          ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- Newsletter Section -->
  <?php
  luvee_get_template_part('newsletter', null, array(
    'title' => 'Fique por dentro das novidades',
    'subtitle' => 'Receba ofertas exclusivas, lançamentos e dicas de beleza em primeira mão',
    'style' => 'elegant',
    'show_benefits' => true
  ));
  ?>

</main>

<style>
/* Features Section */
.features-section {
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.feature-item {
  transition: transform 0.3s ease;
}

.feature-item:hover {
  transform: translateY(-5px);
}

.feature-icon i {
  transition: transform 0.3s ease;
}

.feature-item:hover .feature-icon i {
  transform: scale(1.1);
}

/* Hero Banner Integration - Aligned with site content */
.luvee-hero-container {
  margin: 0 0 20px 0 !important;
  padding: 0 !important;
}

.luvee-hero-container .container {
  /* Alinha com o container do header: container-fluid container-xxl */
  max-width: 1320px !important;
  /* container-xxl max-width */
  padding-left: var(--bs-gutter-x, 0.75rem) !important;
  padding-right: var(--bs-gutter-x, 0.75rem) !important;
  margin: 0 auto !important;
  width: 100% !important;
}

.luvee-hero-banner {
  border-radius: 0 !important;
  margin: 0 !important;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
  max-height: 450px !important;
  overflow: hidden !important;
}

.luvee-hero-image {
  width: 100% !important;
  height: auto !important;
  max-height: 450px !important;
  object-fit: cover !important;
  object-position: center !important;
}

/* Categories */
.category-card {
  height: 300px;
  transition: transform 0.3s ease;
}

.category-card:hover {
  transform: translateY(-5px);
}

.category-image img {
  width: 100%;
  height: 300px;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.category-card:hover .category-image img {
  transform: scale(1.1);
}

.category-overlay {
  background: linear-gradient(45deg, rgba(212, 134, 156, 0.8), rgba(181, 107, 130, 0.9));
  opacity: 0;
  transition: opacity 0.3s ease;
}

.category-card:hover .category-overlay {
  opacity: 1;
}

.testimonial-card {
  transition: transform 0.3s ease;
}

.testimonial-card:hover {
  transform: translateY(-5px);
}

.stat-item h3 {
  font-size: 2.5rem;
  font-weight: 700;
}

/* Responsive breakpoints following Bootstrap container-xxl standards */
@media (max-width: 1399.98px) {
  .luvee-hero-container .container {
    max-width: 1140px !important;
    /* container-xl */
  }
}

@media (max-width: 1199.98px) {
  .luvee-hero-container .container {
    max-width: 960px !important;
    /* container-lg */
  }
}

@media (max-width: 991.98px) {
  .luvee-hero-container .container {
    max-width: 720px !important;
    /* container-md */
  }
}

@media (max-width: 767.98px) {
  .luvee-hero-container {
    margin: 0 0 15px 0 !important;
  }

  .luvee-hero-container .container {
    max-width: 540px !important;
    padding-left: 12px !important;
    padding-right: 12px !important;
  }

  .luvee-hero-banner {
    max-height: 300px !important;
  }

  .luvee-hero-image {
    max-height: 300px !important;
  }

  .category-card {
    height: 250px;
  }

  .category-image img {
    height: 250px;
  }

  .features-section .col-lg-4 {
    margin-bottom: 1rem;
  }
}

@media (max-width: 575.98px) {
  .luvee-hero-container .container {
    max-width: 100% !important;
    padding-left: 12px !important;
    padding-right: 12px !important;
  }
}
</style>

<?php get_footer(); ?>