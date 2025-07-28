<?php
/**
 * Product Grid Widget for Elementor
 */

class Astra_Child_Product_Grid_Widget extends \Elementor\Widget_Base
{

  public function get_name()
  {
    return 'astra_child_product_grid';
  }

  public function get_title()
  {
    return esc_html__('Product Grid', 'astra-child');
  }

  public function get_icon()
  {
    return 'eicon-products-grid';
  }

  public function get_categories()
  {
    return ['astra-child-ecommerce'];
  }

  protected function register_controls()
  {
    // Query Section
    $this->start_controls_section(
      'query_section',
      [
        'label' => esc_html__('Query', 'astra-child'),
        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
      ]
    );

    $this->add_control(
      'posts_per_page',
      [
        'label' => esc_html__('Products Per Page', 'astra-child'),
        'type' => \Elementor\Controls_Manager::NUMBER,
        'default' => 8,
        'min' => 1,
        'max' => 50,
      ]
    );

    $this->add_control(
      'orderby',
      [
        'label' => esc_html__('Order By', 'astra-child'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'date',
        'options' => [
          'date' => esc_html__('Date', 'astra-child'),
          'title' => esc_html__('Title', 'astra-child'),
          'menu_order' => esc_html__('Menu Order', 'astra-child'),
          'rand' => esc_html__('Random', 'astra-child'),
        ],
      ]
    );

    $this->add_control(
      'order',
      [
        'label' => esc_html__('Order', 'astra-child'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'DESC',
        'options' => [
          'DESC' => esc_html__('Descending', 'astra-child'),
          'ASC' => esc_html__('Ascending', 'astra-child'),
        ],
      ]
    );

    $this->add_control(
      'category',
      [
        'label' => esc_html__('Category', 'astra-child'),
        'type' => \Elementor\Controls_Manager::SELECT2,
        'options' => $this->get_product_categories(),
        'multiple' => true,
      ]
    );

    $this->end_controls_section();

    // Layout Section
    $this->start_controls_section(
      'layout_section',
      [
        'label' => esc_html__('Layout', 'astra-child'),
        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
      ]
    );

    $this->add_responsive_control(
      'columns',
      [
        'label' => esc_html__('Columns', 'astra-child'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => '3',
        'tablet_default' => '2',
        'mobile_default' => '1',
        'options' => [
          '1' => '1',
          '2' => '2',
          '3' => '3',
          '4' => '4',
          '5' => '5',
          '6' => '6',
        ],
        'selectors' => [
          '{{WRAPPER}} .products-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
        ],
      ]
    );

    $this->add_control(
      'show_pagination',
      [
        'label' => esc_html__('Show Pagination', 'astra-child'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Show', 'astra-child'),
        'label_off' => esc_html__('Hide', 'astra-child'),
        'default' => 'yes',
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

    $this->add_control(
      'grid_gap',
      [
        'label' => esc_html__('Grid Gap', 'astra-child'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => ['px'],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 100,
            'step' => 1,
          ],
        ],
        'default' => [
          'unit' => 'px',
          'size' => 30,
        ],
        'selectors' => [
          '{{WRAPPER}} .products-grid' => 'gap: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $this->end_controls_section();
  }

  private function get_product_categories()
  {
    $categories = get_terms([
      'taxonomy' => 'product_category',
      'hide_empty' => false,
    ]);

    $options = [];
    if (!is_wp_error($categories)) {
      foreach ($categories as $category) {
        $options[$category->term_id] = $category->name;
      }
    }

    return $options;
  }

  protected function render()
  {
    $settings = $this->get_settings_for_display();

    // Build query args
    $args = [
      'post_type' => 'post',
      'posts_per_page' => $settings['posts_per_page'],
      'orderby' => $settings['orderby'],
      'order' => $settings['order'],
      'meta_query' => [
        [
          'key' => '_product_price',
          'compare' => 'EXISTS',
        ],
      ],
    ];

    // Add category filter
    if (!empty($settings['category'])) {
      $args['tax_query'] = [
        [
          'taxonomy' => 'product_category',
          'field' => 'term_id',
          'terms' => $settings['category'],
        ],
      ];
    }

    $products_query = new WP_Query($args);

    if ($products_query->have_posts()):
      ?>
      <div class="products-grid">
        <?php while ($products_query->have_posts()):
          $products_query->the_post(); ?>
          <?php echo astra_child_product_card(); ?>
        <?php endwhile; ?>
      </div>

      <?php if ($settings['show_pagination'] === 'yes'): ?>
        <div class="products-pagination">
          <?php
          echo paginate_links([
            'total' => $products_query->max_num_pages,
            'current' => max(1, get_query_var('paged')),
            'prev_text' => '<i class="fas fa-chevron-left"></i> Anterior',
            'next_text' => 'Próximo <i class="fas fa-chevron-right"></i>',
          ]);
          ?>
        </div>
      <?php endif; ?>

      <?php
      wp_reset_postdata();
    else:
      ?>
      <p><?php esc_html_e('Nenhum produto encontrado.', 'astra-child'); ?></p>
      <?php
    endif;
  }
}