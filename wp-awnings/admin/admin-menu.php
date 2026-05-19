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
    include WP_AWNINGS_PATH . '/admin/content-admin-v2.php';
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

/**
 * AJAX: get content blocks by page (admin)
 */
add_action('wp_ajax_wp_awnings_get_content_blocks', function() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Доступ запрещен'), 403);
    }

    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wp_rest')) {
        wp_send_json_error(array('message' => 'Неверный nonce'), 403);
    }

    $page = isset($_POST['page']) ? sanitize_text_field(wp_unslash($_POST['page'])) : 'home';

    $args = array(
        'post_type' => 'content_block',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => 'block_page',
                'value' => $page,
            ),
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

    wp_send_json_success($blocks);
});

/**
 * AJAX: update content block (admin)
 */
add_action('wp_ajax_wp_awnings_update_content_block', function() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Доступ запрещен'), 403);
    }

    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wp_rest')) {
        wp_send_json_error(array('message' => 'Неверный nonce'), 403);
    }

    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    if ($id <= 0) {
        wp_send_json_error(array('message' => 'Некорректный ID блока'), 400);
    }

    $post = get_post($id);
    if (!$post || $post->post_type !== 'content_block') {
        wp_send_json_error(array('message' => 'Блок не найден'), 404);
    }

    $block_name  = isset($_POST['block_name']) ? sanitize_text_field(wp_unslash($_POST['block_name'])) : get_post_meta($id, 'block_name', true);
    $block_title = isset($_POST['block_title']) ? sanitize_text_field(wp_unslash($_POST['block_title'])) : get_post_meta($id, 'block_title', true);
    $block_text  = isset($_POST['block_text']) ? sanitize_textarea_field(wp_unslash($_POST['block_text'])) : get_post_meta($id, 'block_text', true);
    $block_image = isset($_POST['block_image']) ? esc_url_raw(wp_unslash($_POST['block_image'])) : get_post_meta($id, 'block_image', true);
    $block_data  = isset($_POST['block_data']) ? wp_unslash($_POST['block_data']) : get_post_meta($id, 'block_data', true);

    wp_update_post(array(
        'ID' => $id,
        'post_title' => $block_name,
    ));

    update_post_meta($id, 'block_name', $block_name);
    update_post_meta($id, 'block_title', $block_title);
    update_post_meta($id, 'block_text', $block_text);
    update_post_meta($id, 'block_image', $block_image);
    update_post_meta($id, 'block_data', sanitize_text_field($block_data));

    wp_send_json_success(array('message' => 'Сохранено'));
});

/**
 * AJAX handler for resetting content blocks
 */
add_action('wp_ajax_wp_awnings_reset_content', function() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wp_rest')) {
        wp_die('Доступ запрещен');
    }
    
    if (!current_user_can('manage_options')) {
        wp_die('Доступ запрещен');
    }
    
    // Delete all existing content blocks - completely clean
    $blocks = get_posts(array(
        'post_type' => 'content_block',
        'posts_per_page' => -1,
        'post_status' => 'any',
    ));
    
    foreach ($blocks as $block) {
        wp_delete_post($block->ID, true);
    }
    
    // Create fresh initial content blocks
    wp_awnings_create_initial_content_fresh();
    
    echo json_encode(array('success' => true, 'message' => 'Контент успешно пересоздан'));
    wp_die();
});

/**
 * Create fresh initial content blocks - clean version
 */
function wp_awnings_create_initial_content_fresh() {
    // ===== HOME PAGE =====
    $order = 1;
    
    // Hero section
    $hero_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Главный баннер', 'post_status' => 'publish'));
    update_post_meta($hero_id, 'block_name', 'Главный баннер');
    update_post_meta($hero_id, 'block_type', 'hero');
    update_post_meta($hero_id, 'block_page', 'home');
    update_post_meta($hero_id, 'block_title', 'Современные модульные решения для участка в едином стиле');
    update_post_meta($hero_id, 'block_text', 'Беседки для отдыха, мангальные зоны для встреч, навесы для авто для повседневного удобства.');
    update_post_meta($hero_id, 'block_data', json_encode(array(
        'button_text' => 'Посмотреть комплектации',
        'button_link' => '/catalog',
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
    
    // Why Us (4 cards)
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
    
    // What we do (3 cards)
    $whatdoing_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Что мы делаем', 'post_status' => 'publish'));
    update_post_meta($whatdoing_id, 'block_name', 'Что мы делаем');
    update_post_meta($whatdoing_id, 'block_type', 'features');
    update_post_meta($whatdoing_id, 'block_page', 'home');
    update_post_meta($whatdoing_id, 'block_title', 'Беседки, навесы и мангальные зоны для тех, кто ценит удобство и современный внешний вид');
    update_post_meta($whatdoing_id, 'block_data', json_encode(array(
        array('title' => 'Беседка для отдыха', 'category' => 'Сад'),
        array('title' => 'Мангальные зоны', 'category' => 'Барбекю'),
        array('title' => 'Навесы для автомобилей', 'category' => 'Авто'),
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($whatdoing_id, 'block_order', $order++);
    
    // Company numbers
    $companynums_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Компания в цифрах', 'post_status' => 'publish'));
    update_post_meta($companynums_id, 'block_name', 'Компания в цифрах');
    update_post_meta($companynums_id, 'block_type', 'features');
    update_post_meta($companynums_id, 'block_page', 'home');
    update_post_meta($companynums_id, 'block_data', json_encode(array(
        array('title' => '15', 'subtitle' => 'лет на рынке', 'desc' => 'проектируем и устанавливаем конструкции'),
        array('title' => '3 200+', 'subtitle' => 'установленных навесов', 'desc' => 'частные и коммерческие объекты'),
        array('title' => '52', 'subtitle' => 'города доставки', 'desc' => 'организуем логистику и монтаж'),
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($companynums_id, 'block_order', $order++);

    // How we work (home)
    $how_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Как мы работаем', 'post_status' => 'publish'));
    update_post_meta($how_id, 'block_name', 'Как мы работаем');
    update_post_meta($how_id, 'block_type', 'icon-cards');
    update_post_meta($how_id, 'block_page', 'home');
    update_post_meta($how_id, 'block_title', 'Как мы работаем');
    update_post_meta($how_id, 'block_data', json_encode(array(
        array('title' => 'Оставьте заявку', 'text' => 'Заполните форму на сайте или позвоните — ответим в течение 30 минут', 'icon' => 'card-icon-1'),
        array('title' => 'Получите расчёт', 'text' => 'Менеджер уточнит размеры и комплектацию, пришлёт смету', 'icon' => 'card-icon-2'),
        array('title' => 'Подпишите договор', 'text' => 'Фиксируем цену, сроки и состав работ документально', 'icon' => 'card-icon-3'),
        array('title' => 'Монтаж и сдача', 'text' => 'Установим конструкцию за 1-2 дня и подпишем акт приёмки', 'icon' => 'card-icon-1'),
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($how_id, 'block_order', $order++);

    // Our projects (home)
    $proj_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Наши проекты', 'post_status' => 'publish'));
    update_post_meta($proj_id, 'block_name', 'Наши проекты');
    update_post_meta($proj_id, 'block_type', 'gallery');
    update_post_meta($proj_id, 'block_page', 'home');
    update_post_meta($proj_id, 'block_title', 'Наши проекты');
    update_post_meta($proj_id, 'block_data', json_encode(array(
        array('title' => 'Беседка 6м2', 'price' => '126 000', 'image' => 'card.png'),
        array('title' => 'Мангальная зона Стандарт', 'price' => '126 000', 'image' => 'card.png'),
        array('title' => 'Навес для автомобиля 6м2', 'price' => '126 000', 'image' => 'card.png'),
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($proj_id, 'block_order', $order++);

    // ===== ABOUT PAGE =====
    $about_order = 1;

    $about_intro_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'О компании - Вступление', 'post_status' => 'publish'));
    update_post_meta($about_intro_id, 'block_name', 'О компании - Вступление');
    update_post_meta($about_intro_id, 'block_type', 'section');
    update_post_meta($about_intro_id, 'block_page', 'about');
    update_post_meta($about_intro_id, 'block_title', 'О компании');
    update_post_meta($about_intro_id, 'block_text', 'Надежный партнер для вашего участка');
    update_post_meta($about_intro_id, 'block_order', $about_order++);

    $about_why_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'О компании - Почему мы', 'post_status' => 'publish'));
    update_post_meta($about_why_id, 'block_name', 'Почему мы');
    update_post_meta($about_why_id, 'block_type', 'features');
    update_post_meta($about_why_id, 'block_page', 'about');
    update_post_meta($about_why_id, 'block_title', 'Почему вы выбираете нас?');
    update_post_meta($about_why_id, 'block_text', 'Быстрые сроки и высокое качество работы, а так же конфигуратор моделей под любой бюджет');
    update_post_meta($about_why_id, 'block_data', json_encode(array(
        array('title' => 'Собственное производство', 'text' => 'Производим конструкции на собственном заводе в Екатеринбурге.', 'icon' => 'card-icon-1'),
        array('title' => 'Гарантия качества', 'text' => 'Используем сертифицированные материалы с гарантией.', 'icon' => 'card-icon-2'),
        array('title' => 'Индивидуальный подход', 'text' => 'Разрабатываем проекты под ваши задачи и бюджет.', 'icon' => 'card-icon-3')
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($about_why_id, 'block_order', $about_order++);

    $about_prod_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'О компании - Производство', 'post_status' => 'publish'));
    update_post_meta($about_prod_id, 'block_name', 'Производство');
    update_post_meta($about_prod_id, 'block_type', 'icon-cards');
    update_post_meta($about_prod_id, 'block_page', 'about');
    update_post_meta($about_prod_id, 'block_title', 'Наше производство');
    update_post_meta($about_prod_id, 'block_data', json_encode(array(
        array('title' => 'Проектирование', 'text' => 'Подготавливаем точные чертежи и расчеты.', 'icon' => 'card-icon-1'),
        array('title' => 'Производство', 'text' => 'Изготавливаем элементы на собственном оборудовании.', 'icon' => 'card-icon-2'),
        array('title' => 'Монтаж', 'text' => 'Собираем и сдаем объект под ключ.', 'icon' => 'card-icon-3')
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($about_prod_id, 'block_order', $about_order++);

    // ===== CONTACTS PAGE =====
    $contacts_order = 1;

    $contacts_info_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Контакты - Информация', 'post_status' => 'publish'));
    update_post_meta($contacts_info_id, 'block_name', 'Контактная информация');
    update_post_meta($contacts_info_id, 'block_type', 'contact');
    update_post_meta($contacts_info_id, 'block_page', 'contacts');
    update_post_meta($contacts_info_id, 'block_title', 'Контакты');
    update_post_meta($contacts_info_id, 'block_data', json_encode(array(
        'phone' => '+7 (900) 123-45-67',
        'email' => 'info@navesstroy.ru',
        'address' => 'г. Екатеринбург, ул. Промышленная, д. 4, стр. 2',
        'schedule' => 'Пн-Вс: 9:00-18:00'
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($contacts_info_id, 'block_order', $contacts_order++);

    $contacts_form_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Контакты - Форма', 'post_status' => 'publish'));
    update_post_meta($contacts_form_id, 'block_name', 'Форма обратной связи');
    update_post_meta($contacts_form_id, 'block_type', 'section');
    update_post_meta($contacts_form_id, 'block_page', 'contacts');
    update_post_meta($contacts_form_id, 'block_title', 'Остались вопросы?');
    update_post_meta($contacts_form_id, 'block_text', 'Оставьте заявку и наш менеджер свяжется с вами, чтобы ответить на ваши вопросы!');
    update_post_meta($contacts_form_id, 'block_order', $contacts_order++);

    // ===== DELIVERY PAGE =====
    $delivery_order = 1;

    $delivery_regions_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Доставка - Регионы', 'post_status' => 'publish'));
    update_post_meta($delivery_regions_id, 'block_name', 'Регионы доставки');
    update_post_meta($delivery_regions_id, 'block_type', 'regions');
    update_post_meta($delivery_regions_id, 'block_page', 'delivery');
    update_post_meta($delivery_regions_id, 'block_title', 'Регионы доставки');
    update_post_meta($delivery_regions_id, 'block_text', 'Осуществляем доставку и установку по всей территории России');
    update_post_meta($delivery_regions_id, 'block_image', 'delivery-regions.png');
    update_post_meta($delivery_regions_id, 'block_data', json_encode(array(
        'Москва', 'Санкт-Петербург', 'Екатеринбург', 'Казань', 'Новосибирск', 'Челябинск', 'Тюмень', 'Пермь'
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($delivery_regions_id, 'block_order', $delivery_order++);

    $delivery_how_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Доставка - Как происходит доставка', 'post_status' => 'publish'));
    update_post_meta($delivery_how_id, 'block_name', 'Как происходит доставка');
    update_post_meta($delivery_how_id, 'block_type', 'icon-cards');
    update_post_meta($delivery_how_id, 'block_page', 'delivery');
    update_post_meta($delivery_how_id, 'block_title', 'Как происходит доставка');
    update_post_meta($delivery_how_id, 'block_data', json_encode(array(
        array('title' => 'Заявка', 'text' => 'Принимаем заявку и уточняем параметры.', 'icon' => 'card-icon-1'),
        array('title' => 'Согласование', 'text' => 'Подтверждаем сроки и дату отгрузки.', 'icon' => 'card-icon-2'),
        array('title' => 'Доставка', 'text' => 'Доставляем и разгружаем комплект.', 'icon' => 'card-icon-3'),
        array('title' => 'Монтаж', 'text' => 'Собираем конструкцию на объекте.', 'icon' => 'card-icon-1')
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($delivery_how_id, 'block_order', $delivery_order++);

    $delivery_pay_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Доставка - Способы оплаты', 'post_status' => 'publish'));
    update_post_meta($delivery_pay_id, 'block_name', 'Способы оплаты');
    update_post_meta($delivery_pay_id, 'block_type', 'features');
    update_post_meta($delivery_pay_id, 'block_page', 'delivery');
    update_post_meta($delivery_pay_id, 'block_data', json_encode(array(
        array('title' => 'Безналичный расчет', 'text' => 'Для юридических лиц и ИП. Выставляем счет.', 'icon' => 'payment-1'),
        array('title' => 'Банковский перевод', 'text' => 'Перевод на расчетный счет компании.', 'icon' => 'payment-2'),
        array('title' => 'Наличные', 'text' => 'Оплата при получении или в офисе компании.', 'icon' => 'payment-3'),
        array('title' => 'Рассрочка', 'text' => 'Возможна рассрочка платежа на срок до 6 месяцев.', 'icon' => 'payment-4')
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($delivery_pay_id, 'block_order', $delivery_order++);
    
    // ===== FAQ PAGE =====
    $faq_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Частые вопросы', 'post_status' => 'publish'));
    update_post_meta($faq_id, 'block_name', 'Частые вопросы');
    update_post_meta($faq_id, 'block_type', 'faq');
    update_post_meta($faq_id, 'block_page', 'faq');
    update_post_meta($faq_id, 'block_data', json_encode(array(
        array('question' => 'Какие конструкции Вы изготавливаете?', 'answer' => 'Мы изготавливаем навесы, беседки, мангальные зоны и террасы из металла с различными типами кровли.'),
        array('question' => 'Подходят ли конструкции для круглогодичного использования?', 'answer' => 'Да, все наши конструкции рассчитаны на эксплуатацию в любое время года.'),
        array('question' => 'Можно ли выбрать размер конструкции?', 'answer' => 'Да, мы изготавливаем конструкции по индивидуальным размерам под ваши задачи.'),
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($faq_id, 'block_order', 1);

    // ===== NEWS PAGE =====
    $news_order = 1;

    $news_header_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Новости - Заголовок', 'post_status' => 'publish'));
    update_post_meta($news_header_id, 'block_name', 'Новости - Заголовок');
    update_post_meta($news_header_id, 'block_type', 'section');
    update_post_meta($news_header_id, 'block_page', 'news');
    update_post_meta($news_header_id, 'block_title', 'Новости и статьи');
    update_post_meta($news_header_id, 'block_text', 'Полезная информация о навесах, материалах и уходе');
    update_post_meta($news_header_id, 'block_order', $news_order++);

    $news_cards_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Новости - Карточки', 'post_status' => 'publish'));
    update_post_meta($news_cards_id, 'block_name', 'Новости - Карточки');
    update_post_meta($news_cards_id, 'block_type', 'news-cards');
    update_post_meta($news_cards_id, 'block_page', 'news');
    update_post_meta($news_cards_id, 'block_data', json_encode(array(
        array(
            'title' => 'Уход за металлическим навесом: советы по обслуживанию',
            'description' => 'Регулярный уход продлевает жизнь навеса. Узнайте, как правильно чистить и обслуживать конструкцию.',
            'date' => '15 марта 2025 г.',
            'image' => 'company-card-1.png',
            'link' => '/news-articles/1'
        ),
        array(
            'title' => 'Как выбрать навес для автомобиля',
            'description' => 'Разбираемся в материалах, размерах и конструкциях навесов для вашего авто.',
            'date' => '10 марта 2025 г.',
            'image' => 'company-card-1.png',
            'link' => '/news-articles/2'
        ),
        array(
            'title' => 'Навесы из поликарбоната: преимущества и недостатки',
            'description' => 'Всё о самом популярном материале для навесов.',
            'date' => '5 марта 2025 г.',
            'image' => 'company-card-1.png',
            'link' => '/news-articles/3'
        )
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($news_cards_id, 'block_order', $news_order++);

    // ===== GARANT PAGE =====
    $gar_order = 1;

    $gar1_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Гарантия - Баннер', 'post_status' => 'publish'));
    update_post_meta($gar1_id, 'block_name', 'Гарантия - Баннер');
    update_post_meta($gar1_id, 'block_type', 'section');
    update_post_meta($gar1_id, 'block_page', 'garant');
    update_post_meta($gar1_id, 'block_title', 'Гарантия');
    update_post_meta($gar1_id, 'block_text', 'Мы обеспечиваем заказчикам гарантию на срок 12 месяцев, в том числе 12 месяцев претензионного обслуживания бесплатно');
    update_post_meta($gar1_id, 'block_image', 'hero-bg.png');
    update_post_meta($gar1_id, 'block_order', $gar_order++);

    $gar2_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Гарантия - На что распространяется', 'post_status' => 'publish'));
    update_post_meta($gar2_id, 'block_name', 'Гарантия - На что распространяется');
    update_post_meta($gar2_id, 'block_type', 'faq');
    update_post_meta($gar2_id, 'block_page', 'garant');
    update_post_meta($gar2_id, 'block_title', 'На что распространяется гарантия');
    update_post_meta($gar2_id, 'block_data', json_encode(array(
        array('question' => 'Дефекты материалов', 'answer' => 'Гарантия распространяется на скрытые дефекты материалов и комплектующих.'),
        array('question' => 'Деформация конструкции', 'answer' => 'Устраняем деформации, возникшие не по вине заказчика.'),
        array('question' => 'Производственный брак', 'answer' => 'Исправляем недочеты, допущенные при производстве.'),
        array('question' => 'Протечки кровли', 'answer' => 'Устраняем нарушения герметичности при соблюдении условий эксплуатации.')
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($gar2_id, 'block_order', $gar_order++);

    $gar3_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Гарантия - На что не распространяется', 'post_status' => 'publish'));
    update_post_meta($gar3_id, 'block_name', 'Гарантия - На что не распространяется');
    update_post_meta($gar3_id, 'block_type', 'faq');
    update_post_meta($gar3_id, 'block_page', 'garant');
    update_post_meta($gar3_id, 'block_title', 'На что не распространяется гарантия');
    update_post_meta($gar3_id, 'block_data', json_encode(array(
        array('question' => 'Механические повреждения', 'answer' => 'Повреждения из-за ударов, неосторожной эксплуатации и внешнего воздействия.'),
        array('question' => 'Самостоятельная модификация', 'answer' => 'Изменения конструкции без согласования с производителем.'),
        array('question' => 'Нарушение правил эксплуатации', 'answer' => 'Использование с нарушением технических требований и инструкций.')
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($gar3_id, 'block_order', $gar_order++);

    $gar4_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Гарантия - Как воспользоваться', 'post_status' => 'publish'));
    update_post_meta($gar4_id, 'block_name', 'Гарантия - Как воспользоваться');
    update_post_meta($gar4_id, 'block_type', 'icon-cards');
    update_post_meta($gar4_id, 'block_page', 'garant');
    update_post_meta($gar4_id, 'block_title', 'Как воспользоваться гарантией?');
    update_post_meta($gar4_id, 'block_data', json_encode(array(
        array('icon' => 'payment-1', 'title' => 'Свяжитесь с нами', 'text' => 'Позвоните или напишите о возникшей проблеме.'),
        array('icon' => 'payment-2', 'title' => 'Диагностика', 'text' => 'Наш специалист осмотрит изделие и определит причину.'),
        array('icon' => 'payment-3', 'title' => 'Устранение', 'text' => 'Бесплатно устраним дефект или заменим изделие.')
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($gar4_id, 'block_order', $gar_order++);
    
    // ===== FOOTER =====
    $foot_order = 1;
    
    $footer_main_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Футер - Основная информация', 'post_status' => 'publish'));
    update_post_meta($footer_main_id, 'block_name', 'Футер - Основная информация');
    update_post_meta($footer_main_id, 'block_type', 'footer');
    update_post_meta($footer_main_id, 'block_page', 'footer');
    update_post_meta($footer_main_id, 'block_title', 'НавесСтрой');
    update_post_meta($footer_main_id, 'block_text', 'Производство и продажа металлических навесов в Екатеринбурге и Свердловской области. Доставка и монтаж по всей России.');
    update_post_meta($footer_main_id, 'block_data', json_encode(array(
        'copyright' => '© 2026 НавесСтрой. Все права защищены.',
        'privacy' => 'Политика конфиденциальности',
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($footer_main_id, 'block_order', $foot_order++);
    
    $footer_contacts_id = wp_insert_post(array('post_type' => 'content_block', 'post_title' => 'Футер - Контакты', 'post_status' => 'publish'));
    update_post_meta($footer_contacts_id, 'block_name', 'Футер - Контакты');
    update_post_meta($footer_contacts_id, 'block_type', 'footer');
    update_post_meta($footer_contacts_id, 'block_page', 'footer');
    update_post_meta($footer_contacts_id, 'block_data', json_encode(array(
        'phone' => '+7 (900) 123-45-67',
        'email' => 'info@navesstroy.ru',
    ), JSON_UNESCAPED_UNICODE));
    update_post_meta($footer_contacts_id, 'block_order', $foot_order++);
}
