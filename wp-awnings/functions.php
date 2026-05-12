<?php
/**
 * Theme functions and definitions
 *
 * @package wp-awnings
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define constants
define('WP_AWNINGS_VERSION', '1.0.0');
define('WP_AWNINGS_PATH', get_template_directory());
define('WP_AWNINGS_URL', get_template_directory_uri());

/**
 * Enqueue parent and child theme styles
 */
function wp_awnings_enqueue_styles() {
    wp_enqueue_style(
        'wp-awnings-style',
        get_stylesheet_uri(),
        array(),
        WP_AWNINGS_VERSION
    );
}
add_action('wp_enqueue_scripts', 'wp_awnings_enqueue_styles');

/**
 * Register REST API routes for Products (Catalog)
 */
function wp_awnings_register_product_routes() {
    // Register REST API namespace
    $namespace = 'wp-awnings/v1';

    // Register route for getting all products (with optional category filter)
    register_rest_route($namespace, '/products', array(
        'methods' => 'GET',
        'callback' => 'wp_awnings_get_products',
        'permission_callback' => '__return_true',
    ));
    
    // Register route for filtered products
    register_rest_route($namespace, '/products/category/(?P<cat_slug>[^/]+)', array(
        'methods' => 'GET',
        'callback' => 'wp_awnings_get_products_by_category',
        'permission_callback' => '__return_true',
    ));

    // Register route for getting single product
    register_rest_route($namespace, '/products/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'wp_awnings_get_product',
        'permission_callback' => '__return_true',
    ));

    // Register route for creating product
    register_rest_route($namespace, '/products', array(
        'methods' => 'POST',
        'callback' => 'wp_awnings_create_product',
        'permission_callback' => '__return_true',
    ));

    // Register route for updating product
    register_rest_route($namespace, '/products/(?P<id>\d+)', array(
        'methods' => 'PUT',
        'callback' => 'wp_awnings_update_product',
        'permission_callback' => '__return_true',
    ));

    // Register route for deleting product
    register_rest_route($namespace, '/products/(?P<id>\d+)', array(
        'methods' => 'DELETE',
        'callback' => 'wp_awnings_delete_product',
        'permission_callback' => '__return_true',
    ));

    // Register route for getting categories (public)
    register_rest_route($namespace, '/product-categories', array(
        'methods' => 'GET',
        'callback' => 'wp_awnings_get_categories',
        'permission_callback' => '__return_true',
    ));

    // Register route for creating category
    register_rest_route($namespace, '/product-categories', array(
        'methods' => 'POST',
        'callback' => 'wp_awnings_create_category',
        'permission_callback' => '__return_true',
    ));

    // Register route for updating category
    register_rest_route($namespace, '/product-categories/(?P<id>\d+)', array(
        'methods' => 'PUT',
        'callback' => 'wp_awnings_update_category',
        'permission_callback' => '__return_true',
    ));

    // Register route for deleting category
    register_rest_route($namespace, '/product-categories/(?P<id>\d+)', array(
        'methods' => 'DELETE',
        'callback' => 'wp_awnings_delete_category',
        'permission_callback' => '__return_true',
    ));

    // Register route for image upload
    register_rest_route($namespace, '/upload', array(
        'methods' => 'POST',
        'callback' => 'wp_awnings_upload_image',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'wp_awnings_register_product_routes');

/**
 * Get all products
 */
function wp_awnings_get_products($request) {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    );

    $query = new WP_Query($args);
    $products = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $products[] = wp_awnings_format_product(get_post());
        }
        wp_reset_postdata();
    }

    return new WP_REST_Response($products, 200);
}

/**
 * Get products by category slug
 */
function wp_awnings_get_products_by_category($request) {
    $category_slug = sanitize_text_field($request['cat_slug']);
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_category',
                'field' => 'slug',
                'terms' => $category_slug,
            ),
        ),
    );

    $query = new WP_Query($args);
    $products = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $products[] = wp_awnings_format_product(get_post());
        }
        wp_reset_postdata();
    }

    return new WP_REST_Response($products, 200);
}

/**
 * Get single product
 */
function wp_awnings_get_product($request) {
    $id = (int) $request['id'];
    $post = get_post($id);

    if (!$post || $post->post_type !== 'product') {
        return new WP_Error('not_found', 'Product not found', array('status' => 404));
    }

    return new WP_REST_Response(wp_awnings_format_product($post), 200);
}

/**
 * Create new product
 */
function wp_awnings_create_product($request) {
    $title = isset($request['title']) ? sanitize_text_field($request['title']) : '';
    $content = isset($request['content']) ? wp_kses_post($request['content']) : '';
    $price = isset($request['price']) ? sanitize_text_field($request['price']) : '';
    $category_id = isset($request['category_id']) ? (int)$request['category_id'] : 0;
    
    // Handle image_url as array or string
    $image_raw = $request->get_param('image_url');
    if (is_array($image_raw)) {
        $image_url = array_map('esc_url_raw', $image_raw);
    } else {
        $image_url = esc_url_raw($image_raw);
    }

    if (empty($title)) {
        return new WP_Error('missing_title', 'Product title is required', array('status' => 400));
    }

    $post_data = array(
        'post_type' => 'product',
        'post_title' => $title,
        'post_content' => $content,
        'post_status' => 'publish',
    );

    $post_id = wp_insert_post($post_data);

    if (is_wp_error($post_id)) {
        return new WP_Error('create_failed', 'Failed to create product', array('status' => 500));
    }

    if (!empty($price)) {
        update_post_meta($post_id, 'product_price', $price);
    }
    if (!empty($image_url)) {
        update_post_meta($post_id, 'product_image_url', $image_url);
    }

    if ($category_id > 0) {
        $term = get_term($category_id, 'product_category');
        if ($term && !is_wp_error($term)) {
            wp_set_object_terms($post_id, $category_id, 'product_category');
            update_post_meta($post_id, 'product_category_id', $category_id);
        }
    }

    return new WP_REST_Response(wp_awnings_format_product(get_post($post_id)), 201);
}

/**
 * Update product
 */
function wp_awnings_update_product($request) {
    $id = (int) $request['id'];
    $post = get_post($id);

    if (!$post || $post->post_type !== 'product') {
        return new WP_Error('not_found', 'Product not found', array('status' => 404));
    }

    $title = isset($request['title']) ? sanitize_text_field($request['title']) : $post->post_title;
    $content = isset($request['content']) ? wp_kses_post($request['content']) : $post->post_content;
    $price = isset($request['price']) ? sanitize_text_field($request['price']) : get_post_meta($id, 'product_price', true);
    $category_id = isset($request['category_id']) ? (int)$request['category_id'] : 0;
    
    // Handle image_url as array or string
    $image_raw = $request->get_param('image_url');
    if ($image_raw !== null) {
        if (is_array($image_raw)) {
            $image_url = array_map('esc_url_raw', $image_raw);
        } else {
            $image_url = esc_url_raw($image_raw);
        }
    } else {
        $image_url = get_post_meta($id, 'product_image_url', true);
    }

    $post_data = array(
        'ID' => $id,
        'post_title' => $title,
        'post_content' => $content,
    );

    wp_update_post($post_data);

    update_post_meta($id, 'product_price', $price);
    update_post_meta($id, 'product_image_url', $image_url);

    if ($category_id > 0) {
        $term = get_term($category_id, 'product_category');
        if ($term && !is_wp_error($term)) {
            wp_set_object_terms($id, $category_id, 'product_category');
            update_post_meta($id, 'product_category_id', $category_id);
        }
    }

    return new WP_REST_Response(wp_awnings_format_product(get_post($id)), 200);
}

/**
 * Delete product
 */
function wp_awnings_delete_product($request) {
    $id = (int) $request['id'];
    $post = get_post($id);

    if (!$post || $post->post_type !== 'product') {
        return new WP_Error('not_found', 'Product not found', array('status' => 404));
    }

    $deleted = wp_delete_post($id, true);

    if (!$deleted) {
        return new WP_Error('delete_failed', 'Failed to delete product', array('status' => 500));
    }

    return new WP_REST_Response(array('message' => 'Product deleted successfully'), 200);
}

/**
 * Get product categories
 */
function wp_awnings_get_categories($request) {
    $categories = get_terms(array(
        'taxonomy' => 'product_category',
        'hide_empty' => false,
    ));

    $formatted_categories = array();

    if (!is_wp_error($categories)) {
        foreach ($categories as $cat) {
            $formatted_categories[] = array(
                'id' => $cat->term_id,
                'name' => $cat->name,
                'slug' => rawurldecode($cat->slug),
                'description' => $cat->description,
                'count' => $cat->count,
            );
        }
    }

    return new WP_REST_Response($formatted_categories, 200);
}

/**
 * Create new category
 */
function wp_awnings_create_category($request) {
    $name = isset($request['name']) ? sanitize_text_field($request['name']) : '';

    if (empty($name)) {
        return new WP_Error('missing_name', 'Category name is required', array('status' => 400));
    }

    $result = wp_insert_term($name, 'product_category');

    if (is_wp_error($result)) {
        return new WP_Error('create_failed', $result->get_error_message(), array('status' => 500));
    }

    $term = get_term($result['term_id'], 'product_category');

    return new WP_REST_Response(array(
        'id' => $term->term_id,
        'name' => $term->name,
        'slug' => $term->slug,
        'count' => $term->count,
    ), 201);
}

/**
 * Update category
 */
function wp_awnings_update_category($request) {
    $id = (int) $request['id'];
    $term = get_term($id, 'product_category');

    if (!$term || is_wp_error($term)) {
        return new WP_Error('not_found', 'Category not found', array('status' => 404));
    }

    $name = isset($request['name']) ? sanitize_text_field($request['name']) : $term->name;
    $description = isset($request['description']) ? sanitize_text_field($request['description']) : $term->description;

    $result = wp_update_term($id, 'product_category', array(
        'name' => $name,
        'description' => $description,
    ));

    if (is_wp_error($result)) {
        return new WP_Error('update_failed', $result->get_error_message(), array('status' => 500));
    }

    $updated_term = get_term($id, 'product_category');

    return new WP_REST_Response(array(
        'id' => $updated_term->term_id,
        'name' => $updated_term->name,
        'slug' => $updated_term->slug,
        'description' => $updated_term->description,
        'count' => $updated_term->count,
    ), 200);
}

/**
 * Delete category
 */
function wp_awnings_delete_category($request) {
    $id = (int) $request['id'];
    $term = get_term($id, 'product_category');

    if (!$term || is_wp_error($term)) {
        return new WP_Error('not_found', 'Category not found', array('status' => 404));
    }

    $result = wp_delete_term($id, 'product_category');

    if (!$result || is_wp_error($result)) {
        return new WP_Error('delete_failed', 'Failed to delete category', array('status' => 500));
    }

    return new WP_REST_Response(array('message' => 'Category deleted successfully'), 200);
}

/**
 * Format product data for REST response
 */
function wp_awnings_format_product($post) {
    $categories = get_the_terms($post->ID, 'product_category');
    $category_id = 0;
    $category = '';
    $category_name = '';
    if (!empty($categories) && !is_wp_error($categories)) {
        $category_id = $categories[0]->term_id;
        $category = urldecode($categories[0]->slug);
        $category_name = $categories[0]->name;
    }
    
    return array(
        'id' => $post->ID,
        'title' => $post->post_title,
        'content' => $post->post_content,
        'price' => get_post_meta($post->ID, 'product_price', true),
        'category_id' => $category_id,
        'category' => $category,
        'category_name' => $category_name,
        'image_url' => get_post_meta($post->ID, 'product_image_url', true),
        'slug' => $post->post_name,
        'date' => $post->post_date,
    );
}

/**
 * Register REST API routes for Form Submissions (Leads)
 */
function wp_awnings_register_lead_routes() {
    $namespace = 'wp-awnings/v1';

    register_rest_route($namespace, '/leads', array(
        'methods' => 'POST',
        'callback' => 'wp_awnings_create_lead',
        'permission_callback' => '__return_true',
    ));

    register_rest_route($namespace, '/leads', array(
        'methods' => 'GET',
        'callback' => 'wp_awnings_get_leads',
        'permission_callback' => function() {
            return current_user_can('edit_posts');
        },
    ));

    register_rest_route($namespace, '/leads/(?P<id>\d+)', array(
        'methods' => 'DELETE',
        'callback' => 'wp_awnings_delete_lead',
        'permission_callback' => function() {
            return current_user_can('delete_posts');
        },
    ));

    // Register route for processing lead (mark as processed)
    register_rest_route($namespace, '/leads/(?P<id>\d+)/process', array(
        'methods' => 'POST',
        'callback' => 'wp_awnings_process_lead',
        'permission_callback' => function() {
            return current_user_can('edit_posts');
        },
    ));
}
add_action('rest_api_init', 'wp_awnings_register_lead_routes');

/**
 * Create new lead from form submission
 */
function wp_awnings_create_lead($request) {
    $name = isset($request['name']) ? sanitize_text_field($request['name']) : '';
    $phone = isset($request['phone']) ? sanitize_text_field($request['phone']) : '';
    $message = isset($request['message']) ? sanitize_text_field($request['message']) : '';
    $product_id = isset($request['product_id']) ? (int) $request['product_id'] : 0;
    $agree = isset($request['agree']) ? (bool) $request['agree'] : false;

    if (empty($name)) {
        return new WP_Error('missing_name', 'Name is required', array('status' => 400));
    }
    if (empty($phone)) {
        return new WP_Error('missing_phone', 'Phone is required', array('status' => 400));
    }
    if (!$agree) {
        return new WP_Error('missing_agreement', 'You must agree to data processing', array('status' => 400));
    }

    $post_data = array(
        'post_type' => 'lead',
        'post_title' => $name . ' - ' . $phone,
        'post_content' => $message,
        'post_status' => 'publish',
    );

    $post_id = wp_insert_post($post_data);

    if (is_wp_error($post_id)) {
        return new WP_Error('create_failed', 'Failed to save lead', array('status' => 500));
    }

    update_post_meta($post_id, 'lead_name', $name);
    update_post_meta($post_id, 'lead_phone', $phone);
    update_post_meta($post_id, 'lead_message', $message);
    update_post_meta($post_id, 'lead_product_id', $product_id);
    update_post_meta($post_id, 'lead_date', current_time('mysql'));
    update_post_meta($post_id, 'lead_status', 'new');

    return new WP_REST_Response(array(
        'success' => true,
        'message' => 'Заявка успешно отправлена!',
        'lead_id' => $post_id,
    ), 201);
}

/**
 * Get all leads
 */
function wp_awnings_get_leads($request) {
    $args = array(
        'post_type' => 'lead',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    );

    $query = new WP_Query($args);
    $leads = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post = get_post();
            $leads[] = array(
                'id' => $post->ID,
                'name' => get_post_meta($post->ID, 'lead_name', true),
                'phone' => get_post_meta($post->ID, 'lead_phone', true),
                'message' => get_post_meta($post->ID, 'lead_message', true),
                'product_id' => get_post_meta($post->ID, 'lead_product_id', true),
                'date' => get_post_meta($post->ID, 'lead_date', true),
                'status' => get_post_meta($post->ID, 'lead_status', true),
            );
        }
        wp_reset_postdata();
    }

    return new WP_REST_Response($leads, 200);
}

/**
 * Delete lead
 */
function wp_awnings_delete_lead($request) {
    $id = (int) $request['id'];
    $post = get_post($id);

    if (!$post || $post->post_type !== 'lead') {
        return new WP_Error('not_found', 'Lead not found', array('status' => 404));
    }

    $deleted = wp_delete_post($id, true);

    if (!$deleted) {
        return new WP_Error('delete_failed', 'Failed to delete lead', array('status' => 500));
    }

    return new WP_REST_Response(array('message' => 'Lead deleted successfully'), 200);
}

/**
 * Process lead (mark as processed)
 */
function wp_awnings_process_lead($request) {
    $id = (int) $request['id'];
    $post = get_post($id);

    if (!$post || $post->post_type !== 'lead') {
        return new WP_Error('not_found', 'Lead not found', array('status' => 404));
    }

    update_post_meta($id, 'lead_status', 'processed');

    return new WP_REST_Response(array(
        'success' => true,
        'message' => 'Lead processed successfully',
    ), 200);
}

/**
 * Register custom post types
 */
function wp_awnings_register_post_types() {
    register_post_type('product', array(
        'labels' => array(
            'name' => 'Товары',
            'singular_name' => 'Товар',
            'add_new' => 'Добавить товар',
            'add_new_item' => 'Добавить новый товар',
            'edit_item' => 'Редактировать товар',
            'new_item' => 'Новый товар',
            'view_item' => 'Просмотр товара',
            'search_items' => 'Поиск товаров',
            'not_found' => 'Товары не найдены',
            'not_found_in_trash' => 'В корзине товаров нет',
        ),
        'public' => true,
        'show_in_rest' => true,
        'rest_base' => 'products',
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'menu_icon' => 'dashicons-products',
        'has_archive' => true,
        'rewrite' => array('slug' => 'catalog'),
        'show_ui' => false,
        'show_in_menu' => false,
    ));

    register_post_type('lead', array(
        'labels' => array(
            'name' => 'Заявки',
            'singular_name' => 'Заявка',
            'add_new' => 'Добавить заявку',
            'add_new_item' => 'Добавить новую заявку',
            'edit_item' => 'Редактировать заявку',
            'new_item' => 'Новая заявка',
            'view_item' => 'Просмотр заявки',
            'search_items' => 'Поиск заявок',
            'not_found' => 'Заявки не найдены',
        ),
        'public' => false,
        'show_in_rest' => true,
        'rest_base' => 'leads',
        'supports' => array('title', 'editor', 'custom-fields'),
        'menu_icon' => 'dashicons-email-alt',
        'show_ui' => false,
        'show_in_menu' => false,
    ));

    register_post_type('content_block', array(
        'labels' => array(
            'name' => 'Контентные блоки',
            'singular_name' => 'Контентный блок',
            'add_new' => 'Добавить блок',
            'add_new_item' => 'Добавить новый блок',
            'edit_item' => 'Редактировать блок',
            'new_item' => 'Новый блок',
            'view_item' => 'Просмотр блока',
            'search_items' => 'Поиск блоков',
            'not_found' => 'Блоки не найдены',
        ),
        'public' => false,
        'show_in_rest' => true,
        'rest_base' => 'content-blocks',
        'supports' => array('title', 'editor', 'custom-fields'),
        'menu_icon' => 'dashicons-text-page',
        'show_ui' => false,
        'show_in_menu' => false,
    ));
}
add_action('init', 'wp_awnings_register_post_types');

/**
 * Register custom taxonomy for product categories
 */
function wp_awnings_register_taxonomies() {
    register_taxonomy('product_category', 'product', array(
        'labels' => array(
            'name' => 'Категории товаров',
            'singular_name' => 'Категория товара',
            'search_items' => 'Поиск категорий',
            'all_items' => 'Все категории',
            'edit_item' => 'Редактировать категорию',
            'add_new_item' => 'Добавить новую категорию',
            'new_item_name' => 'Название новой категории',
        ),
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'product-category'),
    ));
}
add_action('init', 'wp_awnings_register_taxonomies');

/**
 * Add CORS headers for API
 */
function wp_awnings_cors_headers() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
}
add_action('rest_api_init', 'wp_awnings_cors_headers', 15);

/**
 * Register REST API routes for Content Blocks
 */
function wp_awnings_register_content_routes() {
    $namespace = 'wp-awnings/v1';

    register_rest_route($namespace, '/content-blocks/page/(?P<page>[^/]+)', array(
        'methods' => 'GET',
        'callback' => 'wp_awnings_get_content_blocks',
        'permission_callback' => '__return_true',
    ));
    
    // Also keep simple endpoint for default page
    register_rest_route($namespace, '/content-blocks', array(
        'methods' => 'GET',
        'callback' => 'wp_awnings_get_content_blocks',
        'permission_callback' => '__return_true',
    ));

    register_rest_route($namespace, '/content-blocks', array(
        'methods' => 'POST',
        'callback' => 'wp_awnings_create_content_block',
        'permission_callback' => function() {
            return current_user_can('edit_posts');
        },
    ));

    register_rest_route($namespace, '/content-blocks/(?P<id>\d+)', array(
        'methods' => array('PUT', 'POST'),
        'callback' => 'wp_awnings_update_content_block',
        'permission_callback' => function() {
            return current_user_can('edit_posts');
        },
    ));

    register_rest_route($namespace, '/content-blocks/(?P<id>\d+)', array(
        'methods' => 'DELETE',
        'callback' => 'wp_awnings_delete_content_block',
        'permission_callback' => function() {
            return current_user_can('delete_posts');
        },
    ));

    register_rest_route($namespace, '/content-blocks/public', array(
        'methods' => 'GET',
        'callback' => 'wp_awnings_get_public_content_blocks',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'wp_awnings_register_content_routes');

/**
 * Get content blocks (admin)
 */
function wp_awnings_get_content_blocks($request) {
    $page = isset($request['page']) ? sanitize_text_field($request['page']) : 'home';
    
    $args = array(
        'post_type' => 'content_block',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => 'block_page',
                'value' => $page,
            )
        ),
        'meta_key' => 'block_order',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
    );

    $query = new WP_Query($args);
    $blocks = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post = get_post();
            $blocks[] = array(
                'id' => $post->ID,
                'block_name' => get_post_meta($post->ID, 'block_name', true),
                'block_type' => get_post_meta($post->ID, 'block_type', true),
                'block_title' => get_post_meta($post->ID, 'block_title', true),
                'block_text' => get_post_meta($post->ID, 'block_text', true),
                'block_image' => get_post_meta($post->ID, 'block_image', true),
                'block_data' => get_post_meta($post->ID, 'block_data', true),
                'block_page' => get_post_meta($post->ID, 'block_page', true),
                'block_order' => get_post_meta($post->ID, 'block_order', true),
            );
        }
        wp_reset_postdata();
    }

    return new WP_REST_Response($blocks, 200);
}

/**
 * Create content block (admin)
 */
function wp_awnings_create_content_block($request) {
    $block_name = isset($request['block_name']) ? sanitize_text_field($request['block_name']) : 'Новый блок';
    $block_type = isset($request['block_type']) ? sanitize_text_field($request['block_type']) : 'text';
    $block_page = isset($request['block_page']) ? sanitize_text_field($request['block_page']) : 'home';
    $block_title = isset($request['block_title']) ? sanitize_text_field($request['block_title']) : '';
    $block_text = isset($request['block_text']) ? sanitize_text_field($request['block_text']) : '';
    $block_image = isset($request['block_image']) ? esc_url_raw($request['block_image']) : '';
    $block_data = isset($request['block_data']) ? sanitize_text_field($request['block_data']) : '{}';
    $block_order = isset($request['block_order']) ? (int)$request['block_order'] : 0;

    $post_data = array(
        'post_type' => 'content_block',
        'post_title' => $block_name,
        'post_status' => 'publish',
    );

    $post_id = wp_insert_post($post_data);

    if (is_wp_error($post_id)) {
        return new WP_Error('create_failed', 'Failed to create block', array('status' => 500));
    }

    update_post_meta($post_id, 'block_name', $block_name);
    update_post_meta($post_id, 'block_type', $block_type);
    update_post_meta($post_id, 'block_page', $block_page);
    update_post_meta($post_id, 'block_title', $block_title);
    update_post_meta($post_id, 'block_text', $block_text);
    update_post_meta($post_id, 'block_image', $block_image);
    update_post_meta($post_id, 'block_data', $block_data);
    update_post_meta($post_id, 'block_order', $block_order);

    return new WP_REST_Response(array(
        'id' => $post_id,
        'block_name' => $block_name,
    ), 201);
}

/**
 * Update content block (admin)
 */
function wp_awnings_update_content_block($request) {
    $id = (int) $request['id'];
    $post = get_post($id);

    if (!$post || $post->post_type !== 'content_block') {
        return new WP_Error('not_found', 'Block not found', array('status' => 404));
    }

    $block_name = isset($request['block_name']) ? sanitize_text_field($request['block_name']) : get_post_meta($id, 'block_name', true);
    $block_type = isset($request['block_type']) ? sanitize_text_field($request['block_type']) : get_post_meta($id, 'block_type', true);
    $block_page = isset($request['block_page']) ? sanitize_text_field($request['block_page']) : get_post_meta($id, 'block_page', true);
    $block_title = isset($request['block_title']) ? sanitize_text_field($request['block_title']) : get_post_meta($id, 'block_title', true);
    $block_text = isset($request['block_text']) ? sanitize_text_field($request['block_text']) : get_post_meta($id, 'block_text', true);
    $block_image = isset($request['block_image']) ? esc_url_raw($request['block_image']) : get_post_meta($id, 'block_image', true);
    $block_data = isset($request['block_data']) ? sanitize_text_field($request['block_data']) : get_post_meta($id, 'block_data', true);
    $block_order = isset($request['block_order']) ? (int)$request['block_order'] : get_post_meta($id, 'block_order', true);

    wp_update_post(array('ID' => $id, 'post_title' => $block_name));

    update_post_meta($id, 'block_name', $block_name);
    update_post_meta($id, 'block_type', $block_type);
    update_post_meta($id, 'block_page', $block_page);
    update_post_meta($id, 'block_title', $block_title);
    update_post_meta($id, 'block_text', $block_text);
    update_post_meta($id, 'block_image', $block_image);
    update_post_meta($id, 'block_data', $block_data);
    update_post_meta($id, 'block_order', $block_order);

    return new WP_REST_Response(array('success' => true, 'id' => $id), 200);
}

/**
 * Delete content block (admin)
 */
function wp_awnings_delete_content_block($request) {
    $id = (int) $request['id'];
    $post = get_post($id);

    if (!$post || $post->post_type !== 'content_block') {
        return new WP_Error('not_found', 'Block not found', array('status' => 404));
    }

    $deleted = wp_delete_post($id, true);

    if (!$deleted) {
        return new WP_Error('delete_failed', 'Failed to delete block', array('status' => 500));
    }

    return new WP_REST_Response(array('success' => true), 200);
}

/**
 * Get public content blocks (for frontend)
 */
function wp_awnings_get_public_content_blocks($request) {
    $page = isset($request['page']) ? sanitize_text_field($request['page']) : 'home';
    
    $args = array(
        'post_type' => 'content_block',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => 'block_page',
                'value' => $page,
            )
        ),
        'meta_key' => 'block_order',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
    );

    $query = new WP_Query($args);
    $blocks = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post = get_post();
            $blocks[] = array(
                'id' => $post->ID,
                'block_name' => get_post_meta($post->ID, 'block_name', true),
                'block_type' => get_post_meta($post->ID, 'block_type', true),
                'block_title' => get_post_meta($post->ID, 'block_title', true),
                'block_text' => get_post_meta($post->ID, 'block_text', true),
                'block_image' => get_post_meta($post->ID, 'block_image', true),
                'block_data' => get_post_meta($post->ID, 'block_data', true),
            );
        }
        wp_reset_postdata();
    }

    return new WP_REST_Response($blocks, 200);
}

/**
 * Theme setup
 */
function wp_awnings_setup() {
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    add_editor_style('style.css');
}
add_action('after_setup_theme', 'wp_awnings_setup');

/**
 * Register widget areas
 */
function wp_awnings_widgets_init() {
    register_sidebar(array(
        'name'          => 'Sidebar',
        'id'            => 'sidebar-1',
        'description'   => 'Add widgets here.',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'wp_awnings_widgets_init');

// Include admin files
require_once WP_AWNINGS_PATH . '/admin/admin-menu.php';

// Allow SVG uploads
add_filter('upload_mimes', function($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
});

/**
 * Upload image for product
 */
function wp_awnings_upload_image($request) {
    if (!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }
    
    $file = isset($_FILES['file']) ? $_FILES['file'] : null;
    
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        return new WP_Error('upload_error', 'Ошибка загрузки файла', array('status' => 500));
    }
    
    // Check file type by extension
    $allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'tiff', 'tif', 'ico', 'heic', 'heif', 'avif', 'apng');
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($file_ext, $allowed_ext)) {
        return new WP_Error('invalid_type', 'Неподдерживаемый формат файла', array('status' => 400));
    }
    
    // Override WordPress security check for SVG
    add_filter('wp_check_filetype_and_ext', function($file, $file_path, $filename, $mimes) {
        if (strtolower(pathinfo($filename, PATHINFO_EXTENSION)) === 'svg') {
            $file['ext'] = 'svg';
            $file['type'] = 'image/svg+xml';
            $file['proper_filename'] = $filename;
        }
        return $file;
    }, 10, 4);
    
    // Move to uploads
    $movefile = wp_handle_upload($file, array('test_form' => false));
    
    if ($movefile && !isset($movefile['error'])) {
        return new WP_REST_Response(array(
            'url' => $movefile['url'],
            'path' => $movefile['file'],
        ), 200);
    } else {
        return new WP_Error('upload_failed', $movefile['error'] ?: 'Ошибка загрузки', array('status' => 500));
    }
}

/**
 * Create initial content blocks on theme activation
 */
function wp_awnings_create_initial_content() {
    $existing = get_posts(array(
        'post_type' => 'content_block',
        'posts_per_page' => 1,
        'post_status' => 'any',
    ));
    
    if (!empty($existing)) {
        return;
    }
    
    // ===== HOME PAGE =====
    
    // Hero section
    $order = 1;
    $hero_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Главный баннер', 'post_status' => 'publish'));
    update_post_meta($hero_id, 'block_name', 'Главный баннер');
    update_post_meta($hero_id, 'block_type', 'hero');
    update_post_meta($hero_id, 'block_page', 'home');
    update_post_meta($hero_id, 'block_title', 'Современные модульные решения для участка в едином стиле');
    update_post_meta($hero_id, 'block_text', 'Беседки для отдыха, мангальные зоны для встреч, навесы для авто для повседневного удобства.');
    update_post_meta($hero_id, 'block_data', json_encode(array(
        'button_text' => 'Посмотреть комплектации',
        'button_link' => '/catalog',
        'features' => array(
            array('title' => 'Единый стиль участка', 'text' => 'Все решения визуально сочетаются между собой.', 'icon' => 'group-1'),
            array('title' => 'Продуманная конструкция', 'text' => 'Надёжный каркас, современные материалы, чистая геометрия.', 'icon' => 'group-2'),
            array('title' => 'Готовые комплектации', 'text' => 'Понятный выбор без лишней сложности.', 'icon' => 'group-3'),
        ),
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($hero_id, 'block_order', $order++);
    
    // Features (3 items)
    $features_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Преимущества', 'post_status' => 'publish'));
    update_post_meta($features_id, 'block_name', 'Преимущества');
    update_post_meta($features_id, 'block_type', 'features');
    update_post_meta($features_id, 'block_page', 'home');
    update_post_meta($features_id, 'block_data', json_encode(array(
        array('title' => 'Единый стиль участка', 'text' => 'Все решения визуально сочетаются между собой.', 'icon' => 'group-1'),
        array('title' => 'Продуманная конструкция', 'text' => 'Надёжный каркас, современные материалы, чистая геометрия.', 'icon' => 'group-2'),
        array('title' => 'Готовые комплектации', 'text' => 'Понятный выбор без лишней сложности.', 'icon' => 'group-3'),
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($features_id, 'block_order', $order++);
    
    // Why Us section (4 cards)
    $whyus_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Почему выбирают нас', 'post_status' => 'publish'));
    update_post_meta($whyus_id, 'block_name', 'Почему выбирают нас');
    update_post_meta($whyus_id, 'block_type', 'features');
    update_post_meta($whyus_id, 'block_page', 'home');
    update_post_meta($whyus_id, 'block_data', json_encode(array(
        array('title' => 'Гарантия до 15 лет', 'text' => 'Даём письменную гарантию на конструкцию и монтаж', 'icon' => 'why-us-1'),
        array('title' => 'Доставка по России', 'text' => 'Отправим в любой регион — транспортной компанией или своим транспортом', 'icon' => 'why-us-2'),
        array('title' => 'Монтаж под ключ', 'text' => 'Наша бригада установит навес за 1–2 дня без вашего участия', 'icon' => 'why-us-3'),
        array('title' => 'Консультация бесплатно', 'text' => 'Позвоните или оставьте заявку — подберём модель под ваши задачи', 'icon' => 'why-us-4'),
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($whyus_id, 'block_order', $order++);
    
    // Что мы делаем section (3 cards)
    $whatdoing_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Что мы делаем', 'post_status' => 'publish'));
    update_post_meta($whatdoing_id, 'block_name', 'Что мы делаем');
    update_post_meta($whatdoing_id, 'block_type', 'features');
    update_post_meta($whatdoing_id, 'block_page', 'home');
    update_post_meta($whatdoing_id, 'block_title', 'Беседки, навесы и мангальные зоны для тех, кто ценит удобство и современный внешний вид');
    update_post_meta($whatdoing_id, 'block_data', json_encode(array(
        array('title' => 'Беседка для отдыха', 'category' => 'Сад', 'image' => 'card.png'),
        array('title' => 'Мангальные зоны', 'category' => 'Барбекю', 'image' => 'card.png'),
        array('title' => 'Навесы для автомобилей', 'category' => 'Авто', 'image' => 'card.png'),
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($whatdoing_id, 'block_order', $order++);
    
    // Компания в цифрах section (home page version)
    $companynums_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Компания в цифрах', 'post_status' => 'publish'));
    update_post_meta($companynums_id, 'block_name', 'Компания в цифрах');
    update_post_meta($companynums_id, 'block_type', 'features');
    update_post_meta($companynums_id, 'block_page', 'home');
    update_post_meta($companynums_id, 'block_data', json_encode(array(
        array('title' => '15', 'subtitle' => 'лет на рынке', 'desc' => 'проектируем и устанавливаем конструкции, которые выдерживают реальные условия эксплуатации'),
        array('title' => '3 200+', 'subtitle' => 'установленных навесов', 'desc' => 'Отработали десятки сценариев: частные участки, коммерческие объекты, нестандартные решения'),
        array('title' => '52', 'subtitle' => 'города доставки', 'desc' => 'Организуем логистику и монтаж так, чтобы вы получили готовый результат без срывов'),
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($companynums_id, 'block_order', $order++);
    
    // ===== ABOUT PAGE =====
    
    // Company block
    $about1_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'О компании', 'post_status' => 'publish'));
    update_post_meta($about1_id, 'block_name', 'О компании');
    update_post_meta($about1_id, 'block_type', 'section');
    update_post_meta($about1_id, 'block_page', 'about');
    update_post_meta($about1_id, 'block_title', 'Надежный партнер для вашего участка');
    update_post_meta($about1_id, 'block_text', 'Мы специализируемся на производстве и установке модульных конструкций для загородных участков. Наша цель - создавать функциональные и эстетичные решения, которые служат десятилетиями.');
    update_post_meta($about1_id, 'block_order', 1);
    
    // Company numbers
    $numbers_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Компания в цифрах', 'post_status' => 'publish'));
    update_post_meta($numbers_id, 'block_name', 'Компания в цифрах');
    update_post_meta($numbers_id, 'block_type', 'features');
    update_post_meta($numbers_id, 'block_page', 'about');
    update_post_meta($numbers_id, 'block_data', json_encode(array(
        array('title' => '15', 'subtitle' => 'лет на рынке', 'desc' => 'проектируем и устанавливаем конструкции, которые выдерживают реальные условия эксплуатации', 'image' => 'company-card-1.png'),
        array('title' => '3 200+', 'subtitle' => 'установленных навесов', 'desc' => 'Отработали десятки сценариев: частные участки, коммерческие объекты, нестандартные решения', 'image' => 'company-card-2.png'),
        array('title' => '52', 'subtitle' => 'города доставки', 'desc' => 'Организуем логистику и монтаж так, чтобы вы получили готовый результат без срывов', 'image' => 'company-card-3.jpg'),
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($numbers_id, 'block_order', 2);
    
    // Company why us
    $about2_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Почему мы', 'post_status' => 'publish'));
    update_post_meta($about2_id, 'block_name', 'Почему мы');
    update_post_meta($about2_id, 'block_type', 'features');
    update_post_meta($about2_id, 'block_page', 'about');
    update_post_meta($about2_id, 'block_data', json_encode(array(
        array('title' => 'Собственное производство', 'text' => 'Производим конструкции на собственном заводе в Екатеринбурге.', 'icon' => 'card-icon-1'),
        array('title' => 'Гарантия качества', 'text' => 'Используем сертифицированные материалы с гарантией.', 'icon' => 'card-icon-2'),
        array('title' => 'Индивидуальный подход', 'text' => 'Разрабатываем проекты под ваши задачи и бюджет.', 'icon' => 'card-icon-3'),
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($about2_id, 'block_order', 3);
    
    // Company production
    $prod_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Производство', 'post_status' => 'publish'));
    update_post_meta($prod_id, 'block_name', 'Производство');
    update_post_meta($prod_id, 'block_type', 'section');
    update_post_meta($prod_id, 'block_page', 'about');
    update_post_meta($prod_id, 'block_title', 'Собственное производство');
    update_post_meta($prod_id, 'block_text', 'Наша компания располагает современным производством в Екатеринбурге. Мы производим каркасы из стального профиля, используем качественные материалы для кровли и обшивки.');
    update_post_meta($prod_id, 'block_image', 'production-image');
    update_post_meta($prod_id, 'block_order', 4);
    
    // Company our works
    $works_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Наши работы', 'post_status' => 'publish'));
    update_post_meta($works_id, 'block_name', 'Наши работы');
    update_post_meta($works_id, 'block_type', 'gallery');
    update_post_meta($works_id, 'block_page', 'about');
    update_post_meta($works_id, 'block_title', 'Реализованные проекты');
    update_post_meta($works_id, 'block_order', 5);
    
    // Our history
    $history_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Наша история', 'post_status' => 'publish'));
    update_post_meta($history_id, 'block_name', 'Наша история');
    update_post_meta($history_id, 'block_type', 'section');
    update_post_meta($history_id, 'block_page', 'about');
    update_post_meta($history_id, 'block_title', 'Наша история');
    update_post_meta($history_id, 'block_text', 'За 15 лет работы мы установили более 3200 навесов по всей России. Каждый проект — это опыт, который делает нас лучше.');
    update_post_meta($history_id, 'block_order', 6);
    
    // ===== CONTACTS PAGE =====
    
    // Contacts info
    $contacts_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Контактная информация', 'post_status' => 'publish'));
    update_post_meta($contacts_id, 'block_name', 'Контактная информация');
    update_post_meta($contacts_id, 'block_type', 'contact');
    update_post_meta($contacts_id, 'block_page', 'contacts');
    update_post_meta($contacts_id, 'block_data', json_encode(array(
        'phone' => '+7 (900) 123-45-67',
        'email' => 'info@navesstroy.ru',
        'address' => 'г. Екатеринбург, ул. Промышленная, д. 4, стр. 2',
        'schedule' => 'Пн-Вс: 9:00-18:00',
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($contacts_id, 'block_order', 1);
    
    // Contacts form section
    $contact_form_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Форма обратной связи', 'post_status' => 'publish'));
    update_post_meta($contact_form_id, 'block_name', 'Форма обратной связи');
    update_post_meta($contact_form_id, 'block_type', 'section');
    update_post_meta($contact_form_id, 'block_page', 'contacts');
    update_post_meta($contact_form_id, 'block_title', 'Остались вопросы?');
    update_post_meta($contact_form_id, 'block_text', 'Оставьте заявку и наш менеджер свяжется с вами, чтобы ответить на ваши вопросы!');
    update_post_meta($contact_form_id, 'block_order', 2);
    
    // ===== DELIVERY PAGE =====
    
    $del_order = 1;
    
    // Delivery regions
    $del1_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Регионы доставки', 'post_status' => 'publish'));
    update_post_meta($del1_id, 'block_name', 'Регионы доставки');
    update_post_meta($del1_id, 'block_type', 'section');
    update_post_meta($del1_id, 'block_page', 'delivery');
    update_post_meta($del1_id, 'block_title', 'Доставляем по всей России');
    update_post_meta($del1_id, 'block_text', 'Работаем с транспортными компаниями и собственным транспортом. Доставка в любой регион России.');
    update_post_meta($del1_id, 'block_image', 'delivery-regions.png');
    update_post_meta($del1_id, 'block_order', $del_order++);
    
    // Payment methods
    $pay_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Способы оплаты', 'post_status' => 'publish'));
    update_post_meta($pay_id, 'block_name', 'Способы оплаты');
    update_post_meta($pay_id, 'block_type', 'features');
    update_post_meta($pay_id, 'block_page', 'delivery');
    update_post_meta($pay_id, 'block_data', json_encode(array(
        array('title' => 'Наличные', 'text' => 'Оплата наличными при получении', 'icon' => 'payment-1'),
        array('title' => 'Карта', 'text' => 'Оплата картой онлайн или при получении', 'icon' => 'payment-2'),
        array('title' => 'Рассрочка', 'text' => 'Беспроцентная рассрочка до 12 месяцев', 'icon' => 'payment-3'),
        array('title' => 'Безнал', 'text' => 'Оплата по счету для юридических лиц', 'icon' => 'payment-4'),
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($pay_id, 'block_order', $del_order++);
    
    // ===== GARANT PAGE =====
    
    $gar_order = 1;
    
    // Garant intro
    $gar1_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Гарантия', 'post_status' => 'publish'));
    update_post_meta($gar1_id, 'block_name', 'Гарантия');
    update_post_meta($gar1_id, 'block_type', 'section');
    update_post_meta($gar1_id, 'block_page', 'garant');
    update_post_meta($gar1_id, 'block_title', 'Гарантийные обязательства');
    update_post_meta($gar1_id, 'block_text', 'Мы уверены в качестве наших конструкций и предоставляем письменную гарантию на все работы.');
    update_post_meta($gar1_id, 'block_order', $gar_order++);
    
    // Garant conditions
    $gar2_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Условия гарантии', 'post_status' => 'publish'));
    update_post_meta($gar2_id, 'block_name', 'Условия гарантии');
    update_post_meta($gar2_id, 'block_type', 'features');
    update_post_meta($gar2_id, 'block_page', 'garant');
    update_post_meta($gar2_id, 'block_data', json_encode(array(
        array('title' => 'До 15 лет', 'text' => 'Гарантия на конструкцию'),
        array('title' => 'Монтаж', 'text' => 'Гарантия на установку'),
        array('title' => 'Материалы', 'text' => 'Сертифицированные комплектующие'),
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($gar2_id, 'block_order', $gar_order++);
    
    // Garant maintenance
    $gar3_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Обслуживание', 'post_status' => 'publish'));
    update_post_meta($gar3_id, 'block_name', 'Обслуживание');
    update_post_meta($gar3_id, 'block_type', 'features');
    update_post_meta($gar3_id, 'block_page', 'garant');
    update_post_meta($gar3_id, 'block_data', json_encode(array(
        array('title' => 'Первый месяц после установки', 'text' => 'Бесплатный осмотр и консультация'),
        array('title' => 'Регулярная очистка', 'text' => 'Рекомендации по уходу за конструкцией'),
        array('title' => 'Профилактическая покраска', 'text' => 'Обновление защитного покрытия'),
        array('title' => 'Проверка и ремонт', 'text' => 'Оперативный ремонт при необходимости'),
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($gar3_id, 'block_order', $gar_order++);
    
    // Garant FAQ
    $gar4_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Частые вопросы по гарантии', 'post_status' => 'publish'));
    update_post_meta($gar4_id, 'block_name', 'Частые вопросы по гарантии');
    update_post_meta($gar4_id, 'block_type', 'faq');
    update_post_meta($gar4_id, 'block_page', 'garant');
    update_post_meta($gar4_id, 'block_data', json_encode(array(
        array('question' => 'Как воспользоваться гарантией?', 'answer' => 'Свяжитесь с нами по телефону или оставьте заявку на сайте.'),
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($gar4_id, 'block_order', $gar_order++);
    
    // ===== FOOTER =====
    
    $foot_order = 1;
    
    // Footer main info
    $footer1_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Футер - Основная информация', 'post_status' => 'publish'));
    update_post_meta($footer1_id, 'block_name', 'Футер - Основная информация');
    update_post_meta($footer1_id, 'block_type', 'footer');
    update_post_meta($footer1_id, 'block_page', 'footer');
    update_post_meta($footer1_id, 'block_title', 'Название');
    update_post_meta($footer1_id, 'block_text', 'Производство и продажа металлических навесов в Екатеринбурге и Свердловской области. Доставка и монтаж по всей России.');
    update_post_meta($footer1_id, 'block_data', json_encode(array(
        'copyright' => '© 2026 Название. Все права защищены.',
        'privacy' => 'Политика конфиденциальности',
        'agreement' => 'Пользовательское соглашение',
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($footer1_id, 'block_order', $foot_order++);
    
    // Footer catalog links
    $footer2_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Футер - Каталог', 'post_status' => 'publish'));
    update_post_meta($footer2_id, 'block_name', 'Футер - Каталог');
    update_post_meta($footer2_id, 'block_type', 'footer');
    update_post_meta($footer2_id, 'block_page', 'footer');
    update_post_meta($footer2_id, 'block_data', json_encode(array(
        array('text' => 'Беседки'),
        array('text' => 'Мангальные зоны'),
        array('text' => 'Навесы для авто'),
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($footer2_id, 'block_order', $foot_order++);
    
    // Footer client links
    $footer3_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Футер - Покупателям', 'post_status' => 'publish'));
    update_post_meta($footer3_id, 'block_name', 'Футер - Покупателям');
    update_post_meta($footer3_id, 'block_type', 'footer');
    update_post_meta($footer3_id, 'block_page', 'footer');
    update_post_meta($footer3_id, 'block_data', json_encode(array(
        array('text' => 'О компании'),
        array('text' => 'Новости и статьи'),
        array('text' => 'Доставка и оплата'),
        array('text' => 'Гарантия'),
        array('text' => 'Контакты'),
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($footer3_id, 'block_order', $foot_order++);
    
    // Footer contacts
    $footer4_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Футер - Контакты', 'post_status' => 'publish'));
    update_post_meta($footer4_id, 'block_name', 'Футер - Контакты');
    update_post_meta($footer4_id, 'block_type', 'footer');
    update_post_meta($footer4_id, 'block_page', 'footer');
    update_post_meta($footer4_id, 'block_data', json_encode(array(
        'phone' => '+7 (900) 123-45-67',
        'email' => 'info@navesstroy.ru',
        'address' => 'г. Екатеринбург, ул. Промышленная, д. 4, стр. 2',
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($footer4_id, 'block_order', $foot_order++);
    
    // ===== HEADER =====
    
    $head_order = 1;
    
    // Header menu items
    $header1_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Шапка - Меню', 'post_status' => 'publish'));
    update_post_meta($header1_id, 'block_name', 'Шапка - Меню');
    update_post_meta($header1_id, 'block_type', 'header');
    update_post_meta($header1_id, 'block_page', 'header');
    update_post_meta($header1_id, 'block_data', json_encode(array(
        array('text' => 'Каталог', 'link' => '/catalog'),
        array('text' => 'О компании', 'link' => '/about'),
        array('text' => 'Доставка и оплата', 'link' => '/delivery'),
        array('text' => 'Гарантия', 'link' => '/garant'),
        array('text' => 'Контакты', 'link' => '/contacts'),
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($header1_id, 'block_order', $head_order++);
    
    // Header CTA button
    $header2_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Шапка - Кнопка', 'post_status' => 'publish'));
    update_post_meta($header2_id, 'block_name', 'Шапка - Кнопка');
    update_post_meta($header2_id, 'block_type', 'header');
    update_post_meta($header2_id, 'block_page', 'header');
    update_post_meta($header2_id, 'block_data', json_encode(array(
        'text' => 'Каталог',
        'link' => '/catalog',
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($header2_id, 'block_order', $head_order++);
    
    // ===== NEWS PAGE =====
    
    $news_order = 1;
    
    // News page header
    $news1_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Новости - Заголовок', 'post_status' => 'publish'));
    update_post_meta($news1_id, 'block_name', 'Новости - Заголовок');
    update_post_meta($news1_id, 'block_type', 'section');
    update_post_meta($news1_id, 'block_page', 'news');
    update_post_meta($news1_id, 'block_title', 'Новости и статьи');
    update_post_meta($news1_id, 'block_order', $news_order++);
    
    // News intro text
    $news2_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Новости - Вступление', 'post_status' => 'publish'));
    update_post_meta($news2_id, 'block_name', 'Новости - Вступление');
    update_post_meta($news2_id, 'block_type', 'section');
    update_post_meta($news2_id, 'block_page', 'news');
    update_post_meta($news2_id, 'block_text', 'Полезная информация о навесах, беседках и мангальных зонах.');
    update_post_meta($news2_id, 'block_order', $news_order++);
    
    // ===== FAQ PAGE =====
    
    $faq_order = 1;
    
    // FAQ header
    $faq1_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'FAQ - Заголовок', 'post_status' => 'publish'));
    update_post_meta($faq1_id, 'block_name', 'FAQ - Заголовок');
    update_post_meta($faq1_id, 'block_type', 'section');
    update_post_meta($faq1_id, 'block_page', 'faq');
    update_post_meta($faq1_id, 'block_title', 'Самые популярные вопросы');
    update_post_meta($faq1_id, 'block_order', $faq_order++);
    
    // FAQ items
    $faq2_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'FAQ - Вопросы и ответы', 'post_status' => 'publish'));
    update_post_meta($faq2_id, 'block_name', 'FAQ - Вопросы и ответы');
    update_post_meta($faq2_id, 'block_type', 'faq');
    update_post_meta($faq2_id, 'block_page', 'faq');
    update_post_meta($faq2_id, 'block_data', json_encode(array(
        array('question' => 'Какие конструкции Вы изготавливаете?', 'answer' => 'Мы изготавливаем навесы, беседки, мангальные зоны и террасы из металла с различными типами кровли.'),
        array('question' => 'Подходят ли конструкции для круглогодичного использования?', 'answer' => 'Да, все наши конструкции рассчитаны на эксплуатацию в любое время года.'),
        array('question' => 'Можно ли выбрать размер конструкции?', 'answer' => 'Да, мы изготавливаем конструкции по индивидуальным размерам под ваши задачи.'),
        array('question' => 'Можно ли заказать мангальную зону как отдельное решение?', 'answer' => 'Да, мангальные зоны доступны как отдельные конструкции.'),
        array('question' => 'Из каких материалов изготавливаются конструкции?', 'answer' => 'Каркас из стального профиля, кровля из поликарбоната или металлочерепицы.'),
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($faq2_id, 'block_order', $faq_order++);
    // ===== HOME - HOW WE WORK =====
    
    $how_order = 1;
    
    // How we work section
    $how_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Как мы работаем', 'post_status' => 'publish'));
    update_post_meta($how_id, 'block_name', 'Как мы работаем');
    update_post_meta($how_id, 'block_type', 'features');
    update_post_meta($how_id, 'block_page', 'home');
    update_post_meta($how_id, 'block_data', json_encode(array(
        array('title' => 'Оставьте заявку', 'text' => 'Заполните форму на сайте или позвоните — ответим в течение 30 минут', 'icon' => 'card-icon-1'),
        array('title' => 'Получите расчёт', 'text' => 'Менеджер уточнит размеры и комплектацию, пришлёт смету', 'icon' => 'card-icon-2'),
        array('title' => 'Подпишите договор', 'text' => 'Фиксируем цену, сроки и состав работ документально', 'icon' => 'card-icon-3'),
        array('title' => 'Монтаж и сдача', 'text' => 'Установим конструкцию за 1-2 дня и подпишем акт приёмки', 'icon' => 'card-icon-4'),
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($how_id, 'block_order', $how_order++);
    
    // ===== HOME - OUR PROJECTS =====
    
    $proj_order = 1;
    
    // Our projects header
    $proj_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Наши проекты', 'post_status' => 'publish'));
    update_post_meta($proj_id, 'block_name', 'Наши проекты');
    update_post_meta($proj_id, 'block_type', 'gallery');
    update_post_meta($proj_id, 'block_page', 'home');
    update_post_meta($proj_id, 'block_title', 'Наши проекты');
    update_post_meta($proj_id, 'block_order', $proj_order++);
}
add_action('after_setup_theme', 'wp_awnings_create_initial_content');

// AJAX handler to reset content blocks
add_action('wp_ajax_wp_awnings_reset_content', function() {
    // Delete all existing content blocks
    $blocks = get_posts(array(
        'post_type' => 'content_block',
        'posts_per_page' => -1,
        'post_status' => 'any',
    ));
    foreach ($blocks as $block) {
        wp_delete_post($block->ID, true);
    }
    
    // Recreate initial content
    wp_awnings_create_initial_content();
    
    wp_send_json_success(array('message' => 'Контент обновлён!'));
});
