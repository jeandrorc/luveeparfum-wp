<?php
/**
 * Product Card Widget for Elementor
 */

class Astra_Child_Product_Card_Widget extends \Elementor\Widget_Base
{

  public function get_name()
  {
    return 'astra_child_product_card';
  }

  public function get_title()
  {
    return esc_html__('Product Card', 'astra-child');
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
      'show_badge',
      [
        'label' => esc_html__('Show Badge', 'astra-child'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Show', 'astra-child'),
        'label_off' => esc_html__('Hide', 'astra-child'),
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'show_category',
      [
        'label' => esc_html__('Show Category', 'astra-child'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Show', 'astra-child'),
        'label_off' => esc_html__('Hide', 'astra-child'),
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'show_wishlist',
      [
        'label' => esc_html__('Show Wishlist', 'astra-child'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Show', 'astra-child'),
        'label_off' => esc_html__('Hide', 'astra-child'),
        'default' => 'yes',
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

    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'title_typography',
        'label' => esc_html__('Title Typography', 'astra-child'),
        'selector' => '{{WRAPPER}} .product-card__title',
      ]
    );

    $this->add_control(
      'title_color',
      [
        'label' => esc_html__('Title Color', 'astra-child'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .product-card__title' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'price_color',
      [
        'label' => esc_html__('Price Color', 'astra-child'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .product-card__current-price' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'button_color',
      [
        'label' => esc_html__('Button Color', 'astra-child'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .product-card__btn--primary' => 'background-color: {{VALUE}};',
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
    $product_badge = get_post_meta($product_id, '_product_badge', true);
    $product_categories = get_the_terms($product_id, 'product_category');

    // Calculate discount
    $discount_percentage = '';
    if ($product_old_price && $product_price) {
      $discount = (($product_old_price - $product_price) / $product_old_price) * 100;
      $discount_percentage = round($discount);
    }
    ?>

    <div class="product-card">
      <div class="product-card__image">
        <?php if (has_post_thumbnail($product_id)): ?>
          <?php echo get_the_post_thumbnail($product_id, 'medium'); ?>
        <?php else: ?>
          <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/placeholder-product.jpg"
            alt="<?php echo get_the_title($product_id); ?>">
        <?php endif; ?>

        <?php if ($product_badge && $settings['show_badge'] === 'yes'): ?>
          <div class="product-card__badge"><?php echo esc_html($product_badge); ?></div>
        <?php endif; ?>
      </div>

      <div class="product-card__content">
        <?php if ($product_categories && !is_wp_error($product_categories) && $settings['show_category'] === 'yes'): ?>
          <div class="product-card__category"><?php echo esc_html($product_categories[0]->name); ?></div>
        <?php endif; ?>

        <h3 class="product-card__title">
          <a href="<?php echo get_permalink($product_id); ?>"><?php echo get_the_title($product_id); ?></a>
        </h3>

        <div class="product-card__price">
          <?php if ($product_price): ?>
            <span class="product-card__current-price">R$ <?php echo number_format($product_price, 2, ',', '.'); ?></span>
          <?php endif; ?>

          <?php if ($product_old_price && $product_old_price > $product_price): ?>
            <span class="product-card__old-price">R$ <?php echo number_format($product_old_price, 2, ',', '.'); ?></span>
          <?php endif; ?>

          <?php if ($discount_percentage): ?>
            <span class="product-card__discount">-<?php echo $discount_percentage; ?>%</span>
          <?php endif; ?>
        </div>

        <div class="product-card__actions">
          <a href="<?php echo get_permalink($product_id); ?>" class="product-card__btn product-card__btn--primary">
            Ver Produto
          </a>
          <?php if ($settings['show_wishlist'] === 'yes'): ?>
            <button class="product-card__wishlist" data-product-id="<?php echo $product_id; ?>">
              <i class="fas fa-heart"></i>
            </button>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <?php
  }
}