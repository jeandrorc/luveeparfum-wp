<?php
/**
 * Exemplos de Uso do Best Sellers
 * Como usar a funcionalidade de produtos mais vendidos
 */
?>

<!DOCTYPE html>
<html>

<head>
  <title>🏆 Best Sellers - Exemplos de Uso</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
      padding: 2rem 0;
    }

    .example-section {
      background: white;
      padding: 2rem;
      border-radius: 8px;
      margin-bottom: 2rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .code-block {
      background: #2d3748;
      color: #e2e8f0;
      padding: 1.5rem;
      border-radius: 8px;
      font-family: 'Courier New', monospace;
      font-size: 14px;
      overflow-x: auto;
    }

    .highlight {
      background: #4a5568;
      padding: 2px 4px;
      border-radius: 3px;
    }

    .comment {
      color: #68d391;
    }

    .string {
      color: #fbb6ce;
    }

    .keyword {
      color: #90cdf4;
    }
  </style>
</head>

<body>

  <div class="container">
    <h1 class="text-center mb-5">🏆 Best Sellers - Exemplos de Uso</h1>

    <!-- Exemplo 1: Grid Básico -->
    <div class="example-section">
      <h2>📊 Exemplo 1: Grid Básico de Best Sellers</h2>
      <p>Grid simples com 4 colunas e 2 linhas, mostrando os 8 produtos mais vendidos.</p>

      <div class="code-block">
        <span class="comment">&lt;!-- Best Sellers Grid Básico --&gt;</span><br>
        <span class="keyword">&lt;?php</span> <span class="function">luvee_get_template_part</span>(<span
          class="string">'product-section'</span>, <span class="keyword">null</span>, <span
          class="keyword">array</span>(<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'title'</span> => <span class="string">'Produtos Mais
          Vendidos'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'subtitle'</span> => <span class="string">'Os favoritos dos nossos
          clientes'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'type'</span> => <span class="highlight">'best_sellers'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'columns'</span> => <span class="number">4</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'rows'</span> => <span class="number">2</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'show_view_all'</span> => <span class="keyword">true</span><br>
        )); <span class="keyword">?&gt;</span>
      </div>

      <div class="alert alert-info mt-3">
        <strong>💡 Resultado:</strong> Grid responsivo com 8 produtos (4x2), título "Produtos Mais Vendidos" e botão
        "Ver Todos".
      </div>
    </div>

    <!-- Exemplo 2: Carrossel -->
    <div class="example-section">
      <h2>🎠 Exemplo 2: Carrossel de Best Sellers</h2>
      <p>Carrossel automático com navegação, ideal para destaque na homepage.</p>

      <div class="code-block">
        <span class="comment">&lt;!-- Best Sellers Carousel --&gt;</span><br>
        <span class="keyword">&lt;?php</span> <span class="function">luvee_get_template_part</span>(<span
          class="string">'product-section'</span>, <span class="keyword">null</span>, <span
          class="keyword">array</span>(<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'title'</span> => <span class="string">'🏆 Top Vendas'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'subtitle'</span> => <span class="string">'Descubra os produtos que
          mais fazem sucesso'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'type'</span> => <span class="highlight">'best_sellers'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'display_mode'</span> => <span
          class="highlight">'carousel'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'columns'</span> => <span class="number">4</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'limit'</span> => <span class="number">12</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'carousel_autoplay'</span> => <span
          class="keyword">true</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'carousel_speed'</span> => <span class="number">4000</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'carousel_arrows'</span> => <span class="keyword">true</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'carousel_dots'</span> => <span class="keyword">true</span><br>
        )); <span class="keyword">?&gt;</span>
      </div>

      <div class="alert alert-success mt-3">
        <strong>✨ Resultado:</strong> Carrossel com 12 produtos, autoplay de 4 segundos, setas de navegação e
        indicadores.
      </div>
    </div>

    <!-- Exemplo 3: Grid Flexbox Customizado -->
    <div class="example-section">
      <h2>📐 Exemplo 3: Grid Flexbox Customizado</h2>
      <p>Layout flexbox com mais produtos em uma grade compacta.</p>

      <div class="code-block">
        <span class="comment">&lt;!-- Best Sellers Flexbox Grid --&gt;</span><br>
        <span class="keyword">&lt;?php</span> <span class="function">luvee_get_template_part</span>(<span
          class="string">'product-section'</span>, <span class="keyword">null</span>, <span
          class="keyword">array</span>(<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'title'</span> => <span class="string">'Campeões de
          Venda'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'type'</span> => <span class="highlight">'best_sellers'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'display_mode'</span> => <span
          class="highlight">'grid-flexbox'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'columns'</span> => <span class="number">5</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'rows'</span> => <span class="number">2</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'section_id'</span> => <span
          class="string">'top-sellers-section'</span><br>
        )); <span class="keyword">?&gt;</span>
      </div>

      <div class="alert alert-warning mt-3">
        <strong>⚡ Resultado:</strong> Grid flexbox 5x2 (10 produtos), layout dinâmico que se adapta melhor ao conteúdo.
      </div>
    </div>

    <!-- Exemplo 4: Seção Compacta -->
    <div class="example-section">
      <h2>📱 Exemplo 4: Seção Compacta (Mobile-First)</h2>
      <p>Layout otimizado para móvel com menos produtos.</p>

      <div class="code-block">
        <span class="comment">&lt;!-- Best Sellers Compacto --&gt;</span><br>
        <span class="keyword">&lt;?php</span> <span class="function">luvee_get_template_part</span>(<span
          class="string">'product-section'</span>, <span class="keyword">null</span>, <span
          class="keyword">array</span>(<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'title'</span> => <span class="string">'Top 6'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'subtitle'</span> => <span class="string">'Os mais
          procurados'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'type'</span> => <span class="highlight">'best_sellers'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'columns'</span> => <span class="number">3</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'rows'</span> => <span class="number">2</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'show_view_all'</span> => <span class="keyword">false</span><br>
        )); <span class="keyword">?&gt;</span>
      </div>

      <div class="alert alert-info mt-3">
        <strong>📱 Resultado:</strong> Grid 3x2 compacto (6 produtos), sem botão "Ver Todos", ideal para seções
        secundárias.
      </div>
    </div>

    <!-- Exemplo 5: Homepage Completa -->
    <div class="example-section">
      <h2>🏠 Exemplo 5: Implementação na Homepage</h2>
      <p>Como integrar múltiplas seções na homepage do tema.</p>

      <div class="code-block">
        <span class="comment">&lt;!-- front-page.php ou homepage template --&gt;</span><br><br>

        <span class="comment">&lt;!-- Hero Section (exemplo) --&gt;</span><br>
        <span class="keyword">&lt;?php</span> <span class="function">get_template_part</span>(<span
          class="string">'components/hero-banner'</span>); <span class="keyword">?&gt;</span><br><br>

        <span class="comment">&lt;!-- Featured Products --&gt;</span><br>
        <span class="keyword">&lt;?php</span> <span class="function">luvee_get_template_part</span>(<span
          class="string">'product-section'</span>, <span class="keyword">null</span>, <span
          class="keyword">array</span>(<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'title'</span> => <span class="string">'Produtos em
          Destaque'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'type'</span> => <span class="string">'featured'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'display_mode'</span> => <span
          class="string">'carousel'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'columns'</span> => <span class="number">4</span><br>
        )); <span class="keyword">?&gt;</span><br><br>

        <span class="comment">&lt;!-- Best Sellers Section --&gt;</span><br>
        <span class="keyword">&lt;?php</span> <span class="function">luvee_get_template_part</span>(<span
          class="string">'product-section'</span>, <span class="keyword">null</span>, <span
          class="keyword">array</span>(<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'title'</span> => <span class="string">'Mais Vendidos'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'subtitle'</span> => <span class="string">'Os preferidos dos nossos
          clientes'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'type'</span> => <span class="highlight">'best_sellers'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'display_mode'</span> => <span
          class="string">'grid-flexbox'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'columns'</span> => <span class="number">4</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'rows'</span> => <span class="number">2</span><br>
        )); <span class="keyword">?&gt;</span><br><br>

        <span class="comment">&lt;!-- Products on Sale --&gt;</span><br>
        <span class="keyword">&lt;?php</span> <span class="function">luvee_get_template_part</span>(<span
          class="string">'product-section'</span>, <span class="keyword">null</span>, <span
          class="keyword">array</span>(<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'title'</span> => <span class="string">'Ofertas
          Especiais'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'type'</span> => <span class="string">'sale'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'columns'</span> => <span class="number">3</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'rows'</span> => <span class="number">1</span><br>
        )); <span class="keyword">?&gt;</span><br><br>

        <span class="comment">&lt;!-- Recent Products --&gt;</span><br>
        <span class="keyword">&lt;?php</span> <span class="function">luvee_get_template_part</span>(<span
          class="string">'product-section'</span>, <span class="keyword">null</span>, <span
          class="keyword">array</span>(<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'title'</span> => <span class="string">'Últimos
          Lançamentos'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'type'</span> => <span class="string">'recent'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'display_mode'</span> => <span
          class="string">'carousel'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'columns'</span> => <span class="number">4</span><br>
        )); <span class="keyword">?&gt;</span>
      </div>

      <div class="alert alert-success mt-3">
        <strong>🏠 Resultado:</strong> Homepage completa com hero, featured, best sellers, ofertas e lançamentos.
      </div>
    </div>

    <!-- API Avançada -->
    <div class="example-section">
      <h2>⚙️ Exemplo 6: Uso Avançado da API</h2>
      <p>Como usar a API PHP para customizações específicas.</p>

      <div class="code-block">
        <span class="comment">// Obter apenas produtos com mais de 10 vendas</span><br>
        <span class="keyword">$top_sellers</span> = <span
          class="function">Luvee_Site_Featured_Products::get_best_sellers</span>(<span class="keyword">array</span>(<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'limit'</span> => <span class="number">5</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'meta_query'</span> => <span class="keyword">array</span>(<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">array</span>(<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'key'</span> =>
        <span class="string">'total_sales'</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'value'</span> =>
        <span class="number">10</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'compare'</span> =>
        <span class="string">'>='</span>,<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="string">'type'</span> =>
        <span class="string">'NUMERIC'</span><br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)<br>
        &nbsp;&nbsp;&nbsp;&nbsp;)<br>
        ));<br><br>

        <span class="comment">// Obter estatísticas</span><br>
        <span class="keyword">$stats</span> = <span
          class="function">Luvee_Site_Featured_Products::get_sales_stats</span>();<br>
        <span class="keyword">echo</span> <span class="string">"Total de vendas: "</span> . <span
          class="keyword">$stats</span>[<span class="string">'total_sales'</span>];<br><br>

        <span class="comment">// Exibir top seller</span><br>
        <span class="keyword">if</span> (<span class="keyword">$stats</span>[<span
          class="string">'top_seller_id'</span>]) {<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">$product</span> = <span
          class="function">wc_get_product</span>(<span class="keyword">$stats</span>[<span
          class="string">'top_seller_id'</span>]);<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">echo</span> <span class="string">"🏆 Top Seller: "</span> . <span
          class="keyword">$product</span>-><span class="function">get_name</span>();<br>
        }<br><br>

        <span class="comment">// Loop customizado</span><br>
        <span class="keyword">foreach</span> (<span class="keyword">$top_sellers</span> <span class="keyword">as</span>
        <span class="keyword">$product</span>) {<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">$sales</span> = <span class="function">get_post_meta</span>(<span
          class="keyword">$product</span>-><span class="function">get_id</span>(), <span
          class="string">'total_sales'</span>, <span class="keyword">true</span>);<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">echo</span> <span class="string">"{$product->get_name()}: {$sales}
          vendas"</span>;<br>
        }
      </div>

      <div class="alert alert-info mt-3">
        <strong>🔧 Resultado:</strong> Controle total sobre a query e exibição dos best sellers.
      </div>
    </div>

    <!-- Configurações Importantes -->
    <div class="example-section">
      <h2>⚙️ Configurações e Parâmetros</h2>

      <div class="row">
        <div class="col-md-6">
          <h4>📋 Parâmetros Obrigatórios:</h4>
          <ul>
            <li><code>'type' => 'best_sellers'</code></li>
          </ul>

          <h4>🎯 Parâmetros Opcionais:</h4>
          <ul>
            <li><code>'title'</code> - Título da seção</li>
            <li><code>'subtitle'</code> - Subtítulo</li>
            <li><code>'columns'</code> - Colunas (1-6)</li>
            <li><code>'rows'</code> - Linhas</li>
            <li><code>'limit'</code> - Total de produtos</li>
            <li><code>'display_mode'</code> - grid|carousel|grid-flexbox</li>
          </ul>
        </div>

        <div class="col-md-6">
          <h4>🎠 Parâmetros do Carrossel:</h4>
          <ul>
            <li><code>'carousel_autoplay'</code> - true/false</li>
            <li><code>'carousel_speed'</code> - velocidade (ms)</li>
            <li><code>'carousel_arrows'</code> - setas</li>
            <li><code>'carousel_dots'</code> - indicadores</li>
          </ul>

          <h4>🔍 Outros:</h4>
          <ul>
            <li><code>'show_view_all'</code> - botão ver todos</li>
            <li><code>'section_id'</code> - ID personalizado</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Dicas de Performance -->
    <div class="example-section">
      <h2>⚡ Dicas de Performance e Boas Práticas</h2>

      <div class="row">
        <div class="col-md-6">
          <h4>✅ Recomendações:</h4>
          <ul>
            <li>Use <code>limit</code> entre 6-12 produtos</li>
            <li>Grid flexbox é mais responsivo</li>
            <li>Carousel ideal para hero sections</li>
            <li>Combine com cache de produtos</li>
          </ul>
        </div>

        <div class="col-md-6">
          <h4>⚠️ Cuidados:</h4>
          <ul>
            <li>Muitos produtos afetam performance</li>
            <li>Autoplay pode incomodar usuários</li>
            <li>Teste em dispositivos móveis</li>
            <li>Monitore queries SQL</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="text-center mt-4">
      <div class="alert alert-success">
        <h4>🏆 Best Sellers está pronto para uso!</h4>
        <p>Copie os exemplos acima e customize conforme sua necessidade. A funcionalidade está integrada no Plugin Luvee
          e no theme.</p>
      </div>

      <div class="btn-group" role="group">
        <a href="../../../plugins/luvee-site/test-best-sellers.php" class="btn btn-primary" target="_blank">🧪 Testar
          API</a>
        <a href="../../../plugins/luvee-site/FEATURED-PRODUCTS-PLUGIN.md" class="btn btn-outline-primary"
          target="_blank">📚 Documentação</a>
      </div>
    </div>
  </div>

</body>

</html>