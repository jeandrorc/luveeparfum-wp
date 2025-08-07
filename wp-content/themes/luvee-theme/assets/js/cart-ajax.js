/**
 * Luvee Cart AJAX System
 * Sistema completo de carrinho com AJAX, mini-cart e atualizações em tempo real
 */

(function($) {
    'use strict';

    const LuveeCart = {
        
        // Configuration
        config: {
            ajaxUrl: luvee_cart_ajax.ajax_url,
            nonce: luvee_cart_ajax.nonce,
            cartUrl: luvee_cart_ajax.cart_url,
            animationDuration: 300,
            notificationDuration: 3000
        },

        // Initialize
        init: function() {
            this.bindEvents();
            this.initMiniCart();
            this.updateCartFragments();
        },

        // Bind all events
        bindEvents: function() {
            const self = this;

            // Add to cart buttons
            $(document).on('click', '.btn-add-to-cart, .single_add_to_cart_button', function(e) {
                e.preventDefault();
                self.addToCart($(this));
            });

            // Mini-cart toggle
            $(document).on('click', '.cart-link, .mini-cart-toggle', function(e) {
                e.preventDefault();
                self.toggleMiniCart();
            });

            // Mini-cart quantity updates
            $(document).on('change', '.mini-cart-quantity', function() {
                const key = $(this).data('cart-key');
                const quantity = parseInt($(this).val());
                self.updateCartItem(key, quantity);
            });

            // Remove item from mini-cart
            $(document).on('click', '.mini-cart-remove', function(e) {
                e.preventDefault();
                const key = $(this).data('cart-key');
                self.removeCartItem(key);
            });

            // Close mini-cart when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.mini-cart-container').length && !$(e.target).closest('.cart-link').length) {
                    self.closeMiniCart();
                }
            });

            // Keyboard navigation
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    self.closeMiniCart();
                }
            });

            // WooCommerce fragments refresh
            $(document.body).on('wc_fragments_refreshed', function() {
                self.updateCartCount();
                self.refreshMiniCart();
            });
        },

        // Initialize mini-cart
        initMiniCart: function() {
            if (!$('.mini-cart-container').length) {
                this.createMiniCartHTML();
            }
            // Initialize cart count and fragments
            this.updateCartFragments();
        },

        // Create mini-cart HTML structure
        createMiniCartHTML: function() {
            const miniCartHTML = `
                <div class="mini-cart-container">
                    <div class="mini-cart-overlay"></div>
                    <div class="mini-cart-sidebar">
                        <div class="mini-cart-header">
                            <h5 class="mini-cart-title">
                                <i class="fas fa-shopping-bag me-2"></i>
                                Meu Carrinho
                            </h5>
                            <button type="button" class="mini-cart-close" aria-label="Fechar carrinho">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="mini-cart-content">
                            <div class="mini-cart-loading text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Carregando...</span>
                                </div>
                            </div>
                        </div>
                        <div class="mini-cart-footer">
                            <div class="mini-cart-total mb-3">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semibold">Total:</span>
                                    <span class="fw-bold text-primary cart-total">R$ 0,00</span>
                                </div>
                            </div>
                            <div class="mini-cart-actions">
                                <a href="${this.config.cartUrl}" class="btn btn-outline-primary w-100 mb-2">
                                    Ver Carrinho
                                </a>
                                <a href="${luvee_cart_ajax.checkout_url}" class="btn btn-primary w-100">
                                    Finalizar Compra
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('body').append(miniCartHTML);
            
            // Bind close events
            $('.mini-cart-close, .mini-cart-overlay').on('click', () => this.closeMiniCart());
        },

        // Add product to cart
        addToCart: function($btn) {
            const self = this;
            const productId = $btn.data('product-id') || $btn.closest('form.cart').find('[name="add-to-cart"]').val();
            const quantity = $btn.closest('form.cart').find('[name="quantity"]').val() || 1;
            const variation = this.getVariationData($btn);
            
            if (!productId) {
                this.showNotification('Produto não encontrado', 'error');
                return;
            }

            // UI feedback - novo sistema de estados
            const productName = $btn.data('product-name') || 'Produto';
            $btn.prop('disabled', true).addClass('loading');
            
            // Mostrar estado de loading
            $btn.find('.btn-text').addClass('d-none');
            $btn.find('.btn-success-text').addClass('d-none');
            $btn.find('.btn-loading').removeClass('d-none');

            // AJAX request
            $.ajax({
                url: this.config.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'luvee_add_to_cart',
                    product_id: productId,
                    quantity: quantity,
                    variation_id: variation.variation_id || '',
                    variation: variation.attributes || {},
                    nonce: this.config.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Success feedback com novo sistema
                        $btn.removeClass('loading').addClass('success');
                        $btn.find('.btn-loading').addClass('d-none');
                        $btn.find('.btn-success-text').removeClass('d-none');
                        
                        // Update cart UI
                        self.updateCartCount(response.data.cart_count);
                        self.updateCartFragments();
                        
                        // Show notification
                        self.showNotification(`${productName} adicionado ao carrinho!`, 'success');
                        
                        // Show mini-cart briefly
                        setTimeout(() => {
                            self.showMiniCart();
                        }, 500);
                        
                        // Reset button after delay
                        setTimeout(() => {
                            $btn.removeClass('success').prop('disabled', false);
                            $btn.find('.btn-success-text').addClass('d-none');
                            $btn.find('.btn-text').removeClass('d-none');
                        }, 2000);
                        
                    } else {
                        // Error feedback com novo sistema
                        $btn.removeClass('loading').addClass('error');
                        $btn.find('.btn-loading').addClass('d-none');
                        $btn.find('.btn-text').removeClass('d-none').html('<i class="fas fa-times me-1"></i>Erro');
                        
                        self.showNotification(response.data.message || 'Erro ao adicionar produto', 'error');
                        
                        // Reset button
                        setTimeout(() => {
                            $btn.removeClass('error').prop('disabled', false);
                            $btn.find('.btn-text').html('<i class="fas fa-shopping-cart me-1"></i>Adicionar ao Carrinho');
                        }, 2000);
                    }
                },
                error: function() {
                    // Network error com novo sistema
                    $btn.removeClass('loading').addClass('error');
                    $btn.find('.btn-loading').addClass('d-none');
                    $btn.find('.btn-text').removeClass('d-none').html('<i class="fas fa-wifi me-1"></i>Sem Conexão');
                    
                    self.showNotification('Erro de conexão. Tente novamente.', 'error');
                    
                    // Reset button
                    setTimeout(() => {
                        $btn.removeClass('error').prop('disabled', false);
                        $btn.find('.btn-text').html('<i class="fas fa-shopping-cart me-1"></i>Adicionar ao Carrinho');
                    }, 2000);
                }
            });
        },

        // Get variation data for variable products
        getVariationData: function($btn) {
            const $form = $btn.closest('form.cart');
            const data = {
                variation_id: '',
                attributes: {}
            };

            if ($form.length) {
                // Get variation ID
                const variationId = $form.find('[name="variation_id"]').val();
                if (variationId) {
                    data.variation_id = variationId;
                }

                // Get variation attributes
                $form.find('[name^="attribute_"]').each(function() {
                    const name = $(this).attr('name');
                    const value = $(this).val();
                    if (name && value) {
                        data.attributes[name] = value;
                    }
                });
            }

            return data;
        },

        // Handle add to cart errors
        handleError: function($btn, originalText, message) {
            $btn.html('<i class="fas fa-times me-2"></i>Erro');
            $btn.removeClass('btn-add-to-cart btn-primary').addClass('btn-danger');
            
            this.showNotification(message, 'error');
            
            setTimeout(() => {
                $btn.html(originalText);
                $btn.removeClass('btn-danger').addClass('btn-add-to-cart btn-primary');
                $btn.prop('disabled', false);
            }, 2000);
        },

        // Update cart item quantity
        updateCartItem: function(cartKey, quantity) {
            const self = this;
            
            if (quantity <= 0) {
                this.removeCartItem(cartKey);
                return;
            }

            $.ajax({
                url: this.config.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'luvee_update_cart_item',
                    cart_key: cartKey,
                    quantity: quantity,
                    nonce: this.config.nonce
                },
                success: function(response) {
                    if (response.success) {
                        self.refreshMiniCart();
                        self.updateCartFragments();
                        self.showNotification('Carrinho atualizado', 'success');
                    } else {
                        self.showNotification(response.data.message || 'Erro ao atualizar carrinho', 'error');
                    }
                },
                error: function() {
                    self.showNotification('Erro de conexão', 'error');
                }
            });
        },

        // Remove item from cart
        removeCartItem: function(cartKey) {
            const self = this;
            
            $.ajax({
                url: this.config.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'luvee_remove_cart_item',
                    cart_key: cartKey,
                    nonce: this.config.nonce
                },
                success: function(response) {
                    if (response.success) {
                        self.refreshMiniCart();
                        self.updateCartFragments();
                        self.showNotification('Item removido do carrinho', 'success');
                    } else {
                        self.showNotification(response.data.message || 'Erro ao remover item', 'error');
                    }
                },
                error: function() {
                    self.showNotification('Erro de conexão', 'error');
                }
            });
        },

        // Show mini-cart
        showMiniCart: function() {
            this.refreshMiniCart();
            $('.mini-cart-container').addClass('show');
            $('body').addClass('mini-cart-open');
        },

        // Toggle mini-cart
        toggleMiniCart: function() {
            if ($('.mini-cart-container').hasClass('show')) {
                this.closeMiniCart();
            } else {
                this.showMiniCart();
            }
        },

        // Close mini-cart
        closeMiniCart: function() {
            $('.mini-cart-container').removeClass('show');
            $('body').removeClass('mini-cart-open');
        },

        // Refresh mini-cart content
        refreshMiniCart: function() {
            const self = this;
            const $content = $('.mini-cart-content');
            
            $content.html('<div class="mini-cart-loading text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Carregando...</span></div></div>');
            
            $.ajax({
                url: this.config.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'luvee_get_mini_cart',
                    nonce: this.config.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $content.html(response.data.content);
                        // Usar o total já limpo do backend
                        $('.cart-total').text(response.data.total || 'R$ 0,00');
                        self.updateCartCount(response.data.count);
                        
                        // Debug para verificar dados
                        console.log('Cart data:', response.data);
                    } else {
                        $content.html('<div class="text-center py-4"><p>Erro ao carregar carrinho</p></div>');
                    }
                },
                error: function() {
                    $content.html('<div class="text-center py-4"><p>Erro de conexão</p></div>');
                }
            });
        },

        // Update cart count in header
        updateCartCount: function(count) {
            const $cartCount = $('.cart-count');
            
            if (typeof count !== 'undefined') {
                if (count > 0) {
                    $cartCount.text(count).show();
                } else {
                    $cartCount.hide();
                }
            }
        },

        // Update WooCommerce cart fragments
        updateCartFragments: function() {
            $(document.body).trigger('wc_fragment_refresh');
        },

        // Clean price text from HTML tags and entities
        cleanPrice: function(priceText) {
            if (!priceText) return 'R$ 0,00';
            
            // Convert to string and remove HTML tags
            let cleaned = String(priceText).replace(/<[^>]*>/g, '');
            
            // Decode HTML entities
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = cleaned;
            cleaned = tempDiv.textContent || tempDiv.innerText || cleaned;
            
            // Remove extra whitespace and non-breaking spaces
            cleaned = cleaned.replace(/\s+/g, ' ').replace(/&nbsp;/g, ' ').trim();
            
            // If it doesn't have R$ but has numbers, assume it's a price
            if (cleaned && !cleaned.includes('R$') && /\d/.test(cleaned)) {
                cleaned = 'R$ ' + cleaned;
            }
            
            // Ensure proper format
            if (cleaned && !cleaned.match(/^R\$\s*\d/)) {
                return 'R$ 0,00';
            }
            
            return cleaned || 'R$ 0,00';
        },

        // Show notification
        showNotification: function(message, type = 'success') {
            // Remove existing notifications
            $('.luvee-notification').remove();
            
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
            
            const notification = $(`
                <div class="luvee-notification alert ${alertClass} alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                    <i class="${icon} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
            
            $('body').append(notification);
            
            // Auto remove
            setTimeout(() => {
                notification.alert('close');
            }, this.config.notificationDuration);
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        // Check if cart AJAX is configured
        if (typeof luvee_cart_ajax !== 'undefined') {
            LuveeCart.init();
            console.log('✅ Luvee Cart AJAX initialized');
        } else {
            console.warn('⚠️ Luvee Cart AJAX not configured');
        }
    });

    // Make LuveeCart globally accessible
    window.LuveeCart = LuveeCart;

})(jQuery);
