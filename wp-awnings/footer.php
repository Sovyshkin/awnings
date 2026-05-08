<?php
/**
 * Footer template
 *
 * @package wp-awnings
 */
?>

<footer class="site-footer">
    <div class="container">
        <div class="footer-main">
            <div class="footer-name">
                <h2>Название</h2>
                <p>Производство и продажа металлических навесов в Екатеринбурге и Свердловской области. Доставка и монтаж по всей России.</p>
            </div>
            <div class="footer-nav">
                <div class="catalog">
                    <h3>Каталог</h3>
                    <ul class="list-nav">
                        <li class="item-nav"><a href="<?php echo home_url('/product-category/besedka'); ?>">Беседки</a></li>
                        <li class="item-nav"><a href="<?php echo home_url('/product-category/mangal'); ?>">Мангальные зоны</a></li>
                        <li class="item-nav"><a href="<?php echo home_url('/product-category/naves'); ?>">Навесы для авто</a></li>
                    </ul>
                </div>
                <div class="client">
                    <h3>Покупателям</h3>
                    <ul class="list-nav">
                        <li class="item-nav"><a href="<?php echo home_url('/about-company'); ?>">О компании</a></li>
                        <li class="item-nav"><a href="<?php echo home_url('/news-articles'); ?>">Новости и статьи</a></li>
                        <li class="item-nav"><a href="<?php echo home_url('/delivery-and-payment'); ?>">Доставка и оплата</a></li>
                        <li class="item-nav"><a href="<?php echo home_url('/garant'); ?>">Гарантия</a></li>
                        <li class="item-nav"><a href="<?php echo home_url('/contacts'); ?>">Контакты</a></li>
                    </ul>
                </div>
                <div class="contact">
                    <h3>Контакты</h3>
                    <ul class="list-nav">
                        <li class="item-nav">+7 (900) 123-45-67</li>
                        <li class="item-nav">info@navesstroy.ru</li>
                        <li class="item-nav">г. Екатеринбург, ул. Промышленная, д. 4, стр. 2</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-sub">
            <span class="copy">© <?php echo date('Y'); ?> Название. Все права защищены.</span>
            <div class="docs">
                <span>Политика конфиденциальности</span>
                <span>Пользовательское соглашение</span>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>