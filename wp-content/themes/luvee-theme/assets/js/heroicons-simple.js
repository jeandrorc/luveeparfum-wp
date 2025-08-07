// === HEROICONS SIMPLES - VERSÃO DE TESTE ===
console.log('🧪 Heroicons Simple carregado!');

// Função global de teste
window.testHeroicons = function() {
    console.log('🧪 Teste Heroicons executado!');
    
    const elements = document.querySelectorAll('[data-heroicon]');
    console.log(`🔍 Encontrados ${elements.length} elementos com data-heroicon`);
    
    elements.forEach((el, index) => {
        const iconName = el.getAttribute('data-heroicon');
        console.log(`  ${index + 1}. ${iconName} - ${el.outerHTML}`);
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
    console.log('🔧 Aplicando emojis fallback...');
    
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
            console.log(`  ✅ ${iconName} → ${emoji}`);
        }
    });
    
    console.log(`✨ ${count} ícones atualizados com emojis`);
    return count;
};

// Auto-aplicar emojis ao carregar
document.addEventListener('DOMContentLoaded', function() {
    console.log('📚 DOM carregado, aplicando emojis...');
    setTimeout(simpleHeroicons, 500);
});

// Backup adicional
setTimeout(() => {
    console.log('🔄 Backup: aplicando emojis...');
    simpleHeroicons();
}, 2000);

console.log('🛠️ Funções disponíveis: testHeroicons(), simpleHeroicons()');