<?php
/**
 * Hero Component - Versão Bootstrap 5 Refinada
 */

$hero_title = $args['title'] ?? 'Bem-vindo à ' . get_bloginfo('name');
$hero_subtitle = $args['subtitle'] ?? 'Descubra nossa coleção exclusiva de produtos de beleza e estética';
$hero_button_text = $args['button_text'] ?? 'Explorar Produtos';
$hero_button_link = $args['button_link'] ?? (function_exists('WC') ? get_permalink(get_option('woocommerce_shop_page_id')) : '#');
$hero_image = $args['image'] ?? '';
$hero_style = $args['style'] ?? 'default'; // default, centered, split
?>

<section class="hero py-section <?php echo esc_attr('hero-' . $hero_style); ?>" 
         <?php if ($hero_image): ?>
         style="background-image: linear-gradient(rgba(244, 228, 230, 0.8), rgba(248, 246, 243, 0.8)), url('<?php echo esc_url($hero_image); ?>'); background-size: cover; background-position: center;"
         <?php endif; ?>>
    
    <div class="container-fluid container-xxl">
        <div class="row align-items-center min-vh-50">
            
            <?php if ($hero_style === 'split'): ?>
                <!-- Split Layout -->
                <div class="col-lg-6 order-2 order-lg-1">
                    <div class="hero-content pe-lg-4">
                        <h1 class="hero-title mb-4" data-aos="fade-up">
                            <?php echo wp_kses_post($hero_title); ?>
                        </h1>
                        
                        <?php if ($hero_subtitle): ?>
                        <p class="hero-subtitle lead mb-4" data-aos="fade-up" data-aos-delay="100">
                            <?php echo wp_kses_post($hero_subtitle); ?>
                        </p>
                        <?php endif; ?>
                        
                        <?php if ($hero_button_text && $hero_button_link): ?>
                        <div class="hero-actions" data-aos="fade-up" data-aos-delay="200">
                            <a href="<?php echo esc_url($hero_button_link); ?>" 
                               class="btn btn-primary btn-lg me-3 mb-3">
                                <?php echo esc_html($hero_button_text); ?>
                                <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                            
                            <?php if (function_exists('WC')): ?>
                            <a href="<?php echo wc_get_page_permalink('shop'); ?>" 
                               class="btn btn-outline-primary btn-lg mb-3">
                                Ver Catálogo
                            </a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="col-lg-6 order-1 order-lg-2">
                    <div class="hero-image text-center" data-aos="zoom-in" data-aos-delay="300">
                        <?php if ($hero_image): ?>
                        <img src="<?php echo esc_url($hero_image); ?>" 
                             alt="<?php echo esc_attr($hero_title); ?>" 
                             class="img-fluid rounded-lg shadow-lg">
                        <?php else: ?>
                        <!-- Placeholder ou imagem padrão -->
                        <div class="placeholder-image bg-light-custom rounded-lg p-5 shadow">
                            <i class="fas fa-image fa-5x text-muted-custom mb-3"></i>
                            <p class="text-muted-custom">Imagem do Hero</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
            <?php else: ?>
                <!-- Centered Layout (Default) -->
                <div class="col-12">
                    <div class="hero-content text-center">
                        <h1 class="hero-title mb-4" data-aos="fade-up">
                            <?php echo wp_kses_post($hero_title); ?>
                        </h1>
                        
                        <?php if ($hero_subtitle): ?>
                        <p class="hero-subtitle lead mb-5 mx-auto" style="max-width: 600px;" data-aos="fade-up" data-aos-delay="100">
                            <?php echo wp_kses_post($hero_subtitle); ?>
                        </p>
                        <?php endif; ?>
                        
                        <?php if ($hero_button_text && $hero_button_link): ?>
                        <div class="hero-actions" data-aos="fade-up" data-aos-delay="200">
                            <a href="<?php echo esc_url($hero_button_link); ?>" 
                               class="btn btn-primary btn-lg me-3 mb-3">
                                <?php echo esc_html($hero_button_text); ?>
                                <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                            
                            <?php if (function_exists('WC')): ?>
                            <a href="<?php echo wc_get_page_permalink('shop'); ?>" 
                               class="btn btn-outline-primary btn-lg mb-3">
                                <i class="fas fa-search me-2"></i>
                                Explorar Catálogo
                            </a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
        </div>
        
        <!-- Features/Benefits (opcional) -->
        <?php if (isset($args['features']) && is_array($args['features'])): ?>
        <div class="row mt-5 pt-4">
            <?php foreach ($args['features'] as $feature): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-item text-center" data-aos="fade-up" data-aos-delay="<?php echo 100 * $loop->index; ?>">
                    <?php if (isset($feature['icon'])): ?>
                    <div class="feature-icon mb-3">
                        <i class="<?php echo esc_attr($feature['icon']); ?> fa-2x text-primary-custom"></i>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($feature['title'])): ?>
                    <h5 class="feature-title mb-2"><?php echo esc_html($feature['title']); ?></h5>
                    <?php endif; ?>
                    
                    <?php if (isset($feature['description'])): ?>
                    <p class="feature-description text-muted-custom mb-0">
                        <?php echo esc_html($feature['description']); ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Scroll indicator -->
    <div class="hero-scroll text-center mt-4" data-aos="fade-up" data-aos-delay="400">
        <a href="#main-content" class="text-decoration-none">
            <i class="fas fa-chevron-down fa-2x text-primary-custom animate-bounce"></i>
        </a>
    </div>
</section>