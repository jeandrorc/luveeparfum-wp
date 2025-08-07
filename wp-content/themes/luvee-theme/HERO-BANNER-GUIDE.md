# Guia do Hero Banner - Plugin Luvee Site

## ✅ Integração Concluída

O sistema de Hero Banner foi integrado com sucesso na `front-page.php` e está funcionando perfeitamente.

## 📍 Localização no Código

**Arquivo:** `wp-content/themes/luvee-theme/front-page.php`
**Linhas:** 10-14

```php
<!-- Hero Banner Section - Plugin Luvee Site -->
<?php 
// Exibe todos os hero banners ativos (carrossel automático se múltiplos)
echo do_shortcode('[luvee_hero]');
?>
```

## 🎯 Como Funciona

### 1 Banner Ativo
- Exibe como banner simples e estático
- Link opcional configurável
- Hover com overlay de conteúdo

### 2+ Banners Ativos  
- Automaticamente vira carrossel
- Autoplay de 5 segundos
- Setas de navegação
- Pontos indicadores
- Suporte a touch/swipe

## 🛠️ Gerenciamento

### No WordPress Admin:
1. Acesse **Luvee Site → Hero Banners**
2. Clique em **Adicionar Novo Hero Banner**
3. Configure:
   - **Título**: Nome do banner
   - **Conteúdo**: Descrição (opcional)
   - **URL da Imagem**: Selecione ou insira URL
   - **Alt Text**: Para SEO e acessibilidade
   - **Link**: URL de destino (opcional)
   - **Target**: Mesma janela ou nova janela
   - **Status**: Ativo/Inativo
   - **Datas**: Início e validade (opcional)

## 📱 Responsividade

O hero banner está totalmente integrado com o Bootstrap do tema:
- **Container alinhado**: Usa `container-xxl` igual ao header e conteúdo
- **Mesma largura**: Alinha perfeitamente com o resto do site
- **Breakpoints responsivos**: Segue padrões Bootstrap 5
- **Mobile-first**: Otimizado para celulares
- **Touch gestures**: Swipe para navegar no carrossel

### Larguras por Breakpoint (alinhadas com o site):
- **XXL (≥1400px)**: 1320px max-width (container-xxl)
- **XL (≥1200px)**: 1140px max-width (container-xl)
- **LG (≥992px)**: 960px max-width (container-lg)
- **MD (≥768px)**: 720px max-width (container-md)
- **SM (≥576px)**: 540px max-width (container-sm)
- **XS (<576px)**: 100% width com padding consistente

### Alturas Responsivas:
- **Desktop (≥768px)**: 450px altura máxima
- **Mobile (<768px)**: 300px altura máxima
- **Object-fit**: cover (mantém proporção e preenche)
- **Object-position**: center (centraliza a imagem)

## 🎨 Personalização CSS

O tema já inclui estilos personalizados em `front-page.php`:

```css
/* Hero Banner Integration - Boxed Layout */
.luvee-hero-container {
    margin: 0 0 20px 0 !important; /* Sem margem no topo - colado no header */
    padding: 0 !important;
}

.luvee-hero-container .container {
    /* Alinha com o container do header: container-fluid container-xxl */
    max-width: 1320px !important; /* container-xxl max-width */
    padding-left: var(--bs-gutter-x, 0.75rem) !important;
    padding-right: var(--bs-gutter-x, 0.75rem) !important;
    margin: 0 auto !important;
    width: 100% !important;
}

.luvee-hero-banner {
    border-radius: 0 !important; /* Sem bordas arredondadas */
    margin: 0 !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
    max-height: 450px !important; /* Altura máxima */
    overflow: hidden !important;
}

.luvee-hero-image {
    width: 100% !important;
    height: auto !important;
    max-height: 450px !important;
    object-fit: cover !important; /* Mantém proporção e preenche o espaço */
    object-position: center !important; /* Centraliza a imagem */
}
```

## 📊 Analytics

O sistema inclui rastreamento automático:
- **Visualizações**: Contadas automaticamente
- **Cliques**: Rastreados via JavaScript
- **Relatórios**: Disponíveis em **Luvee Site → Dashboard**

## 🔧 Shortcodes Disponíveis

```php
// Todos os hero banners (usado na home)
[luvee_hero]

// Banner específico
[luvee_hero id="123"]

// Com configurações customizadas
[luvee_hero autoplay="false" show_arrows="false"]
```

## ✨ Features Implementadas

✅ **Upload de imagem** com seletor visual  
✅ **Alt text** obrigatório para SEO  
✅ **Links configuráveis** com target  
✅ **Sistema de datas** com validade  
✅ **Carrossel automático** quando múltiplos banners  
✅ **Bootstrap compatible** (visual encaixotado)  
✅ **Hover effects** e animações  
✅ **Mobile-friendly** com touch support  
✅ **Analytics integrado**  

O sistema está **100% funcional** e pronto para uso! 🚀
