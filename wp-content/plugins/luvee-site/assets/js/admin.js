/**
 * JavaScript administrativo do plugin Luvee Site
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Inicializa funcionalidades do admin
        LuveeSiteAdmin.init();
        
    });
    
    /**
     * Objeto principal do admin
     */
    var LuveeSiteAdmin = {
        
        /**
         * Inicializa o admin
         */
        init: function() {
            this.initCarouselItems();
            this.initImageSelector();
            this.initConfirmDialogs();
        },
        
        /**
         * Inicializa o gerenciamento de itens do carrossel
         */
        initCarouselItems: function() {
            var self = this;
            var container = $('#carousel-items-container');
            var template = $('#carousel-item-template').html();
            var itemIndex = container.find('.carousel-item-wrapper').length;
            
            // Adicionar novo item
            $('#add-carousel-item').on('click', function(e) {
                e.preventDefault();
                
                if (template) {
                    var newItem = template.replace(/\{\{INDEX\}\}/g, itemIndex)
                                         .replace(/\{\{ITEM_NUMBER\}\}/g, itemIndex + 1);
                    container.append(newItem);
                    itemIndex++;
                    
                    // Atualiza numeração
                    self.updateItemNumbers();
                }
            });
            
            // Remover item
            container.on('click', '.remove-carousel-item', function(e) {
                e.preventDefault();
                
                if (confirm('Tem certeza que deseja remover este item?')) {
                    $(this).closest('.carousel-item-wrapper').remove();
                    self.updateItemNumbers();
                }
            });
        },
        
        /**
         * Atualiza a numeração dos itens
         */
        updateItemNumbers: function() {
            $('#carousel-items-container .carousel-item-wrapper').each(function(index) {
                $(this).find('.item-number').text(index + 1);
                $(this).attr('data-index', index);
                
                // Atualiza os nomes dos campos
                $(this).find('input, textarea, select').each(function() {
                    var name = $(this).attr('name');
                    if (name && name.indexOf('carousel_items[') === 0) {
                        var newName = name.replace(/carousel_items\[\d+\]/, 'carousel_items[' + index + ']');
                        $(this).attr('name', newName);
                    }
                });
            });
        },
        
        /**
         * Inicializa o seletor de imagens
         */
        initImageSelector: function() {
            var mediaUploader;
            
            $(document).on('click', '.select-image', function(e) {
                e.preventDefault();
                
                var button = $(this);
                var inputField = button.siblings('.carousel-image-url');
                var preview = button.siblings('.image-preview');
                
                // Se o media uploader já existe, abre ele
                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }
                
                // Cria uma nova instância do media uploader
                mediaUploader = wp.media({
                    title: 'Selecionar Imagem',
                    button: {
                        text: 'Usar esta imagem'
                    },
                    multiple: false
                });
                
                // Quando uma imagem é selecionada
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    
                    // Define a URL no campo
                    inputField.val(attachment.url);
                    
                    // Atualiza o preview
                    if (preview.length) {
                        preview.html('<img src="' + attachment.url + '" style="max-width: 200px; height: auto;" />');
                    }
                });
                
                // Abre o media uploader
                mediaUploader.open();
            });
        },
        
        /**
         * Inicializa diálogos de confirmação
         */
        initConfirmDialogs: function() {
            // Confirmação para exclusão de posts
            $('.trash a').on('click', function(e) {
                if (!confirm('Tem certeza que deseja mover este item para a lixeira?')) {
                    e.preventDefault();
                }
            });
            
            // Confirmação para exclusão permanente
            $('.delete a').on('click', function(e) {
                if (!confirm('Tem certeza que deseja excluir permanentemente este item? Esta ação não pode ser desfeita.')) {
                    e.preventDefault();
                }
            });
        },
        
        /**
         * Mostra/esconde campos baseado em condições
         */
        toggleFields: function() {
            // Mostra/esconde velocidade do autoplay
            $('#luvee_carousel_autoplay').on('change', function() {
                var speedRow = $('#luvee_carousel_autoplay_speed').closest('tr');
                if ($(this).is(':checked')) {
                    speedRow.show();
                } else {
                    speedRow.hide();
                }
            }).trigger('change');
        },
        
        /**
         * Valida formulários
         */
        validateForms: function() {
            // Validação básica para campos obrigatórios
            $('form').on('submit', function(e) {
                var hasError = false;
                
                // Verifica campos obrigatórios
                $(this).find('input[required], select[required], textarea[required]').each(function() {
                    if (!$(this).val().trim()) {
                        $(this).addClass('error');
                        hasError = true;
                    } else {
                        $(this).removeClass('error');
                    }
                });
                
                if (hasError) {
                    e.preventDefault();
                    alert('Por favor, preencha todos os campos obrigatórios.');
                }
            });
        },
        
        /**
         * Faz uma requisição AJAX
         */
        ajaxRequest: function(action, data, callback) {
            var requestData = {
                action: 'luvee_site_' + action,
                nonce: luvee_site_ajax.nonce
            };
            
            if (data) {
                $.extend(requestData, data);
            }
            
            $.ajax({
                url: luvee_site_ajax.ajax_url,
                type: 'POST',
                data: requestData,
                dataType: 'json',
                success: function(response) {
                    if (callback) {
                        callback(response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    if (callback) {
                        callback({success: false, message: 'Erro na requisição AJAX'});
                    }
                }
            });
        },
        
        /**
         * Mostra notificação
         */
        showNotice: function(message, type) {
            type = type || 'info';
            
            var notice = $('<div class="notice notice-' + type + ' is-dismissible"><p>' + message + '</p></div>');
            
            // Adiciona botão de fechar
            notice.append('<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dispensar este aviso.</span></button>');
            
            // Adiciona ao topo da página
            $('.wrap h1').after(notice);
            
            // Remove automaticamente após 5 segundos
            setTimeout(function() {
                notice.fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
            
            // Handler para o botão de fechar
            notice.find('.notice-dismiss').on('click', function() {
                notice.fadeOut(function() {
                    $(this).remove();
                });
            });
        }
    };
    
    // Disponibiliza globalmente
    window.LuveeSiteAdmin = LuveeSiteAdmin;
    
})(jQuery);
