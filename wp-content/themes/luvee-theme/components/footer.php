<?php
/**
 * Footer Component - Versão Bootstrap 5 Refinada
 */
?>

<footer class="footer-main">
    <div class="container-fluid container-xxl">
        <!-- Main Footer Content -->
        <div class="row gy-4">
            <!-- Brand Info -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand">
                    <h5 class="mb-3">
                        <?php bloginfo('name'); ?>
                    </h5>
                    <p class="mb-3">
                        <?php 
                        $description = get_bloginfo('description');
                        echo $description ? esc_html($description) : 'Sua loja de beleza e estética online, com os melhores produtos e atendimento personalizado.';
                        ?>
                    </p>
                    
                    <!-- Social Media -->
                    <div class="social-links">
                        <h6 class="mb-2">Siga-nos</h6>
                        <div class="d-flex gap-2">
                            <?php if (get_theme_mod('luvee_facebook_url')): ?>
                            <a href="<?php echo esc_url(get_theme_mod('luvee_facebook_url')); ?>" class="btn btn-outline-light btn-sm" target="_blank" rel="noopener">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (get_theme_mod('luvee_instagram_url')): ?>
                            <a href="<?php echo esc_url(get_theme_mod('luvee_instagram_url')); ?>" class="btn btn-outline-light btn-sm" target="_blank" rel="noopener">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (get_theme_mod('luvee_whatsapp_number')): ?>
                            <a href="https://wa.me/<?php echo esc_attr(get_theme_mod('luvee_whatsapp_number')); ?>" class="btn btn-outline-light btn-sm" target="_blank" rel="noopener">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <div class="footer-links">
                    <h6 class="mb-3">Links Rápidos</h6>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'menu_class'     => 'list-unstyled mb-0',
                        'container'      => false,
                        'depth'          => 1,
                        'fallback_cb'    => false,
                        'add_link_class' => 'text-decoration-none',
                    ));
                    ?>
                    
                    <!-- Default links if no menu set -->
                    <?php if (!has_nav_menu('footer')): ?>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <a href="<?php echo home_url(); ?>" class="text-decoration-none">Home</a>
                        </li>
                        <?php if (function_exists('WC')): ?>
                        <li class="mb-2">
                            <a href="<?php echo get_permalink(get_option('woocommerce_shop_page_id')); ?>" class="text-decoration-none">Loja</a>
                        </li>
                        <?php endif; ?>
                        <li class="mb-2">
                            <a href="<?php echo home_url('/sobre'); ?>" class="text-decoration-none">Sobre</a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo home_url('/contato'); ?>" class="text-decoration-none">Contato</a>
                        </li>
                    </ul>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Customer Service -->
            <div class="col-lg-3 col-md-6">
                <div class="footer-service">
                    <h6 class="mb-3">Atendimento</h6>
                    <ul class="list-unstyled mb-0">
                        <?php if (function_exists('WC')): ?>
                        <li class="mb-2">
                            <a href="<?php echo wc_get_page_permalink('myaccount'); ?>" class="text-decoration-none">Minha Conta</a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo wc_get_page_permalink('shop'); ?>" class="text-decoration-none">Fazer Pedido</a>
                        </li>
                        <?php endif; ?>
                        <li class="mb-2">
                            <a href="<?php echo home_url('/politica-privacidade'); ?>" class="text-decoration-none">Política de Privacidade</a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo home_url('/termos-uso'); ?>" class="text-decoration-none">Termos de Uso</a>
                        </li>
                        <li class="mb-2">
                            <a href="<?php echo home_url('/faq'); ?>" class="text-decoration-none">FAQ</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6">
                <div class="footer-contact">
                    <h6 class="mb-3">Contato</h6>
                    
                    <?php if (get_theme_mod('luvee_phone')): ?>
                    <div class="contact-item mb-2 d-flex align-items-center">
                        <i class="fas fa-phone me-2 text-primary-custom"></i>
                        <a href="tel:<?php echo esc_attr(get_theme_mod('luvee_phone')); ?>" class="text-decoration-none">
                            <?php echo esc_html(get_theme_mod('luvee_phone')); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (get_theme_mod('luvee_email')): ?>
                    <div class="contact-item mb-2 d-flex align-items-center">
                        <i class="fas fa-envelope me-2 text-primary-custom"></i>
                        <a href="mailto:<?php echo esc_attr(get_theme_mod('luvee_email')); ?>" class="text-decoration-none">
                            <?php echo esc_html(get_theme_mod('luvee_email')); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (get_theme_mod('luvee_address')): ?>
                    <div class="contact-item mb-2 d-flex align-items-start">
                        <i class="fas fa-map-marker-alt me-2 text-primary-custom mt-1"></i>
                        <span><?php echo esc_html(get_theme_mod('luvee_address')); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="row mt-4 pt-4 border-top border-secondary">
            <div class="col-md-6">
                <p class="mb-0 text-center text-md-start">
                    &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. 
                    <?php _e('Todos os direitos reservados.', 'luvee-theme'); ?>
                </p>
            </div>
            <div class="col-md-6">
                <div class="text-center text-md-end">
                    <small class="text-muted">
                        <?php _e('Desenvolvido com', 'luvee-theme'); ?> 
                        <i class="fas fa-heart text-danger"></i> 
                        <?php _e('para beleza e estética', 'luvee-theme'); ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
</footer>