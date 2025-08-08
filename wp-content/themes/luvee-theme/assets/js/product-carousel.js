/**
 * Product Carousel JavaScript
 * Sistema de carrossel para product-section
 */

(function($) {
    'use strict';

    const ProductCarousel = {
        
        // Configuration
        config: {
            autoplayInterval: 3000,
            animationDuration: 500,
            touchThreshold: 50
        },

        // Initialize all carousels
        init: function() {
            $('.products-carousel').each(function() {
                const $carousel = $(this);
                ProductCarousel.setupCarousel($carousel);
            });
        },

        // Setup individual carousel
        setupCarousel: function($carousel) {
            const carouselId = $carousel.attr('id');
            const autoplay = $carousel.data('autoplay') === true;
            const speed = $carousel.data('speed') || this.config.autoplayInterval;
            const arrows = $carousel.data('arrows') !== false;
            const dots = $carousel.data('dots') !== false;
            const columns = parseInt($carousel.data('columns')) || 4;

            // Create carousel structure
            this.createCarouselStructure($carousel, arrows, dots);
            
            // Setup responsive breakpoints
            this.setupResponsive($carousel, columns);
            
            // Initialize carousel logic
            this.initializeCarousel($carousel, {
                autoplay: autoplay,
                speed: speed,
                arrows: arrows,
                dots: dots,
                columns: columns
            });

            
        },

        // Create carousel HTML structure
        createCarouselStructure: function($carousel, arrows, dots) {
            const $items = $carousel.find('.carousel-item');
            const $container = $carousel.parent();

            // Create track
            const $track = $('<div class="carousel-track"></div>');
            $track.append($items);
            $carousel.html($track);

            // Add navigation arrows
            if (arrows) {
                const $prevBtn = $('<button class="carousel-nav prev" aria-label="Anterior"><i class="fas fa-chevron-left"></i></button>');
                const $nextBtn = $('<button class="carousel-nav next" aria-label="Próximo"><i class="fas fa-chevron-right"></i></button>');
                
                $container.append($prevBtn, $nextBtn);
                
                // Bind arrow events
                $prevBtn.on('click', () => this.slide($carousel, 'prev'));
                $nextBtn.on('click', () => this.slide($carousel, 'next'));
            }

            // Add dots
            if (dots) {
                this.createDots($carousel, $container);
            }
        },

        // Create dots navigation
        createDots: function($carousel, $container) {
            const $items = $carousel.find('.carousel-item');
            const columns = parseInt($carousel.data('columns')) || 4;
            const totalSlides = Math.ceil($items.length / columns);

            if (totalSlides <= 1) return;

            const $dotsContainer = $('<div class="carousel-dots"></div>');
            
            for (let i = 0; i < totalSlides; i++) {
                const $dot = $(`<button class="carousel-dot ${i === 0 ? 'active' : ''}" data-slide="${i}"></button>`);
                $dot.on('click', () => this.goToSlide($carousel, i));
                $dotsContainer.append($dot);
            }
            
            $container.append($dotsContainer);
        },

        // Setup responsive behavior
        setupResponsive: function($carousel, columns) {
            const updateLayout = () => {
                const width = $(window).width();
                let responsiveColumns = columns;

                // Responsive breakpoints
                if (width < 576) {
                    responsiveColumns = 1;
                } else if (width < 768) {
                    responsiveColumns = Math.min(2, columns);
                } else if (width < 992) {
                    responsiveColumns = Math.min(3, columns);
                }

                $carousel.attr('data-current-columns', responsiveColumns);
                this.updateCarouselLayout($carousel);
            };

            // Initial setup
            updateLayout();
            
            // Update on resize
            $(window).on('resize.carousel', updateLayout);
        },

        // Initialize carousel functionality
        initializeCarousel: function($carousel, options) {
            const carouselData = {
                currentSlide: 0,
                totalSlides: 0,
                isAnimating: false,
                autoplayTimer: null,
                touchStartX: 0,
                touchEndX: 0,
                options: options
            };

            // Store data
            $carousel.data('carousel', carouselData);

            // Calculate initial state
            this.updateCarouselLayout($carousel);

            // Setup autoplay
            if (options.autoplay) {
                this.startAutoplay($carousel);
                
                // Pause on hover
                $carousel.parent().on('mouseenter', () => this.pauseAutoplay($carousel));
                $carousel.parent().on('mouseleave', () => this.resumeAutoplay($carousel));
            }

            // Setup touch events
            this.setupTouchEvents($carousel);

            // Initial display
            this.updateCarouselDisplay($carousel);
        },

        // Update carousel layout
        updateCarouselLayout: function($carousel) {
            const $items = $carousel.find('.carousel-item');
            const currentColumns = parseInt($carousel.attr('data-current-columns')) || 4;
            const totalSlides = Math.ceil($items.length / currentColumns);
            
            const carouselData = $carousel.data('carousel');
            carouselData.totalSlides = totalSlides;
            carouselData.currentColumns = currentColumns;

            // Update dots
            this.updateDots($carousel);
            
            // Ensure current slide is valid
            if (carouselData.currentSlide >= totalSlides) {
                carouselData.currentSlide = Math.max(0, totalSlides - 1);
            }
        },

        // Update carousel display
        updateCarouselDisplay: function($carousel) {
            const carouselData = $carousel.data('carousel');
            const $track = $carousel.find('.carousel-track');
            const $items = $carousel.find('.carousel-item');
            const currentColumns = carouselData.currentColumns;
            const currentSlide = carouselData.currentSlide;

            // Hide all items
            $items.hide();

            // Show items for current slide
            const startIndex = currentSlide * currentColumns;
            const endIndex = startIndex + currentColumns;
            
            $items.slice(startIndex, endIndex).show();

            // Update dots
            $carousel.parent().find('.carousel-dot').removeClass('active')
                .eq(currentSlide).addClass('active');
        },

        // Slide to next/previous
        slide: function($carousel, direction) {
            const carouselData = $carousel.data('carousel');
            
            if (carouselData.isAnimating) return;

            const currentSlide = carouselData.currentSlide;
            let newSlide;

            if (direction === 'next') {
                newSlide = (currentSlide + 1) % carouselData.totalSlides;
            } else {
                newSlide = currentSlide === 0 ? carouselData.totalSlides - 1 : currentSlide - 1;
            }

            this.goToSlide($carousel, newSlide);
        },

        // Go to specific slide
        goToSlide: function($carousel, slideIndex) {
            const carouselData = $carousel.data('carousel');
            
            if (carouselData.isAnimating || slideIndex === carouselData.currentSlide) return;

            carouselData.isAnimating = true;
            carouselData.currentSlide = slideIndex;

            // Animate transition
            $carousel.addClass('loading');
            
            setTimeout(() => {
                this.updateCarouselDisplay($carousel);
                $carousel.removeClass('loading');
                carouselData.isAnimating = false;
            }, 300);

            // Reset autoplay
            if (carouselData.options.autoplay) {
                this.resetAutoplay($carousel);
            }
        },

        // Update dots navigation
        updateDots: function($carousel) {
            const carouselData = $carousel.data('carousel');
            const $container = $carousel.parent();
            const $existingDots = $container.find('.carousel-dots');
            
            // Remove existing dots
            $existingDots.remove();
            
            // Create new dots if needed
            if (carouselData.totalSlides > 1 && carouselData.options.dots) {
                this.createDots($carousel, $container);
            }
        },

        // Autoplay functions
        startAutoplay: function($carousel) {
            const carouselData = $carousel.data('carousel');
            const speed = carouselData.options.speed || this.config.autoplayInterval;

            carouselData.autoplayTimer = setInterval(() => {
                this.slide($carousel, 'next');
            }, speed);
        },

        pauseAutoplay: function($carousel) {
            const carouselData = $carousel.data('carousel');
            if (carouselData.autoplayTimer) {
                clearInterval(carouselData.autoplayTimer);
                carouselData.autoplayTimer = null;
            }
        },

        resumeAutoplay: function($carousel) {
            const carouselData = $carousel.data('carousel');
            if (carouselData.options.autoplay && !carouselData.autoplayTimer) {
                this.startAutoplay($carousel);
            }
        },

        resetAutoplay: function($carousel) {
            this.pauseAutoplay($carousel);
            this.resumeAutoplay($carousel);
        },

        // Touch events for mobile
        setupTouchEvents: function($carousel) {
            const $container = $carousel.parent();
            $container.addClass('touch-enabled');

            $container.on('touchstart', (e) => {
                const carouselData = $carousel.data('carousel');
                carouselData.touchStartX = e.originalEvent.touches[0].clientX;
            });

            $container.on('touchend', (e) => {
                const carouselData = $carousel.data('carousel');
                carouselData.touchEndX = e.originalEvent.changedTouches[0].clientX;
                this.handleTouchEnd($carousel);
            });
        },

        // Handle touch end
        handleTouchEnd: function($carousel) {
            const carouselData = $carousel.data('carousel');
            const touchDiff = carouselData.touchStartX - carouselData.touchEndX;

            if (Math.abs(touchDiff) > this.config.touchThreshold) {
                if (touchDiff > 0) {
                    this.slide($carousel, 'next');
                } else {
                    this.slide($carousel, 'prev');
                }
            }
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        ProductCarousel.init();
    });

    // Re-initialize on window resize (throttled)
    let resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            $('.products-carousel').each(function() {
                ProductCarousel.updateCarouselLayout($(this));
                ProductCarousel.updateCarouselDisplay($(this));
            });
        }, 250);
    });

    // Make ProductCarousel globally accessible
    window.ProductCarousel = ProductCarousel;

})(jQuery);
