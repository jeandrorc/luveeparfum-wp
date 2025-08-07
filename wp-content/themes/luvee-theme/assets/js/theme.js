/**
 * Luvée Theme JavaScript
 * Funcionalidades básicas e interações
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Inicialização de componentes Bootstrap se necessário
    initializeComponents();
    
    // Smooth scroll para âncoras
    initializeSmoothScroll();
    
    // Mobile menu handling
    initializeMobileMenu();
    
    // Component showcase features
    initializeComponentShowcase();
    
});

/**
 * Inicializar componentes Bootstrap
 */
function initializeComponents() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Inicializar popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
}

/**
 * Smooth scroll para links âncora
 */
function initializeSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

/**
 * Funcionalidades do menu mobile
 */
function initializeMobileMenu() {
    // Adicionar funcionalidades específicas do menu mobile se necessário
    const mobileToggle = document.querySelector('.navbar-toggler');
    if (mobileToggle) {
        mobileToggle.addEventListener('click', function() {
            // Adicionar animações ou comportamentos específicos
        });
    }
}

/**
 * Funcionalidades específicas da página de showcase de componentes
 */
function initializeComponentShowcase() {
    // Highlight de código se necessário
    highlightCodeBlocks();
    
    // Copy to clipboard para códigos
    initializeCopyToClipboard();
    
    // Navegação suave para TOC
    improveTableOfContents();
    
    // Demonstrações interativas
    initializeInteractiveDemos();
}

/**
 * Highlight de blocos de código
 */
function highlightCodeBlocks() {
    const codeBlocks = document.querySelectorAll('code');
    codeBlocks.forEach(block => {
        block.addEventListener('click', function() {
            // Selecionar texto do código ao clicar
            const range = document.createRange();
            range.selectNodeContents(this);
            const selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
        });
    });
}

/**
 * Copy to clipboard para códigos
 */
function initializeCopyToClipboard() {
    // Adicionar botão de copy para códigos se necessário
    const codeBlocks = document.querySelectorAll('code');
    codeBlocks.forEach(block => {
        if (block.textContent.length > 10) {
            block.style.cursor = 'pointer';
            block.title = 'Clique para selecionar';
        }
    });
}

/**
 * Melhorar Table of Contents
 */
function improveTableOfContents() {
    const tocLinks = document.querySelectorAll('.toc-nav a[href^="#"]');
    
    tocLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                const headerOffset = 100;
                const elementPosition = targetElement.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
                
                // Highlight temporário da seção
                targetElement.style.backgroundColor = 'rgba(212, 134, 156, 0.1)';
                setTimeout(() => {
                    targetElement.style.backgroundColor = '';
                }, 2000);
            }
        });
    });
}

/**
 * Demonstrações interativas
 */
function initializeInteractiveDemos() {
    // Adicionar interatividade aos botões de demonstração
    const demoButtons = document.querySelectorAll('.component-demo button');
    
    demoButtons.forEach(button => {
        if (!button.closest('form')) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Efeito visual de clique
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
                
                // Mostrar feedback
                showTemporaryMessage('Botão clicado! Este é apenas uma demonstração.');
            });
        }
    });
}

/**
 * Mostrar mensagem temporária
 */
function showTemporaryMessage(message) {
    const alert = document.createElement('div');
    alert.className = 'alert alert-info alert-dismissible fade show position-fixed';
    alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 300px;';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alert);
    
    // Auto-remover após 3 segundos
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 3000);
}