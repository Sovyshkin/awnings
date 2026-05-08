<?php
/**
 * Header template
 *
 * @package wp-awnings
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <button class="menu-toggle" aria-label="Меню">
        <span></span>
        <span></span>
        <span></span>
    </button>
    
    <nav class="main-nav">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'container' => false,
            'menu_class' => 'nav-menu',
            'fallback_cb' => function() {
                echo '<ul class="nav-menu">
                    <li><a href="' . home_url('/') . '">Главная</a></li>
                    <li><a href="' . home_url('/catalog') . '">Каталог</a></li>
                    <li><a href="' . home_url('/contacts') . '">Контакты</a></li>
                </ul>';
            }
        ));
        ?>
    </nav>
    
    <a href="<?php echo home_url('/catalog'); ?>" class="nav-link">Каталог</a>
</header>

<?php if (is_front_page()) : ?>
<!-- Hero section for homepage -->
<section class="hero-section">
    <div class="hero-content">
        <h1>Навесы и беседки<br>для вашего комфорта</h1>
        <p>Производство и монтаж металлических конструкций<br>в Екатеринбурге и Свердловской области</p>
        <a href="<?php echo home_url('/catalog'); ?>" class="hero-btn">Смотреть каталог</a>
    </div>
</section>
<?php endif; ?>