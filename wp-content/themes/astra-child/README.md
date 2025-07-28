# Astra Child Theme - Ecommerce Components

Um child theme completo do Astra com componentes customizados para ecommerce, seguindo as melhores práticas de UX/UI e padrões modernos.

## 🚀 Características

### Componentes Incluídos

1. **Product Card** - Card de produto para listagem
2. **Mega Menu** - Menu expansivo com categorias
3. **Footer** - Rodapé completo com newsletter
4. **Hero Carousel** - Carrossel para página inicial

### Funcionalidades

- ✅ Design responsivo e moderno
- ✅ Animações suaves e interativas
- ✅ Suporte a touch/swipe
- ✅ Integração com AJAX para wishlist
- ✅ Meta boxes para informações de produto
- ✅ Sistema de categorias customizado
- ✅ Font Awesome para ícones
- ✅ CSS com escopo bem definido

## 📁 Estrutura do Projeto

```
astra-child/
├── style.css                 # Estilos principais
├── functions.php             # Funcionalidades PHP
├── template-ecommerce.php    # Template de demonstração
├── README.md                # Esta documentação
└── assets/
    └── js/
        └── components.js     # JavaScript dos componentes
```

## 🎨 Componentes

### 1. Product Card

**Uso:**
```php
<?php echo astra_child_product_card(); ?>
```

**Características:**
- Imagem do produto com hover effect
- Badge de promoção
- Preço atual e antigo
- Porcentagem de desconto
- Botão de wishlist
- Categoria do produto
- Design responsivo

**Meta Fields Necessários:**
- `_product_price` - Preço atual
- `_product_old_price` - Preço antigo
- `_product_sku` - SKU do produto
- `_product_badge` - Badge (Promoção, Novo, etc.)

### 2. Hero Carousel

**Uso:**
```php
<?php echo astra_child_hero_carousel(); ?>
```

**Características:**
- Autoplay com pausa no hover
- Navegação por setas e dots
- Suporte a touch/swipe
- Overlay gradiente
- Botões de call-to-action
- Transições suaves

### 3. Mega Menu

**Uso:**
```php
<?php echo astra_child_mega_menu(); ?>
```

**Características:**
- Categorias organizadas
- Links dinâmicos
- Hover effects
- Design responsivo
- Animações suaves

### 4. Footer

**Uso:**
```php
<?php echo astra_child_footer(); ?>
```

**Características:**
- Newsletter signup
- Links organizados por seção
- Redes sociais
- Design moderno
- Formulário funcional

## 🛠️ Instalação

1. **Ativar o Child Theme:**
   - Acesse WordPress Admin → Aparência → Temas
   - Ative o tema "Astra Child - Ecommerce"

2. **Configurar Produtos:**
   - Crie posts normais
   - Adicione informações de produto nos meta boxes
   - Adicione imagens destacadas

3. **Usar os Componentes:**
   - Use as funções PHP nos templates
   - Ou crie uma página com o template "Ecommerce Components Demo"

## 📝 Meta Boxes

O tema adiciona meta boxes automáticos para posts:

- **Preço (R$)** - Preço atual do produto
- **Preço Antigo (R$)** - Preço anterior para mostrar desconto
- **SKU** - Código do produto
- **Badge** - Texto para badge (Promoção, Novo, etc.)

## 🎯 Funcionalidades JavaScript

### Hero Carousel
- Autoplay com 5 segundos de intervalo
- Pausa no hover
- Navegação por setas e dots
- Suporte a touch/swipe
- Transições suaves

### Product Card
- Wishlist com AJAX
- Notificações de feedback
- Hover effects
- Loading states

### Mega Menu
- Hover para mostrar seções
- Clique para navegar
- Animações suaves

### Newsletter
- Validação de e-mail
- Feedback visual
- Loading states

## 🎨 Customização

### Cores Principais
```css
--primary-color: #3498db;
--secondary-color: #2980b9;
--success-color: #27ae60;
--danger-color: #ff4757;
--text-dark: #2c3e50;
--text-light: #6c757d;
```

### Breakpoints
```css
--mobile: 480px;
--tablet: 768px;
--desktop: 1200px;
```

## 📱 Responsividade

O tema é totalmente responsivo com:

- **Mobile First** approach
- Grid layouts adaptativos
- Touch-friendly interactions
- Otimização para diferentes tamanhos de tela

## 🔧 Personalização

### Alterar Cores
Edite as variáveis CSS no arquivo `style.css`:

```css
:root {
    --primary-color: #sua-cor;
    --secondary-color: #sua-cor-secundaria;
}
```

### Adicionar Novos Componentes
1. Crie a função PHP em `functions.php`
2. Adicione os estilos CSS em `style.css`
3. Adicione JavaScript em `assets/js/components.js`

## 🚀 Performance

- CSS otimizado com escopo bem definido
- JavaScript modular e eficiente
- Lazy loading para imagens
- Debounce e throttle para eventos
- Font Awesome via CDN

## 📋 Checklist de Implementação

- [ ] Ativar o child theme
- [ ] Configurar meta boxes para produtos
- [ ] Adicionar imagens aos produtos
- [ ] Testar responsividade
- [ ] Configurar newsletter (se necessário)
- [ ] Personalizar cores (opcional)
- [ ] Testar funcionalidades JavaScript

## 🐛 Troubleshooting

### Componentes não aparecem
- Verifique se o child theme está ativo
- Confirme se os arquivos estão carregando
- Verifique o console do navegador para erros JavaScript

### Estilos não aplicam
- Limpe o cache do WordPress
- Verifique se o CSS está sendo carregado
- Confirme se não há conflitos com outros plugins

### JavaScript não funciona
- Verifique se jQuery está carregado
- Confirme se o arquivo `components.js` está sendo carregado
- Verifique o console do navegador para erros

## 📞 Suporte

Para dúvidas ou problemas:

1. Verifique esta documentação
2. Teste em um ambiente limpo
3. Verifique compatibilidade com plugins
4. Consulte os logs de erro

## 📄 Licença

Este projeto é parte do tema Astra Child e segue as mesmas licenças do tema pai.

---

**Desenvolvido para Luvee** 🚀 