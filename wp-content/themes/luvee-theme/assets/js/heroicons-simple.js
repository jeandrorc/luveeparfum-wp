// === HEROICONS SIMPLES - VERSÃO DE TESTE ===
// debug removido

// Função global de teste
window.testHeroicons = function() {
    
    
    const elements = document.querySelectorAll('[data-heroicon]');
    
    
    elements.forEach((el, index) => {
        const iconName = el.getAttribute('data-heroicon');
        
    });
    
    return {
        total: elements.length,
        elements: Array.from(elements).map(el => ({
            icon: el.getAttribute('data-heroicon'),
            classes: el.className,
            parent: el.parentNode?.tagName
        }))
    };
};

// Função para aplicar emojis imediatamente
window.simpleHeroicons = function() {
    
    
    const elements = document.querySelectorAll('[data-heroicon]');
    const icons = {
        'magnifying-glass': '🔍',
        'user': '👤',
        'shopping-bag': '🛍️',
        'bars-3': '☰',
        'x-mark': '✕'
    };
    
    let count = 0;
    elements.forEach(el => {
        const iconName = el.getAttribute('data-heroicon');
        const emoji = icons[iconName] || '●';
        
        if (el.innerHTML.trim() === '' || el.innerHTML === emoji) {
            el.innerHTML = emoji;
            el.style.fontSize = '16px';
            el.style.lineHeight = '1';
            count++;
            
        }
    });
    
    
    return count;
};

// Auto-aplicar emojis ao carregar
document.addEventListener('DOMContentLoaded', function() {
    
    setTimeout(simpleHeroicons, 500);
});

// Backup adicional
setTimeout(() => {
    
    simpleHeroicons();
}, 2000);

// debug removido