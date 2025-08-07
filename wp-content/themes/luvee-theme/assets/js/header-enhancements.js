/**
 * Header Enhancements - Funcionalidades avançadas do header
 * Luvée Theme
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // === HEADER STICKY DESABILITADO ===
    // Sticky header removido conforme solicitação do usuário
    
    // === SEARCH ENHANCEMENTS ===
    const searchInput = document.getElementById('header-search');
    const searchSuggestions = document.querySelector('.search-suggestions');
    
    if (searchInput && searchSuggestions) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            clearTimeout(searchTimeout);
            
            if (query.length >= 2) {
                searchTimeout = setTimeout(() => {
                    showSearchSuggestions(query);
                }, 300);
            } else {
                hideSearchSuggestions();
            }
        });
        
        // Esconder sugestões quando clicar fora
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
                hideSearchSuggestions();
            }
        });
    }
    
    function showSearchSuggestions(query) {
        const suggestionsContent = document.querySelector('.suggestions-content');
        if (!suggestionsContent) return;
        
        searchSuggestions.classList.remove('d-none');
        
        // Aqui você pode fazer uma chamada AJAX para buscar sugestões
        // Por enquanto, mostraremos apenas o loading
        suggestionsContent.innerHTML = `
            <div class="suggestions-loading text-center py-3">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Buscando...</span>
                </div>
            </div>
        `;
        
        // Simular busca (remover em produção)
        setTimeout(() => {
            suggestionsContent.innerHTML = `
                <div class="suggestion-item p-2 border-bottom">
                    <i class="fas fa-search me-2 text-muted"></i>
                    <span>${query} em perfumes</span>
                </div>
                <div class="suggestion-item p-2 border-bottom">
                    <i class="fas fa-search me-2 text-muted"></i>
                    <span>${query} em cosméticos</span>
                </div>
                <div class="suggestion-item p-2">
                    <i class="fas fa-fire me-2 text-primary"></i>
                    <span>Produtos em destaque</span>
                </div>
            `;
        }, 500);
    }
    
    function hideSearchSuggestions() {
        if (searchSuggestions) {
            searchSuggestions.classList.add('d-none');
        }
    }
    
    // === MEGA MENU ENHANCEMENTS ===
    const megaDropdowns = document.querySelectorAll('.mega-dropdown');
    
    megaDropdowns.forEach(dropdown => {
        const trigger = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.mega-menu');
        
        if (trigger && menu) {
            let hoverTimeout;
            
            // Remover comportamento de clique padrão do Bootstrap para desktop
            trigger.addEventListener('click', function(e) {
                if (window.innerWidth >= 992) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            });
            
            // Desabilitar dropdown do Bootstrap em desktop
            if (window.innerWidth >= 992) {
                trigger.removeAttribute('data-bs-toggle');
            }
            
            // Reabilitar no resize se necessário
            window.addEventListener('resize', function() {
                if (window.innerWidth < 992) {
                    trigger.setAttribute('data-bs-toggle', 'dropdown');
                } else {
                    trigger.removeAttribute('data-bs-toggle');
                }
            });
            
            // Mostrar no hover (desktop) imediatamente
            dropdown.addEventListener('mouseenter', function() {
                if (window.innerWidth >= 992) {
                    clearTimeout(hoverTimeout);
                    
                    // Fechar outros menus abertos
                    document.querySelectorAll('.mega-menu.show, .mega-menu.showing').forEach(otherMenu => {
                        if (otherMenu !== menu) {
                            otherMenu.classList.remove('show', 'showing');
                        }
                    });
                    
                    // Mostrar imediatamente
                    menu.classList.add('show', 'showing');
                    
                    // Ajustar posição se sair da tela
                    setTimeout(() => {
                        const rect = menu.getBoundingClientRect();
                        const viewportWidth = window.innerWidth;
                        const margin = 20; // Margem de segurança
                        
                        if (rect.right > (viewportWidth - margin)) {
                            menu.style.left = 'auto';
                            menu.style.right = '0';
                            menu.style.transform = 'translateX(0)';
                        } else if (rect.left < margin) {
                            menu.style.left = '0';
                            menu.style.right = 'auto';
                            menu.style.transform = 'translateX(0)';
                        } else {
                            menu.style.left = '50%';
                            menu.style.right = 'auto';
                            menu.style.transform = 'translateX(-50%)';
                        }
                    }, 10);
                }
            });
            
            // Hover também no trigger individual para mais tolerância
            trigger.addEventListener('mouseenter', function() {
                if (window.innerWidth >= 992) {
                    clearTimeout(hoverTimeout);
                }
            });
            
            // Esconder com delay MUITO maior do trigger (tolerância para movimento)
            dropdown.addEventListener('mouseleave', function(e) {
                if (window.innerWidth >= 992) {
                    // Verificar se o mouse está indo para o menu
                    const menuRect = menu.getBoundingClientRect();
                    const mouseX = e.clientX;
                    const mouseY = e.clientY;
                    
                    // Se o mouse está próximo do menu, dar mais tempo
                    const isNearMenu = (
                        mouseY >= menuRect.top - 50 &&
                        mouseY <= menuRect.bottom + 20 &&
                        mouseX >= menuRect.left - 20 &&
                        mouseX <= menuRect.right + 20
                    );
                    
                    const delay = isNearMenu ? 600 : 300; // Mais tempo se estiver próximo
                    
                    hoverTimeout = setTimeout(() => {
                        menu.classList.remove('show', 'showing');
                    }, delay);
                }
            });
            
            // Manter menu aberto quando hover no próprio menu
            menu.addEventListener('mouseenter', function() {
                if (window.innerWidth >= 992) {
                    clearTimeout(hoverTimeout);
                }
            });
            
            // Delay menor quando sai do menu (já está dentro)
            menu.addEventListener('mouseleave', function() {
                if (window.innerWidth >= 992) {
                    hoverTimeout = setTimeout(() => {
                        menu.classList.remove('show', 'showing');
                    }, 150); // Delay menor quando já estava no menu
                }
            });
        }
    });
    
    // === CART COUNT ANIMATION ===
    const cartLink = document.querySelector('.cart-link');
    const cartCount = document.querySelector('.cart-count');
    
    if (cartCount) {
        // Observer para mudanças no contador
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' || mutation.type === 'characterData') {
                    cartCount.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        cartCount.style.transform = 'scale(1)';
                    }, 200);
                }
            });
        });
        
        observer.observe(cartCount, {
            childList: true,
            characterData: true,
            subtree: true
        });
    }
    
    // === MOBILE MENU FULLSCREEN ===
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenuOverlay = document.getElementById('mobile-menu');
    const mobileMenuClose = document.getElementById('mobile-menu-close');
    
    if (mobileMenuToggle && mobileMenuOverlay && mobileMenuClose) {
        
        // Abrir menu (o onclick inline já cuida disso, mas mantemos como reforço)
        mobileMenuToggle.addEventListener('click', function(e) {
            if (!mobileMenuOverlay.classList.contains('show')) {
                mobileMenuOverlay.classList.add('show');
                document.body.classList.add('mobile-menu-open');
                document.body.style.overflow = 'hidden';
                
                // Foco no primeiro link para acessibilidade
                setTimeout(() => {
                    const firstLink = mobileMenuOverlay.querySelector('.mobile-menu-link');
                    if (firstLink) firstLink.focus();
                }, 300);
            }
        });
        
        // Fechar menu
        mobileMenuClose.addEventListener('click', function() {
            closeMobileMenu();
        });
        
        // Fechar ao clicar no overlay
        mobileMenuOverlay.addEventListener('click', function(e) {
            if (e.target === mobileMenuOverlay) {
                closeMobileMenu();
            }
        });
        
        // Fechar com ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenuOverlay.classList.contains('show')) {
                closeMobileMenu();
            }
        });
        
        // Função para fechar menu
        function closeMobileMenu() {
            mobileMenuOverlay.classList.remove('show');
            document.body.classList.remove('mobile-menu-open');
            document.body.style.overflow = ''; // Restaurar scroll
            mobileMenuToggle.focus(); // Retornar foco para o botão
        }
        
        // Fechar menu quando clicar em qualquer link
        const mobileLinks = mobileMenuOverlay.querySelectorAll('.mobile-menu-link, .mobile-category-item');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Pequeno delay para permitir a navegação
                setTimeout(() => {
                    closeMobileMenu();
                }, 100);
            });
        });
    } else {
        console.warn('⚠️ Alguns elementos do mobile menu não encontrados - usando onclick inline como fallback');
    }
    
    // Menu mobile com onclick inline já implementado no HTML como fallback principal
    
    // === PERFORMANCE OPTIMIZATIONS ===
    
    // Lazy load para imagens do mega menu
    const megaImages = document.querySelectorAll('.mega-menu img');
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                }
            });
        });
        
        megaImages.forEach(img => imageObserver.observe(img));
    }
    
    // Preload para links importantes
    const importantLinks = document.querySelectorAll('.nav-link[href*="shop"], .nav-link[href*="produtos"]');
    importantLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            const linkElement = document.createElement('link');
            linkElement.rel = 'prefetch';
            linkElement.href = this.href;
            document.head.appendChild(linkElement);
        }, { once: true });
    });
});

// === UTILITY FUNCTIONS ===

/**
 * Debounce function para otimizar eventos
 */
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

/**
 * Smooth scroll para links internos
 */
function initSmoothScroll() {
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Inicializar smooth scroll
initSmoothScroll();

// === TESTE MANUAL DO MOBILE MENU ===
// Função global para teste manual no console
window.testMobileMenu = function() {
    const overlay = document.getElementById('mobile-menu');
    const toggle = document.getElementById('mobile-menu-toggle');
    
    console.log('🧪 Teste manual do mobile menu:', {
        overlay: !!overlay,
        toggle: !!toggle,
        overlayClasses: overlay ? Array.from(overlay.classList) : 'não encontrado',
        overlayStyle: overlay ? getComputedStyle(overlay).display : 'não encontrado'
    });
    
    if (overlay) {
        console.log('🔄 Forçando abertura do menu...');
        overlay.classList.add('show');
        overlay.style.display = 'flex';
        overlay.style.opacity = '1';
        overlay.style.visibility = 'visible';
        overlay.style.pointerEvents = 'auto';
        
        console.log('✅ Menu forçado. Classes atuais:', Array.from(overlay.classList));
    }
    
    return { overlay, toggle };
};

// === TESTE DE CARREGAMENTO ===
console.log('🚀 Header enhancements carregado!');
console.log('📍 Arquivo: header-enhancements.js');
console.log('⏰ Timestamp:', new Date().toLocaleTimeString());

// Função de teste simples
window.testJS = function() {
    console.log('✅ JavaScript funcionando!');
    return 'JavaScript carregado corretamente';
};

// Log das funções disponíveis
console.log('🛠️ Funções de debug disponíveis:');
console.log('  - testHeroicons() - Debug completo');
console.log('  - simpleHeroicons() - Aplicar emojis fallback');
console.log('  - forceHeroicons() - Forçar reinicialização');
console.log('  - addHeroicon(nome, svg) - Adicionar novo ícone');

// Debug imediato dos elementos
setTimeout(() => {
    const debugElements = document.querySelectorAll('[data-heroicon]');
    console.log('🔍 Elementos com data-heroicon encontrados:', debugElements.length);
    debugElements.forEach((el, index) => {
        console.log(`  ${index + 1}. ${el.getAttribute('data-heroicon')} - Classes: ${el.className}`);
        console.log(`     Parent: ${el.parentNode?.tagName}, HTML: ${el.outerHTML}`);
    });
}, 500);

// === SISTEMA HEROICONS CUSTOMIZADO ===
// Mapeamento dos ícones Heroicons para seus SVGs

// Versão simplificada para debug imediato
window.simpleHeroicons = function() {
    console.log('🔧 Aplicando versão simplificada dos Heroicons...');
    const elements = document.querySelectorAll('[data-heroicon]');
    
    const simpleIcons = {
        'magnifying-glass': '🔍',
        'user': '👤', 
        'shopping-bag': '🛍️',
        'bars-3': '☰',
        'x-mark': '✕'
    };
    
    elements.forEach(el => {
        const iconName = el.getAttribute('data-heroicon');
        const fallback = simpleIcons[iconName] || '●';
        el.innerHTML = fallback;
        el.style.fontSize = '16px';
        console.log(`Aplicado fallback para ${iconName}: ${fallback}`);
    });
    
    return elements.length;
};

const heroiconsSVG = {
    'magnifying-glass': '<svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>',
    'user': '<svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>',
    'shopping-bag': '<svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119.993Z" /></svg>',
    'bars-3': '<svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>',
    'x-mark': '<svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>',
    // Ícones extras para uso futuro
    'heart': '<svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>',
    'star': '<svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>',
    'chevron-down': '<svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>',
    'home': '<svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>'
};

// Função para inicializar os ícones Heroicons
function initHeroicons() {
    console.log('🔄 Iniciando processamento de Heroicons...');
    const heroiconElements = document.querySelectorAll('[data-heroicon]');
    console.log(`📊 Encontrados ${heroiconElements.length} elementos para processar`);
    
    if (heroiconElements.length === 0) {
        console.warn('⚠️ Nenhum elemento [data-heroicon] encontrado no DOM');
        return;
    }
    
    heroiconElements.forEach((element, index) => {
        const iconName = element.getAttribute('data-heroicon');
        const svgContent = heroiconsSVG[iconName];
        
        console.log(`  🔄 Processando ícone ${index + 1}: "${iconName}"`);
        
        if (svgContent) {
            try {
                // Preservar classes existentes
                const existingClasses = element.className;
                const existingId = element.id;
                
                console.log(`    ✅ SVG encontrado para "${iconName}"`);
                console.log(`    📝 Classes: ${existingClasses}`);
                
                // Criar elemento SVG
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = svgContent;
                const svgElement = tempDiv.firstElementChild;
                
                if (!svgElement) {
                    console.error(`    ❌ Erro ao criar SVG para "${iconName}"`);
                    return;
                }
                
                // Aplicar classes e ID do elemento original
                svgElement.className = existingClasses;
                if (existingId) svgElement.id = existingId;
                
                // Adicionar atributos para acessibilidade e identificação
                svgElement.setAttribute('data-heroicon-processed', iconName);
                svgElement.setAttribute('aria-hidden', 'true');
                svgElement.setAttribute('focusable', 'false');
                
                // Substituir o elemento original
                element.parentNode.replaceChild(svgElement, element);
                console.log(`    ✅ Ícone "${iconName}" substituído com sucesso`);
                
            } catch (error) {
                console.error(`    ❌ Erro ao processar "${iconName}":`, error);
                element.innerHTML = '❌'; // Indicar erro visualmente
            }
        } else {
            console.warn(`    ⚠️ Ícone Heroicon não encontrado: "${iconName}"`);
            console.log(`    📚 Ícones disponíveis:`, Object.keys(heroiconsSVG));
            // Fallback: usar um ícone padrão ou manter o elemento original
            element.innerHTML = '⚪'; // Círculo como fallback visual
        }
    });
    
    const processedCount = heroiconElements.length;
    const finalSvgCount = document.querySelectorAll('svg[data-heroicon-processed]').length;
    console.log(`✨ Processamento concluído: ${processedCount} elementos processados, ${finalSvgCount} SVGs criados`);
    
    // Verificação final
    setTimeout(() => {
        const finalCheck = document.querySelectorAll('[data-heroicon]');
        if (finalCheck.length > 0) {
            console.warn('⚠️ Ainda existem elementos não processados:', finalCheck.length);
            finalCheck.forEach(el => {
                console.log(`   Elemento não processado: ${el.getAttribute('data-heroicon')}`);
            });
        }
    }, 100);
}

// Inicializar quando a página carregar
console.log('📍 Estado do documento:', document.readyState);

if (document.readyState === 'loading') {
    console.log('📚 Aguardando DOMContentLoaded...');
    document.addEventListener('DOMContentLoaded', () => {
        console.log('📚 DOMContentLoaded disparado!');
        setTimeout(initHeroicons, 100);
    });
} else {
    console.log('📚 DOM já carregado, inicializando imediatamente...');
    setTimeout(initHeroicons, 100);
}

// Backup com window.onload
window.addEventListener('load', () => {
    console.log('🪟 Window load disparado!');
    setTimeout(initHeroicons, 200);
});

// Reinicializar ícones após mudanças dinâmicas no DOM
const heroiconsObserver = new MutationObserver((mutations) => {
    let shouldReinit = false;
    
    mutations.forEach(mutation => {
        if (mutation.type === 'childList') {
            mutation.addedNodes.forEach(node => {
                if (node.nodeType === 1) { // Element node
                    if (node.hasAttribute && node.hasAttribute('data-heroicon')) {
                        shouldReinit = true;
                    } else if (node.querySelector && node.querySelector('[data-heroicon]')) {
                        shouldReinit = true;
                    }
                }
            });
        }
    });
    
    if (shouldReinit) {
        setTimeout(initHeroicons, 50); // Pequeno delay para aguardar o DOM se estabilizar
    }
});

heroiconsObserver.observe(document.body, {
    childList: true,
    subtree: true
});

// === GARANTIR CARREGAMENTO DOS ÍCONES ===
// Backup para garantir que os ícones sejam carregados
setTimeout(() => {
    console.log('🔄 Executando backup de inicialização...');
    initHeroicons();
}, 1000);

// Backup adicional mais tardio
setTimeout(() => {
    console.log('🔄 Executando segundo backup de inicialização...');
    initHeroicons();
}, 3000);

// === FALLBACK MANUAL PARA TESTE ===
// Adicionar ícones manualmente se o sistema automático falhar
window.forceHeroicons = function() {
    console.log('🚨 Forçando inicialização manual dos Heroicons...');
    
    // Tentar várias vezes até funcionar
    let attempts = 0;
    const maxAttempts = 5;
    
    const tryInit = () => {
        attempts++;
        console.log(`Tentativa ${attempts}/${maxAttempts}`);
        
        const elements = document.querySelectorAll('[data-heroicon]');
        console.log(`Elementos encontrados: ${elements.length}`);
        
        if (elements.length > 0) {
            initHeroicons();
            return true;
        }
        
        if (attempts < maxAttempts) {
            setTimeout(tryInit, 1000);
        } else {
            console.error('❌ Falha ao encontrar elementos após todas as tentativas');
        }
        
        return false;
    };
    
    tryInit();
};

// === FUNÇÃO DE TESTE E DEBUG PARA HEROICONS ===
window.testHeroicons = function() {
    const heroiconElements = document.querySelectorAll('[data-heroicon]');
    const svgElements = document.querySelectorAll('svg.heroicon');
    const processedElements = document.querySelectorAll('[data-heroicon-processed]');
    
    console.log('🧪 Teste Heroicons:', {
        'elementos-data-heroicon': heroiconElements.length,
        'elementos-svg': svgElements.length,
        'elementos-processados': processedElements.length,
        'ícones-disponíveis': Object.keys(heroiconsSVG),
        'elementos-encontrados': Array.from(heroiconElements).map(el => el.getAttribute('data-heroicon')),
        'elementos-processados-nomes': Array.from(processedElements).map(el => el.getAttribute('data-heroicon-processed'))
    });
    
    if (heroiconElements.length > 0) {
        console.log('🔄 Reinicializando ícones...');
        initHeroicons();
    }
    
    return {
        dataElements: heroiconElements.length,
        svgElements: svgElements.length,
        processedElements: processedElements.length,
        available: Object.keys(heroiconsSVG)
    };
};

// === FUNÇÃO PARA ADICIONAR NOVOS ÍCONES DINAMICAMENTE ===
window.addHeroicon = function(iconName, svgContent) {
    heroiconsSVG[iconName] = svgContent;
    console.log(`➕ Ícone '${iconName}' adicionado à biblioteca Heroicons`);
    initHeroicons(); // Reinicializar para processar novos ícones
};