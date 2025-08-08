// === TESTE SIMPLES ===
// debug removido

window.testSimple = function() {
    
    return 'JavaScript carregando corretamente';
};

// Aplicar emojis imediatamente
const icons = {
    'magnifying-glass': '🔍',
    'user': '👤',
    'shopping-bag': '🛍️',
    'bars-3': '☰',
    'x-mark': '✕'
};

function applyTestEmojis() {
    const elements = document.querySelectorAll('[data-heroicon]');
    let count = 0;
    
    elements.forEach(el => {
        const iconName = el.getAttribute('data-heroicon');
        const emoji = icons[iconName];
        
        if (emoji) {
            el.innerHTML = emoji;
            el.style.fontSize = '16px';
            count++;
        }
    });
    
    
    return count;
}

// Aplicar automaticamente
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', applyTestEmojis);
} else {
    applyTestEmojis();
}

setTimeout(applyTestEmojis, 500);

// debug removido