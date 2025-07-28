<?php
/**
 * Mega Menu Widget for Elementor
 */

class Astra_Child_Mega_Menu_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'astra_child_mega_menu';
    }

    public function get_title() {
        return esc_html__('Mega Menu', 'astra-child');
    }

    public function get_icon() {
        return 'eicon-nav-menu';
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

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'category_title',
            [
                'label' => esc_html__('Category Title', 'astra-child'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Category', 'astra-child'),
            ]
        );

        $repeater->add_control(
            'menu_items',
            [
                'label' => esc_html__('Menu Items', 'astra-child'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'item_text',
                        'label' => esc_html__('Item Text', 'astra-child'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => esc_html__('Menu Item', 'astra-child'),
                    ],
                    [
                        'name' => 'item_url',
                        'label' => esc_html__('Item URL', 'astra-child'),
                        'type' => \Elementor\Controls_Manager::URL,
                        'placeholder' => esc_html__('https://your-link.com', 'astra-child'),
                    ],
                ],
                'default' => [
                    [
                        'item_text' => esc_html__('Menu Item 1', 'astra-child'),
                    ],
                    [
                        'item_text' => esc_html__('Menu Item 2', 'astra-child'),
                    ],
                ],
                'title_field' => '{{{ item_text }}}',
            ]
        );

        $this->add_control(
            'menu_sections',
            [
                'label' => esc_html__('Menu Sections', 'astra-child'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'category_title' => esc_html__('Categorias', 'astra-child'),
                        'menu_items' => [
                            ['item_text' => esc_html__('Eletrônicos', 'astra-child')],
                            ['item_text' => esc_html__('Moda', 'astra-child')],
                            ['item_text' => esc_html__('Casa e Decoração', 'astra-child')],
                        ],
                    ],
                    [
                        'category_title' => esc_html__('Marcas', 'astra-child'),
                        'menu_items' => [
                            ['item_text' => esc_html__('Apple', 'astra-child')],
                            ['item_text' => esc_html__('Samsung', 'astra-child')],
                            ['item_text' => esc_html__('Nike', 'astra-child')],
                        ],
                    ],
                ],
                'title_field' => '{{{ category_title }}}',
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
            'menu_background',
            [
                'label' => esc_html__('Menu Background', 'astra-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mega-menu' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'category_color',
            [
                'label' => esc_html__('Category Color', 'astra-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mega-menu__section-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => esc_html__('Link Color', 'astra-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mega-menu__link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        if (empty($settings['menu_sections'])) {
            return;
        }
        ?>
        
        <div class="mega-menu">
            <div class="mega-menu__container">
                <div class="mega-menu__categories">
                    <?php foreach ($settings['menu_sections'] as $index => $section) : ?>
                        <div class="mega-menu__category" data-category="<?php echo $index; ?>">
                            <?php echo esc_html($section['category_title']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="mega-menu__content">
                    <?php foreach ($settings['menu_sections'] as $index => $section) : ?>
                        <div class="mega-menu__section" data-category="<?php echo $index; ?>">
                            <h4 class="mega-menu__section-title"><?php echo esc_html($section['category_title']); ?></h4>
                            <div class="mega-menu__links">
                                <?php 
                                if (!empty($section['menu_items'])) {
                                    foreach ($section['menu_items'] as $item) {
                                        $url = $item['item_url']['url'] ?: '#';
                                        $target = $item['item_url']['is_external'] ? 'target="_blank"' : '';
                                        $nofollow = $item['item_url']['nofollow'] ? 'rel="nofollow"' : '';
                                        ?>
                                        <a href="<?php echo esc_url($url); ?>" 
                                           class="mega-menu__link"
                                           <?php echo $target; ?>
                                           <?php echo $nofollow; ?>>
                                            <?php echo esc_html($item['item_text']); ?>
                                        </a>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <?php
    }
} 