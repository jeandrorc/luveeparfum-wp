<?php
/**
 * Teste da Funcionalidade Best Sellers
 * Verificar e testar produtos mais vendidos
 */

// Incluir WordPress
require_once('../../../../wp-config.php');
require_once('../../../../wp-load.php');

if (!function_exists('WC')) {
    die('❌ WooCommerce não está ativo!');
}

if (!class_exists('Luvee_Site_Featured_Products')) {
    die('❌ Plugin Luvee Site não está ativo!');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>🏆 Teste - Best Sellers Functionality</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body { background: #f8f9fa; padding: 2rem 0; }
        .test-section { background: white; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .test-result { padding: 1rem; border-radius: 4px; margin: 1rem 0; }
        .test-success { background: #d4edda; border-left: 4px solid #28a745; }
        .test-error { background: #f8d7da; border-left: 4px solid #dc3545; }
        .test-warning { background: #fff3cd; border-left: 4px solid #ffc107; }
        .test-info { background: #d1ecf1; border-left: 4px solid #0dcaf0; }
        .code-block { background: #f8f9fa; padding: 1rem; border-radius: 4px; font-family: monospace; font-size: 0.9rem; }
        .product-card { border: 1px solid #ddd; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; }
        .sales-badge { background: linear-gradient(135deg, #ff6b9d 0%, #ff8cc8 100%); color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.8rem; }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center mb-5">🏆 Teste - Best Sellers Functionality</h1>
    
    <!-- Status da API -->
    <div class="test-section">
        <h2>🔍 Status da API Best Sellers</h2>
        
        <?php
        // Verificar métodos disponíveis
        $methods = [
            'get_best_sellers' => method_exists('Luvee_Site_Featured_Products', 'get_best_sellers'),
            'get_sales_stats' => method_exists('Luvee_Site_Featured_Products', 'get_sales_stats'),
            'simulate_sales' => method_exists('Luvee_Site_Featured_Products', 'simulate_sales'),
        ];
        
        foreach ($methods as $method => $exists) {
            $status = $exists ? 'test-success' : 'test-error';
            $icon = $exists ? '✅' : '❌';
            echo "<div class='test-result {$status}'>{$icon} <strong>Método {$method}():</strong> " . ($exists ? 'Disponível' : 'Não encontrado') . "</div>";
        }
        ?>
    </div>

    <!-- Estatísticas de Vendas -->
    <div class="test-section">
        <h2>📊 Estatísticas Atuais de Vendas</h2>
        
        <?php
        $stats = Luvee_Site_Featured_Products::get_sales_stats();
        ?>
        
        <div class="row">
            <div class="col-md-3">
                <div class="test-result test-info text-center">
                    <h3><?php echo $stats['products_with_sales']; ?></h3>
                    <strong>Produtos com Vendas</strong>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="test-result test-info text-center">
                    <h3><?php echo number_format($stats['total_sales']); ?></h3>
                    <strong>Total de Vendas</strong>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="test-result <?php echo $stats['top_seller_id'] ? 'test-success' : 'test-warning'; ?> text-center">
                    <h3><?php echo $stats['top_seller_sales']; ?></h3>
                    <strong>Top Seller</strong>
                    <?php if ($stats['top_seller_id']): ?>
                        <br><small>ID: <?php echo $stats['top_seller_id']; ?></small>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="test-result <?php echo ($stats['products_with_sales'] > 0) ? 'test-success' : 'test-warning'; ?> text-center">
                    <h3><?php echo ($stats['products_with_sales'] > 0) ? '✅' : '⚠️'; ?></h3>
                    <strong>Status</strong>
                    <br><small><?php echo ($stats['products_with_sales'] > 0) ? 'Dados OK' : 'Sem dados'; ?></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Simular Vendas (se necessário) -->
    <?php if ($stats['products_with_sales'] === 0): ?>
    <div class="test-section">
        <h2>🎭 Simular Dados de Teste</h2>
        
        <div class="alert alert-warning">
            <h5>⚠️ Nenhum produto com vendas encontrado!</h5>
            <p>Vou simular alguns dados de vendas para testar a funcionalidade.</p>
        </div>
        
        <?php
        // Obter alguns produtos para simular vendas
        $test_products = wc_get_products(array('limit' => 5, 'status' => 'publish'));
        
        if (!empty($test_products)) {
            echo "<h4>🎲 Simulando vendas para produtos:</h4>";
            
            foreach ($test_products as $product) {
                $sales_count = rand(5, 50);
                $result = Luvee_Site_Featured_Products::simulate_sales($product->get_id(), $sales_count);
                
                $status = $result ? 'test-success' : 'test-error';
                $icon = $result ? '✅' : '❌';
                
                echo "<div class='test-result {$status}'>";
                echo "{$icon} <strong>{$product->get_name()}</strong> (ID: {$product->get_id()}): {$sales_count} vendas simuladas";
                echo "</div>";
            }
            
            // Atualizar stats
            $stats = Luvee_Site_Featured_Products::get_sales_stats();
            
            echo "<div class='alert alert-info mt-3'>";
            echo "<strong>📈 Dados atualizados:</strong><br>";
            echo "• Produtos com vendas: {$stats['products_with_sales']}<br>";
            echo "• Total de vendas: " . number_format($stats['total_sales']) . "<br>";
            echo "• Top seller: {$stats['top_seller_sales']} vendas";
            echo "</div>";
        }
        ?>
    </div>
    <?php endif; ?>

    <!-- Teste Best Sellers Query -->
    <div class="test-section">
        <h2>🏆 Produtos Best Sellers</h2>
        
        <?php
        // Obter best sellers
        $best_sellers = Luvee_Site_Featured_Products::get_best_sellers(array('limit' => 8));
        $count = count($best_sellers);
        
        if ($count > 0) {
            echo "<div class='test-result test-success'>";
            echo "✅ <strong>{$count} produtos best sellers encontrados!</strong>";
            echo "</div>";
            
            echo "<div class='row'>";
            foreach ($best_sellers as $index => $product) {
                $sales = get_post_meta($product->get_id(), 'total_sales', true);
                $price = $product->get_price_html();
                
                echo "<div class='col-md-6 col-lg-4'>";
                echo "<div class='product-card'>";
                echo "<h6>{$product->get_name()}</h6>";
                echo "<p class='mb-1'><strong>Preço:</strong> {$price}</p>";
                echo "<p class='mb-1'><strong>ID:</strong> {$product->get_id()}</p>";
                echo "<div class='d-flex justify-content-between align-items-center'>";
                echo "<span class='sales-badge'>🏆 {$sales} vendas</span>";
                echo "<small class='text-muted'>#{$index + 1}</small>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<div class='test-result test-warning'>";
            echo "⚠️ <strong>Nenhum produto best seller encontrado</strong>";
            echo "<br><small>Certifique-se de que existem produtos com vendas registradas (meta_key: total_sales)</small>";
            echo "</div>";
        }
        ?>
    </div>

    <!-- Teste Product Section -->
    <div class="test-section">
        <h2>🎯 Teste Product Section Integration</h2>
        
        <p>Para usar a funcionalidade best sellers no frontend, utilize o seguinte código:</p>
        
        <div class="code-block">
&lt;?php luvee_get_template_part('product-section', null, array(<br>
&nbsp;&nbsp;&nbsp;&nbsp;'title' => 'Produtos Mais Vendidos',<br>
&nbsp;&nbsp;&nbsp;&nbsp;'subtitle' => 'Os favoritos dos nossos clientes',<br>
&nbsp;&nbsp;&nbsp;&nbsp;'type' => '<strong>best_sellers</strong>',<br>
&nbsp;&nbsp;&nbsp;&nbsp;'columns' => 4,<br>
&nbsp;&nbsp;&nbsp;&nbsp;'rows' => 2,<br>
&nbsp;&nbsp;&nbsp;&nbsp;'show_view_all' => true<br>
)); ?&gt;
        </div>
        
        <div class="alert alert-info mt-3">
            <h5>📋 Tipos Disponíveis:</h5>
            <ul class="mb-0">
                <li><code>'featured'</code> - Produtos em destaque</li>
                <li><code>'best_sellers'</code> - Produtos mais vendidos ⭐</li>
                <li><code>'recent'</code> - Produtos recentes</li>
                <li><code>'sale'</code> - Produtos em promoção</li>
                <li><code>'category'</code> - Por categoria específica</li>
            </ul>
        </div>
    </div>

    <!-- Query Debug -->
    <div class="test-section">
        <h2>🔍 Debug da Query Best Sellers</h2>
        
        <?php
        // Mostrar a query que está sendo usada
        $query_args = array(
            'limit' => 8,
            'status' => 'publish',
            'orderby' => 'meta_value_num',
            'order' => 'DESC',
            'meta_key' => 'total_sales',
            'meta_query' => array(
                array(
                    'key' => 'total_sales',
                    'value' => 0,
                    'compare' => '>',
                    'type' => 'NUMERIC'
                )
            )
        );
        
        echo "<h4>📋 Argumentos da Query:</h4>";
        echo "<pre class='code-block'>" . print_r($query_args, true) . "</pre>";
        
        // Debug SQL
        global $wpdb;
        $debug_query = "
            SELECT p.ID, p.post_title, pm.meta_value as total_sales
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            WHERE p.post_type = 'product'
            AND p.post_status = 'publish'
            AND pm.meta_key = 'total_sales'
            AND CAST(pm.meta_value AS UNSIGNED) > 0
            ORDER BY CAST(pm.meta_value AS UNSIGNED) DESC
            LIMIT 10
        ";
        
        $debug_results = $wpdb->get_results($debug_query);
        
        echo "<h4>🔍 SQL Debug (Top 10):</h4>";
        if (!empty($debug_results)) {
            echo "<div class='table-responsive'>";
            echo "<table class='table table-sm'>";
            echo "<thead><tr><th>ID</th><th>Produto</th><th>Vendas</th></tr></thead>";
            echo "<tbody>";
            foreach ($debug_results as $row) {
                echo "<tr>";
                echo "<td>{$row->ID}</td>";
                echo "<td>" . esc_html($row->post_title) . "</td>";
                echo "<td><span class='sales-badge'>{$row->total_sales}</span></td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
            echo "</div>";
        } else {
            echo "<div class='test-result test-warning'>⚠️ Nenhum resultado no SQL debug</div>";
        }
        ?>
    </div>

    <!-- API Examples -->
    <div class="test-section">
        <h2>💻 Exemplos de Uso da API</h2>
        
        <h4>🔧 PHP Examples:</h4>
        
        <div class="code-block">
// Obter 8 produtos mais vendidos<br>
$best_sellers = Luvee_Site_Featured_Products::get_best_sellers();<br><br>

// Com parâmetros customizados<br>
$best_sellers = Luvee_Site_Featured_Products::get_best_sellers(array(<br>
&nbsp;&nbsp;&nbsp;&nbsp;'limit' => 12,<br>
&nbsp;&nbsp;&nbsp;&nbsp;'meta_query' => array(<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;array(<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'key' => 'total_sales',<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'value' => 10,<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'compare' => '>=',<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'type' => 'NUMERIC'<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)<br>
&nbsp;&nbsp;&nbsp;&nbsp;)<br>
));<br><br>

// Obter estatísticas<br>
$stats = Luvee_Site_Featured_Products::get_sales_stats();<br>
echo "Total de vendas: " . $stats['total_sales'];<br><br>

// Simular vendas (para teste)<br>
Luvee_Site_Featured_Products::simulate_sales($product_id, 25);
        </div>
        
        <h4>🎨 Frontend Examples:</h4>
        
        <div class="code-block">
&lt;!-- Best Sellers Carousel --&gt;<br>
&lt;?php luvee_get_template_part('product-section', null, array(<br>
&nbsp;&nbsp;&nbsp;&nbsp;'title' => 'Mais Vendidos',<br>
&nbsp;&nbsp;&nbsp;&nbsp;'type' => 'best_sellers',<br>
&nbsp;&nbsp;&nbsp;&nbsp;'display_mode' => 'carousel',<br>
&nbsp;&nbsp;&nbsp;&nbsp;'columns' => 4<br>
)); ?&gt;<br><br>

&lt;!-- Best Sellers Grid --&gt;<br>
&lt;?php luvee_get_template_part('product-section', null, array(<br>
&nbsp;&nbsp;&nbsp;&nbsp;'title' => 'Top Vendas',<br>
&nbsp;&nbsp;&nbsp;&nbsp;'type' => 'best_sellers',<br>
&nbsp;&nbsp;&nbsp;&nbsp;'display_mode' => 'grid-flexbox',<br>
&nbsp;&nbsp;&nbsp;&nbsp;'columns' => 3,<br>
&nbsp;&nbsp;&nbsp;&nbsp;'rows' => 2<br>
)); ?&gt;
        </div>
    </div>

    <!-- Links Úteis -->
    <div class="test-section">
        <h2>🔗 Links e Recursos</h2>
        
        <div class="row">
            <div class="col-md-6">
                <h5>🛠️ Admin WordPress:</h5>
                <ul>
                    <li><a href="<?php echo admin_url('edit.php?post_type=product'); ?>" target="_blank">Gerenciar Produtos</a></li>
                    <li><a href="<?php echo admin_url('admin.php?page=wc-reports&tab=orders&report=sales_by_product'); ?>" target="_blank">Relatórios WooCommerce</a></li>
                </ul>
            </div>
            
            <div class="col-md-6">
                <h5>📚 Documentação:</h5>
                <ul>
                    <li><a href="FEATURED-PRODUCTS-PLUGIN.md" target="_blank">Plugin Documentation</a></li>
                    <li><a href="../../../themes/luvee-theme/ADVANCED-PRODUCT-SECTION.md" target="_blank">Product Section Guide</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <div class="alert alert-success">
            <h4>🏆 Best Sellers Functionality Implementada!</h4>
            <p>A funcionalidade está pronta. Use <code>'type' => 'best_sellers'</code> no product-section para exibir produtos mais vendidos.</p>
        </div>
    </div>
</div>

</body>
</html>
