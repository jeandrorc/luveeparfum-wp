<?php
/**
 * Shop Sidebar with Advanced Filters
 *
 * @package Luvee
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

// Get current filters from URL
$current_filters = array(
    'min_price' => isset($_GET['min_price']) ? floatval($_GET['min_price']) : '',
    'max_price' => isset($_GET['max_price']) ? floatval($_GET['max_price']) : '',
    'product_cat' => isset($_GET['product_cat']) ? sanitize_text_field($_GET['product_cat']) : '',
    'product_tag' => isset($_GET['product_tag']) ? sanitize_text_field($_GET['product_tag']) : '',
    'rating_filter' => isset($_GET['rating_filter']) ? intval($_GET['rating_filter']) : '',
    'on_sale' => isset($_GET['on_sale']) ? 1 : 0,
    'in_stock' => isset($_GET['in_stock']) ? 1 : 0,
    'featured' => isset($_GET['featured']) ? 1 : 0,
    'orderby' => isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : '',
);

// Get price range
$price_range = luvee_get_product_price_range();
?>

<div class="shop-filters bg-white rounded-3 shadow-sm">
    <div class="filters-header p-4 border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="h5 mb-0 fw-semibold"><?php esc_html_e('Filtros', 'luvee'); ?></h4>
            <button type="button" class="btn btn-link btn-sm p-0 clear-filters text-primary fw-medium" 
                    style="<?php echo empty(array_filter($current_filters)) ? 'display:none;' : ''; ?>">
                <i class="fas fa-times me-1"></i><?php esc_html_e('Limpar', 'luvee'); ?>
            </button>
        </div>
    </div>

    <div class="filters-content p-4">

    <form id="shop-filters-form" method="get" action="">
        
        <!-- Price Range Filter -->
        <div class="filter-section mb-4">
            <h5 class="filter-title h6 mb-3 fw-semibold d-flex align-items-center">
                <i class="fas fa-dollar-sign me-2 text-primary"></i>
                <?php esc_html_e('Faixa de Preço', 'luvee'); ?>
            </h5>
            
            <div class="price-filter">
                <div class="price-inputs mb-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="number" 
                                   class="form-control form-control-sm" 
                                   name="min_price" 
                                   id="min_price"
                                   placeholder="Min"
                                   min="<?php echo esc_attr($price_range['min']); ?>"
                                   max="<?php echo esc_attr($price_range['max']); ?>"
                                   value="<?php echo esc_attr($current_filters['min_price']); ?>">
                        </div>
                        <div class="col-6">
                            <input type="number" 
                                   class="form-control form-control-sm" 
                                   name="max_price" 
                                   id="max_price"
                                   placeholder="Max"
                                   min="<?php echo esc_attr($price_range['min']); ?>"
                                   max="<?php echo esc_attr($price_range['max']); ?>"
                                   value="<?php echo esc_attr($current_filters['max_price']); ?>">
                        </div>
                    </div>
                </div>
                
                <!-- Price Range Slider -->
                <div class="price-slider">
                    <div id="price-range-slider" 
                         data-min="<?php echo esc_attr($price_range['min']); ?>"
                         data-max="<?php echo esc_attr($price_range['max']); ?>"
                         data-current-min="<?php echo esc_attr($current_filters['min_price'] ?: $price_range['min']); ?>"
                         data-current-max="<?php echo esc_attr($current_filters['max_price'] ?: $price_range['max']); ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Filter -->
        <?php 
        $product_categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'parent' => 0, // Only top-level categories
        ));
        
        if (!is_wp_error($product_categories) && !empty($product_categories)) : ?>
            <div class="filter-section mb-4">
                <h5 class="filter-title h6 mb-3 fw-semibold d-flex align-items-center">
                    <i class="fas fa-tags me-2 text-primary"></i>
                    <?php esc_html_e('Categorias', 'luvee'); ?>
                </h5>
                
                <div class="categories-filter">
                    <?php foreach ($product_categories as $category) : 
                        $child_categories = get_terms(array(
                            'taxonomy' => 'product_cat',
                            'hide_empty' => true,
                            'parent' => $category->term_id,
                        ));
                    ?>
                        <div class="category-item mb-2">
                            <div class="form-check">
                                <input type="radio" 
                                       class="form-check-input" 
                                       name="product_cat" 
                                       id="cat_<?php echo esc_attr($category->slug); ?>"
                                       value="<?php echo esc_attr($category->slug); ?>"
                                       <?php checked($current_filters['product_cat'], $category->slug); ?>>
                                <label class="form-check-label d-flex justify-content-between" 
                                       for="cat_<?php echo esc_attr($category->slug); ?>">
                                    <span><?php echo esc_html($category->name); ?></span>
                                    <span class="text-muted small">(<?php echo esc_html($category->count); ?>)</span>
                                </label>
                            </div>
                            
                            <?php if (!empty($child_categories)) : ?>
                                <div class="subcategories ms-4 mt-1">
                                    <?php foreach ($child_categories as $child_cat) : ?>
                                        <div class="form-check">
                                            <input type="radio" 
                                                   class="form-check-input" 
                                                   name="product_cat" 
                                                   id="cat_<?php echo esc_attr($child_cat->slug); ?>"
                                                   value="<?php echo esc_attr($child_cat->slug); ?>"
                                                   <?php checked($current_filters['product_cat'], $child_cat->slug); ?>>
                                            <label class="form-check-label d-flex justify-content-between small" 
                                                   for="cat_<?php echo esc_attr($child_cat->slug); ?>">
                                                <span><?php echo esc_html($child_cat->name); ?></span>
                                                <span class="text-muted">(<?php echo esc_html($child_cat->count); ?>)</span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Product Tags Filter -->
        <?php 
        $product_tags = get_terms(array(
            'taxonomy' => 'product_tag',
            'hide_empty' => true,
            'number' => 10, // Limit to avoid too many tags
        ));
        
        if (!is_wp_error($product_tags) && !empty($product_tags)) : ?>
            <div class="filter-section mb-4">
                <h5 class="filter-title h6 mb-3">
                    <i class="fas fa-hashtag me-2"></i>
                    <?php esc_html_e('Tags', 'luvee'); ?>
                </h5>
                
                <div class="tags-filter">
                    <select class="form-select form-select-sm" name="product_tag">
                        <option value=""><?php esc_html_e('Todas as tags', 'luvee'); ?></option>
                        <?php foreach ($product_tags as $tag) : ?>
                            <option value="<?php echo esc_attr($tag->slug); ?>" 
                                    <?php selected($current_filters['product_tag'], $tag->slug); ?>>
                                <?php echo esc_html($tag->name); ?> (<?php echo esc_html($tag->count); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        <?php endif; ?>

        <!-- Rating Filter -->
        <div class="filter-section mb-4">
            <h5 class="filter-title h6 mb-3">
                <i class="fas fa-star me-2"></i>
                <?php esc_html_e('Avaliação', 'luvee'); ?>
            </h5>
            
            <div class="rating-filter">
                <?php for ($i = 5; $i >= 1; $i--) : ?>
                    <div class="form-check">
                        <input type="radio" 
                               class="form-check-input" 
                               name="rating_filter" 
                               id="rating_<?php echo esc_attr($i); ?>"
                               value="<?php echo esc_attr($i); ?>"
                               <?php checked($current_filters['rating_filter'], $i); ?>>
                        <label class="form-check-label d-flex align-items-center" 
                               for="rating_<?php echo esc_attr($i); ?>">
                            <span class="rating-stars me-2">
                                <?php echo str_repeat('<i class="fas fa-star text-warning"></i>', $i); ?>
                                <?php echo str_repeat('<i class="far fa-star text-muted"></i>', 5 - $i); ?>
                            </span>
                            <span class="small"><?php echo esc_html__('e acima', 'luvee'); ?></span>
                        </label>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Product Status Filters -->
        <div class="filter-section mb-4">
            <h5 class="filter-title h6 mb-3">
                <i class="fas fa-filter me-2"></i>
                <?php esc_html_e('Status', 'luvee'); ?>
            </h5>
            
            <div class="status-filters">
                <div class="form-check">
                    <input type="checkbox" 
                           class="form-check-input" 
                           name="on_sale" 
                           id="on_sale"
                           value="1"
                           <?php checked($current_filters['on_sale'], 1); ?>>
                    <label class="form-check-label" for="on_sale">
                        <i class="fas fa-tag text-danger me-1"></i>
                        <?php esc_html_e('Em promoção', 'luvee'); ?>
                    </label>
                </div>
                
                <div class="form-check">
                    <input type="checkbox" 
                           class="form-check-input" 
                           name="in_stock" 
                           id="in_stock"
                           value="1"
                           <?php checked($current_filters['in_stock'], 1); ?>>
                    <label class="form-check-label" for="in_stock">
                        <i class="fas fa-check-circle text-success me-1"></i>
                        <?php esc_html_e('Em estoque', 'luvee'); ?>
                    </label>
                </div>
                
                <div class="form-check">
                    <input type="checkbox" 
                           class="form-check-input" 
                           name="featured" 
                           id="featured"
                           value="1"
                           <?php checked($current_filters['featured'], 1); ?>>
                    <label class="form-check-label" for="featured">
                        <i class="fas fa-star text-warning me-1"></i>
                        <?php esc_html_e('Em destaque', 'luvee'); ?>
                    </label>
                </div>
            </div>
        </div>

        <!-- Apply/Reset Buttons -->
        <div class="filter-actions pt-3 border-top">
            <button type="submit" class="btn btn-primary w-100 mb-3 fw-semibold">
                <i class="fas fa-search me-2"></i>
                <?php esc_html_e('Aplicar Filtros', 'luvee'); ?>
            </button>
            
            <button type="button" class="btn btn-outline-secondary w-100 clear-filters">
                <i class="fas fa-undo me-2"></i>
                <?php esc_html_e('Limpar Filtros', 'luvee'); ?>
            </button>
        </div>

        <!-- Preserve other query parameters -->
        <?php if (isset($_GET['s'])) : ?>
            <input type="hidden" name="s" value="<?php echo esc_attr($_GET['s']); ?>">
        <?php endif; ?>
        
        <?php if (isset($_GET['post_type'])) : ?>
            <input type="hidden" name="post_type" value="<?php echo esc_attr($_GET['post_type']); ?>">
        <?php endif; ?>
    </form>

    </div> <!-- .filters-content -->
</div> <!-- .shop-filters -->

<style>
.shop-filters {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 1.5rem;
    position: sticky;
    top: 20px;
}

.filter-title {
    color: #333;
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 0.5rem;
}

.filter-section {
    border-bottom: 1px solid #f8f9fa;
    padding-bottom: 1rem;
}

.filter-section:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.price-slider {
    height: 10px;
    background: #e9ecef;
    border-radius: 5px;
    position: relative;
    margin: 10px 0;
}

.category-item .subcategories {
    border-left: 2px solid #f8f9fa;
    padding-left: 1rem;
}

.rating-stars .fa-star {
    font-size: 0.875rem;
}

.clear-filters {
    color: #6c757d;
    text-decoration: none;
}

.clear-filters:hover {
    color: #495057;
    text-decoration: underline;
}

@media (max-width: 767.98px) {
    .shop-filters {
        position: static;
        margin-bottom: 2rem;
    }
}
</style>
