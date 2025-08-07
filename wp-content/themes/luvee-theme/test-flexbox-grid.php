<?php
/**
 * Teste do Grid Flexbox
 * Verificar se o novo sistema flexbox está funcionando
 */

// Simulação de teste (você pode adicionar na homepage temporariamente)
?>

<!-- Teste Grid 2×2 Flexbox -->
<?php luvee_get_template_part('product-section', null, array(
  'title' => 'Teste Grid 2×2 Flexbox',
  'subtitle' => 'Testando o novo sistema flexbox',
  'display_mode' => 'grid',
  'type' => 'featured',
  'columns' => 2,
  'rows' => 2,
  'show_view_all' => false,
  'section_id' => 'test-2x2-flexbox'
)); ?>

<!-- Teste Grid 3×3 Flexbox -->
<?php luvee_get_template_part('product-section', null, array(
  'title' => 'Teste Grid 3×3 Flexbox',
  'subtitle' => 'Grid quadrado com flexbox',
  'display_mode' => 'grid',
  'type' => 'recent',
  'columns' => 3,
  'rows' => 3,
  'show_view_all' => false,
  'section_id' => 'test-3x3-flexbox'
)); ?>

<!-- Teste Grid 4×2 Flexbox -->
<?php luvee_get_template_part('product-section', null, array(
  'title' => 'Teste Grid 4×2 Flexbox',
  'subtitle' => 'Layout padrão com flexbox',
  'display_mode' => 'grid',
  'type' => 'featured',
  'columns' => 4,
  'rows' => 2,
  'show_view_all' => false,
  'section_id' => 'test-4x2-flexbox'
)); ?>

<!-- Teste Grid 6×1 Flexbox -->
<?php luvee_get_template_part('product-section', null, array(
  'title' => 'Teste Grid 6×1 Flexbox',
  'subtitle' => 'Linha horizontal com flexbox',
  'display_mode' => 'grid',
  'type' => 'recent',
  'columns' => 6,
  'rows' => 1,
  'show_view_all' => false,
  'section_id' => 'test-6x1-flexbox'
)); ?>

<!-- Comparação: Carrossel vs Grid -->
<div class="row mt-5">
  <div class="col-md-6">
    <!-- Carrossel -->
    <?php luvee_get_template_part('product-section', null, array(
      'title' => 'Carrossel (Comparação)',
      'display_mode' => 'carousel',
      'type' => 'featured',
      'columns' => 3,
      'limit' => 9,
      'carousel_autoplay' => false,
      'show_view_all' => false,
      'section_id' => 'test-carousel-comparison'
    )); ?>
  </div>

  <div class="col-md-6">
    <!-- Grid Flexbox -->
    <?php luvee_get_template_part('product-section', null, array(
      'title' => 'Grid Flexbox (Comparação)',
      'display_mode' => 'grid',
      'type' => 'featured',
      'columns' => 3,
      'rows' => 3,
      'show_view_all' => false,
      'section_id' => 'test-grid-comparison'
    )); ?>
  </div>
</div>

<!-- Debug Info -->
<div class="alert alert-info mt-4">
  <h5>🔧 Informações de Debug:</h5>
  <ul>
    <li><strong>Grid Flexbox:</strong> CSS dinâmico injetado por seção</li>
    <li><strong>Responsividade:</strong> Adapta automaticamente no mobile</li>
    <li><strong>Altura Consistente:</strong> Cards com altura uniforme</li>
    <li><strong>Gap Flexível:</strong> Espaçamento responsivo entre items</li>
  </ul>

  <h6>📱 Breakpoints:</h6>
  <ul>
    <li><strong>&lt;576px:</strong> 1 coluna</li>
    <li><strong>576-767px:</strong> Máximo 2 colunas</li>
    <li><strong>768-991px:</strong> Máximo 3 colunas</li>
    <li><strong>992-1199px:</strong> Máximo 4 colunas</li>
    <li><strong>&gt;1200px:</strong> Layout original</li>
  </ul>
</div>