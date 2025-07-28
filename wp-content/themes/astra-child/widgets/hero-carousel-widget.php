<?php
/**
 * Hero Carousel Widget for Elementor
 */

class Astra_Child_Hero_Carousel_Widget extends \Elementor\Widget_Base
{

  public function get_name()
  {
    return 'astra_child_hero_carousel';
  }

  public function get_title()
  {
    return esc_html__('Hero Carousel', 'astra-child');
  }

  public function get_icon()
  {
    return 'eicon-slides';
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

    $repeater = new \Elementor\Repeater();

    $repeater->add_control(
      'slide_image',
      [
        'label' => esc_html__('Slide Image', 'astra-child'),
        'type' => \Elementor\Controls_Manager::MEDIA,
        'default' => [
          'url' => \Elementor\Utils::get_placeholder_image_src(),
        ],
      ]
    );

    $repeater->add_control(
      'slide_title',
      [
        'label' => esc_html__('Title', 'astra-child'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__('Slide Title', 'astra-child'),
      ]
    );

    $repeater->add_control(
      'slide_subtitle',
      [
        'label' => esc_html__('Subtitle', 'astra-child'),
        'type' => \Elementor\Controls_Manager::TEXTAREA,
        'default' => esc_html__('Slide subtitle text', 'astra-child'),
      ]
    );

    $repeater->add_control(
      'button_text',
      [
        'label' => esc_html__('Button Text', 'astra-child'),
        'type' => \Elementor\Controls_Manager::TEXT,
        'default' => esc_html__('Learn More', 'astra-child'),
      ]
    );

    $repeater->add_control(
      'button_url',
      [
        'label' => esc_html__('Button URL', 'astra-child'),
        'type' => \Elementor\Controls_Manager::URL,
        'placeholder' => esc_html__('https://your-link.com', 'astra-child'),
      ]
    );

    $this->add_control(
      'slides',
      [
        'label' => esc_html__('Slides', 'astra-child'),
        'type' => \Elementor\Controls_Manager::REPEATER,
        'fields' => $repeater->get_controls(),
        'default' => [
          [
            'slide_title' => esc_html__('Novos Produtos Chegaram', 'astra-child'),
            'slide_subtitle' => esc_html__('Descubra nossa coleção mais recente com descontos especiais', 'astra-child'),
            'button_text' => esc_html__('Comprar Agora', 'astra-child'),
          ],
          [
            'slide_title' => esc_html__('Ofertas Imperdíveis', 'astra-child'),
            'slide_subtitle' => esc_html__('Até 50% de desconto em produtos selecionados', 'astra-child'),
            'button_text' => esc_html__('Ver Ofertas', 'astra-child'),
          ],
        ],
        'title_field' => '{{{ slide_title }}}',
      ]
    );

    $this->end_controls_section();

    // Settings Section
    $this->start_controls_section(
      'settings_section',
      [
        'label' => esc_html__('Settings', 'astra-child'),
        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
      ]
    );

    $this->add_control(
      'autoplay',
      [
        'label' => esc_html__('Autoplay', 'astra-child'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'astra-child'),
        'label_off' => esc_html__('No', 'astra-child'),
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'autoplay_speed',
      [
        'label' => esc_html__('Autoplay Speed', 'astra-child'),
        'type' => \Elementor\Controls_Manager::NUMBER,
        'default' => 5000,
        'min' => 1000,
        'max' => 10000,
        'step' => 500,
        'condition' => [
          'autoplay' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'show_navigation',
      [
        'label' => esc_html__('Show Navigation', 'astra-child'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Show', 'astra-child'),
        'label_off' => esc_html__('Hide', 'astra-child'),
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'show_dots',
      [
        'label' => esc_html__('Show Dots', 'astra-child'),
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
      'title_color',
      [
        'label' => esc_html__('Title Color', 'astra-child'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .hero-carousel__title' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'subtitle_color',
      [
        'label' => esc_html__('Subtitle Color', 'astra-child'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .hero-carousel__subtitle' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'button_color',
      [
        'label' => esc_html__('Button Color', 'astra-child'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .hero-carousel__btn' => 'background-color: {{VALUE}};',
        ],
      ]
    );

    $this->end_controls_section();
  }

  protected function render()
  {
    $settings = $this->get_settings_for_display();

    if (empty($settings['slides'])) {
      return;
    }
    ?>

    <div class="hero-carousel" data-autoplay="<?php echo $settings['autoplay'] === 'yes' ? 'true' : 'false'; ?>"
      data-speed="<?php echo $settings['autoplay_speed']; ?>">

      <div class="hero-carousel__container">
        <?php foreach ($settings['slides'] as $index => $slide): ?>
          <div class="hero-carousel__slide">
            <img src="<?php echo esc_url($slide['slide_image']['url']); ?>"
              alt="<?php echo esc_attr($slide['slide_title']); ?>" class="hero-carousel__image">

            <div class="hero-carousel__overlay"></div>

            <div class="hero-carousel__content">
              <h2 class="hero-carousel__title"><?php echo esc_html($slide['slide_title']); ?></h2>
              <p class="hero-carousel__subtitle"><?php echo esc_html($slide['slide_subtitle']); ?></p>

              <?php if ($slide['button_text'] && $slide['button_url']['url']): ?>
                <a href="<?php echo esc_url($slide['button_url']['url']); ?>" class="hero-carousel__btn" <?php echo $slide['button_url']['is_external'] ? 'target="_blank"' : ''; ?>         <?php echo $slide['button_url']['nofollow'] ? 'rel="nofollow"' : ''; ?>>
                  <?php echo esc_html($slide['button_text']); ?>
                </a>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <?php if ($settings['show_navigation'] === 'yes'): ?>
        <button class="hero-carousel__nav hero-carousel__nav--prev">
          <i class="fas fa-chevron-left"></i>
        </button>
        <button class="hero-carousel__nav hero-carousel__nav--next">
          <i class="fas fa-chevron-right"></i>
        </button>
      <?php endif; ?>

      <?php if ($settings['show_dots'] === 'yes'): ?>
        <div class="hero-carousel__dots">
          <?php for ($i = 0; $i < count($settings['slides']); $i++): ?>
            <button class="hero-carousel__dot<?php echo $i === 0 ? ' active' : ''; ?>" data-slide="<?php echo $i; ?>"></button>
          <?php endfor; ?>
        </div>
      <?php endif; ?>
    </div>

    <?php
  }
}