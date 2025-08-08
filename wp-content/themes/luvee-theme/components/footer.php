<?php
/**
 * Footer Component - Luvee Perfumaria Moderno
 * Baseado em design profissional para e-commerce de perfumes
 */

// Definir links e categorias
$footer_links = array(
    'institucional' => array(
        'Sobre a Luvee' => home_url('/sobre'),
        'Nossa Curadoria' => home_url('/curadoria'),
        'Trabalhe Conosco' => home_url('/trabalhe-conosco'),
        'Programa de Afiliados' => home_url('/afiliados'),
        'Sustentabilidade' => home_url('/sustentabilidade')
    ),
    'atendimento' => array(
        'Central de Ajuda' => home_url('/ajuda'),
        'Meus Pedidos' => function_exists('WC') ? wc_get_page_permalink('myaccount') : home_url('/minha-conta'),
        'Trocas e Devoluções' => home_url('/trocas-devolucoes'),
        'Política de Privacidade' => home_url('/politica-privacidade'),
        'Termos de Uso' => home_url('/termos-uso')
    ),
    'categorias' => array(
        'Perfumes Femininos' => function_exists('WC') ? get_term_link('perfumes-femininos', 'product_cat') : home_url('/perfumes-femininos'),
        'Perfumes Masculinos' => function_exists('WC') ? get_term_link('perfumes-masculinos', 'product_cat') : home_url('/perfumes-masculinos'),
        'Perfumes Unissex' => function_exists('WC') ? get_term_link('perfumes-unissex', 'product_cat') : home_url('/perfumes-unissex'),
        'Perfumes Importados' => function_exists('WC') ? get_term_link('perfumes-importados', 'product_cat') : home_url('/perfumes-importados'),
        'Cosméticos' => function_exists('WC') ? get_term_link('cosmeticos', 'product_cat') : home_url('/cosmeticos')
    )
);

$payment_methods = array('Visa', 'Mastercard', 'American Express', 'Elo', 'PIX', 'Boleto');

$social_links = array(
    array(
        'icon' => 'fab fa-instagram',
        'label' => 'Instagram',
        'url' => get_theme_mod('luvee_instagram_url', '#'),
        'color' => '#E4405F'
    ),
    array(
        'icon' => 'fab fa-facebook-f',
        'label' => 'Facebook',
        'url' => get_theme_mod('luvee_facebook_url', '#'),
        'color' => '#1877F2'
    ),
    array(
        'icon' => 'fab fa-whatsapp',
        'label' => 'WhatsApp',
        'url' => get_theme_mod('luvee_whatsapp_number') ? 'https://wa.me/' . get_theme_mod('luvee_whatsapp_number') : '#',
        'color' => '#25D366'
    ),
    array(
        'icon' => 'fab fa-youtube',
        'label' => 'YouTube',
        'url' => get_theme_mod('luvee_youtube_url', '#'),
        'color' => '#FF0000'
    )
);
?>

<footer class="footer-modern bg-dark text-light">
    <!-- Main Footer Content -->
    <div class="container-fluid container-xxl py-5">
        <div class="row g-4">

            <!-- Brand Section -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand">
                    <h3 class="h4 mb-4">
                        <span class="brand-gradient"><?php bloginfo('name'); ?></span>
                    </h3>
                    <p class="text-light-custom mb-4 lh-relaxed">
                        Sua perfumaria online com curadoria exclusiva,
                        oferecendo as melhores fragrâncias com qualidade
                        excepcional e entrega rápida para todo o Brasil.
                    </p>

                    <!-- Contact Info -->
                    <div class="contact-info mb-4">
                        <?php if (get_theme_mod('luvee_phone')): ?>
                            <div class="contact-item d-flex align-items-center mb-2">
                                <div class="contact-icon me-3">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Atendimento</small>
                                    <a href="tel:<?php echo esc_attr(get_theme_mod('luvee_phone')); ?>"
                                        class="text-light text-decoration-none">
                                        <?php echo esc_html(get_theme_mod('luvee_phone', '(11) 4000-0000')); ?>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="contact-item d-flex align-items-center mb-2">
                            <div class="contact-icon me-3">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Garantia</small>
                                <span class="text-light-custom">Compra 100% segura</span>
                            </div>
                        </div>

                        <div class="contact-item d-flex align-items-center mb-2">
                            <div class="contact-icon me-3">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Entrega</small>
                                <span class="text-light-custom">Frete grátis acima de R$ 199</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Institucional Links -->
            <div class="col-lg-2 col-md-6">
                <div class="footer-links">
                    <h5 class="h6 mb-4 text-primary-custom">Institucional</h5>
                    <ul class="list-unstyled">
                        <?php foreach ($footer_links['institucional'] as $label => $url): ?>
                            <li class="mb-2">
                                <a href="<?php echo esc_url($url); ?>"
                                    class="text-light-custom text-decoration-none footer-link">
                                    <?php echo esc_html($label); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Atendimento Links -->
            <div class="col-lg-2 col-md-6">
                <div class="footer-links">
                    <h5 class="h6 mb-4 text-primary-custom">Atendimento</h5>
                    <ul class="list-unstyled">
                        <?php foreach ($footer_links['atendimento'] as $label => $url): ?>
                            <li class="mb-2">
                                <a href="<?php echo esc_url($url); ?>"
                                    class="text-light-custom text-decoration-none footer-link">
                                    <?php echo esc_html($label); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Categorias Links -->
            <div class="col-lg-2 col-md-6">
                <div class="footer-links">
                    <h5 class="h6 mb-4 text-primary-custom">Categorias</h5>
                    <ul class="list-unstyled">
                        <?php foreach ($footer_links['categorias'] as $label => $url): ?>
                            <li class="mb-2">
                                <a href="<?php echo esc_url($url); ?>"
                                    class="text-light-custom text-decoration-none footer-link">
                                    <?php echo esc_html($label); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Newsletter -->
            <div class="col-lg-2 col-md-6">
                <div class="footer-newsletter">
                    <h5 class="h6 mb-4 text-primary-custom">Newsletter</h5>
                    <p class="text-light-custom small mb-3">
                        Receba novidades sobre lançamentos e ofertas exclusivas
                    </p>

                    <form class="newsletter-form" action="#" method="post">
                        <div class="input-group input-group-sm mb-3">
                            <input type="email" class="form-control border-0" placeholder="Seu e-mail"
                                aria-label="Email">
                            <button class="btn btn-primary-custom" type="submit" aria-label="Assinar newsletter">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>

                    <small class="text-muted">
                        <i class="fas fa-lock me-1"></i>
                        Seus dados estão protegidos
                    </small>
                </div>
            </div>
        </div>

        <!-- Social Media & Payment Methods -->
        <div class="border-top border-secondary mt-5 pt-4">
            <div class="row align-items-center">

                <!-- Social Media -->
                <div class="col-lg-6 mb-3 mb-lg-0">
                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center">
                        <span class="text-light-custom me-3 mb-2 mb-sm-0 small">Siga-nos:</span>
                        <div class="social-links d-flex gap-2">
                            <?php foreach ($social_links as $social): ?>
                                <?php if ($social['url'] && $social['url'] !== '#'): ?>
                                    <a href="<?php echo esc_url($social['url']); ?>" class="social-link" target="_blank"
                                        rel="noopener noreferrer" aria-label="<?php echo esc_attr($social['label']); ?>"
                                        data-color="<?php echo esc_attr($social['color']); ?>">
                                        <i class="<?php echo esc_attr($social['icon']); ?>"></i>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="col-lg-6">
                    <div
                        class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-lg-end">
                        <span class="text-light-custom me-3 mb-2 mb-sm-0 small">Formas de pagamento:</span>
                        <div class="payment-methods d-flex align-items-center gap-2">
                            <i class="fas fa-credit-card text-light-custom"></i>
                            <div class="payment-badges d-flex gap-1 flex-wrap">
                                <?php foreach ($payment_methods as $method): ?>
                                    <span class="payment-badge"><?php echo esc_html($method); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Footer -->
    <div class="footer-bottom bg-darker border-top border-secondary">
        <div class="container-fluid container-xxl py-3">
            <div class="row align-items-center">
                <div class="col-md-6 mb-2 mb-md-0">
                    <p class="mb-0 small text-muted text-center text-md-start">
                        &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Todos os direitos reservados.
                    </p>
                </div>
                <div class="col-md-6">
                    <p class="mb-0 small text-muted text-center text-md-end">
                        CNPJ: 00.000.000/0001-00 | Rua das Fragrâncias, 123 - São Paulo, SP
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>