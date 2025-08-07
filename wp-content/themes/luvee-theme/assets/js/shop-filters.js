/**
 * Shop Filters JavaScript
 * Handles filtering, AJAX requests, and shop interactions
 */

(function($) {
    'use strict';

    const ShopFilters = {
        
        // Configuration
        config: {
            filtersForm: '#shop-filters-form',
            productsContainer: '#products-container',
            loadingClass: 'loading',
            animationDuration: 300
        },

        // Initialize
        init: function() {
            this.bindEvents();
            this.initPriceSlider();
            this.initViewToggle();
            this.handleURLFilters();
        },

        // Bind all events
        bindEvents: function() {
            const self = this;

            // Form submission
            $(document).on('submit', this.config.filtersForm, function(e) {
                e.preventDefault();
                self.applyFilters();
            });

            // Clear filters
            $(document).on('click', '.clear-filters', function(e) {
                e.preventDefault();
                self.clearFilters();
            });

            // Price inputs change
            $(document).on('change', '#min_price, #max_price', function() {
                self.updatePriceSlider();
            });

            // Filter changes (auto-apply)
            $(document).on('change', 'input[name="product_cat"], input[name="product_tag"], input[name="rating_filter"], input[type="checkbox"]', function() {
                if ($(this).closest('form').hasClass('auto-filter')) {
                    self.applyFilters();
                }
            });

            // Sorting change
            $(document).on('change', '.woocommerce-ordering select', function() {
                self.applyFilters();
            });

            // Pagination
            $(document).on('click', '.shop-pagination a', function(e) {
                e.preventDefault();
                const page = self.getPageFromURL($(this).attr('href'));
                self.applyFilters(page);
            });

            // View toggle
            $(document).on('click', '.shop-view-toggle button', function() {
                self.toggleView($(this).attr('id'));
            });

            // Quick view
            $(document).on('click', '[data-bs-target="#quickViewModal"]', function() {
                const productId = $(this).data('product-id');
                self.loadQuickView(productId);
            });

            // Wishlist
            $(document).on('click', '.wishlist-btn', function(e) {
                e.preventDefault();
                const productId = $(this).data('product-id');
                self.toggleWishlist(productId, $(this));
            });

            // Adicionar ao carrinho - delegado para o sistema global
            $(document).on('click', '.btn-add-to-cart', function(e) {
                e.preventDefault();
                // O sistema global LuveeCart irá lidar com isto
                if (typeof window.LuveeCart !== 'undefined') {
                    window.LuveeCart.addToCart($(this));
                } else {
                    // Fallback para compatibilidade
                    const productId = $(this).data('product-id');
                    self.addToCart(productId, $(this));
                }
            });
        },

        // Initialize price range slider
        initPriceSlider: function() {
            const slider = document.getElementById('price-range-slider');
            if (!slider) return;

            const minPrice = parseInt(slider.dataset.min);
            const maxPrice = parseInt(slider.dataset.max);
            const currentMin = parseInt(slider.dataset.currentMin);
            const currentMax = parseInt(slider.dataset.currentMax);

            // Create simple range slider implementation
            slider.innerHTML = `
                <div class="range-slider">
                    <input type="range" id="range-min" min="${minPrice}" max="${maxPrice}" value="${currentMin}" class="range-input">
                    <input type="range" id="range-max" min="${minPrice}" max="${maxPrice}" value="${currentMax}" class="range-input">
                    <div class="range-track"></div>
                </div>
            `;

            const rangeMin = slider.querySelector('#range-min');
            const rangeMax = slider.querySelector('#range-max');
            const minPriceInput = document.getElementById('min_price');
            const maxPriceInput = document.getElementById('max_price');

            function updateSlider() {
                const min = parseInt(rangeMin.value);
                const max = parseInt(rangeMax.value);

                if (min >= max) {
                    rangeMin.value = max - 1;
                }

                minPriceInput.value = rangeMin.value;
                maxPriceInput.value = rangeMax.value;

                // Update visual track
                const percent1 = ((rangeMin.value - minPrice) / (maxPrice - minPrice)) * 100;
                const percent2 = ((rangeMax.value - minPrice) / (maxPrice - minPrice)) * 100;
                
                const track = slider.querySelector('.range-track');
                track.style.left = percent1 + '%';
                track.style.width = (percent2 - percent1) + '%';
            }

            rangeMin.addEventListener('input', updateSlider);
            rangeMax.addEventListener('input', updateSlider);
            updateSlider();
        },

        // Update price slider when inputs change
        updatePriceSlider: function() {
            const slider = document.getElementById('price-range-slider');
            if (!slider) return;

            const minInput = document.getElementById('min_price');
            const maxInput = document.getElementById('max_price');
            const rangeMin = slider.querySelector('#range-min');
            const rangeMax = slider.querySelector('#range-max');

            if (minInput.value) rangeMin.value = minInput.value;
            if (maxInput.value) rangeMax.value = maxInput.value;

            // Trigger update
            rangeMin.dispatchEvent(new Event('input'));
        },

        // Initialize view toggle
        initViewToggle: function() {
            const currentView = localStorage.getItem('shop_view') || 'grid';
            this.toggleView(currentView + '-view');
        },

        // Toggle between grid and list view
        toggleView: function(viewId) {
            const $toggleButtons = $('.shop-view-toggle button');
            const $productsContainer = $(this.config.productsContainer);
            
            $toggleButtons.removeClass('active btn-secondary').addClass('btn-outline-secondary');
            $('#' + viewId).removeClass('btn-outline-secondary').addClass('btn-secondary active');

            if (viewId === 'list-view') {
                $productsContainer.addClass('list-view').removeClass('grid-view');
                localStorage.setItem('shop_view', 'list');
            } else {
                $productsContainer.addClass('grid-view').removeClass('list-view');
                localStorage.setItem('shop_view', 'grid');
            }
        },

        // Handle URL filters on page load
        handleURLFilters: function() {
            const urlParams = new URLSearchParams(window.location.search);
            const form = $(this.config.filtersForm);

            // Set form values from URL
            urlParams.forEach((value, key) => {
                const input = form.find(`[name="${key}"]`);
                if (input.length) {
                    if (input.attr('type') === 'checkbox') {
                        input.prop('checked', value === '1');
                    } else if (input.attr('type') === 'radio') {
                        input.filter(`[value="${value}"]`).prop('checked', true);
                    } else {
                        input.val(value);
                    }
                }
            });

            // Update price slider
            this.updatePriceSlider();
        },

        // Apply filters
        applyFilters: function(page = 1) {
            const self = this;
            const $form = $(this.config.filtersForm);
            const $container = $(this.config.productsContainer);
            
            // Show loading
            this.showLoading();
            
            // Get form data
            const formData = $form.serialize();
            const sortingSelect = $('.woocommerce-ordering select');
            let queryString = formData;
            
            // Add sorting
            if (sortingSelect.length && sortingSelect.val()) {
                queryString += '&orderby=' + sortingSelect.val();
            }
            
            // Add page
            if (page > 1) {
                queryString += '&paged=' + page;
            }

            // Update URL without reload
            const newURL = window.location.pathname + '?' + queryString;
            window.history.pushState({}, '', newURL);

            // AJAX request
            $.ajax({
                url: luvee_shop_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'luvee_shop_filters',
                    nonce: luvee_shop_ajax.nonce,
                    ...Object.fromEntries(new URLSearchParams(queryString))
                },
                success: function(response) {
                    if (response.success) {
                        // Update content
                        $container.fadeOut(self.config.animationDuration, function() {
                            $(this).html(response.data.content).fadeIn(self.config.animationDuration);
                        });
                        
                        // Update results count
                        $('.woocommerce-result-count').text(
                            self.formatResultsCount(response.data.found_posts, page)
                        );
                        
                        // Scroll to top of products
                        $('html, body').animate({
                            scrollTop: $container.offset().top - 100
                        }, 300);
                    }
                },
                error: function() {
                    console.error('Error applying filters');
                },
                complete: function() {
                    self.hideLoading();
                }
            });
        },

        // Clear all filters
        clearFilters: function() {
            const $form = $(this.config.filtersForm);
            
            // Reset form
            $form[0].reset();
            
            // Clear checkboxes and radios
            $form.find('input[type="checkbox"], input[type="radio"]').prop('checked', false);
            
            // Reset price slider
            this.initPriceSlider();
            
            // Navigate to clean shop URL
            window.location.href = luvee_shop_ajax.shop_url;
        },

        // Show loading state
        showLoading: function() {
            const $container = $(this.config.productsContainer);
            $container.addClass(this.config.loadingClass);
            
            // Add loading overlay
            if (!$container.find('.loading-overlay').length) {
                $container.append(`
                    <div class="loading-overlay">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                    </div>
                `);
            }
        },

        // Hide loading state
        hideLoading: function() {
            const $container = $(this.config.productsContainer);
            $container.removeClass(this.config.loadingClass);
            $container.find('.loading-overlay').remove();
        },

        // Get page number from URL
        getPageFromURL: function(url) {
            const match = url.match(/paged=(\d+)/);
            return match ? parseInt(match[1]) : 1;
        },

        // Format results count text
        formatResultsCount: function(total, page) {
            const perPage = 12; // Default posts per page
            const start = ((page - 1) * perPage) + 1;
            const end = Math.min(page * perPage, total);
            
            return `Mostrando ${start}–${end} de ${total} resultados`;
        },

        // Load quick view modal
        loadQuickView: function(productId) {
            // Create modal if it doesn't exist
            if (!$('#quickViewModal').length) {
                $('body').append(`
                    <div class="modal fade" id="quickViewModal" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Visualização Rápida</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center py-4">
                                        <div class="spinner-border" role="status"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }

            const $modal = $('#quickViewModal');
            const $modalBody = $modal.find('.modal-body');

            // Load product data via AJAX
            $.ajax({
                url: luvee_shop_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'luvee_quick_view',
                    product_id: productId,
                    nonce: luvee_shop_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $modalBody.html(response.data.content);
                    } else {
                        $modalBody.html('<p>Erro ao carregar produto.</p>');
                    }
                },
                error: function() {
                    $modalBody.html('<p>Erro ao carregar produto.</p>');
                }
            });
        },

        // Toggle wishlist
        toggleWishlist: function(productId, $btn) {
            const isAdded = $btn.hasClass('added');
            const $icon = $btn.find('i');

            // Optimistic UI update
            if (isAdded) {
                $btn.removeClass('added');
                $icon.removeClass('fas').addClass('far');
                $btn.attr('title', 'Adicionar à Lista de Desejos');
            } else {
                $btn.addClass('added');
                $icon.removeClass('far').addClass('fas');
                $btn.attr('title', 'Remover da Lista de Desejos');
            }

            // AJAX request
            $.ajax({
                url: luvee_shop_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'luvee_toggle_wishlist',
                    product_id: productId,
                    nonce: luvee_shop_ajax.nonce
                },
                success: function(response) {
                    if (!response.success) {
                        // Revert on error
                        if (isAdded) {
                            $btn.addClass('added');
                            $icon.removeClass('far').addClass('fas');
                        } else {
                            $btn.removeClass('added');
                            $icon.removeClass('fas').addClass('far');
                        }
                    }
                }
            });
        },

        // Adicionar ao carrinho
        addToCart: function(productId, $btn) {
            const originalText = $btn.html();
            const $icon = $btn.find('i');
            
            // Loading state
            $btn.prop('disabled', true);
            $btn.html('<i class="fas fa-spinner fa-spin me-2"></i>Adicionando...');
            
            // AJAX request para adicionar ao carrinho
            $.ajax({
                url: luvee_shop_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'woocommerce_add_to_cart',
                    product_id: productId,
                    quantity: 1,
                    nonce: luvee_shop_ajax.nonce
                },
                success: function(response) {
                    // Success feedback
                    $btn.html('<i class="fas fa-check me-2"></i>Adicionado!');
                    $btn.removeClass('btn-add-to-cart').addClass('btn-success');
                    
                    // Trigger WooCommerce cart update
                    $('body').trigger('wc_fragment_refresh');
                    
                    // Show success message
                    const productName = $btn.closest('.product-item').find('.product-title a').text();
                    self.showNotification('Produto adicionado ao carrinho!', 'success');
                    
                    // Reset button after delay
                    setTimeout(function() {
                        $btn.html(originalText);
                        $btn.removeClass('btn-success').addClass('btn-add-to-cart');
                        $btn.prop('disabled', false);
                    }, 2000);
                },
                error: function() {
                    // Error feedback
                    $btn.html('<i class="fas fa-times me-2"></i>Erro');
                    $btn.removeClass('btn-add-to-cart').addClass('btn-danger');
                    
                    self.showNotification('Erro ao adicionar produto!', 'error');
                    
                    // Reset button after delay
                    setTimeout(function() {
                        $btn.html(originalText);
                        $btn.removeClass('btn-danger').addClass('btn-add-to-cart');
                        $btn.prop('disabled', false);
                    }, 2000);
                }
            });
        },

        // Mostrar notificação
        showNotification: function(message, type = 'success') {
            const notification = $(`
                <div class="product-notification alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
            
            $('body').append(notification);
            
            // Auto remove after 3 seconds
            setTimeout(function() {
                notification.alert('close');
            }, 3000);
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        ShopFilters.init();
        
        // Adicionar animações baseadas na referência
        $('.product-card-modern').each(function(index) {
            $(this).addClass('fade-in').css('animation-delay', (index * 0.1) + 's');
        });
        
        // Melhorar hover effects com animações mais suaves
        $('.product-card-modern').hover(
            function() {
                $(this).find('.product-actions-hover')
                    .removeClass('opacity-0')
                    .addClass('opacity-100')
                    .css('transform', 'translateY(0)');
                $(this).find('.product-overlay-hover')
                    .removeClass('opacity-0')
                    .addClass('opacity-100');
                
                // Adicionar efeito de brilho sutil
                $(this).addClass('hover-glow');
            },
            function() {
                $(this).find('.product-actions-hover')
                    .removeClass('opacity-100')
                    .addClass('opacity-0')
                    .css('transform', 'translateY(-10px)');
                $(this).find('.product-overlay-hover')
                    .removeClass('opacity-100')
                    .addClass('opacity-0');
                
                $(this).removeClass('hover-glow');
            }
        );
        
        // Animação de entrada para elementos principais
        $('.shop-header, .products-grid-container, .shop-filters').each(function(index) {
            $(this).css({
                'opacity': '0',
                'transform': 'translateY(20px)'
            }).animate({
                'opacity': '1'
            }, {
                duration: 600,
                step: function(now) {
                    $(this).css('transform', 'translateY(' + (20 - (20 * now)) + 'px)');
                },
                delay: index * 200
            });
        });
        
        // Efeito parallax sutil para o background
        $(window).scroll(function() {
            var scrolled = $(window).scrollTop();
            var parallax = scrolled * 0.2;
            $('.shop-page-wrapper::before').css('transform', 'translateY(' + parallax + 'px)');
        });
        
        // Smooth scroll para resultados
        if (window.location.hash) {
            $('html, body').animate({
                scrollTop: $(window.location.hash).offset().top - 100
            }, 300);
        }
    });

})(jQuery);

// CSS for loading and animations
const shopFilterStyles = `
<style>
.products-grid-container.loading {
    position: relative;
    opacity: 0.6;
    pointer-events: none;
}

.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
}

.range-slider {
    position: relative;
    height: 10px;
    background: #e9ecef;
    border-radius: 5px;
    margin: 10px 0;
}

.range-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.range-track {
    position: absolute;
    height: 100%;
    background: var(--bs-primary);
    border-radius: 5px;
    left: 25%;
    width: 50%;
}

.shop-view-toggle .btn.active {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
    color: white;
}

.product-item .product-actions {
    transition: opacity 0.3s ease;
}

.product-item:hover .product-actions {
    opacity: 1 !important;
}

.list-view .product-item {
    display: flex;
    flex-direction: row;
    margin-bottom: 1rem;
}

.list-view .product-image {
    flex: 0 0 200px;
    margin-right: 1rem;
}

.list-view .card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

@media (max-width: 767.98px) {
    .list-view .product-item {
        flex-direction: column;
    }
    
    .list-view .product-image {
        flex: none;
        margin-right: 0;
        margin-bottom: 1rem;
    }
}
</style>
`;

// Add styles to head
if (typeof document !== 'undefined') {
    document.head.insertAdjacentHTML('beforeend', shopFilterStyles);
}
