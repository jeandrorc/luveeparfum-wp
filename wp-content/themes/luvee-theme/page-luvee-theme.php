<?php
/**
 * Template Name: Luvée Theme - Showcase de Componentes
 * 
 * Página para demonstrar todos os componentes do tema
 */

get_header(); ?>

<main id="main-content" class="site-main">

  <!-- Page Header -->
  <section class="page-header py-section-sm bg-primary-gradient text-white">
    <div class="container-fluid container-xxl">
      <div class="row">
        <div class="col-12 text-center">
          <h1 class="mb-3" data-aos="fade-up">Luvée Theme Components</h1>
          <p class="lead mb-0" data-aos="fade-up" data-aos-delay="100">
            Showcase de todos os componentes e variações disponíveis
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Table of Contents -->
  <section class="toc-section py-4 bg-light-custom">
    <div class="container-fluid container-xxl">
      <div class="row">
        <div class="col-12">
          <nav class="toc-nav">
            <h5 class="mb-3">Componentes Disponíveis:</h5>
            <div class="row">
              <div class="col-md-6">
                <ul class="list-unstyled">
                  <li><a href="#hero-components" class="text-decoration-none">🎯 Hero Sections</a></li>
                  <li><a href="#product-components" class="text-decoration-none">🛍️ Product Sections</a></li>
                  <li><a href="#newsletter-components" class="text-decoration-none">📧 Newsletter</a></li>
                </ul>
              </div>
              <div class="col-md-6">
                <ul class="list-unstyled">
                  <li><a href="#buttons-components" class="text-decoration-none">🎨 Botões</a></li>
                  <li><a href="#cards-components" class="text-decoration-none">🃏 Cards</a></li>
                  <li><a href="#forms-components" class="text-decoration-none">📝 Formulários</a></li>
                </ul>
              </div>
            </div>
          </nav>
        </div>
      </div>
    </div>
  </section>

  <!-- Hero Components Section -->
  <section id="hero-components" class="component-showcase py-section">
    <div class="container-fluid container-xxl">
      <div class="row mb-5">
        <div class="col-12">
          <h2 class="component-title mb-3">🎯 Hero Sections</h2>
          <p class="text-muted-custom">Diferentes variações do componente Hero</p>
        </div>
      </div>
    </div>

    <!-- Hero Style: Default (Centered) -->
    <div class="component-demo mb-5">
      <div class="component-header bg-light p-3">
        <h4 class="mb-1">Hero - Style Default (Centered)</h4>
        <small class="text-muted">Layout centralizado com foco no conteúdo</small>
      </div>
      <?php
      luvee_get_template_part('hero', null, array(
        'title' => 'Hero Section <span class="text-primary-custom">Centralizado</span>',
        'subtitle' => 'Este é um exemplo do hero section no estilo padrão, perfeito para landing pages e chamadas principais.',
        'button_text' => 'Call to Action',
        'button_link' => '#',
        'style' => 'default'
      ));
      ?>
    </div>

    <!-- Hero Style: Split -->
    <div class="component-demo mb-5">
      <div class="component-header bg-light p-3">
        <h4 class="mb-1">Hero - Style Split</h4>
        <small class="text-muted">Layout dividido com conteúdo + imagem</small>
      </div>
      <?php
      luvee_get_template_part('hero', null, array(
        'title' => 'Hero Section <span class="text-primary-custom">Split Layout</span>',
        'subtitle' => 'Layout em duas colunas, ideal para destacar produtos ou serviços com apoio visual.',
        'button_text' => 'Explorar',
        'button_link' => '#',
        'style' => 'split',
        'image' => 'https://images.unsplash.com/photo-1560066984-138dadb4c035?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400&q=80'
      ));
      ?>
    </div>

    <!-- Hero with Features -->
    <div class="component-demo mb-5">
      <div class="component-header bg-light p-3">
        <h4 class="mb-1">Hero - Com Features</h4>
        <small class="text-muted">Hero section incluindo benefícios/características</small>
      </div>
      <?php
      luvee_get_template_part('hero', null, array(
        'title' => 'Hero com <span class="text-primary-custom">Características</span>',
        'subtitle' => 'Exemplo de hero section que inclui características ou benefícios abaixo do conteúdo principal.',
        'button_text' => 'Começar Agora',
        'button_link' => '#',
        'style' => 'default',
        'features' => array(
          array(
            'icon' => 'fas fa-rocket',
            'title' => 'Performance',
            'description' => 'Tema otimizado para velocidade e performance'
          ),
          array(
            'icon' => 'fas fa-mobile-alt',
            'title' => 'Responsivo',
            'description' => 'Design mobile-first em todos os dispositivos'
          ),
          array(
            'icon' => 'fas fa-cog',
            'title' => 'Customizável',
            'description' => 'Fácil personalização via CSS Variables'
          )
        )
      ));
      ?>
    </div>
  </section>

  <!-- Product Components Section -->
  <section id="product-components" class="component-showcase py-section bg-light-custom">
    <div class="container-fluid container-xxl">
      <div class="row mb-5">
        <div class="col-12">
          <h2 class="component-title mb-3">🛍️ Product Sections</h2>
          <p class="text-muted-custom">Componentes para exibição de produtos WooCommerce</p>
        </div>
      </div>
    </div>

    <?php if (function_exists('WC')): ?>

      <!-- Products: Featured -->
      <div class="component-demo mb-5">
        <div class="component-header bg-white p-3">
          <h4 class="mb-1">Products - Featured (4 colunas)</h4>
          <small class="text-muted">Grid de produtos em destaque</small>
        </div>
        <?php
        luvee_get_template_part('product-section', null, array(
          'title' => 'Produtos em Destaque',
          'subtitle' => 'Os melhores produtos selecionados especialmente para você',
          'limit' => 4,
          'columns' => 4,
          'type' => 'featured',
          'show_view_all' => false
        ));
        ?>
      </div>

      <!-- Products: Recent (3 columns) -->
      <div class="component-demo mb-5">
        <div class="component-header bg-white p-3">
          <h4 class="mb-1">Products - Recent (3 colunas)</h4>
          <small class="text-muted">Produtos mais recentes em layout de 3 colunas</small>
        </div>
        <?php
        luvee_get_template_part('product-section', null, array(
          'title' => 'Lançamentos',
          'subtitle' => 'Confira os produtos que acabaram de chegar',
          'limit' => 3,
          'columns' => 3,
          'type' => 'recent',
          'show_view_all' => false
        ));
        ?>
      </div>

      <!-- Products: Sale -->
      <div class="component-demo mb-5">
        <div class="component-header bg-white p-3">
          <h4 class="mb-1">Products - On Sale (6 colunas)</h4>
          <small class="text-muted">Produtos em promoção em grid compacto</small>
        </div>
        <?php
        luvee_get_template_part('product-section', null, array(
          'title' => 'Ofertas Especiais',
          'subtitle' => 'Aproveite os melhores descontos',
          'limit' => 6,
          'columns' => 6,
          'type' => 'sale',
          'show_view_all' => true
        ));
        ?>
      </div>

    <?php else: ?>
      <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        <strong>WooCommerce não está ativo.</strong> Ative o plugin para ver os componentes de produtos.
      </div>
    <?php endif; ?>
  </section>

  <!-- Newsletter Components Section -->
  <section id="newsletter-components" class="component-showcase py-section">
    <div class="container-fluid container-xxl">
      <div class="row mb-5">
        <div class="col-12">
          <h2 class="component-title mb-3">📧 Newsletter Components</h2>
          <p class="text-muted-custom">Diferentes estilos do componente de newsletter</p>
        </div>
      </div>
    </div>

    <!-- Newsletter: Default Style -->
    <div class="component-demo mb-5">
      <div class="component-header bg-light p-3">
        <h4 class="mb-1">Newsletter - Style Default</h4>
        <small class="text-muted">Estilo padrão com gradiente rosa</small>
      </div>
      <?php
      luvee_get_template_part('newsletter', null, array(
        'title' => 'Newsletter Padrão',
        'subtitle' => 'Exemplo do componente newsletter no estilo padrão',
        'style' => 'default',
        'show_benefits' => false
      ));
      ?>
    </div>

    <!-- Newsletter: Elegant Style -->
    <div class="component-demo mb-5">
      <div class="component-header bg-light p-3">
        <h4 class="mb-1">Newsletter - Style Elegant</h4>
        <small class="text-muted">Estilo elegante com gradiente animado</small>
      </div>
      <?php
      luvee_get_template_part('newsletter', null, array(
        'title' => 'Newsletter Elegante',
        'subtitle' => 'Versão com gradiente animado e benefícios visuais',
        'style' => 'elegant',
        'show_benefits' => true
      ));
      ?>
    </div>

    <!-- Newsletter: Minimal Style -->
    <div class="component-demo mb-5">
      <div class="component-header bg-light p-3">
        <h4 class="mb-1">Newsletter - Style Minimal</h4>
        <small class="text-muted">Estilo minimalista com cores suaves</small>
      </div>
      <?php
      luvee_get_template_part('newsletter', null, array(
        'title' => 'Newsletter Minimalista',
        'subtitle' => 'Versão clean e minimalista para layouts sutis',
        'style' => 'minimal',
        'show_benefits' => true
      ));
      ?>
    </div>
  </section>

  <!-- Buttons Components Section -->
  <section id="buttons-components" class="component-showcase py-section bg-light-custom">
    <div class="container-fluid container-xxl">
      <div class="row mb-5">
        <div class="col-12">
          <h2 class="component-title mb-3">🎨 Botões Bootstrap Customizados</h2>
          <p class="text-muted-custom">Variações dos botões com nossa identidade visual</p>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Botões Principais</h5>
            </div>
            <div class="card-body">
              <div class="d-flex flex-wrap gap-2 mb-3">
                <button class="btn btn-primary">Primary</button>
                <button class="btn btn-primary btn-lg">Primary Large</button>
                <button class="btn btn-primary btn-sm">Primary Small</button>
              </div>

              <div class="d-flex flex-wrap gap-2 mb-3">
                <button class="btn btn-outline-primary">Outline Primary</button>
                <button class="btn btn-outline-primary btn-lg">Outline Large</button>
                <button class="btn btn-outline-primary btn-sm">Outline Small</button>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Botões Secundários</h5>
            </div>
            <div class="card-body">
              <div class="d-flex flex-wrap gap-2 mb-3">
                <button class="btn btn-secondary">Secondary</button>
                <button class="btn btn-warning">Warning</button>
                <button class="btn btn-success">Success</button>
              </div>

              <div class="d-flex flex-wrap gap-2 mb-3">
                <button class="btn btn-light">Light</button>
                <button class="btn btn-dark">Dark</button>
                <button class="btn btn-link">Link</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Cards Components Section -->
  <section id="cards-components" class="component-showcase py-section">
    <div class="container-fluid container-xxl">
      <div class="row mb-5">
        <div class="col-12">
          <h2 class="component-title mb-3">🃏 Cards Customizados</h2>
          <p class="text-muted-custom">Diferentes variações dos cards Bootstrap</p>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-lg-4">
          <div class="card">
            <img
              src="https://images.unsplash.com/photo-1560066984-138dadb4c035?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&h=250&q=80"
              class="card-img-top" alt="Card com imagem">
            <div class="card-body">
              <h5 class="card-title">Card com Imagem</h5>
              <p class="card-text">Exemplo de card padrão com imagem, título e conteúdo.</p>
              <a href="#" class="btn btn-primary">Ver mais</a>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card text-center">
            <div class="card-body">
              <i class="fas fa-heart fa-3x text-primary-custom mb-3"></i>
              <h5 class="card-title">Card com Ícone</h5>
              <p class="card-text">Card centralizado com ícone, ideal para serviços ou características.</p>
              <a href="#" class="btn btn-outline-primary">Saiba mais</a>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card border-primary">
            <div class="card-header bg-primary text-white">
              <h5 class="mb-0">Card Destacado</h5>
            </div>
            <div class="card-body">
              <h6 class="card-subtitle mb-2 text-muted">Subtitle</h6>
              <p class="card-text">Card com bordas e header colorido para destacar conteúdo importante.</p>
              <a href="#" class="btn btn-primary">Ação principal</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Forms Components Section -->
  <section id="forms-components" class="component-showcase py-section bg-light-custom">
    <div class="container-fluid container-xxl">
      <div class="row mb-5">
        <div class="col-12">
          <h2 class="component-title mb-3">📝 Formulários Customizados</h2>
          <p class="text-muted-custom">Componentes de formulário com nossa identidade visual</p>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Formulário de Contato</h5>
            </div>
            <div class="card-body">
              <form>
                <div class="mb-3">
                  <label for="name" class="form-label">Nome completo</label>
                  <input type="text" class="form-control" id="name" placeholder="Seu nome">
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">E-mail</label>
                  <input type="email" class="form-control" id="email" placeholder="seu@email.com">
                </div>
                <div class="mb-3">
                  <label for="subject" class="form-label">Assunto</label>
                  <select class="form-select" id="subject">
                    <option selected>Escolha um assunto</option>
                    <option value="1">Dúvida sobre produto</option>
                    <option value="2">Suporte técnico</option>
                    <option value="3">Sugestão</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="message" class="form-label">Mensagem</label>
                  <textarea class="form-control" id="message" rows="3" placeholder="Sua mensagem..."></textarea>
                </div>
                <div class="mb-3 form-check">
                  <input type="checkbox" class="form-check-input" id="terms">
                  <label class="form-check-label" for="terms">
                    Concordo com os termos de uso
                  </label>
                </div>
                <button type="submit" class="btn btn-primary">Enviar mensagem</button>
              </form>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Estados dos Inputs</h5>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <label for="input-normal" class="form-label">Input Normal</label>
                <input type="text" class="form-control" id="input-normal" placeholder="Estado normal">
              </div>

              <div class="mb-3">
                <label for="input-valid" class="form-label">Input Válido</label>
                <input type="text" class="form-control is-valid" id="input-valid" placeholder="Estado válido">
                <div class="valid-feedback">Perfeito!</div>
              </div>

              <div class="mb-3">
                <label for="input-invalid" class="form-label">Input Inválido</label>
                <input type="text" class="form-control is-invalid" id="input-invalid" placeholder="Estado inválido">
                <div class="invalid-feedback">Campo obrigatório</div>
              </div>

              <div class="mb-3">
                <label for="input-disabled" class="form-label">Input Desabilitado</label>
                <input type="text" class="form-control" id="input-disabled" placeholder="Campo desabilitado" disabled>
              </div>

              <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Buscar...">
                <button class="btn btn-outline-primary" type="button">Buscar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Color Palette Section -->
  <section class="color-palette py-section">
    <div class="container-fluid container-xxl">
      <div class="row mb-5">
        <div class="col-12 text-center">
          <h2 class="component-title mb-3">🎨 Paleta de Cores</h2>
          <p class="text-muted-custom">Cores disponíveis no tema</p>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-lg-3 col-md-6">
          <div class="color-card text-center">
            <div class="color-swatch mb-3"
              style="background: var(--luvee-primary); height: 100px; border-radius: 0.5rem;"></div>
            <h6>Primary</h6>
            <code>#d4869c</code>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="color-card text-center">
            <div class="color-swatch mb-3"
              style="background: var(--luvee-secondary); height: 100px; border-radius: 0.5rem;"></div>
            <h6>Secondary</h6>
            <code>#f4e4e6</code>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="color-card text-center">
            <div class="color-swatch mb-3"
              style="background: var(--luvee-accent); height: 100px; border-radius: 0.5rem;"></div>
            <h6>Accent</h6>
            <code>#daa520</code>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="color-card text-center">
            <div class="color-swatch mb-3"
              style="background: var(--luvee-charcoal); height: 100px; border-radius: 0.5rem;"></div>
            <h6>Charcoal</h6>
            <code>#4a453f</code>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>

<style>
  .component-showcase {
    border-bottom: 1px solid var(--luvee-gray);
  }

  .component-demo {
    border: 1px solid var(--luvee-gray);
    border-radius: var(--luvee-border-radius-lg);
    overflow: hidden;
    box-shadow: var(--luvee-box-shadow);
  }

  .component-header {
    border-bottom: 1px solid var(--luvee-gray);
  }

  .component-title {
    color: var(--luvee-primary);
    font-weight: 700;
  }

  .toc-nav a {
    color: var(--luvee-primary);
    padding: 0.25rem 0;
    display: block;
    transition: var(--luvee-transition-fast);
  }

  .toc-nav a:hover {
    color: var(--luvee-primary-dark);
    padding-left: 0.5rem;
  }

  .color-card {
    padding: 1rem;
    background: white;
    border-radius: var(--luvee-border-radius);
    box-shadow: var(--luvee-box-shadow);
    transition: var(--luvee-transition);
  }

  .color-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--luvee-box-shadow-lg);
  }

  .color-swatch {
    border: 2px solid white;
    box-shadow: var(--luvee-box-shadow);
  }

  /* Smooth scroll */
  html {
    scroll-behavior: smooth;
  }

  /* Code styling */
  code {
    background: var(--luvee-light-gray);
    color: var(--luvee-charcoal);
    padding: 0.125rem 0.25rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
  }
</style>

<?php get_footer(); ?>