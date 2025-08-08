/**
 * Checkout Page - Luvee Perfumaria
 * Melhorias de UX no checkout
 */
(function ($) {
  'use strict';

  const LuveeCheckout = {
    init() {
      this.bindEvents();
      this.enhancePaymentSection();
      this.scrollOnErrors();
      this.copyBillingToShipping();
      
    },

    bindEvents() {
      // Toggle cupom
      $(document).on('click', '.toggle-checkout-coupon', function (e) {
        e.preventDefault();
        const target = $(this).data('bsTarget') || '#luveeCheckoutCoupon';
        $(target).collapse('toggle');
      });

      // Atualizar resumo ao alterar campos
      $(document.body).on('change', 'input, select, textarea', function () {
        // Apenas em campos do checkout
        if ($(this).closest('form.checkout').length) {
          $(document.body).trigger('update_checkout');
        }
      });

      // Realce do método de pagamento selecionado
      $(document).on('click', '#payment ul.wc_payment_methods li input', function () {
        $('#payment ul.wc_payment_methods li').removeClass('active');
        $(this).closest('li').addClass('active');
      });
    },

    enhancePaymentSection() {
      // Adiciona classes para visual moderno
      $('#payment').addClass('card border-0 shadow-sm');
      $('#payment .wc_payment_methods').addClass('list-unstyled');
      $('#payment .wc_payment_methods li').addClass('payment-method-item');
    },

    scrollOnErrors() {
      // Quando WooCommerce retornar erros, rolar para o topo
      $(document.body).on('checkout_error', function () {
        const $notices = $('.woocommerce-NoticeGroup, .woocommerce-error');
        if ($notices.length) {
          $('html, body').animate({ scrollTop: $notices.first().offset().top - 120 }, 400);
        }
      });
    },

    copyBillingToShipping() {
      // Se a opção "Enviar para um endereço diferente" estiver desmarcada,
      // sincroniza alguns campos de faturamento com entrega
      const sync = () => {
        const $shipToDiff = $('#ship-to-different-address-checkbox');
        if ($shipToDiff.length && !$shipToDiff.is(':checked')) {
          const map = [
            ['billing_first_name', 'shipping_first_name'],
            ['billing_last_name', 'shipping_last_name'],
            ['billing_address_1', 'shipping_address_1'],
            ['billing_address_2', 'shipping_address_2'],
            ['billing_city', 'shipping_city'],
            ['billing_postcode', 'shipping_postcode'],
            ['billing_state', 'shipping_state'],
            ['billing_country', 'shipping_country'],
            ['billing_phone', 'shipping_phone']
          ];
          map.forEach(([from, to]) => {
            const $from = $(`#${from}`);
            const $to = $(`#${to}`);
            if ($from.length && $to.length) {
              $to.val($from.val()).trigger('change');
            }
          });
        }
      };

      $(document).on('change', '#ship-to-different-address-checkbox', sync);
      $(document).on('change', '#billing_country, #billing_state', sync);
    }
  };

  $(function () {
    if ($('.luvee-checkout-page').length) {
      LuveeCheckout.init();
    }
  });

  window.LuveeCheckout = LuveeCheckout;
})(jQuery);


