<?php
/**
 * Newsletter Component - Versão Bootstrap 5 Refinada
 */

$newsletter_title = $args['title'] ?? 'Fique por dentro das novidades';
$newsletter_subtitle = $args['subtitle'] ?? 'Receba ofertas exclusivas, lançamentos e dicas de beleza em primeira mão';
$newsletter_button_text = $args['button_text'] ?? 'Assinar Newsletter';
$newsletter_style = $args['style'] ?? 'default'; // default, minimal, elegant
$show_benefits = $args['show_benefits'] ?? true;
?>

<section class="newsletter py-section <?php echo esc_attr('newsletter-' . $newsletter_style); ?>">
  <div class="container-fluid container-xxl">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-xl-6">

        <!-- Newsletter Content -->
        <div class="newsletter-content text-center">

          <!-- Icon -->
          <div class="newsletter-icon mb-4" data-aos="zoom-in">
            <i class="fas fa-envelope-open fa-3x text-white"></i>
          </div>

          <!-- Title and Subtitle -->
          <h3 class="newsletter-title mb-3" data-aos="fade-up">
            <?php echo esc_html($newsletter_title); ?>
          </h3>

          <?php if ($newsletter_subtitle): ?>
          <p class="newsletter-subtitle mb-4" data-aos="fade-up" data-aos-delay="100">
            <?php echo esc_html($newsletter_subtitle); ?>
          </p>
          <?php endif; ?>

          <!-- Newsletter Form -->
          <form class="newsletter-form mb-4" method="post" action="" data-aos="fade-up" data-aos-delay="200">
            <?php wp_nonce_field('luvee_newsletter_action', 'luvee_newsletter_nonce'); ?>

            <div class="row g-2 justify-content-center">
              <div class="col-12 col-sm-8 col-md-6">
                <div class="input-group">
                  <input type="email" name="newsletter_email" class="form-control form-control-lg"
                    placeholder="Seu melhor e-mail" required autocomplete="email">
                  <button type="submit" name="newsletter_submit" class="btn btn-warning btn-lg">
                    <i class="fas fa-paper-plane me-1"></i>
                    <span class="d-none d-md-inline"><?php echo esc_html($newsletter_button_text); ?></span>
                    <span class="d-md-none">Enviar</span>
                  </button>
                </div>
              </div>
            </div>

            <!-- Privacy notice -->
            <div class="newsletter-privacy mt-3">
              <small class="text-white-50">
                <i class="fas fa-lock me-1"></i>
                Seus dados estão seguros. Não compartilhamos com terceiros.
              </small>
            </div>
          </form>

          <!-- Benefits -->
          <?php if ($show_benefits): ?>
          <div class="newsletter-benefits" data-aos="fade-up" data-aos-delay="300">
            <div class="row g-3 text-center">
              <div class="col-md-4">
                <div class="benefit-item">
                  <i class="fas fa-tag fa-2x text-warning mb-2"></i>
                  <h6 class="text-white mb-1">Ofertas Exclusivas</h6>
                  <small class="text-white-50">Descontos especiais só para assinantes</small>
                </div>
              </div>
              <div class="col-md-4">
                <div class="benefit-item">
                  <i class="fas fa-star fa-2x text-warning mb-2"></i>
                  <h6 class="text-white mb-1">Lançamentos</h6>
                  <small class="text-white-50">Seja a primeira a conhecer novos produtos</small>
                </div>
              </div>
              <div class="col-md-4">
                <div class="benefit-item">
                  <i class="fas fa-heart fa-2x text-warning mb-2"></i>
                  <h6 class="text-white mb-1">Dicas de Beleza</h6>
                  <small class="text-white-50">Conteúdo exclusivo e tutoriais</small>
                </div>
              </div>
            </div>
          </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>

  <!-- Background decoration -->
  <div class="newsletter-decoration position-absolute top-0 start-0 w-100 h-100 overflow-hidden" style="z-index: -1;">
    <div class="decoration-shape position-absolute"
      style="top: 10%; left: 10%; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%; transform: rotate(45deg);">
    </div>
    <div class="decoration-shape position-absolute"
      style="bottom: 20%; right: 15%; width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%;">
    </div>
    <div class="decoration-shape position-absolute"
      style="top: 60%; left: 80%; width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%;">
    </div>
  </div>
</section>

<?php
// Processamento do formulário de newsletter
if (isset($_POST['newsletter_submit']) && wp_verify_nonce($_POST['luvee_newsletter_nonce'], 'luvee_newsletter_action')) {
    $email = sanitize_email($_POST['newsletter_email']);
    
    if (is_email($email)) {
        // Verificar se já está cadastrado
        $existing_subscriber = get_posts(array(
            'post_type' => 'luvee_newsletter',
            'meta_query' => array(
                array(
                    'key' => 'subscriber_email',
                    'value' => $email,
                    'compare' => '='
                )
            ),
            'posts_per_page' => 1
        ));
        
        if (empty($existing_subscriber)) {
            // Criar novo subscriber
            $subscriber_id = wp_insert_post(array(
                'post_type' => 'luvee_newsletter',
                'post_title' => $email,
                'post_status' => 'publish',
                'meta_input' => array(
                    'subscriber_email' => $email,
                    'subscription_date' => current_time('Y-m-d H:i:s'),
                    'subscription_ip' => $_SERVER['REMOTE_ADDR'],
                    'subscription_source' => 'website_newsletter'
                )
            ));
            
            if ($subscriber_id) {
                // Enviar email de boas-vindas (opcional)
                $subject = 'Bem-vinda à ' . get_bloginfo('name') . '!';
                $message = "Obrigado por se inscrever em nossa newsletter!\n\n";
                $message .= "Você receberá ofertas exclusivas e novidades em primeira mão.\n\n";
                $message .= "Atenciosamente,\n" . get_bloginfo('name');
                
                wp_mail($email, $subject, $message);
                
                echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        alert("✅ Inscrição realizada com sucesso! Verifique seu e-mail.");
                    });
                </script>';
            }
        } else {
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    alert("ℹ️ Este e-mail já está cadastrado em nossa newsletter.");
                });
            </script>';
        }
    } else {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                alert("❌ Por favor, insira um e-mail válido.");
            });
        </script>';
    }
}
?>

<style>
.newsletter {
  position: relative;
}

.newsletter-minimal {
  background: linear-gradient(135deg, var(--luvee-secondary), var(--luvee-light-gray));
  color: var(--luvee-charcoal);
}

.newsletter-minimal .text-white {
  color: var(--luvee-charcoal) !important;
}

.newsletter-minimal .text-white-50 {
  color: var(--luvee-dark-gray) !important;
}

.newsletter-elegant {
  background: linear-gradient(45deg, var(--luvee-primary), var(--luvee-accent), var(--luvee-primary-dark));
  background-size: 400% 400%;
  animation: gradientShift 8s ease infinite;
}

@keyframes gradientShift {
  0% {
    background-position: 0% 50%;
  }

  50% {
    background-position: 100% 50%;
  }

  100% {
    background-position: 0% 50%;
  }
}

.decoration-shape {
  animation: float 6s ease-in-out infinite;
}

.decoration-shape:nth-child(2) {
  animation-delay: -2s;
}

.decoration-shape:nth-child(3) {
  animation-delay: -4s;
}

@keyframes float {

  0%,
  100% {
    transform: translateY(0px) rotate(0deg);
  }

  50% {
    transform: translateY(-20px) rotate(180deg);
  }
}

.benefit-item {
  transition: transform 0.3s ease;
}

.benefit-item:hover {
  transform: translateY(-5px);
}
</style>