/**
 * JavaScript frontend do plugin Luvee Site
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Inicializa funcionalidades do frontend
        LuveeSiteFrontend.init();
        
    });
    
    /**
     * Objeto principal do frontend
     */
    var LuveeSiteFrontend = {
        
        /**
         * Inicializa o frontend
         */
        init: function() {
            this.initCarousels();
            this.initClickTracking();
        },
        
        /**
         * Inicializa todos os carrosséis
         */
        initCarousels: function() {
            var self = this;
            
            $('.luvee-carousel').each(function() {
                self.initCarousel($(this));
            });
            
            $('.luvee-hero-carousel').each(function() {
                self.initHeroCarousel($(this));
            });
        },
        
        /**
         * Inicializa um carrossel específico
         */
        initCarousel: function($carousel) {
            var self = this;
            var $items = $carousel.find('.luvee-carousel-item');
            var $container = $carousel.closest('.luvee-carousel-container');
            
            if ($items.length <= 1) {
                return; // Não precisa de carrossel para um item só
            }
            
            // Configurações do carrossel
            var config = {
                autoplay: $carousel.data('autoplay') === 'true',
                autoplaySpeed: parseInt($carousel.data('autoplay-speed')) || 3000,
                showArrows: $carousel.data('show-arrows') !== 'false',
                showDots: $carousel.data('show-dots') !== 'false',
                infinite: $carousel.data('infinite') !== 'false'
            };
            
            var currentIndex = 0;
            var intervalId = null;
            
            // Mostra o primeiro item
            $items.eq(0).addClass('active');
            
            // Adiciona setas se habilitadas
            if (config.showArrows) {
                var $prevArrow = $('<button class="luvee-carousel-arrow prev">‹</button>');
                var $nextArrow = $('<button class="luvee-carousel-arrow next">›</button>');
                
                $carousel.append($prevArrow, $nextArrow);
                
                $prevArrow.on('click', function() {
                    self.goToPrevSlide($carousel, $items, currentIndex, config);
                    currentIndex = self.getCurrentIndex($carousel, $items);
                });
                
                $nextArrow.on('click', function() {
                    self.goToNextSlide($carousel, $items, currentIndex, config);
                    currentIndex = self.getCurrentIndex($carousel, $items);
                });
            }
            
            // Adiciona pontos se habilitados
            if (config.showDots) {
                var $dotsContainer = $('<div class="luvee-carousel-dots"></div>');
                
                for (var i = 0; i < $items.length; i++) {
                    var $dot = $('<span class="luvee-carousel-dot"></span>');
                    if (i === 0) $dot.addClass('active');
                    $dotsContainer.append($dot);
                }
                
                $carousel.append($dotsContainer);
                
                $dotsContainer.on('click', '.luvee-carousel-dot', function() {
                    var dotIndex = $(this).index();
                    self.goToSlide($carousel, $items, dotIndex);
                    currentIndex = dotIndex;
                    self.updateDots($carousel, dotIndex);
                });
            }
            
            // Inicia autoplay se habilitado
            if (config.autoplay) {
                self.startAutoplay($carousel, $items, config);
                
                // Pausa no hover
                $carousel.on('mouseenter', function() {
                    self.stopAutoplay();
                });
                
                $carousel.on('mouseleave', function() {
                    self.startAutoplay($carousel, $items, config);
                });
            }
            
            // Suporte a touch/swipe em dispositivos móveis
            this.initTouchSupport($carousel, $items, config);
        },
        
        /**
         * Vai para o slide anterior
         */
        goToPrevSlide: function($carousel, $items, currentIndex, config) {
            var prevIndex = currentIndex - 1;
            
            if (prevIndex < 0) {
                if (config.infinite) {
                    prevIndex = $items.length - 1;
                } else {
                    return;
                }
            }
            
            this.goToSlide($carousel, $items, prevIndex);
            this.updateDots($carousel, prevIndex);
        },
        
        /**
         * Vai para o próximo slide
         */
        goToNextSlide: function($carousel, $items, currentIndex, config) {
            var nextIndex = currentIndex + 1;
            
            if (nextIndex >= $items.length) {
                if (config.infinite) {
                    nextIndex = 0;
                } else {
                    return;
                }
            }
            
            this.goToSlide($carousel, $items, nextIndex);
            this.updateDots($carousel, nextIndex);
        },
        
        /**
         * Vai para um slide específico
         */
        goToSlide: function($carousel, $items, index) {
            $items.removeClass('active');
            $items.eq(index).addClass('active');
        },
        
        /**
         * Atualiza os pontos
         */
        updateDots: function($carousel, activeIndex) {
            var $dots = $carousel.find('.luvee-carousel-dot');
            $dots.removeClass('active');
            $dots.eq(activeIndex).addClass('active');
        },
        
        /**
         * Obtém o índice atual
         */
        getCurrentIndex: function($carousel, $items) {
            return $items.filter('.active').index();
        },
        
        /**
         * Inicia autoplay
         */
        startAutoplay: function($carousel, $items, config) {
            var self = this;
            
            this.stopAutoplay(); // Para qualquer autoplay anterior
            
            this.intervalId = setInterval(function() {
                var currentIndex = self.getCurrentIndex($carousel, $items);
                self.goToNextSlide($carousel, $items, currentIndex, config);
            }, config.autoplaySpeed);
        },
        
        /**
         * Para autoplay
         */
        stopAutoplay: function() {
            if (this.intervalId) {
                clearInterval(this.intervalId);
                this.intervalId = null;
            }
        },
        
        /**
         * Inicializa suporte a touch
         */
        initTouchSupport: function($carousel, $items, config) {
            var self = this;
            var startX = 0;
            var endX = 0;
            var threshold = 50; // Mínimo de pixels para considerar um swipe
            
            $carousel.on('touchstart', function(e) {
                startX = e.originalEvent.touches[0].clientX;
            });
            
            $carousel.on('touchend', function(e) {
                endX = e.originalEvent.changedTouches[0].clientX;
                var diff = startX - endX;
                var currentIndex = self.getCurrentIndex($carousel, $items);
                
                if (Math.abs(diff) > threshold) {
                    if (diff > 0) {
                        // Swipe para a esquerda - próximo slide
                        self.goToNextSlide($carousel, $items, currentIndex, config);
                    } else {
                        // Swipe para a direita - slide anterior
                        self.goToPrevSlide($carousel, $items, currentIndex, config);
                    }
                    
                    self.updateDots($carousel, self.getCurrentIndex($carousel, $items));
                }
            });
        },
        
        /**
         * Inicializa um carrossel de hero banner
         */
        initHeroCarousel: function($carousel) {
            var self = this;
            var $slides = $carousel.find('.luvee-hero-slide');
            var $container = $carousel.closest('.luvee-hero-carousel-wrapper');
            
            if ($slides.length <= 1) {
                return; // Não precisa de carrossel para um slide só
            }
            
            // Configurações do carrossel
            var config = {
                autoplay: $carousel.data('autoplay') === 'true',
                autoplaySpeed: parseInt($carousel.data('autoplay-speed')) || 5000,
                showArrows: $carousel.data('show-arrows') !== 'false',
                showDots: $carousel.data('show-dots') !== 'false',
                infinite: true
            };
            
            var currentIndex = 0;
            var intervalId = null;
            
            // Mostra o primeiro slide
            $slides.eq(0).addClass('active');
            
            // Adiciona setas se habilitadas
            if (config.showArrows) {
                var $prevArrow = $('<button class="luvee-carousel-arrow prev">‹</button>');
                var $nextArrow = $('<button class="luvee-carousel-arrow next">›</button>');
                
                $carousel.append($prevArrow, $nextArrow);
                
                $prevArrow.on('click', function() {
                    self.goToHeroPrevSlide($carousel, $slides, currentIndex, config);
                    currentIndex = self.getHeroCurrentIndex($carousel, $slides);
                    self.updateHeroDots($carousel, currentIndex);
                });
                
                $nextArrow.on('click', function() {
                    self.goToHeroNextSlide($carousel, $slides, currentIndex, config);
                    currentIndex = self.getHeroCurrentIndex($carousel, $slides);
                    self.updateHeroDots($carousel, currentIndex);
                });
            }
            
            // Adiciona pontos se habilitados
            if (config.showDots) {
                var $dotsContainer = $('<div class="luvee-carousel-dots"></div>');
                
                for (var i = 0; i < $slides.length; i++) {
                    var $dot = $('<span class="luvee-carousel-dot"></span>');
                    if (i === 0) $dot.addClass('active');
                    $dotsContainer.append($dot);
                }
                
                $carousel.append($dotsContainer);
                
                $dotsContainer.on('click', '.luvee-carousel-dot', function() {
                    var dotIndex = $(this).index();
                    self.goToHeroSlide($carousel, $slides, dotIndex);
                    currentIndex = dotIndex;
                    self.updateHeroDots($carousel, dotIndex);
                });
            }
            
            // Inicia autoplay se habilitado
            if (config.autoplay) {
                self.startHeroAutoplay($carousel, $slides, config);
                
                // Pausa no hover
                $carousel.on('mouseenter', function() {
                    self.stopHeroAutoplay();
                });
                
                $carousel.on('mouseleave', function() {
                    self.startHeroAutoplay($carousel, $slides, config);
                });
            }
            
            // Suporte a touch/swipe em dispositivos móveis
            this.initHeroTouchSupport($carousel, $slides, config);
        },
        
        /**
         * Vai para o slide anterior do hero
         */
        goToHeroPrevSlide: function($carousel, $slides, currentIndex, config) {
            var prevIndex = currentIndex - 1;
            
            if (prevIndex < 0) {
                if (config.infinite) {
                    prevIndex = $slides.length - 1;
                } else {
                    return;
                }
            }
            
            this.goToHeroSlide($carousel, $slides, prevIndex);
        },
        
        /**
         * Vai para o próximo slide do hero
         */
        goToHeroNextSlide: function($carousel, $slides, currentIndex, config) {
            var nextIndex = currentIndex + 1;
            
            if (nextIndex >= $slides.length) {
                if (config.infinite) {
                    nextIndex = 0;
                } else {
                    return;
                }
            }
            
            this.goToHeroSlide($carousel, $slides, nextIndex);
        },
        
        /**
         * Vai para um slide específico do hero
         */
        goToHeroSlide: function($carousel, $slides, index) {
            $slides.removeClass('active');
            $slides.eq(index).addClass('active');
        },
        
        /**
         * Atualiza os pontos do hero
         */
        updateHeroDots: function($carousel, activeIndex) {
            var $dots = $carousel.find('.luvee-carousel-dot');
            $dots.removeClass('active');
            $dots.eq(activeIndex).addClass('active');
        },
        
        /**
         * Obtém o índice atual do hero
         */
        getHeroCurrentIndex: function($carousel, $slides) {
            return $slides.filter('.active').index();
        },
        
        /**
         * Inicia autoplay do hero
         */
        startHeroAutoplay: function($carousel, $slides, config) {
            var self = this;
            
            this.stopHeroAutoplay(); // Para qualquer autoplay anterior
            
            this.heroIntervalId = setInterval(function() {
                var currentIndex = self.getHeroCurrentIndex($carousel, $slides);
                self.goToHeroNextSlide($carousel, $slides, currentIndex, config);
                var newIndex = self.getHeroCurrentIndex($carousel, $slides);
                self.updateHeroDots($carousel, newIndex);
            }, config.autoplaySpeed);
        },
        
        /**
         * Para autoplay do hero
         */
        stopHeroAutoplay: function() {
            if (this.heroIntervalId) {
                clearInterval(this.heroIntervalId);
                this.heroIntervalId = null;
            }
        },
        
        /**
         * Inicializa suporte a touch para hero
         */
        initHeroTouchSupport: function($carousel, $slides, config) {
            var self = this;
            var startX = 0;
            var endX = 0;
            var threshold = 50; // Mínimo de pixels para considerar um swipe
            
            $carousel.on('touchstart', function(e) {
                startX = e.originalEvent.touches[0].clientX;
            });
            
            $carousel.on('touchend', function(e) {
                endX = e.originalEvent.changedTouches[0].clientX;
                var diff = startX - endX;
                var currentIndex = self.getHeroCurrentIndex($carousel, $slides);
                
                if (Math.abs(diff) > threshold) {
                    if (diff > 0) {
                        // Swipe para a esquerda - próximo slide
                        self.goToHeroNextSlide($carousel, $slides, currentIndex, config);
                    } else {
                        // Swipe para a direita - slide anterior
                        self.goToHeroPrevSlide($carousel, $slides, currentIndex, config);
                    }
                    
                    var newIndex = self.getHeroCurrentIndex($carousel, $slides);
                    self.updateHeroDots($carousel, newIndex);
                }
            });
        },
        
        /**
         * Inicializa rastreamento de cliques
         */
        initClickTracking: function() {
            // A função de tracking será chamada inline nos links
            // Este método está aqui para futuras expansões
        }
    };
    
    // Disponibiliza globalmente
    window.LuveeSiteFrontend = LuveeSiteFrontend;
    
})(jQuery);

/**
 * Função global para rastrear cliques em banners
 */
function luveeTrackBannerClick(bannerId, bannerType) {
    if (typeof luvee_site_frontend !== 'undefined') {
        jQuery.ajax({
            url: luvee_site_frontend.ajax_url,
            type: 'POST',
            data: {
                action: 'luvee_track_banner_click',
                banner_id: bannerId,
                banner_type: bannerType,
                nonce: luvee_site_frontend.nonce
            },
            success: function(response) {
                // Click rastreado com sucesso
                console.log('Click tracked:', bannerId, bannerType);
            },
            error: function() {
                // Erro no rastreamento, mas não interrompe a navegação
                console.log('Error tracking click');
            }
        });
    }
}
