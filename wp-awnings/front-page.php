<?php
/**
 * Catalog page template
 *
 * @package wp-awnings
 */

get_header();
?>

<main class="site-main catalog-page">
    <div class="container">
        
        <div class="wrap-title">
            <div class="breadcrumbs">
                <a href="<?php echo home_url('/'); ?>">Главная</a>
                <span>/</span>
                <a href="<?php echo home_url('/catalog'); ?>">Каталог</a>
            </div>
            <h1>Каталог</h1>
        </div>

        <!-- Categories filter -->
        <div class="categories">
            <?php
            $categories = array(
                array('id' => 'all', 'name' => 'Все'),
                array('id' => 'besedka', 'name' => 'Беседки'),
                array('id' => 'mangal', 'name' => 'Мангальные зоны'),
                array('id' => 'naves', 'name' => 'Навесы для авто'),
            );
            
            foreach ($categories as $cat) :
            ?>
                <button class="category-btn" data-category="<?php echo esc_attr($cat['id']); ?>">
                    <?php echo esc_html($cat['name']); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Products grid -->
        <div class="cards" id="products-grid">
            <div class="loading">Загрузка...</div>
        </div>

    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const API_URL = '<?php echo rest_url('wp-awnings/v1'); ?>';
    
    // Load products
    async function loadProducts(category = 'all') {
        const grid = document.getElementById('products-grid');
        grid.innerHTML = '<div class="loading">Загрузка...</div>';
        
        try {
            const url = category === 'all' 
                ? `${API_URL}/products` 
                : `${API_URL}/products?category=${category}`;
            
            const response = await fetch(url);
            const products = await response.json();
            
            if (products.length === 0) {
                grid.innerHTML = '<p>Товары не найдены</p>';
                return;
            }
            
            grid.innerHTML = products.map(product => `
                <div class="card" data-id="${product.id}">
                    <div class="wrap-img">
                        ${product.image_url 
                            ? `<img src="${product.image_url}" alt="${product.title}">` 
                            : '<img src="https://via.placeholder.com/400x300?text=Нет+изображения" alt="">'}
                    </div>
                    <span class="card-title">${product.title}</span>
                    <span class="card-price">${product.price || 'Цена по запросу'}</span>
                    <button class="card-btn" onclick="openLeadForm(${product.id}, '${product.title}')">
                        В конфигуратор модели
                        <div class="wrap-btn-img">
                            <img class="btn-img" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='white'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M7 17L17 7M17 7H7M17 7V17'/%3E%3C/svg%3E" alt=''>
                        </div>
                    </button>
                </div>
            `).join('');
            
        } catch (error) {
            grid.innerHTML = '<p>Ошибка загрузки товаров</p>';
            console.error('Error loading products:', error);
        }
    }
    
    // Category filters
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            loadProducts(this.dataset.category);
        });
    });
    
    // Initial load
    loadProducts();
});

// Global function for lead form
window.openLeadForm = function(productId, productTitle) {
    const name = prompt('Введите ваше имя:');
    if (!name) return;
    
    const phone = prompt('Введите ваш телефон:');
    if (!phone) return;
    
    const message = prompt('Ваш вопрос (необязательно):') || '';
    
    fetch('<?php echo rest_url('wp-awnings/v1'); ?>/leads', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            name: name,
            phone: phone,
            message: message,
            product_id: productId,
            agree: true
        })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message || 'Заявка отправлена!');
    })
    .catch(error => {
        alert('Ошибка отправки заявки');
        console.error('Error:', error);
    });
};
</script>

<?php
get_footer();