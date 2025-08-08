<?php
/**
 * Checkout Form - Luvee Perfumaria
 * Template principal do checkout com layout moderno e responsivo
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.0.0
 */

defined('ABSPATH') || exit;

// Se checkout exigir login e usuário não estiver logado
if (!is_user_logged_in() && (bool) get_option('woocommerce_enable_checkout_login_reminder') === true) {
  wc_print_notice(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('Você precisa estar logado para finalizar a compra.', 'woocommerce')), 'notice');
}

do_action('woocommerce_before_checkout_form', $checkout);

// Se não há produtos no carrinho
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
  echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('Você precisa estar logado para finalizar a compra.', 'woocommerce')));
  return;
}
?>

<div class="luvee-checkout-page">
  <div class="container-fluid container-xxl py-5">

    <!-- Header do Checkout -->
    <div class="checkout-header mb-5">
      <div class="row align-items-center">
        <div class="col-md-8">
          <h1 class="checkout-title mb-2">
            <i class="fas fa-lock me-3 text-primary-custom"></i>
            Finalizar Compra
          </h1>
          <p class="checkout-subtitle text-muted mb-0">Informe seus dados e confirme o pedido</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
          <a class="btn btn-outline-primary" href="<?php echo esc_url(wc_get_cart_url()); ?>">
            <i class="fas fa-arrow-left me-2"></i> Voltar ao carrinho
          </a>
        </div>
      </div>
    </div>

    <form name="checkout" method="post" class="checkout woocommerce-checkout"
      action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

      <div class="row g-4">

        <!-- Coluna Esquerda: Dados do Cliente -->
        <div class="col-lg-8">
          <div class="checkout-customer card border-0 shadow-sm">
            <div class="card-body p-4">

              <?php if (!is_user_logged_in() && (bool) WC()->checkout()->is_registration_enabled()): ?>
                <div class="checkout-login-register mb-4">
                  <?php woocommerce_checkout_login_form(); ?>
                </div>
              <?php endif; ?>

              <!-- Cupom de desconto no checkout -->
              <?php if (wc_coupons_enabled()): ?>
                <div class="checkout-coupon mb-4">
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <h5 class="mb-0"><i
                        class="fas fa-ticket-alt text-primary-custom me-2"></i><?php esc_html_e('Cupom de desconto', 'woocommerce'); ?>
                    </h5>
                    <a href="#" class="small text-decoration-none toggle-checkout-coupon" data-bs-toggle="collapse"
                      data-bs-target="#luveeCheckoutCoupon">Tenho um cupom</a>
                  </div>
                  <div id="luveeCheckoutCoupon" class="collapse">
                    <?php woocommerce_checkout_coupon_form(); ?>
                  </div>
                </div>
              <?php endif; ?>

              <?php do_action('woocommerce_checkout_before_customer_details'); ?>

              <div id="customer_details" class="row">
                <div class="col-12 col-xl-6 mb-4 mb-xl-0">
                  <div class="checkout-section">
                    <h5 class="section-title mb-3"><i
                        class="fas fa-user me-2 text-primary-custom"></i><?php esc_html_e('Dados de faturamento', 'woocommerce'); ?>
                    </h5>
                    <?php do_action('woocommerce_checkout_billing'); ?>
                  </div>
                </div>

                <div class="col-12 col-xl-6">
                  <div class="checkout-section">
                    <h5 class="section-title mb-3"><i
                        class="fas fa-truck me-2 text-primary-custom"></i><?php esc_html_e('Endereço de entrega', 'woocommerce'); ?>
                    </h5>
                    <?php do_action('woocommerce_checkout_shipping'); ?>
                  </div>
                </div>
              </div>

              <?php do_action('woocommerce_checkout_after_customer_details'); ?>

              <!-- Notas do pedido -->
              <div class="checkout-notes mt-4">
                <?php do_action('woocommerce_before_order_notes', $checkout); ?>
                <?php do_action('woocommerce_after_order_notes', $checkout); ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Coluna Direita: Resumo & Pagamento -->
        <div class="col-lg-4">
          <div class="checkout-review card border-0 shadow-sm sticky-top" style="top: 100px;">
            <div class="card-header bg-light border-0">
              <h5 class="mb-0"><i
                  class="fas fa-file-invoice-dollar me-2 text-primary-custom"></i><?php esc_html_e('Resumo do Pedido', 'woocommerce'); ?>
              </h5>
            </div>
            <div class="card-body p-4">
              <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

              <div id="order_review" class="woocommerce-checkout-review-order">
                <?php do_action('woocommerce_checkout_before_order_review'); ?>
                <?php do_action('woocommerce_checkout_order_review'); ?>
                <?php do_action('woocommerce_checkout_after_order_review'); ?>
              </div>

              <!-- Selos de confiança -->
              <div class="trust-badges mt-4">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                  <div class="badge-item d-flex align-items-center gap-2">
                    <i class="fas fa-shield-alt text-success"></i>
                    <small class="text-muted">Compra 100% segura</small>
                  </div>
                  <div class="badge-item d-flex align-items-center gap-2">
                    <i class="fas fa-lock text-primary"></i>
                    <small class="text-muted">Dados criptografados</small>
                  </div>
                  <div class="badge-item d-flex align-items-center gap-2">
                    <i class="fas fa-shipping-fast text-info"></i>
                    <small class="text-muted">Envio rápido</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </form>
  </div>
</div>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>