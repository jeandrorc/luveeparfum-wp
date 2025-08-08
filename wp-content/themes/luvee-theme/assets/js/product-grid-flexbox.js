/**
 * Product Grid Flexbox JavaScript
 * Otimizações para o sistema de grid flexbox
 */

(function($) {
    'use strict';

    const ProductGridFlexbox = {
        
        // Initialize all flexbox grids
        init: function() {
            this.setupFlexboxGrids();
            this.handleResponsiveResize();
            this.equalizeCardHeights();
            
            
        },

        // Setup flexbox grids
        setupFlexboxGrids: function() {
            $('.products-grid-flexbox').each(function() {
                const $grid = $(this);
                const gridId = $grid.attr('id');
                const columns = parseInt($grid.data('columns')) || 4;
                const rows = parseInt($grid.data('rows')) || 2;
                
                // Add data attributes for JavaScript access
                $grid.attr('data-js-columns', columns);
                $grid.attr('data-js-rows', rows);
                
                // Setup grid items
                ProductGridFlexbox.setupGridItems($grid);
                
                
            });
        },

        // Setup individual grid items
        setupGridItems: function($grid) {
            const $items = $grid.find('.product-grid-item');
            
            $items.each(function(index) {
                const $item = $(this);
                
                // Add index for animations
                $item.attr('data-grid-index', index);
                
                // Ensure card structure
                ProductGridFlexbox.ensureCardStructure($item);
            });
        },

        // Ensure proper card structure for flexbox
        ensureCardStructure: function($item) {
            const $card = $item.find('.product-card-modern');
            
            if ($card.length) {
                // Ensure flexbox structure
                if (!$card.hasClass('d-flex')) {
                    $card.addClass('d-flex flex-column h-100');
                }
                
                // Ensure card body grows
                const $cardBody = $card.find('.card-body');
                if ($cardBody.length && !$cardBody.hasClass('flex-grow-1')) {
                    $cardBody.addClass('flex-grow-1 d-flex flex-column');
                }
                
                // Ensure actions are at bottom
                const $actions = $card.find('.product-actions, .add-to-cart-wrapper');
                if ($actions.length && !$actions.hasClass('mt-auto')) {
                    $actions.addClass('mt-auto');
                }
            }
        },

        // Handle responsive resize
        handleResponsiveResize: function() {
            let resizeTimer;
            
            $(window).on('resize.productGridFlexbox', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    ProductGridFlexbox.updateGridsOnResize();
                }, 250);
            });
        },

        // Update grids on window resize
        updateGridsOnResize: function() {
            $('.products-grid-flexbox').each(function() {
                const $grid = $(this);
                ProductGridFlexbox.equalizeCardHeights($grid);
            });
        },

        // Equalize card heights within the same row
        equalizeCardHeights: function($grid) {
            if (!$grid) {
                $('.products-grid-flexbox').each(function() {
                    ProductGridFlexbox.equalizeCardHeights($(this));
                });
                return;
            }
            
            const columns = parseInt($grid.data('js-columns')) || 4;
            const $items = $grid.find('.product-grid-item');
            
            // Reset heights
            $items.find('.product-card-modern').css('height', 'auto');
            
            // Group items by rows and equalize heights
            for (let row = 0; row * columns < $items.length; row++) {
                const rowStart = row * columns;
                const rowEnd = Math.min(rowStart + columns, $items.length);
                const $rowItems = $items.slice(rowStart, rowEnd);
                
                if ($rowItems.length > 1) {
                    this.equalizeRowHeights($rowItems);
                }
            }
        },

        // Equalize heights for items in the same row
        equalizeRowHeights: function($rowItems) {
            let maxHeight = 0;
            
            // Find maximum height
            $rowItems.each(function() {
                const $card = $(this).find('.product-card-modern');
                const height = $card.outerHeight();
                maxHeight = Math.max(maxHeight, height);
            });
            
            // Apply maximum height to all cards in the row
            if (maxHeight > 0) {
                $rowItems.each(function() {
                    $(this).find('.product-card-modern').css('height', maxHeight + 'px');
                });
            }
        },

        // Debug mode toggle
        toggleDebugMode: function($grid) {
            if (!$grid) {
                $('.products-grid-flexbox').toggleClass('debug-mode');
                return;
            }
            
            $grid.toggleClass('debug-mode');
        },

        // Get grid info for debugging
        getGridInfo: function($grid) {
            const columns = parseInt($grid.data('js-columns')) || 4;
            const rows = parseInt($grid.data('js-rows')) || 2;
            const totalItems = $grid.find('.product-grid-item').length;
            const gridId = $grid.attr('id');
            
            return {
                id: gridId,
                columns: columns,
                rows: rows,
                totalItems: totalItems,
                expectedItems: columns * rows,
                isComplete: totalItems >= columns * rows
            };
        },

        // Refresh all grids (useful after AJAX content updates)
        refresh: function() {
            this.setupFlexboxGrids();
            this.equalizeCardHeights();
            
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        ProductGridFlexbox.init();
    });

    // Re-equalize heights when images load
    $(window).on('load', function() {
        setTimeout(() => {
            ProductGridFlexbox.equalizeCardHeights();
        }, 100);
    });

    // Make ProductGridFlexbox globally accessible
    window.ProductGridFlexbox = ProductGridFlexbox;

    // Debug helpers (only in development)
    // Debug helper removido em produção

})(jQuery);
