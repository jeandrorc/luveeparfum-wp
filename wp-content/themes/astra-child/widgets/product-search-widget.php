<?php
/**
 * Product Search Widget for Elementor
 */

class Astra_Child_Product_Search_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'astra_child_product_search';
    }

    public function get_title() {
        return esc_html__('Product Search', 'astra-child');
    }

    public function get_icon() {
        return 'eicon-search';
    }

    public function get_categories() {
        return ['astra-child-ecommerce'];
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'astra-child'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'placeholder',
            [
                'label' => esc_html__('Placeholder', 'astra-child'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Buscar produtos...', 'astra-child'),
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => esc_html__('Button Text', 'astra-child'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Buscar', 'astra-child'),
            ]
        );

        $this->add_control(
            'show_categories',
            [
                'label' => esc_html__('Show Categories', 'astra-child'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'astra-child'),
                'label_off' => esc_html__('Hide', 'astra-child'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_price_range',
            [
                'label' => esc_html__('Show Price Range', 'astra-child'),
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
            'search_background',
            [
                'label' => esc_html__('Search Background', 'astra-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-search' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background',
            [
                'label' => esc_html__('Button Background', 'astra-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .search-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        
        <div class="product-search">
            <form class="search-form" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <div class="search-inputs">
                    <div class="search-field">
                        <input 
                            type="text" 
                            name="s" 
                            placeholder="<?php echo esc_attr($settings['placeholder']); ?>"
                            value="<?php echo get_search_query(); ?>"
                            class="search-input"
                        >
                        <input type="hidden" name="post_type" value="post">
                    </div>

                    <?php if ($settings['show_categories'] === 'yes') : ?>
                        <div class="search-field">
                            <select name="product_category" class="search-select">
                                <option value=""><?php esc_html_e('Todas as Categorias', 'astra-child'); ?></option>
                                <?php
                                $categories = get_terms([
                                    'taxonomy' => 'product_category',
                                    'hide_empty' => false,
                                ]);
                                
                                if (!is_wp_error($categories)) {
                                    foreach ($categories as $category) {
                                        $selected = isset($_GET['product_category']) && $_GET['product_category'] == $category->slug ? 'selected' : '';
                                        echo '<option value="' . esc_attr($category->slug) . '" ' . $selected . '>' . esc_html($category->name) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <?php if ($settings['show_price_range'] === 'yes') : ?>
                        <div class="search-field">
                            <select name="price_range" class="search-select">
                                <option value=""><?php esc_html_e('Qualquer Preço', 'astra-child'); ?></option>
                                <option value="0-50" <?php selected(isset($_GET['price_range']) ? $_GET['price_range'] : '', '0-50'); ?>>
                                    <?php esc_html_e('Até R$ 50', 'astra-child'); ?>
                                </option>
                                <option value="50-100" <?php selected(isset($_GET['price_range']) ? $_GET['price_range'] : '', '50-100'); ?>>
                                    <?php esc_html_e('R$ 50 - R$ 100', 'astra-child'); ?>
                                </option>
                                <option value="100-200" <?php selected(isset($_GET['price_range']) ? $_GET['price_range'] : '', '100-200'); ?>>
                                    <?php esc_html_e('R$ 100 - R$ 200', 'astra-child'); ?>
                                </option>
                                <option value="200+" <?php selected(isset($_GET['price_range']) ? $_GET['price_range'] : '', '200+'); ?>>
                                    <?php esc_html_e('Acima de R$ 200', 'astra-child'); ?>
                                </option>
                            </select>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i>
                        <?php echo esc_html($settings['button_text']); ?>
                    </button>
                </div>
            </form>
        </div>

        <style>
        .product-search {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .search-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .search-inputs {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 15px;
            align-items: end;
        }

        .search-field {
            display: flex;
            flex-direction: column;
        }

        .search-input,
        .search-select {
            padding: 12px 16px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .search-input:focus,
        .search-select:focus {
            outline: none;
            border-color: #3498db;
        }

        .search-button {
            padding: 12px 24px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .search-button:hover {
            background: #2980b9;
        }

        @media (max-width: 768px) {
            .search-inputs {
                grid-template-columns: 1fr;
                gap: 10px;
            }
        }
        </style>

        <?php
        // Handle search results
        if (is_search() && get_query_var('post_type') === 'post') {
            $this->render_search_results();
        }
    }

    private function render_search_results() {
        $search_query = get_search_query();
        $category_filter = isset($_GET['product_category']) ? $_GET['product_category'] : '';
        $price_range = isset($_GET['price_range']) ? $_GET['price_range'] : '';

        // Build meta query for price range
        $meta_query = [];
        if ($price_range) {
            $prices = explode('-', $price_range);
            if (count($prices) === 2) {
                $meta_query[] = [
                    'key' => '_product_price',
                    'value' => [$prices[0], $prices[1]],
                    'type' => 'NUMERIC',
                    'compare' => 'BETWEEN',
                ];
            } elseif ($price_range === '200+') {
                $meta_query[] = [
                    'key' => '_product_price',
                    'value' => 200,
                    'type' => 'NUMERIC',
                    'compare' => '>=',
                ];
            }
        }

        // Build tax query for category
        $tax_query = [];
        if ($category_filter) {
            $tax_query[] = [
                'taxonomy' => 'product_category',
                'field' => 'slug',
                'terms' => $category_filter,
            ];
        }

        $args = [
            'post_type' => 'post',
            'posts_per_page' => 12,
            's' => $search_query,
            'meta_query' => $meta_query,
        ];

        if (!empty($tax_query)) {
            $args['tax_query'] = $tax_query;
        }

        $search_query = new WP_Query($args);

        if ($search_query->have_posts()) :
            ?>
            <div class="search-results">
                <h3><?php printf(esc_html__('Resultados para: %s', 'astra-child'), get_search_query()); ?></h3>
                <div class="products-grid">
                    <?php while ($search_query->have_posts()) : $search_query->the_post(); ?>
                        <?php echo astra_child_product_card(); ?>
                    <?php endwhile; ?>
                </div>
                
                <?php if ($search_query->max_num_pages > 1) : ?>
                    <div class="search-pagination">
                        <?php
                        echo paginate_links([
                            'total' => $search_query->max_num_pages,
                            'current' => max(1, get_query_var('paged')),
                            'prev_text' => '<i class="fas fa-chevron-left"></i> Anterior',
                            'next_text' => 'Próximo <i class="fas fa-chevron-right"></i>',
                        ]);
                        ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php
            wp_reset_postdata();
        else :
            ?>
            <div class="no-results">
                <h3><?php esc_html_e('Nenhum produto encontrado', 'astra-child'); ?></h3>
                <p><?php esc_html_e('Tente ajustar os filtros de busca.', 'astra-child'); ?></p>
            </div>
            <?php
        endif;
    }
} 