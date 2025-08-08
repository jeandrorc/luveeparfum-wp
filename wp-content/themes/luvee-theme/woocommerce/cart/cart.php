<?php
/**
 * Cart Page - Luvee Perfumaria
 * Página de carrinho moderna e funcional
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart'); ?>

<div class="luvee-cart-page">
  <div class="container-fluid container-xxl py-5">

    <!-- Cart Header -->
    <div class="cart-header mb-5">
      <div class="row align-items-center">
        <div class="col-md-8">
          <h1 class="cart-title mb-2">
            <i class="fas fa-shopping-bag me-3 text-primary-custom"></i>
            Seu Carrinho de Compras
          </h1>
          <p class="cart-subtitle text-muted mb-0">
            Revise seus produtos selecionados e finalize sua compra
          </p>
        </div>
        <div class="col-md-4 text-md-end">
          <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>
            Continuar Comprando
          </a>
        </div>
      </div>
    </div>

    <?php if (WC()->cart->is_empty()): ?>

      <!-- Empty Cart -->
      <div class="cart-empty text-center py-5">
        <div class="empty-cart-icon mb-4">
          <i class="fas fa-shopping-cart text-muted" style="font-size: 4rem;"></i>
        </div>
        <h3 class="mb-3">Seu carrinho está vazio</h3>
        <p class="text-muted mb-4">
          Que tal descobrir nossas fragrâncias exclusivas?
        </p>
        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-primary btn-lg">
          <i class="fas fa-gem me-2"></i>
          Descobrir Perfumes
        </a>
      </div>

    <?php else: ?>

      <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
        <?php do_action('woocommerce_before_cart_table'); ?>

        <div class="row g-4">

          <!-- Cart Items -->
          <div class="col-lg-8">
            <div class="cart-items-section">
              <div class="cart-section-header d-flex align-items-center justify-content-between mb-4">
                <h4 class="section-title mb-0">
                  <i class="fas fa-list-ul me-2 text-primary-custom"></i>
                  Produtos Selecionados
                </h4>
                <span class="items-count badge bg-primary">
                  <?php echo WC()->cart->get_cart_contents_count(); ?>
                  <?php echo _n('item', 'itens', WC()->cart->get_cart_contents_count(), 'woocommerce'); ?>
                </span>
              </div>

              <div class="cart-items-container">
                <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item): ?>
                  <?php
                  $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                  $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                  if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)):
                    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                    ?>

                    <div class="cart-item-card mb-3" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
                      <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                          <div class="row align-items-center">

                            <!-- Product Image -->
                            <div class="col-md-2">
                              <div class="product-image">
                                <?php
                                $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('woocommerce_thumbnail'), $cart_item, $cart_item_key);
                                if (!$product_permalink) {
                                  echo $thumbnail;
                                } else {
                                  printf('<a href="%s" class="product-image-link">%s</a>', esc_url($product_permalink), $thumbnail);
                                }
                                ?>
                              </div>
                            </div>

                            <!-- Product Info -->
                            <div class="col-md-4">
                              <div class="product-info">
                                <h6 class="product-name mb-2">
                                  <?php
                                  if (!$product_permalink) {
                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                  } else {
                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s" class="text-decoration-none">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                  }
                                  ?>
                                </h6>

                                <?php
                                // Meta data.
                                echo wc_get_formatted_cart_item_data($cart_item);

                                // Backorder notification.
                                if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                  echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
                                }
                                ?>

                                <!-- Product SKU -->
                                <?php if ($_product->get_sku()): ?>
                                  <small class="text-muted">
                                    SKU: <?php echo esc_html($_product->get_sku()); ?>
                                  </small>
                                <?php endif; ?>
                              </div>
                            </div>

                            <!-- Quantity Controls -->
                            <div class="col-md-3">
                              <div class="quantity-controls">
                                <label class="small text-muted mb-2 d-block">Quantidade:</label>
                                <div class="quantity-wrapper">
                                  <?php
                                  if ($_product->is_sold_individually()) {
                                    $min_quantity = 1;
                                    $max_quantity = 1;
                                  } else {
                                    $min_quantity = 0;
                                    $max_quantity = $_product->get_max_purchase_quantity();
                                  }

                                  $product_quantity = woocommerce_quantity_input(
                                    array(
                                      'input_name' => "cart[{$cart_item_key}][qty]",
                                      'input_value' => $cart_item['quantity'],
                                      'max_value' => $max_quantity,
                                      'min_value' => $min_quantity,
                                      'product_name' => $_product->get_name(),
                                      'classes' => 'form-control form-control-sm text-center',
                                    ),
                                    $_product,
                                    false
                                  );

                                  echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
                                  ?>
                                </div>
                              </div>
                            </div>

                            <!-- Price & Remove -->
                            <div class="col-md-3">
                              <div class="price-and-actions text-md-end">
                                <div class="item-price mb-3">
                                  <div class="price-display">
                                    <?php
                                    echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                                    ?>
                                  </div>
                                  <?php if ($cart_item['quantity'] > 1): ?>
                                    <small class="text-muted d-block">
                                      Total:
                                      <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                                    </small>
                                  <?php endif; ?>
                                </div>

                                <!-- Remove Button -->
                                <div class="remove-item">
                                  <?php
                                  echo apply_filters(
                                    'woocommerce_cart_item_remove_link',
                                    sprintf(
                                      '<a href="%s" class="btn btn-sm btn-outline-danger remove-item-btn" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="fas fa-trash-alt me-1"></i>Remover</a>',
                                      esc_url(wc_get_cart_remove_url($cart_item_key)),
                                      esc_html__('Remove this item', 'woocommerce'),
                                      esc_attr($product_id),
                                      esc_attr($_product->get_sku())
                                    ),
                                    $cart_item_key
                                  );
                                  ?>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  <?php endif; ?>
                <?php endforeach; ?>
              </div>

              <!-- Cart Actions -->
              <div class="cart-actions mt-4">
                <div class="row align-items-center">
                  <div class="col-md-6">
                    <?php if (wc_coupons_enabled()): ?>
                      <div class="coupon-section">
                        <h6 class="mb-3">
                          <i class="fas fa-ticket-alt me-2 text-primary-custom"></i>
                          Cupom de Desconto
                        </h6>
                        <div class="input-group">
                          <input type="text" name="coupon_code" class="form-control" id="coupon_code" value=""
                            placeholder="Código do cupom" aria-label="Coupon code" />
                          <button type="submit" class="btn btn-outline-primary" name="apply_coupon"
                            value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>">
                            <i class="fas fa-check me-1"></i>
                            <?php esc_attr_e('Apply coupon', 'woocommerce'); ?>
                          </button>
                        </div>
                      </div>
                    <?php endif; ?>
                  </div>
                  <div class="col-md-6 text-md-end">
                    <button type="submit" class="btn btn-primary" name="update_cart"
                      value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>">
                      <i class="fas fa-sync-alt me-2"></i>
                      <?php esc_attr_e('Update cart', 'woocommerce'); ?>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Cart Totals -->
          <div class="col-lg-4">
            <div class="cart-totals-section">
              <div class="cart-totals-card card border-0 shadow-sm sticky-top" style="top: 100px;">
                <div class="card-header bg-light border-0">
                  <h5 class="mb-0">
                    <i class="fas fa-calculator me-2 text-primary-custom"></i>
                    Resumo do Pedido
                  </h5>
                </div>
                <div class="card-body">
                  <?php do_action('woocommerce_before_cart_collaterals'); ?>

                  <div
                    class="cart_totals <?php echo (WC()->customer->has_calculated_shipping()) ? 'calculated_shipping' : ''; ?>">
                    <?php do_action('woocommerce_before_cart_totals'); ?>

                    <div class="shop_table shop_table_responsive">
                      <?php foreach (WC()->cart->get_coupons() as $code => $coupon): ?>
                        <div
                          class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?> d-flex justify-content-between align-items-center mb-3 p-3 bg-success bg-opacity-10 rounded">
                          <div>
                            <i class="fas fa-tag text-success me-2"></i>
                            <strong><?php echo esc_html($code); ?></strong>
                          </div>
                          <div class="d-flex align-items-center">
                            <span class="text-success me-2"><?php wc_cart_totals_coupon_html($coupon); ?></span>
                            <a href="<?php echo esc_url(add_query_arg('remove_coupon', urlencode($code), wc_get_cart_url())); ?>"
                              class="btn btn-sm btn-outline-danger"
                              aria-label="<?php echo esc_attr__('Remove coupon', 'woocommerce'); ?>">
                              <i class="fas fa-times"></i>
                            </a>
                          </div>
                        </div>
                      <?php endforeach; ?>

                      <div class="cart-subtotal d-flex justify-content-between mb-3">
                        <span>Subtotal:</span>
                        <strong><?php wc_cart_totals_subtotal_html(); ?></strong>
                      </div>

                      <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()): ?>
                        <?php do_action('woocommerce_cart_totals_before_shipping'); ?>
                        <div class="shipping-section mb-3">
                          <div class="shipping-header d-flex justify-content-between mb-2">
                            <span>Frete:</span>
                            <span><?php wc_cart_totals_shipping_html(); ?></span>
                          </div>
                          <?php if (apply_filters('woocommerce_shipping_show_shipping_calculator', true, 0, WC()->cart->get_displayed_subtotal())): ?>
                            <div class="shipping-calculator-form">
                              <?php woocommerce_shipping_calculator(); ?>
                            </div>
                          <?php endif; ?>
                        </div>
                        <?php do_action('woocommerce_cart_totals_after_shipping'); ?>
                      <?php endif; ?>

                      <?php foreach (WC()->cart->get_fees() as $fee): ?>
                        <div class="fee d-flex justify-content-between mb-2">
                          <span><?php echo esc_html($fee->name); ?>:</span>
                          <span><?php wc_cart_totals_fee_html($fee); ?></span>
                        </div>
                      <?php endforeach; ?>

                      <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()): ?>
                        <?php if ('itemized' === get_option('woocommerce_tax_total_display')): ?>
                          <?php foreach (WC()->cart->get_tax_totals() as $code => $tax): ?>
                            <div
                              class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?> d-flex justify-content-between mb-2">
                              <span><?php echo esc_html($tax->label); ?>:</span>
                              <span><?php echo wp_kses_post($tax->formatted_amount); ?></span>
                            </div>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <div class="tax-total d-flex justify-content-between mb-2">
                            <span><?php echo esc_html(WC()->countries->tax_or_vat()); ?>:</span>
                            <span><?php wc_cart_totals_taxes_total_html(); ?></span>
                          </div>
                        <?php endif; ?>
                      <?php endif; ?>

                      <?php do_action('woocommerce_cart_totals_before_order_total'); ?>

                      <hr>
                      <div class="order-total d-flex justify-content-between mb-4">
                        <span class="h6 mb-0">Total:</span>
                        <strong class="h5 mb-0 text-primary-custom"><?php wc_cart_totals_order_total_html(); ?></strong>
                      </div>

                      <?php do_action('woocommerce_cart_totals_after_order_total'); ?>
                    </div>

                    <!-- Free Shipping Progress -->
                    <?php
                    $free_shipping_amount = 199; // R$ 199 para frete grátis
                    $current_total = WC()->cart->get_subtotal();
                    $remaining = $free_shipping_amount - $current_total;
                    if ($remaining > 0):
                      ?>
                      <div class="free-shipping-progress mb-4 p-3 bg-info bg-opacity-10 rounded">
                        <div class="d-flex align-items-center mb-2">
                          <i class="fas fa-shipping-fast text-info me-2"></i>
                          <small class="text-info">
                            Falta <strong>R$ <?php echo number_format($remaining, 2, ',', '.'); ?></strong> para frete
                            grátis!
                          </small>
                        </div>
                        <div class="progress" style="height: 6px;">
                          <div class="progress-bar bg-info" role="progressbar"
                            style="width: <?php echo min(100, ($current_total / $free_shipping_amount) * 100); ?>%"></div>
                        </div>
                      </div>
                    <?php endif; ?>

                    <!-- Checkout Button -->
                    <div class="wc-proceed-to-checkout">
                      <a href="<?php echo esc_url(wc_get_checkout_url()); ?>"
                        class="btn btn-primary btn-lg w-100 checkout-button">
                        <i class="fas fa-lock me-2"></i>
                        Finalizar Compra
                      </a>
                    </div>

                    <!-- Security Info -->
                    <div class="security-info text-center mt-3">
                      <small class="text-muted d-flex align-items-center justify-content-center">
                        <i class="fas fa-shield-alt text-success me-2"></i>
                        Compra 100% segura e protegida
                      </small>
                    </div>

                    <?php do_action('woocommerce_after_cart_totals'); ?>
                  </div>

                  <?php do_action('woocommerce_after_cart_collaterals'); ?>
                </div>
              </div>
            </div>
          </div>
        </div>

        <?php do_action('woocommerce_after_cart_table'); ?>
      </form>

    <?php endif; ?>

    <!-- Recommendations Section -->
    <?php if (!WC()->cart->is_empty()): ?>
      <div class="cart-recommendations mt-5">
        <div class="section-header text-center mb-4">
          <h4>Você também pode gostar</h4>
          <p class="text-muted">Produtos selecionados especialmente para você</p>
        </div>

        <?php
        // Usar o product-section para mostrar produtos relacionados
        luvee_get_template_part('product-section', null, array(
          'title' => '',
          'type' => 'recent',
          'columns' => 4,
          'rows' => 1,
          'show_view_all' => false,
          'section_id' => 'cart-recommendations'
        ));
        ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php do_action('woocommerce_after_cart'); ?>