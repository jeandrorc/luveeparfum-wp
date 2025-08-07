/**
 * JavaScript para funcionalidades admin dos produtos featured
 */

jQuery(document).ready(function($) {
    
    // ========== QUICK EDIT FUNCTIONALITY ==========
    
    // Preencher valores do quick edit
    $('a.editinline').on('click', function() {
        var postId = $(this).closest('tr').attr('id').replace('post-', '');
        var $row = $('#post-' + postId);
        var $editRow = $('#edit-' + postId);
        
        // Verificar se produto é featured
        var isFeatured = $row.find('.column-luvee_featured .star').hasClass('featured');
        
        if (isFeatured) {
            $editRow.find('select[name="luvee_featured_quick"]').val('yes');
        }
    });
    
    // Salvar quick edit
    $('#bulk-edit').on('click', '.save', function() {
        var $row = $(this).closest('#bulk-edit');
        var postId = $row.find('#post_ID').val();
        var featured = $row.find('select[name="luvee_featured_quick"]').val();
        
        if (featured && postId) {
            $.ajax({
                url: luvee_featured_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'save_featured_quick_edit',
                    post_id: postId,
                    luvee_featured_quick: featured,
                    nonce: luvee_featured_ajax.nonce
                },
                success: function(response) {
                    // Recarregar linha
                    location.reload();
                }
            });
        }
    });
    
    // ========== ENHANCED UX ==========
    
    // Tooltip para estrelas
    $('.column-luvee_featured span').each(function() {
        var $this = $(this);
        var title = $this.attr('title');
        
        $this.on('mouseenter', function() {
            showTooltip($this, title);
        }).on('mouseleave', function() {
            hideTooltip();
        });
    });
    
    // Click na estrela para toggle rápido
    $('.column-luvee_featured span').on('click', function(e) {
        e.preventDefault();
        var $this = $(this);
        var $row = $this.closest('tr');
        var postId = $row.attr('id').replace('post-', '');
        var isFeatured = $this.text() === '⭐';
        
        // Confirmar ação
        var action = isFeatured ? 'desmarcar' : 'marcar';
        if (confirm(`Deseja ${action} este produto como featured?`)) {
            toggleFeatured(postId, !isFeatured);
        }
    });
    
    // ========== BULK ACTIONS ENHANCEMENT ==========
    
    // Contador de selecionados para bulk actions
    $('input[name="post[]"]').on('change', function() {
        updateBulkCounter();
    });
    
    $('#cb-select-all-1, #cb-select-all-2').on('change', function() {
        updateBulkCounter();
    });
    
    // ========== STATISTICS WIDGET ==========
    
    // Adicionar widget de estatísticas
    if ($('.wp-list-table').length) {
        addStatsWidget();
    }
    
    // ========== HELPER FUNCTIONS ==========
    
    function toggleFeatured(postId, featured) {
        var $row = $('#post-' + postId);
        var $star = $row.find('.column-luvee_featured span');
        
        // Loading state
        $star.text('⏳').css('color', '#ccc');
        
        $.ajax({
            url: luvee_featured_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'save_featured_quick_edit',
                post_id: postId,
                luvee_featured_quick: featured ? 'yes' : 'no',
                nonce: luvee_featured_ajax.nonce
            },
            success: function(response) {
                // Atualizar visual
                if (featured) {
                    $star.text('⭐').css('color', '#e7b416').attr('title', 'Produto em destaque');
                } else {
                    $star.text('☆').css('color', '#ddd').attr('title', 'Produto normal');
                }
                
                // Mostrar notificação
                showNotification(featured ? 'Produto marcado como featured!' : 'Featured removido!', 'success');
                
                // Atualizar stats
                updateStatsWidget();
            },
            error: function() {
                // Reverter visual
                $star.text(featured ? '☆' : '⭐');
                showNotification('Erro ao atualizar produto!', 'error');
            }
        });
    }
    
    function updateBulkCounter() {
        var selected = $('input[name="post[]"]:checked').length;
        var $counter = $('#luvee-bulk-counter');
        
        if (selected > 0) {
            if ($counter.length === 0) {
                $counter = $('<div id="luvee-bulk-counter" style="background: #0073aa; color: white; padding: 5px 10px; border-radius: 3px; margin: 10px 0; font-size: 12px;"></div>');
                $('.tablenav.top').append($counter);
            }
            $counter.text(`${selected} produtos selecionados - Use as ações em lote para marcar/desmarcar featured`);
        } else {
            $counter.remove();
        }
    }
    
    function addStatsWidget() {
        // Contar produtos featured na página atual
        var featuredCount = $('.column-luvee_featured span').filter(function() {
            return $(this).text() === '⭐';
        }).length;
        
        var totalCount = $('.column-luvee_featured span').length;
        
        var $widget = $(`
            <div id="luvee-featured-stats" style="background: #fff; border: 1px solid #ccd0d4; padding: 15px; margin: 20px 0; border-radius: 4px;">
                <h4 style="margin: 0 0 10px 0; color: #23282d;">📊 Estatísticas Featured (página atual)</h4>
                <div style="display: flex; gap: 20px; font-size: 14px;">
                    <div><strong>Total:</strong> ${totalCount}</div>
                    <div><strong>Featured:</strong> <span style="color: #e7b416;">${featuredCount}</span></div>
                    <div><strong>Normal:</strong> ${totalCount - featuredCount}</div>
                    <div><strong>%:</strong> ${totalCount > 0 ? Math.round(featuredCount / totalCount * 100) : 0}%</div>
                </div>
                <div style="margin-top: 10px; font-size: 12px; color: #666;">
                    💡 <strong>Dica:</strong> Clique na estrela para toggle rápido, ou use ações em lote para múltiplos produtos.
                </div>
            </div>
        `);
        
        $('.wp-list-table').before($widget);
    }
    
    function updateStatsWidget() {
        setTimeout(function() {
            $('#luvee-featured-stats').remove();
            addStatsWidget();
        }, 100);
    }
    
    function showTooltip($element, text) {
        var $tooltip = $('<div class="luvee-tooltip" style="position: absolute; background: #333; color: white; padding: 5px 8px; border-radius: 3px; font-size: 11px; z-index: 9999; white-space: nowrap;"></div>');
        $tooltip.text(text);
        
        $('body').append($tooltip);
        
        var offset = $element.offset();
        $tooltip.css({
            top: offset.top - $tooltip.outerHeight() - 5,
            left: offset.left + ($element.outerWidth() / 2) - ($tooltip.outerWidth() / 2)
        });
    }
    
    function hideTooltip() {
        $('.luvee-tooltip').remove();
    }
    
    function showNotification(message, type) {
        var $notification = $(`
            <div class="notice notice-${type} is-dismissible" style="position: fixed; top: 32px; right: 20px; z-index: 99999; max-width: 300px;">
                <p><strong>${message}</strong></p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dispensar este aviso.</span></button>
            </div>
        `);
        
        $('body').append($notification);
        
        // Auto dismiss
        setTimeout(function() {
            $notification.fadeOut(function() {
                $(this).remove();
            });
        }, 3000);
        
        // Manual dismiss
        $notification.find('.notice-dismiss').on('click', function() {
            $notification.remove();
        });
    }
    
    // ========== META BOX ENHANCEMENTS ==========
    
    // Enhanced meta box no edit post
    if ($('#luvee_featured_product').length) {
        enhanceMetaBox();
    }
    
    function enhanceMetaBox() {
        var $metaBox = $('#luvee_featured_product');
        var $checkbox = $metaBox.find('#luvee_featured_checkbox');
        
        // Visual feedback no checkbox
        $checkbox.on('change', function() {
            var $label = $(this).next('span');
            if ($(this).is(':checked')) {
                $label.css('color', '#e7b416').html('⭐ Produto marcado como destaque');
            } else {
                $label.css('color', '#666').html('☆ Marcar como produto em destaque');
            }
        });
        
        // Trigger inicial
        $checkbox.trigger('change');
        
        // Preview do badge
        if ($checkbox.is(':checked')) {
            var $preview = $('<div style="margin-top: 15px; padding: 10px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 4px;"><strong>Preview:</strong><br><span class="badge badge-featured" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); color: #212529; padding: 4px 8px; border-radius: 15px; font-size: 11px;">✨ Destaque</span></div>');
            $metaBox.find('.inside').append($preview);
        }
    }
    
    // ========== KEYBOARD SHORTCUTS ==========
    
    $(document).on('keydown', function(e) {
        // Ctrl+Shift+F para toggle featured do produto em foco
        if (e.ctrlKey && e.shiftKey && e.which === 70) {
            e.preventDefault();
            var $focused = $('tr:hover, tr:focus');
            if ($focused.length) {
                var $star = $focused.find('.column-luvee_featured span');
                if ($star.length) {
                    $star.click();
                }
            }
        }
    });
    
    console.log('✅ Luvee Featured Products admin scripts loaded');
});

// ========== CSS INJECTION ==========

jQuery(document).ready(function($) {
    // Adicionar CSS customizado
    var customCSS = `
        <style>
            .column-luvee_featured { width: 60px; text-align: center; }
            .column-luvee_featured span { cursor: pointer; transition: all 0.2s ease; }
            .column-luvee_featured span:hover { transform: scale(1.2); }
            
            .luvee-featured-badge-single { margin-bottom: 15px; }
            .luvee-featured-price-badge { font-size: 0.8em; margin-left: 5px; }
            
            .badge-featured { 
                background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
                color: #212529;
                padding: 4px 8px;
                border-radius: 15px;
                font-size: 11px;
                font-weight: 600;
            }
            
            #luvee_featured_product .inside { padding: 12px; }
            #luvee_featured_product label { font-weight: 500; }
            
            .luvee-tooltip {
                box-shadow: 0 2px 8px rgba(0,0,0,0.2);
                animation: fadeIn 0.2s ease;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-5px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    `;
    
    $('head').append(customCSS);
});
