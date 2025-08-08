/**
 * Cart Page JavaScript - Luvee Perfumaria
 * Interações avançadas para a página de carrinho
 */

(function($) {
    'use strict';
    // ========== CART PAGE MANAGER ========== //

    const LuveeCartPage = {
        
        // Configurações
        config: {
            ajaxUrl: luvee_cart_ajax?.ajax_url || '/wp-admin/admin-ajax.php',
            nonce: luvee_cart_ajax?.nonce || '',
            updateDelay: 800,
            animationDuration: 300
        },

        // Estado
        state: {
            isUpdating: false,
            updateTimers: {},
            originalQuantities: {}
        },

        // Inicialização
        init: function() {
            this.bindEvents();
            this.initQuantityControls();
            this.initTooltips();
            this.saveOriginalQuantities();
            this.updateFreeShippingProgress();
        },

        // ========== EVENT BINDING ========== //

        bindEvents: function() {
            const self = this;

            // Quantity change
            $(document).on('change input', '.quantity-wrapper input[type="number"]', function() {
                self.handleQuantityChange($(this));
            });

            // Remove item with confirmation
            $(document).on('click', '.remove-item-btn', function(e) {
                e.preventDefault();
                self.handleRemoveItem($(this));
            });

            // Update cart button
            $(document).on('click', 'button[name="update_cart"]', function(e) {
                e.preventDefault();
                self.updateCart();
            });

            // Coupon form
            $(document).on('click', 'button[name="apply_coupon"]', function(e) {
                e.preventDefault();
                self.applyCoupon();
            });

            // Quantity increment/decrement buttons
            $(document).on('click', '.qty-btn', function(e) {
                e.preventDefault();
                self.handleQuantityButton($(this));
            });

            // Auto-save on quantity change
            $(document).on('change', '.quantity-wrapper input', function() {
                const $input = $(this);
                const cartItemKey = $input.closest('.cart-item-card').data('cart-item-key');
                
                // Clear existing timer
                if (self.state.updateTimers[cartItemKey]) {
                    clearTimeout(self.state.updateTimers[cartItemKey]);
                }

                // Set new timer for auto-update
                self.state.updateTimers[cartItemKey] = setTimeout(function() {
                    self.updateSingleItem($input);
                }, self.config.updateDelay);
            });

            // Form validation
            $(document).on('submit', '.woocommerce-cart-form', function(e) {
                if (!self.validateCartForm()) {
                    e.preventDefault();
                    return false;
                }
            });

            // Prevent double submission
            $(document).on('click', '.checkout-button', function() {
                if (self.state.isUpdating) {
                    $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Processando...');
                    return false;
                }
            });
        },

        // ========== QUANTITY CONTROLS ========== //

        initQuantityControls: function() {
            $('.quantity-wrapper').each(function() {
                const $wrapper = $(this);
                const $input = $wrapper.find('input[type="number"]');
                
                if ($input.length && !$wrapper.hasClass('enhanced')) {
                    LuveeCartPage.enhanceQuantityInput($wrapper, $input);
                }
            });
        },

        enhanceQuantityInput: function($wrapper, $input) {
            const min = parseInt($input.attr('min')) || 0;
            const max = parseInt($input.attr('max')) || 999;
            const step = parseInt($input.attr('step')) || 1;

            // Add increment/decrement buttons
            const $controls = $(`
                <div class="qty-controls d-flex align-items-center justify-content-center">
                    <button type="button" class="qty-btn qty-minus btn btn-sm btn-outline-secondary" ${min >= parseInt($input.val()) ? 'disabled' : ''}>
                        <i class="fas fa-minus"></i>
                    </button>
                    <div class="qty-input-wrapper mx-2">
                        ${$input.prop('outerHTML')}
                    </div>
                    <button type="button" class="qty-btn qty-plus btn btn-sm btn-outline-secondary" ${max <= parseInt($input.val()) ? 'disabled' : ''}>
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            `);

            $input.remove();
            $wrapper.html($controls).addClass('enhanced');

            // Update button states
            this.updateQuantityButtons($wrapper);
        },

        handleQuantityButton: function($btn) {
            const $wrapper = $btn.closest('.quantity-wrapper');
            const $input = $wrapper.find('input[type="number"]');
            const currentVal = parseInt($input.val()) || 0;
            const min = parseInt($input.attr('min')) || 0;
            const max = parseInt($input.attr('max')) || 999;
            const step = parseInt($input.attr('step')) || 1;

            let newVal = currentVal;

            if ($btn.hasClass('qty-minus')) {
                newVal = Math.max(min, currentVal - step);
            } else if ($btn.hasClass('qty-plus')) {
                newVal = Math.min(max, currentVal + step);
            }

            if (newVal !== currentVal) {
                $input.val(newVal).trigger('change');
                this.updateQuantityButtons($wrapper);
                this.animateQuantityChange($input, currentVal, newVal);
            }
        },

        updateQuantityButtons: function($wrapper) {
            const $input = $wrapper.find('input[type="number"]');
            const $minusBtn = $wrapper.find('.qty-minus');
            const $plusBtn = $wrapper.find('.qty-plus');
            
            const currentVal = parseInt($input.val()) || 0;
            const min = parseInt($input.attr('min')) || 0;
            const max = parseInt($input.attr('max')) || 999;

            $minusBtn.prop('disabled', currentVal <= min);
            $plusBtn.prop('disabled', currentVal >= max);
        },

        animateQuantityChange: function($input, oldVal, newVal) {
            const $card = $input.closest('.cart-item-card');
            
            $card.addClass('cart-item-updating');
            
            setTimeout(() => {
                $card.removeClass('cart-item-updating').addClass('cart-item-updated');
                setTimeout(() => {
                    $card.removeClass('cart-item-updated');
                }, 600);
            }, 200);
        },

        // ========== CART OPERATIONS ========== //

        handleQuantityChange: function($input) {
            const cartItemKey = $input.closest('.cart-item-card').data('cart-item-key');
            const newQuantity = parseInt($input.val()) || 0;
            const originalQuantity = this.state.originalQuantities[cartItemKey] || 0;

            // Validate quantity
            const min = parseInt($input.attr('min')) || 0;
            const max = parseInt($input.attr('max')) || 999;

            if (newQuantity < min) {
                $input.val(min);
                this.showNotification('Quantidade mínima é ' + min, 'warning');
                return;
            }

            if (newQuantity > max) {
                $input.val(max);
                this.showNotification('Quantidade máxima é ' + max, 'warning');
                return;
            }

            // Update quantity buttons
            this.updateQuantityButtons($input.closest('.quantity-wrapper'));

            // Show update indicator if quantity changed
            if (newQuantity !== originalQuantity) {
                this.showUpdateIndicator($input.closest('.cart-item-card'));
            }
        },

        updateSingleItem: function($input) {
            if (this.state.isUpdating) return;

            const $card = $input.closest('.cart-item-card');
            const cartItemKey = $card.data('cart-item-key');
            const quantity = parseInt($input.val()) || 0;

            this.state.isUpdating = true;
            $card.addClass('cart-loading');

            $.ajax({
                url: this.config.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'luvee_update_cart_item',
                    cart_item_key: cartItemKey,
                    quantity: quantity,
                    nonce: this.config.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.handleUpdateSuccess(response.data, $card);
                    } else {
                        this.handleUpdateError(response.data.message, $card);
                    }
                },
                error: () => {
                    this.handleUpdateError('Erro de conexão', $card);
                },
                complete: () => {
                    this.state.isUpdating = false;
                    $card.removeClass('cart-loading');
                }
            });
        },

        updateCart: function() {
            if (this.state.isUpdating) return;

            this.state.isUpdating = true;
            $('.cart-items-container').addClass('cart-loading');

            // Use WooCommerce native form submission with AJAX enhancement
            const $form = $('.woocommerce-cart-form');
            const formData = $form.serialize();

            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: formData + '&update_cart=1',
                success: (response) => {
                    // Reload cart fragments
                    this.reloadCartPage();
                },
                error: () => {
                    this.showNotification('Erro ao atualizar carrinho', 'error');
                },
                complete: () => {
                    this.state.isUpdating = false;
                    $('.cart-items-container').removeClass('cart-loading');
                }
            });
        },

        handleRemoveItem: function($btn) {
            const $card = $btn.closest('.cart-item-card');
            const productName = $card.find('.product-name').text().trim();
            const removeUrl = $btn.attr('href');

            // Confirmation modal
            this.showConfirmModal(
                'Remover Item',
                `Deseja remover "${productName}" do carrinho?`,
                'Remover',
                'Cancelar',
                () => {
                    this.executeRemoveItem($card, removeUrl);
                }
            );
        },

        executeRemoveItem: function($card, removeUrl) {
            $card.addClass('cart-loading');

            $.ajax({
                url: removeUrl,
                type: 'GET',
                success: (response) => {
                    $card.addClass('cart-item-removed');
                    this.showNotification('Item removido do carrinho', 'success');
                    
                    setTimeout(() => {
                        this.reloadCartPage();
                    }, 500);
                },
                error: () => {
                    $card.removeClass('cart-loading');
                    this.showNotification('Erro ao remover item', 'error');
                }
            });
        },

        // ========== COUPON MANAGEMENT ========== //

        applyCoupon: function() {
            const $couponInput = $('#coupon_code');
            const couponCode = $couponInput.val().trim();

            if (!couponCode) {
                this.showNotification('Digite o código do cupom', 'warning');
                $couponInput.focus();
                return;
            }

            $couponInput.prop('disabled', true);
            const $btn = $('button[name="apply_coupon"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Aplicando...');

            $.ajax({
                url: this.config.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'luvee_apply_coupon',
                    coupon_code: couponCode,
                    nonce: this.config.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.showNotification('Cupom aplicado com sucesso!', 'success');
                        this.reloadCartPage();
                    } else {
                        this.showNotification(response.data.message || 'Cupom inválido', 'error');
                    }
                },
                error: () => {
                    this.showNotification('Erro ao aplicar cupom', 'error');
                },
                complete: () => {
                    $couponInput.prop('disabled', false);
                    $btn.prop('disabled', false).html('<i class="fas fa-check me-1"></i>Aplicar cupom');
                }
            });
        },

        // ========== UTILITY FUNCTIONS ========== //

        saveOriginalQuantities: function() {
            $('.cart-item-card').each((index, element) => {
                const $card = $(element);
                const cartItemKey = $card.data('cart-item-key');
                const quantity = parseInt($card.find('input[type="number"]').val()) || 0;
                this.state.originalQuantities[cartItemKey] = quantity;
            });
        },

        updateFreeShippingProgress: function() {
            const $progress = $('.free-shipping-progress');
            if (!$progress.length) return;

            const freeShippingAmount = 199;
            const currentTotal = this.getCurrentSubtotal();
            const remaining = freeShippingAmount - currentTotal;
            const percentage = Math.min(100, (currentTotal / freeShippingAmount) * 100);

            $progress.find('.progress-bar').css('width', percentage + '%');
            
            if (remaining <= 0) {
                $progress.removeClass('bg-info bg-opacity-10').addClass('bg-success bg-opacity-10');
                $progress.find('.text-info').removeClass('text-info').addClass('text-success')
                    .html('<i class="fas fa-check me-2"></i><strong>Parabéns! Você ganhou frete grátis!</strong>');
            }
        },

        getCurrentSubtotal: function() {
            // Extract subtotal from cart totals
            const subtotalText = $('.cart-subtotal strong').text();
            const matches = subtotalText.match(/R\$\s*([\d.,]+)/);
            if (matches) {
                return parseFloat(matches[1].replace(',', '.').replace('.', ''));
            }
            return 0;
        },

        validateCartForm: function() {
            let isValid = true;
            const errors = [];

            $('.quantity-wrapper input[type="number"]').each(function() {
                const $input = $(this);
                const value = parseInt($input.val()) || 0;
                const min = parseInt($input.attr('min')) || 0;
                const max = parseInt($input.attr('max')) || 999;

                if (value < min || value > max) {
                    isValid = false;
                    errors.push(`Quantidade inválida para ${$input.closest('.cart-item-card').find('.product-name').text()}`);
                }
            });

            if (!isValid) {
                this.showNotification(errors.join('<br>'), 'error');
            }

            return isValid;
        },

        reloadCartPage: function() {
            // Use WooCommerce cart fragments or reload page
            if (typeof wc_cart_fragments_params !== 'undefined') {
                $(document.body).trigger('wc_fragment_refresh');
            } else {
                window.location.reload();
            }
        },

        showUpdateIndicator: function($card) {
            const $indicator = $('<div class="update-indicator"><i class="fas fa-exclamation-circle text-warning me-1"></i>Alterado</div>');
            $card.find('.price-and-actions').prepend($indicator);
            
            setTimeout(() => {
                $indicator.fadeOut(() => $indicator.remove());
            }, 3000);
        },

        // ========== UI HELPERS ========== //

        initTooltips: function() {
            // Initialize Bootstrap tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
            
            // Add custom tooltips
            $('.remove-item-btn').attr('title', 'Remover item do carrinho');
            $('.qty-btn').each(function() {
                const $btn = $(this);
                if ($btn.hasClass('qty-minus')) {
                    $btn.attr('title', 'Diminuir quantidade');
                } else {
                    $btn.attr('title', 'Aumentar quantidade');
                }
            });
        },

        showNotification: function(message, type = 'info') {
            const icons = {
                success: 'fas fa-check-circle',
                error: 'fas fa-exclamation-circle',
                warning: 'fas fa-exclamation-triangle',
                info: 'fas fa-info-circle'
            };

            const $notification = $(`
                <div class="cart-notification alert alert-${type} alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999; max-width: 400px;">
                    <i class="${icons[type]} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);

            $('body').append($notification);

            setTimeout(() => {
                $notification.alert('close');
            }, 5000);
        },

        showConfirmModal: function(title, message, confirmText, cancelText, onConfirm) {
            const modalId = 'cartConfirmModal';
            
            // Remove existing modal
            $(`#${modalId}`).remove();

            const $modal = $(`
                <div class="modal fade" id="${modalId}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">${title}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>${message}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">${cancelText}</button>
                                <button type="button" class="btn btn-danger confirm-action">${confirmText}</button>
                            </div>
                        </div>
                    </div>
                </div>
            `);

            $('body').append($modal);

            $modal.find('.confirm-action').on('click', function() {
                $modal.modal('hide');
                onConfirm();
            });

            $modal.modal('show');

            $modal.on('hidden.bs.modal', function() {
                $modal.remove();
            });
        },

        handleUpdateSuccess: function(data, $card) {
            this.showNotification('Carrinho atualizado com sucesso', 'success');
            $card.addClass('cart-item-updated');
            
            // Update totals if provided
            if (data.fragments) {
                Object.keys(data.fragments).forEach(selector => {
                    $(selector).html(data.fragments[selector]);
                });
            }

            this.updateFreeShippingProgress();
            
            setTimeout(() => {
                $card.removeClass('cart-item-updated');
            }, 600);
        },

        handleUpdateError: function(message, $card) {
            this.showNotification(message || 'Erro ao atualizar carrinho', 'error');
            
            // Restore original quantity
            const cartItemKey = $card.data('cart-item-key');
            const originalQty = this.state.originalQuantities[cartItemKey];
            if (originalQty) {
                $card.find('input[type="number"]').val(originalQty);
            }
        }
    };

    // ========== INITIALIZATION ========== //

    $(document).ready(function() {
        const isCartPage = $('.luvee-cart-page').length > 0;
        if (isCartPage) {
            LuveeCartPage.init();
        }
    });

    // Make it globally available
    window.LuveeCartPage = LuveeCartPage;

})(jQuery);
