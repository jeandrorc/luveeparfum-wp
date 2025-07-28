<?php
/**
 * Single Product Widget for Elementor
 */

class Astra_Child_Single_Product_Widget extends \Elementor\Widget_Base
{

  public function get_name()
  {
    return 'astra_child_single_product';
  }

  public function get_title()
  {
    return esc_html__('Single Product', 'astra-child');
  }

  public function get_icon()
  {
    return 'eicon-product';
  }

  public function get_categories()
  {
    return ['astra-child-ecommerce'];
  }

  protected function register_controls()
  {
    // Content Section
    $this->start_controls_section(
      'content_section',
      [
        'label' => esc_html__('Content', 'astra-child'),
        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
      ]
    );

    $this->add_control(
      'product_id',
      [
        'label' => esc_html__('Product ID', 'astra-child'),
        'type' => \Elementor\Controls_Manager::NUMBER,
        'description' => esc_html__('Leave empty to use current product', 'astra-child'),
      ]
    );

    $this->add_control(
      'show_gallery',
      [
        'label' => esc_html__('Show Gallery', 'astra-child'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Show', 'astra-child'),
        'label_off' => esc_html__('Hide', 'astra-child'),
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'show_related',
      [
        'label' => esc_html__('Show Related Products', 'astra-child'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Show', 'astra-child'),
        'label_off' => esc_html__('Hide', 'astra-child'),
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'related_count',
      [
        'label' => esc_html__('Related Products Count', 'astra-child'),
        'type' => \Elementor\Controls_Manager::NUMBER,
        'default' => 4,
        'min' => 1,
        'max' => 12,
        'condition' => [
          'show_related' => 'yes',
        ],
      ]
    );

    $this->end_controls_section();

    // Style Section
    $this->start_controls_section(
      'style_section',
      [
        'label' => esc_html__('Style', 'astra-child'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'gallery_width',
      [
        'label' => esc_html__('Gallery Width', 'astra-child'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => ['%'],
        'range' => [
          '%' => [
            'min' => 30,
            'max' => 70,
            'step' => 5,
          ],
        ],
        'default' => [
          'unit' => '%',
          'size' => 50,
        ],
        'selectors' => [
          '{{WRAPPER}} .product-gallery' => 'width: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_section();
  }

  protected function render()
  {
    $settings = $this->get_settings_for_display();

    $product_id = $settings['product_id'] ?: get_the_ID();

    if (!$product_id) {
      echo '<p>' . esc_html__('No product selected', 'astra-child') . '</p>';
      return;
    }

    // Get product data
    $product_price = get_post_meta($product_id, '_product_price', true);
    $product_old_price = get_post_meta($product_id, '_product_old_price', true);
    $product_sku = get_post_meta($product_id, '_product_sku', true);
    $product_badge = get_post_meta($product_id, '_product_badge', true);
    $product_categories = get_the_terms($product_id, 'product_category');

    // Calculate discount
    $discount_percentage = '';
    if ($product_old_price && $product_price) {
      $discount = (($product_old_price - $product_price) / $product_old_price) * 100;
      $discount_percentage = round($discount);
    }
    ?>

    <div class="single-product-container">
      <div class="product-gallery">
        <?php if ($settings['show_gallery'] === 'yes'): ?>
          <div class="product-images">
            <?php if (has_post_thumbnail($product_id)): ?>
              <div class="main-image">
                <?php echo get_the_post_thumbnail($product_id, 'large'); ?>
              </div>
            <?php endif; ?>

            <?php
            // Get additional images
            $gallery_images = get_post_meta($product_id, '_product_gallery', true);
            if ($gallery_images):
              $gallery_array = explode(',', $gallery_images);
              ?>
              <div class="thumbnail-images">
                <?php foreach ($gallery_array as $image_id): ?>
                  <div class="thumbnail">
                    <?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>

      <div class="product-info">
        <?php if ($product_badge): ?>
          <div class="product-badge"><?php echo esc_html($product_badge); ?></div>
        <?php endif; ?>

        <h1 class="product-title"><?php echo get_the_title($product_id); ?></h1>

        <?php if ($product_categories && !is_wp_error($product_categories)): ?>
          <div class="product-category">
            <?php esc_html_e('Categoria:', 'astra-child'); ?>
            <a href="<?php echo get_term_link($product_categories[0]); ?>">
              <?php echo esc_html($product_categories[0]->name); ?>
            </a>
          </div>
        <?php endif; ?>

        <div class="product-price">
          <?php if ($product_price): ?>
            <span class="current-price">R$ <?php echo number_format($product_price, 2, ',', '.'); ?></span>
          <?php endif; ?>

          <?php if ($product_old_price && $product_old_price > $product_price): ?>
            <span class="old-price">R$ <?php echo number_format($product_old_price, 2, ',', '.'); ?></span>
          <?php endif; ?>

          <?php if ($discount_percentage): ?>
            <span class="discount-badge">-<?php echo $discount_percentage; ?>%</span>
          <?php endif; ?>
        </div>

        <?php if ($product_sku): ?>
          <div class="product-sku">
            <strong><?php esc_html_e('SKU:', 'astra-child'); ?></strong> <?php echo esc_html($product_sku); ?>
          </div>
        <?php endif; ?>

        <div class="product-description">
          <?php echo get_the_excerpt($product_id); ?>
        </div>

        <div class="product-actions">
          <button class="add-to-cart-btn">
            <i class="fas fa-shopping-cart"></i>
            <?php esc_html_e('Adicionar ao Carrinho', 'astra-child'); ?>
          </button>

          <button class="wishlist-btn" data-product-id="<?php echo $product_id; ?>">
            <i class="fas fa-heart"></i>
            <?php esc_html_e('Adicionar à Lista de Desejos', 'astra-child'); ?>
          </button>
        </div>

        <div class="product-meta">
          <div class="meta-item">
            <i class="fas fa-truck"></i>
            <span><?php esc_html_e('Frete grátis para todo o Brasil', 'astra-child'); ?></span>
          </div>
          <div class="meta-item">
            <i class="fas fa-shield-alt"></i>
            <span><?php esc_html_e('Garantia de 30 dias', 'astra-child'); ?></span>
          </div>
          <div class="meta-item">
            <i class="fas fa-undo"></i>
            <span><?php esc_html_e('Devolução em até 7 dias', 'astra-child'); ?></span>
          </div>
        </div>
      </div>
    </div>

    <?php if ($settings['show_related'] === 'yes'): ?>
      <div class="related-products">
        <h3><?php esc_html_e('Produtos Relacionados', 'astra-child'); ?></h3>
        <div class="products-grid">
          <?php
          $related_args = [
            'post_type' => 'post',
            'posts_per_page' => $settings['related_count'],
            'post__not_in' => [$product_id],
            'meta_query' => [
              [
                'key' => '_product_price',
                'compare' => 'EXISTS',
              ],
            ],
          ];

          if ($product_categories && !is_wp_error($product_categories)) {
            $related_args['tax_query'] = [
              [
                'taxonomy' => 'product_category',
                'field' => 'term_id',
                'terms' => $product_categories[0]->term_id,
              ],
            ];
          }

          $related_products = new WP_Query($related_args);

          if ($related_products->have_posts()):
            while ($related_products->have_posts()):
              $related_products->the_post();
              echo astra_child_product_card();
            endwhile;
            wp_reset_postdata();
          endif;
          ?>
        </div>
      </div>
    <?php endif; ?>

    <style>
      .single-product-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        margin-bottom: 60px;
      }

      .product-gallery {
        position: sticky;
        top: 20px;
      }

      .main-image {
        margin-bottom: 20px;
      }

      .main-image img {
        width: 100%;
        height: auto;
        border-radius: 12px;
      }

      .thumbnail-images {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
      }

      .thumbnail img {
        width: 100%;
        height: auto;
        border-radius: 8px;
        cursor: pointer;
        transition: opacity 0.3s ease;
      }

      .thumbnail img:hover {
        opacity: 0.8;
      }

      .product-info {
        padding: 20px 0;
      }

      .product-title {
        font-size: 32px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 16px;
      }

      .product-category {
        margin-bottom: 20px;
        color: #6c757d;
      }

      .product-category a {
        color: #3498db;
        text-decoration: none;
      }

      .product-price {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
      }

      .current-price {
        font-size: 28px;
        font-weight: 700;
        color: #3498db;
      }

      .old-price {
        font-size: 18px;
        color: #6c757d;
        text-decoration: line-through;
      }

      .discount-badge {
        background: #27ae60;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
      }

      .product-sku {
        margin-bottom: 20px;
        color: #6c757d;
      }

      .product-description {
        margin-bottom: 30px;
        line-height: 1.6;
        color: #6c757d;
      }

      .product-actions {
        display: flex;
        gap: 16px;
        margin-bottom: 30px;
        flex-wrap: wrap;
      }

      .add-to-cart-btn,
      .wishlist-btn {
        padding: 15px 30px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
      }

      .add-to-cart-btn {
        background: #3498db;
        color: white;
        flex: 1;
      }

      .add-to-cart-btn:hover {
        background: #2980b9;
      }

      .wishlist-btn {
        background: transparent;
        color: #3498db;
        border: 2px solid #3498db;
      }

      .wishlist-btn:hover {
        background: #3498db;
        color: white;
      }

      .product-meta {
        border-top: 1px solid #e9ecef;
        padding-top: 20px;
      }

      .meta-item {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        color: #6c757d;
      }

      .meta-item i {
        color: #3498db;
        width: 20px;
      }

      .related-products {
        margin-top: 60px;
      }

      .related-products h3 {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 30px;
        text-align: center;
      }

      @media (max-width: 768px) {
        .single-product-container {
          grid-template-columns: 1fr;
          gap: 30px;
        }

        .product-actions {
          flex-direction: column;
        }

        .thumbnail-images {
          grid-template-columns: repeat(3, 1fr);
        }
      }
    </style>

    <script>
      // Gallery functionality
      document.addEventListener('DOMContentLoaded', function () {
        const thumbnails = document.querySelectorAll('.thumbnail img');
        const mainImage = document.querySelector('.main-image img');

        if (thumbnails.length && mainImage) {
          thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function () {
              mainImage.src = this.src.replace('-150x150', '');
              thumbnails.forEach(t => t.style.opacity = '1');
              this.style.opacity = '0.6';
            });
          });
        }
      });
    </script>

    <?php
  }
}