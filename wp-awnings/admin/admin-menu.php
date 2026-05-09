<?php
/**
 * Admin Menu Registration
 *
 * @package wp-awnings
 */

// Helper function to get new leads count
function wp_awnings_get_new_leads_count() {
    $args = array(
        'post_type' => 'lead',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => 'lead_status',
                'value' => 'new',
                'compare' => '='
            )
        )
    );
    $query = new WP_Query($args);
    return $query->found_posts;
}

// Add admin menu pages
function wp_awnings_admin_menu() {
    $new_leads = wp_awnings_get_new_leads_count();
    $leads_badge = $new_leads > 0 ? ' <span class="wpa-leads-badge">' . $new_leads . '</span>' : '';
    
    // Products - главный пункт с Products page как callback
    add_menu_page(
        'Каталог',
        'Каталог',
        'manage_options',
        'wp-awnings-products',
        'wp_awnings_products_page',
        'dashicons-products',
        30
    );

    // Products submenu (дублирует главный пункт)
    add_submenu_page(
        'wp-awnings-products',
        'Управление товарами',
        'Товары',
        'manage_options',
        'wp-awnings-products',
        'wp_awnings_products_page'
    );

    // Categories submenu
    add_submenu_page(
        'wp-awnings-products',
        'Управление категориями',
        'Категории',
        'manage_options',
        'wp-awnings-categories',
        'wp_awnings_categories_page'
    );

    // Leads menu with badge
    add_menu_page(
        'Заявки' . $leads_badge,
        'Заявки' . $leads_badge,
        'manage_options',
        'wp-awnings-leads',
        'wp_awnings_leads_page',
        'dashicons-email-alt',
        31
    );
    
    // Content management menu
    add_menu_page(
        'Контент',
        'Контент',
        'manage_options',
        'wp-awnings-content',
        'wp_awnings_content_page',
        'dashicons-admin-page',
        32
    );
}
add_action('admin_menu', 'wp_awnings_admin_menu');

// Products page callback
function wp_awnings_products_page() {
    include WP_AWNINGS_PATH . '/admin/products-admin.php';
}

// Categories page callback
function wp_awnings_categories_page() {
    include WP_AWNINGS_PATH . '/admin/categories-admin.php';
}

// Leads page callback
function wp_awnings_leads_page() {
    include WP_AWNINGS_PATH . '/admin/leads-admin.php';
}

// Content page callback
function wp_awnings_content_page() {
    include WP_AWNINGS_PATH . '/admin/content-admin.php';
}

// Add CSS for leads badge
add_action('admin_head', function() {
    echo '<style>
        .wpa-leads-badge {
            background: #C96744;
            color: #fff;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 5px;
        }
    </style>';
});
