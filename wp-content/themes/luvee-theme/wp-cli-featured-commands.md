# 🔄 Comandos WP-CLI para Produtos Featured

## 📋 Comandos Úteis para Gerenciar Produtos Featured

### **1. Marcar produto como featured:**
```bash
wp post meta update PRODUCT_ID _featured yes
```

### **2. Desmarcar produto como featured:**
```bash
wp post meta update PRODUCT_ID _featured no
```

### **3. Listar todos os produtos featured:**
```bash
wp post list --post_type=product --meta_key=_featured --meta_value=yes --fields=ID,post_title
```

### **4. Marcar múltiplos produtos:**
```bash
wp post meta update 123 _featured yes
wp post meta update 456 _featured yes
wp post meta update 789 _featured yes
```

### **5. Contar produtos featured:**
```bash
wp post list --post_type=product --meta_key=_featured --meta_value=yes --format=count
```

### **6. Verificar se produto específico é featured:**
```bash
wp post meta get PRODUCT_ID _featured
```

### **7. Desmarcar todos os produtos featured:**
```bash
wp post meta delete --all --meta_key=_featured --meta_value=yes
```

## 💡 Exemplos Práticos:

### Marcar os primeiros 5 produtos como featured:
```bash
# Pegar IDs dos primeiros 5 produtos
wp post list --post_type=product --posts_per_page=5 --fields=ID --format=csv | tail -n +2 | while read id; do
  wp post meta update $id _featured yes
  echo "✅ Produto $id marcado como featured"
done
```

### Marcar produtos de uma categoria específica:
```bash
# Assumindo que a categoria tem term_id = 25
wp post list --post_type=product --tax_query='[{"taxonomy":"product_cat","field":"term_id","terms":"25"}]' --fields=ID --format=csv | tail -n +2 | while read id; do
  wp post meta update $id _featured yes
  echo "✅ Produto $id da categoria marcado como featured"
done
```
