<?php
/**
 * Header Component - Versão Avançada com Duas Rows
 */
?>

<header class="header-main">
    <!-- Top Bar (opcional para anúncios) -->
    <?php if (get_theme_mod('luvee_announcement_text')): ?>
        <div class="header-topbar bg-primary text-white py-2">
            <div class="container-fluid container-xxl">
                <div class="row">
                    <div class="col text-center">
                        <small class="fw-medium">
                            <i class="fas fa-bullhorn me-2"></i>
                            <?php echo esc_html(get_theme_mod('luvee_announcement_text')); ?>
                            <?php if (get_theme_mod('luvee_announcement_link')): ?>
                                <a href="<?php echo esc_url(get_theme_mod('luvee_announcement_link')); ?>"
                                    class="text-white text-decoration-underline ms-2">
                                    Saiba mais
                                </a>
                            <?php endif; ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Header Principal - Row 1: Logo, Busca, Conta, Carrinho -->
    <div class="header-primary py-3 bg-white border-bottom">
        <div class="container-fluid container-xxl">
            <div class="row align-items-center">

                <!-- Logo -->
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="header-logo">
                        <a href="<?php echo home_url(); ?>" class="logo-link">
                            <?php
                            if (has_custom_logo()) {
                                the_custom_logo();
                            } else {
                                ?>
                                <h1 class="logo-text mb-0">
                                    <span class="text-primary-custom"><?php bloginfo('name'); ?></span>
                                </h1>
                                <?php
                            }
                            ?>
                        </a>
                    </div>
                </div>

                <!-- Busca Avançada -->
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="header-search">
                        <form role="search" method="get" class="search-form" action="<?php echo home_url('/'); ?>">
                            <div class="input-group modern-search">
                                <input type="search" class="form-control rounded-search"
                                    placeholder="Buscar produtos, marcas..." value="<?php echo get_search_query(); ?>"
                                    name="s" id="header-search" autocomplete="off">
                                <?php if (function_exists('WC')): ?>
                                    <input type="hidden" name="post_type" value="product">
                                <?php endif; ?>
                                <button class="btn btn-primary search-btn" type="submit" aria-label="Buscar">
                                    <i data-heroicon="magnifying-glass" class="heroicon">🔍</i>
                                </button>
                            </div>

                            <!-- Search Suggestions (será implementado via JS) -->
                            <div class="search-suggestions d-none">
                                <div class="suggestions-content bg-white border rounded-bottom shadow-lg p-3">
                                    <div class="suggestions-loading text-center py-3">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                            <span class="visually-hidden">Carregando...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Minha Conta e Carrinho -->
                <div class="col-lg-3 col-md-8 col-6">
                    <div class="header-actions d-flex align-items-center justify-content-end gap-3">

                        <!-- Mobile Search Toggle -->
                        <button class="btn btn-outline-primary btn-sm d-lg-none mobile-search-toggle" type="button"
                            data-bs-toggle="collapse" data-bs-target="#mobile-search" aria-expanded="false"
                            aria-label="Abrir busca">
                            <i data-heroicon="magnifying-glass" class="heroicon">🔍</i>
                        </button>

                        <!-- Minha Conta -->
                        <div class="header-account dropdown">
                            <a href="#" class="btn btn-outline-primary btn-sm dropdown-toggle modern-btn"
                                data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menu da conta">
                                <i data-heroicon="user" class="heroicon">👤</i>
                                <span class="d-none d-lg-inline">Conta</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php if (is_user_logged_in()): ?>
                                    <li>
                                        <h6 class="dropdown-header">Olá, <?php echo wp_get_current_user()->display_name; ?>!
                                        </h6>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <?php if (function_exists('WC')): ?>
                                        <li><a class="dropdown-item" href="<?php echo wc_get_page_permalink('myaccount'); ?>">
                                                <i class="fas fa-user-circle me-2"></i>Minha Conta
                                            </a></li>
                                        <li><a class="dropdown-item"
                                                href="<?php echo wc_get_page_permalink('myaccount'); ?>orders/">
                                                <i class="fas fa-box me-2"></i>Meus Pedidos
                                            </a></li>
                                        <li><a class="dropdown-item"
                                                href="<?php echo wc_get_page_permalink('myaccount'); ?>edit-address/">
                                                <i class="fas fa-map-marker-alt me-2"></i>Endereços
                                            </a></li>
                                    <?php endif; ?>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="<?php echo wp_logout_url(home_url()); ?>">
                                            <i class="fas fa-sign-out-alt me-2"></i>Sair
                                        </a></li>
                                <?php else: ?>
                                    <li><a class="dropdown-item"
                                            href="<?php echo function_exists('WC') ? wc_get_page_permalink('myaccount') : wp_login_url(); ?>">
                                            <i class="fas fa-sign-in-alt me-2"></i>Entrar
                                        </a></li>
                                    <li><a class="dropdown-item"
                                            href="<?php echo function_exists('WC') ? wc_get_page_permalink('myaccount') : wp_registration_url(); ?>">
                                            <i class="fas fa-user-plus me-2"></i>Criar Conta
                                        </a></li>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <!-- Carrinho -->
                        <?php if (function_exists('WC')): ?>
                            <div class="header-cart">
                                <button type="button"
                                    class="btn btn-primary btn-sm position-relative cart-link mini-cart-toggle modern-btn"
                                    aria-label="Abrir carrinho de compras" title="Carrinho de Compras">
                                    <i data-heroicon="shopping-bag" class="heroicon">🛍️</i>
                                    <span class="d-none d-lg-inline">Carrinho</span>

                                    <?php
                                    $cart_count = WC()->cart->get_cart_contents_count();
                                    if ($cart_count > 0):
                                        ?>
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark cart-count">
                                            <?php echo $cart_count; ?>
                                            <span class="visually-hidden">itens no carrinho</span>
                                        </span>
                                    <?php else: ?>
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark cart-count"
                                            style="display: none;">
                                            0
                                            <span class="visually-hidden">itens no carrinho</span>
                                        </span>
                                    <?php endif; ?>
                                </button>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <!-- Mobile Search (Collapsible) -->
            <div class="collapse mt-3 d-lg-none" id="mobile-search">
                <form role="search" method="get" action="<?php echo home_url('/'); ?>">
                    <div class="input-group">
                        <input type="search" class="form-control" placeholder="Buscar produtos..."
                            value="<?php echo get_search_query(); ?>" name="s">
                        <?php if (function_exists('WC')): ?>
                            <input type="hidden" name="post_type" value="product">
                        <?php endif; ?>
                        <button class="btn btn-primary" type="submit">
                            <i data-heroicon="magnifying-glass" class="heroicon">🔍</i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Header Secundário - Row 2: Menu Principal -->
    <nav class="header-navigation bg-white border-bottom">
        <div class="container-fluid container-xxl">
            <div class="row">
                <div class="col-12">

                    <!-- Desktop Navigation -->
                    <div class="d-none d-lg-block">
                        <ul class="nav nav-pills main-nav py-1 mb-0">

                            <!-- Produtos (Mega Menu) -->
                            <li class="nav-item dropdown mega-dropdown">
                                <a class="nav-link dropdown-toggle fw-semibold"
                                    href="<?php echo function_exists('WC') ? get_permalink(get_option('woocommerce_shop_page_id')) : '#'; ?>"
                                    id="produtos-dropdown" role="button" aria-expanded="false">
                                    Produtos
                                </a>

                                <!-- Mega Menu -->
                                <div class="dropdown-menu mega-menu p-0" aria-labelledby="produtos-dropdown">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <?php
                                            // Buscar categorias principais do WooCommerce
                                            if (function_exists('WC')) {
                                                $parent_categories = get_terms(array(
                                                    'taxonomy' => 'product_cat',
                                                    'hide_empty' => false,
                                                    'parent' => 0,
                                                    'number' => 4 // Limitar para não sobrecarregar
                                                ));

                                                if (!empty($parent_categories) && !is_wp_error($parent_categories)) {
                                                    foreach ($parent_categories as $category) {
                                                        ?>
                                                        <div class="col-lg-3 col-md-6 mb-4">
                                                            <div class="mega-category">
                                                                <h6 class="mega-category-title mb-3">
                                                                    <a href="<?php echo get_term_link($category); ?>"
                                                                        class="text-decoration-none">
                                                                        <?php echo esc_html($category->name); ?>
                                                                    </a>
                                                                </h6>

                                                                <?php
                                                                // Subcategorias
                                                                $subcategories = get_terms(array(
                                                                    'taxonomy' => 'product_cat',
                                                                    'hide_empty' => false,
                                                                    'parent' => $category->term_id,
                                                                    'number' => 5
                                                                ));

                                                                if (!empty($subcategories) && !is_wp_error($subcategories)) {
                                                                    echo '<ul class="list-unstyled mega-subcategories">';
                                                                    foreach ($subcategories as $subcategory) {
                                                                        ?>
                                                <li class="mb-2">
                                                    <a href="<?php echo get_term_link($subcategory); ?>"
                                                        class="text-decoration-none text-muted-custom">
                                                        <?php echo esc_html($subcategory->name); ?>
                                                    </a>
                                                </li>
                                                <?php
                                                                    }
                                                                    echo '</ul>';
                                                                }
                                                                ?>
                                </div>
                            </div>
                            <?php
                                                    }
                                                }
                                            }
                                            ?>


            </div>
        </div>
        </div>
        </li>

        <!-- Links Diretos -->
        <li class="nav-item">
            <a class="nav-link fw-semibold" href="<?php echo home_url('/perfumes/'); ?>">
                <i class="fas fa-spray-can me-2"></i>Perfumes
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link fw-semibold" href="<?php echo home_url('/cosmeticos/'); ?>">
                <i class="fas fa-palette me-2"></i>Cosméticos
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link fw-semibold" href="<?php echo home_url('/body-splash/'); ?>">
                <i class="fas fa-tint me-2"></i>Body Splash
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link fw-semibold text-primary-custom" href="<?php echo home_url('/promocoes/'); ?>">
                <i class="fas fa-tags me-2"></i>Promoções
            </a>
        </li>
        </ul>
        </div>

        <!-- Mobile Navigation -->
        <div class="d-lg-none">
            <button class="btn btn-outline-primary w-100 my-3" type="button" id="mobile-menu-toggle"
                aria-label="Abrir menu principal"
                onclick="document.getElementById('mobile-menu').classList.toggle('show'); document.body.style.overflow = document.getElementById('mobile-menu').classList.contains('show') ? 'hidden' : '';">
                <i data-heroicon="bars-3" class="heroicon me-2">☰</i>Menu Principal
            </button>
        </div>

        </div>
        </div>
        </div>
    </nav>

    <!-- Script inline para teste imediato -->
    <script>
        console.log('🔧 Script inline carregado no header');

        // Função de teste inline
        window.testInline = function () {
            console.log('🧪 Teste inline funcionando!');

            const elements = document.querySelectorAll('[data-heroicon]');
            console.log('🔍 Elementos encontrados:', elements.length);

            elements.forEach((el, i) => {
                console.log(`  ${i + 1}. ${el.getAttribute('data-heroicon')}`);
            });

            return elements.length;
        };

        // Aplicar emojis automaticamente
        function applyEmojis() {
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
                console.log(`✨ ${count} ícones emoji aplicados`);
            }

            return count;
        }

        // Tentar aplicar emojis várias vezes
        document.addEventListener('DOMContentLoaded', function () {
            console.log('📚 DOM carregado - aplicando emojis...');
            applyEmojis();
        });

        setTimeout(applyEmojis, 500);
        setTimeout(applyEmojis, 1500);
        setTimeout(applyEmojis, 3000);

        console.log('🛠️ Função disponível: testInline()');

        // === INFORMAÇÕES DE DEBUG ===
        console.log('🎯 Tema WordPress ativo: <?php echo get_option("stylesheet"); ?>');
        console.log('📁 URL do tema: <?php echo get_template_directory_uri(); ?>');
        console.log('🔗 URL JS esperada: <?php echo get_template_directory_uri(); ?>/assets/js/header-enhancements.js');

        // Criar aliases globais das funções
        window.testHeroicons = window.testInline;
        window.simpleHeroicons = applyEmojis;

        console.log('✅ Funções globais: testHeroicons(), simpleHeroicons(), testInline()');

        // === APLICAÇÃO FORÇADA DE EMOJIS ===
        console.log('🚀 Aplicando emojis via script inline...');
        applyEmojis();

        // Aplicar novamente após um pequeno delay
        setTimeout(() => {
            console.log('🔄 Reaplicando emojis...');
            applyEmojis();
        }, 100);
    </script>

    <!-- Mobile Menu Fullscreen Modal -->
    <div class="mobile-menu-overlay" id="mobile-menu">
        <div class="mobile-menu-container">
            <!-- Header do Menu -->
            <div class="mobile-menu-header">
                <h5 class="mobile-menu-title">
                    <i data-heroicon="bars-3" class="heroicon me-2">☰</i>Menu Principal
                </h5>
                <button type="button" class="mobile-menu-close" id="mobile-menu-close" aria-label="Fechar menu"
                    onclick="document.getElementById('mobile-menu').classList.remove('show'); document.body.style.overflow = '';">
                    <i data-heroicon="x-mark" class="heroicon">✕</i>
                </button>
            </div>

            <!-- Conteúdo do Menu com Scroll -->
            <div class="mobile-menu-content">

                <!-- Links Principais -->
                <div class="mobile-main-links">
                    <a href="<?php echo function_exists('WC') ? get_permalink(get_option('woocommerce_shop_page_id')) : '#'; ?>"
                        class="mobile-menu-link">
                        <i class="fas fa-th-large me-3"></i>Todos os Produtos
                    </a>
                    <a href="<?php echo home_url('/perfumes/'); ?>" class="mobile-menu-link">
                        <i class="fas fa-spray-can me-3"></i>Perfumes
                    </a>
                    <a href="<?php echo home_url('/cosmeticos/'); ?>" class="mobile-menu-link">
                        <i class="fas fa-palette me-3"></i>Cosméticos
                    </a>
                    <a href="<?php echo home_url('/body-splash/'); ?>" class="mobile-menu-link">
                        <i class="fas fa-tint me-3"></i>Body Splash
                    </a>
                    <a href="<?php echo home_url('/promocoes/'); ?>" class="mobile-menu-link featured">
                        <i class="fas fa-tags me-3"></i>Promoções
                    </a>
                </div>

                <!-- Categorias WooCommerce -->
                <?php if (function_exists('WC')): ?>
                    <div class="mobile-categories-section">
                        <h6 class="mobile-section-title">Categorias</h6>
                        <div class="mobile-categories-grid">
                            <?php
                            $categories = get_terms(array(
                                'taxonomy' => 'product_cat',
                                'hide_empty' => false,
                                'parent' => 0,
                                'number' => 12
                            ));

                            if (!empty($categories) && !is_wp_error($categories)) {
                                foreach ($categories as $category) {
                                    ?>
                                    <a href="<?php echo get_term_link($category); ?>" class="mobile-category-item">
                                        <?php echo esc_html($category->name); ?>
                                        <span class="category-count">(<?php echo $category->count; ?>)</span>
                                    </a>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Links Adicionais -->
                <div class="mobile-additional-links">
                    <h6 class="mobile-section-title">Minha Conta</h6>
                    <?php if (is_user_logged_in()): ?>
                        <a href="<?php echo function_exists('WC') ? wc_get_page_permalink('myaccount') : '#'; ?>"
                            class="mobile-menu-link">
                            <i class="fas fa-user-circle me-3"></i>Minha Conta
                        </a>
                        <?php if (function_exists('WC')): ?>
                            <a href="<?php echo wc_get_page_permalink('myaccount'); ?>orders/" class="mobile-menu-link">
                                <i class="fas fa-box me-3"></i>Meus Pedidos
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo wp_logout_url(home_url()); ?>" class="mobile-menu-link">
                            <i class="fas fa-sign-out-alt me-3"></i>Sair
                        </a>
                    <?php else: ?>
                        <a href="<?php echo function_exists('WC') ? wc_get_page_permalink('myaccount') : wp_login_url(); ?>"
                            class="mobile-menu-link">
                            <i class="fas fa-sign-in-alt me-3"></i>Entrar
                        </a>
                        <a href="<?php echo function_exists('WC') ? wc_get_page_permalink('myaccount') : wp_registration_url(); ?>"
                            class="mobile-menu-link">
                            <i class="fas fa-user-plus me-3"></i>Criar Conta
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>