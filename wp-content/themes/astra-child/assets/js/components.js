/**
 * Astra Child Theme Components JavaScript
 * 
 * @package Astra Child
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // ===========================================
    // HERO CAROUSEL COMPONENT
    // ===========================================
    
    class HeroCarousel {
        constructor(element) {
            this.element = element;
            this.container = element.querySelector('.hero-carousel__container');
            this.slides = element.querySelectorAll('.hero-carousel__slide');
            this.dots = element.querySelectorAll('.hero-carousel__dot');
            this.prevBtn = element.querySelector('.hero-carousel__nav--prev');
            this.nextBtn = element.querySelector('.hero-carousel__nav--next');
            
            this.currentSlide = 0;
            this.slideCount = this.slides.length;
            this.autoplayInterval = null;
            this.autoplayDelay = 5000; // 5 seconds
            
            this.init();
        }
        
        init() {
            if (this.slideCount <= 1) return;
            
            this.bindEvents();
            this.startAutoplay();
            this.updateDots();
        }
        
        bindEvents() {
            if (this.prevBtn) {
                this.prevBtn.addEventListener('click', () => this.prevSlide());
            }
            
            if (this.nextBtn) {
                this.nextBtn.addEventListener('click', () => this.nextSlide());
            }
            
            this.dots.forEach((dot, index) => {
                dot.addEventListener('click', () => this.goToSlide(index));
            });
            
            // Pause autoplay on hover
            this.element.addEventListener('mouseenter', () => this.pauseAutoplay());
            this.element.addEventListener('mouseleave', () => this.startAutoplay());
            
            // Touch/swipe support
            this.initTouchSupport();
        }
        
        initTouchSupport() {
            let startX = 0;
            let endX = 0;
            
            this.element.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
            });
            
            this.element.addEventListener('touchend', (e) => {
                endX = e.changedTouches[0].clientX;
                this.handleSwipe(startX, endX);
            });
        }
        
        handleSwipe(startX, endX) {
            const threshold = 50;
            const diff = startX - endX;
            
            if (Math.abs(diff) > threshold) {
                if (diff > 0) {
                    this.nextSlide();
                } else {
                    this.prevSlide();
                }
            }
        }
        
        goToSlide(index) {
            if (index < 0 || index >= this.slideCount) return;
            
            this.currentSlide = index;
            this.updateSlide();
            this.updateDots();
            this.resetAutoplay();
        }
        
        nextSlide() {
            this.currentSlide = (this.currentSlide + 1) % this.slideCount;
            this.updateSlide();
            this.updateDots();
            this.resetAutoplay();
        }
        
        prevSlide() {
            this.currentSlide = (this.currentSlide - 1 + this.slideCount) % this.slideCount;
            this.updateSlide();
            this.updateDots();
            this.resetAutoplay();
        }
        
        updateSlide() {
            const translateX = -this.currentSlide * 100;
            this.container.style.transform = `translateX(${translateX}%)`;
        }
        
        updateDots() {
            this.dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === this.currentSlide);
            });
        }
        
        startAutoplay() {
            if (this.autoplayInterval) return;
            
            this.autoplayInterval = setInterval(() => {
                this.nextSlide();
            }, this.autoplayDelay);
        }
        
        pauseAutoplay() {
            if (this.autoplayInterval) {
                clearInterval(this.autoplayInterval);
                this.autoplayInterval = null;
            }
        }
        
        resetAutoplay() {
            this.pauseAutoplay();
            this.startAutoplay();
        }
    }

    // ===========================================
    // MEGA MENU COMPONENT
    // ===========================================
    
    class MegaMenu {
        constructor(element) {
            this.element = element;
            this.categories = element.querySelectorAll('.mega-menu__category');
            this.sections = element.querySelectorAll('.mega-menu__section');
            this.activeCategory = null;
            
            this.init();
        }
        
        init() {
            this.bindEvents();
            this.showSection(this.categories[0].dataset.category);
        }
        
        bindEvents() {
            this.categories.forEach(category => {
                category.addEventListener('click', (e) => {
                    e.preventDefault();
                    const categoryName = category.dataset.category;
                    this.showSection(categoryName);
                    this.updateActiveCategory(category);
                });
                
                category.addEventListener('mouseenter', (e) => {
                    const categoryName = category.dataset.category;
                    this.showSection(categoryName);
                    this.updateActiveCategory(category);
                });
            });
        }
        
        showSection(categoryName) {
            this.sections.forEach(section => {
                section.style.display = section.dataset.category === categoryName ? 'block' : 'none';
            });
        }
        
        updateActiveCategory(activeCategory) {
            this.categories.forEach(category => {
                category.classList.remove('active');
            });
            activeCategory.classList.add('active');
        }
    }

    // ===========================================
    // PRODUCT CARD COMPONENT
    // ===========================================
    
    class ProductCard {
        constructor(element) {
            this.element = element;
            this.wishlistBtn = element.querySelector('.product-card__wishlist');
            this.productId = this.wishlistBtn?.dataset.productId;
            
            this.init();
        }
        
        init() {
            if (this.wishlistBtn) {
                this.bindWishlistEvents();
            }
        }
        
        bindWishlistEvents() {
            this.wishlistBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleWishlist();
            });
        }
        
        toggleWishlist() {
            if (!this.productId) return;
            
            $.ajax({
                url: astra_child_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'astra_child_wishlist',
                    product_id: this.productId,
                    nonce: astra_child_ajax.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.updateWishlistUI(response.data.action);
                        this.showNotification(response.data.action);
                    }
                },
                error: () => {
                    this.showNotification('error');
                }
            });
        }
        
        updateWishlistUI(action) {
            const icon = this.wishlistBtn.querySelector('i');
            
            if (action === 'added') {
                this.wishlistBtn.classList.add('active');
                icon.style.color = '#ff4757';
            } else {
                this.wishlistBtn.classList.remove('active');
                icon.style.color = '';
            }
        }
        
        showNotification(action) {
            const messages = {
                'added': 'Produto adicionado à lista de desejos!',
                'removed': 'Produto removido da lista de desejos!',
                'error': 'Erro ao atualizar lista de desejos!'
            };
            
            const message = messages[action] || messages.error;
            this.createNotification(message, action === 'error' ? 'error' : 'success');
        }
        
        createNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `product-notification product-notification--${type}`;
            notification.textContent = message;
            
            // Add styles
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 12px 20px;
                border-radius: 6px;
                color: white;
                font-weight: 600;
                z-index: 9999;
                transform: translateX(100%);
                transition: transform 0.3s ease;
                ${type === 'error' ? 'background: #ff4757;' : 'background: #27ae60;'}
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }
    }

    // ===========================================
    // FOOTER NEWSLETTER COMPONENT
    // ===========================================
    
    class NewsletterForm {
        constructor(element) {
            this.element = element;
            this.form = element.querySelector('.footer__newsletter-form');
            this.input = element.querySelector('.footer__newsletter-input');
            this.button = element.querySelector('.footer__newsletter-btn');
            
            this.init();
        }
        
        init() {
            if (this.form) {
                this.bindEvents();
            }
        }
        
        bindEvents() {
            this.form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleSubmit();
            });
        }
        
        handleSubmit() {
            const email = this.input.value.trim();
            
            if (!this.validateEmail(email)) {
                this.showError('Por favor, insira um e-mail válido.');
                return;
            }
            
            this.button.disabled = true;
            this.button.textContent = 'Enviando...';
            
            // Simulate newsletter subscription
            setTimeout(() => {
                this.showSuccess('Inscrição realizada com sucesso!');
                this.input.value = '';
                this.button.disabled = false;
                this.button.textContent = 'Inscrever';
            }, 1000);
        }
        
        validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
        
        showError(message) {
            this.showNotification(message, 'error');
        }
        
        showSuccess(message) {
            this.showNotification(message, 'success');
        }
        
        showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `newsletter-notification newsletter-notification--${type}`;
            notification.textContent = message;
            
            notification.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                padding: 12px 20px;
                border-radius: 6px;
                color: white;
                font-weight: 600;
                z-index: 9999;
                transform: translateY(100%);
                transition: transform 0.3s ease;
                ${type === 'error' ? 'background: #ff4757;' : 'background: #27ae60;'}
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.transform = 'translateY(0)';
            }, 100);
            
            setTimeout(() => {
                notification.style.transform = 'translateY(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }
    }

    // ===========================================
    // UTILITY FUNCTIONS
    // ===========================================
    
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    // ===========================================
    // INITIALIZATION
    // ===========================================
    
    $(document).ready(function() {
        // Initialize Hero Carousels
        document.querySelectorAll('.hero-carousel').forEach(carousel => {
            new HeroCarousel(carousel);
        });
        
        // Initialize Mega Menus
        document.querySelectorAll('.mega-menu').forEach(menu => {
            new MegaMenu(menu);
        });
        
        // Initialize Product Cards
        document.querySelectorAll('.product-card').forEach(card => {
            new ProductCard(card);
        });
        
        // Initialize Newsletter Forms
        document.querySelectorAll('.footer__newsletter').forEach(newsletter => {
            new NewsletterForm(newsletter);
        });
        
        // Add smooth scrolling for anchor links
        $('a[href^="#"]').on('click', function(e) {
            e.preventDefault();
            const target = $(this.getAttribute('href'));
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 80
                }, 800);
            }
        });
        
        // Add scroll to top functionality
        const scrollToTopBtn = $('<button class="scroll-to-top"><i class="fas fa-chevron-up"></i></button>');
        scrollToTopBtn.css({
            position: 'fixed',
            bottom: '20px',
            right: '20px',
            width: '50px',
            height: '50px',
            borderRadius: '50%',
            background: '#3498db',
            color: 'white',
            border: 'none',
            cursor: 'pointer',
            display: 'none',
            zIndex: 9999,
            transition: 'all 0.3s ease'
        });
        
        $('body').append(scrollToTopBtn);
        
        $(window).on('scroll', throttle(function() {
            if ($(this).scrollTop() > 300) {
                scrollToTopBtn.fadeIn();
            } else {
                scrollToTopBtn.fadeOut();
            }
        }, 100));
        
        scrollToTopBtn.on('click', function() {
            $('html, body').animate({ scrollTop: 0 }, 800);
        });
        
        // Add loading states for buttons
        $('.product-card__btn, .hero-carousel__btn').on('click', function() {
            const $btn = $(this);
            const originalText = $btn.text();
            
            $btn.prop('disabled', true).text('Carregando...');
            
            setTimeout(() => {
                $btn.prop('disabled', false).text(originalText);
            }, 1000);
        });
    });

})(jQuery); 