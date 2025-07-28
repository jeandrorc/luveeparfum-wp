# 🚀 Guia de Instalação Rápida

## Passo a Passo para Ativar o Tema

### 1. Ativar o Child Theme
1. Acesse **WordPress Admin** → **Aparência** → **Temas**
2. Procure por **"Astra Child - Ecommerce"**
3. Clique em **"Ativar"**

### 2. Configurar Produtos (Opcional)
Se você quiser usar os produtos como posts normais:

1. Vá para **Posts** → **Adicionar Novo**
2. Adicione uma imagem destacada
3. Preencha os campos de produto que aparecem:
   - **Preço (R$)**
   - **Preço Antigo (R$)**
   - **SKU**
   - **Badge** (Promoção, Novo, etc.)

### 3. Criar Página de Demonstração
1. Vá para **Páginas** → **Adicionar Nova**
2. Título: "Componentes Ecommerce"
3. No painel direito, selecione **"Ecommerce Components Demo"** como template
4. Publique a página

### 4. Testar os Componentes
Acesse a página criada para ver:
- ✅ Hero Carousel
- ✅ Product Cards
- ✅ Mega Menu
- ✅ Footer com Newsletter

## 🎯 Funcionalidades Disponíveis

### Product Card
```php
<?php echo astra_child_product_card(); ?>
```

### Hero Carousel
```php
<?php echo astra_child_hero_carousel(); ?>
```

### Mega Menu
```php
<?php echo astra_child_mega_menu(); ?>
```

### Footer
```php
<?php echo astra_child_footer(); ?>
```

## 🔧 WooCommerce (Opcional)

Se você usa WooCommerce:
1. Instale e ative o plugin WooCommerce
2. O tema automaticamente integrará com ele
3. Os componentes funcionarão com produtos WooCommerce

## 🎨 Personalização

### Cores
Edite no arquivo `style.css`:
```css
:root {
    --primary-color: #sua-cor;
    --secondary-color: #sua-cor-secundaria;
}
```

### Componentes
- Todos os componentes são modulares
- CSS com escopo bem definido
- JavaScript otimizado e responsivo

## 📱 Responsividade

O tema é totalmente responsivo:
- Mobile First
- Touch-friendly
- Otimizado para todos os dispositivos

## 🚀 Pronto!

Seu tema está configurado e pronto para uso! 🎉

---

**Desenvolvido para Luvee** 🚀 