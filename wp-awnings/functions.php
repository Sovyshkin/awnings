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
