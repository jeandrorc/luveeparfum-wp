<?php
/**
 * The Template for displaying product archives, including the main shop page
 * 
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

get_header(); ?>

<div class="shop-page-wrapper" style="background: linear-gradient(135deg, #fefefe 0%, #f8f9fa 100%) !important;">
  <div class="container-fluid container-xxl py-4">
    <div class="row g-4">
      <!-- Sidebar with Filters -->
      <div class="col-lg-3 col-md-4 order-1">
        <aside class="shop-sidebar">
          <?php get_template_part('woocommerce/shop-sidebar'); ?>
        </aside>
      </div>

      <!-- Main Content Area -->
      <div class="col-lg-9 col-md-8 order-2">
        <div class="shop-main-content">

          <?php if (apply_filters('woocommerce_show_page_title', true)): ?>
          <div class="shop-header bg-white rounded-3 p-4 mb-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
              <div class="shop-title-area">
                <h1 class="woocommerce-products-header__title page-title h2 mb-2">
                  <?php woocommerce_page_title(); ?>
                </h1>
                <p class="shop-description text-muted mb-0">
                  Descubra nossa coleção exclusiva de fragrâncias e produtos de beleza
                </p>
              </div>

              <!-- Shop Tools (Sorting, View Toggle) -->
              <div class="shop-tools d-flex align-items-center gap-3 flex-wrap">
                <!-- Sorting -->
                <div class="woocommerce-ordering-wrapper">
                  <label class="form-label small text-muted mb-1">Ordenar por:</label>
                  <?php woocommerce_catalog_ordering(); ?>
                </div>
              </div>
            </div>

            <!-- Results Count -->
            <div class="shop-results-info mt-3 pt-3 border-top">
              <div class="woocommerce-result-count-wrapper">
                <?php woocommerce_result_count(); ?>
              </div>
            </div>
          </div>
          <?php endif; ?>

          <?php
          /**
           * Hook: woocommerce_archive_description.
           *
           * @hooked woocommerce_taxonomy_archive_description - 10
           * @hooked woocommerce_product_archive_description - 10
           */
          do_action('woocommerce_archive_description');
          ?>

          <?php if (woocommerce_product_loop()): ?>

          <?php
            /**
             * Hook: woocommerce_before_shop_loop.
             *
             * @hooked woocommerce_output_all_notices - 10
             * @hooked woocommerce_result_count - 20
             * @hooked woocommerce_catalog_ordering - 30
             */
            // Remove hooks that we're handling manually
            remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
            remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
            do_action('woocommerce_before_shop_loop');
            ?>

          <!-- Products Grid Container -->
          <div class="products-grid-container " id="products-container">
            <?php woocommerce_product_loop_start(); ?>

            <?php if (wc_get_loop_prop('is_shortcode')): ?>
            <?php
                $loop_columns = absint(wc_get_loop_prop('columns'));
                if ($loop_columns < 2) {
                  $loop_columns = 2;
                } elseif ($loop_columns > 4) {
                  $loop_columns = 4;
                }
                ?>
            <div class="row g-4" data-columns="<?php echo esc_attr($loop_columns); ?>">
              <?php else: ?>
              <!-- Custom grid with 4 columns for desktop (mais limpo como na referência) -->
              <div class="row g-4" data-columns="4">
                <?php endif; ?>

                <?php
                  while (have_posts()) {
                    the_post();

                    /**
                     * Hook: woocommerce_shop_loop.
                     */
                    do_action('woocommerce_shop_loop');

                    wc_get_template_part('content', 'product');
                  }
                  ?>

              </div> <!-- .row -->
              <?php woocommerce_product_loop_end(); ?>
            </div>

            <!-- Pagination -->
            <div class="shop-pagination-wrapper mt-4">
              <?php
                /**
                 * Hook: woocommerce_after_shop_loop.
                 *
                 * @hooked woocommerce_pagination - 10
                 */
                do_action('woocommerce_after_shop_loop');
                ?>
            </div>

            <?php else: ?>

            <div class="no-products-found bg-white rounded-3 p-5 shadow-sm text-center">
              <div class="no-products-content">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h3 class="h4 mb-3">Nenhum produto encontrado</h3>
                <p class="text-muted mb-4">Tente ajustar seus filtros ou navegar por outras categorias.</p>
                <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="btn btn-primary">
                  Ver todos os produtos
                </a>
              </div>
            </div>

            <?php endif; ?>

            <!-- Seção "Você Também Pode Gostar" -->
            <?php if (woocommerce_product_loop()): ?>
            <div class="related-products-section mt-5">
              <div class="bg-white rounded-3 p-4 shadow-sm">
                <h2 class="section-title h3 mb-4 text-center">Você Também Pode Gostar</h2>
                <p class="section-subtitle text-muted text-center mb-4">Produtos selecionados especialmente para você
                </p>

                <div class="row g-4">
                  <?php
                    // Buscar produtos relacionados/em destaque
                    $related_products = wc_get_products(array(
                      'limit' => 4,
                      'orderby' => 'rand',
                      'status' => 'publish',
                      'featured' => true,
                    ));

                    if (empty($related_products)) {
                      // Se não há produtos em destaque, buscar produtos aleatórios
                      $related_products = wc_get_products(array(
                        'limit' => 4,
                        'orderby' => 'rand',
                        'status' => 'publish',
                      ));
                    }

                    foreach ($related_products as $product) {
                      global $woocommerce_loop;
                      $woocommerce_loop['columns'] = 4;

                      global $post;
                      $post = get_post($product->get_id());
                      setup_postdata($post);

                      wc_get_template_part('content', 'product');
                    }
                    wp_reset_postdata();
                    ?>
                </div>
              </div>
            </div>
            <?php endif; ?>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Script para garantir fundo claro e heroicons -->
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    console.log('🛍️ Página de produtos carregada - verificando heroicons e fundo...');

    // Forçar fundo claro
    document.body.style.background = '#fefefe';
    document.body.style.backgroundColor = '#fefefe';
    document.documentElement.style.background = '#fefefe';
    document.documentElement.style.backgroundColor = '#fefefe';

    // Aplicar fundo claro ao wrapper principal
    const shopWrapper = document.querySelector('.shop-page-wrapper');
    if (shopWrapper) {
      shopWrapper.style.background = 'linear-gradient(135deg, #fefefe 0%, #f8f9fa 100%)';
      shopWrapper.style.backgroundColor = '#fefefe';
    }

    console.log('✅ Fundo claro aplicado via JavaScript');

    // Função para aplicar emojis como fallback
    function applyProductPageIcons() {
      const icons = {
        'magnifying-glass': '🔍',
        'user': '👤',
        'shopping-bag': '🛍️',
        'bars-3': '☰',
        'x-mark': '✕'
      };

      const elements = document.querySelectorAll('[data-heroicon]');
      let count = 0;

      elements.forEach(el => {
        const iconName = el.getAttribute('data-heroicon');
        const emoji = icons[iconName];

        if (emoji && (!el.innerHTML.trim() || el.innerHTML === emoji)) {
          el.innerHTML = emoji;
          el.style.fontSize = '16px';
          el.style.display = 'inline-block';
          count++;
        }
      });

      if (count > 0) {
        console.log(`✅ ${count} ícones aplicados na página de produtos`);
      }

      return count;
    }

    // Tentar várias abordagens
    setTimeout(applyProductPageIcons, 100);
    setTimeout(applyProductPageIcons, 500);
    setTimeout(applyProductPageIcons, 1000);

    // Usar funções globais se disponíveis
    setTimeout(function() {
      if (typeof window.simpleHeroicons === 'function') {
        window.simpleHeroicons();
        console.log('📦 simpleHeroicons executado na página de produtos');
      }

      if (typeof initHeroicons === 'function') {
        initHeroicons();
        console.log('📦 initHeroicons executado na página de produtos');
      }
    }, 1500);
  });
  </script>

  <?php
  get_footer();