<?php
/**
 * Admin page for managing products
 *
 * @package wp-awnings
 */

if (!current_user_can('manage_options')) {
    wp_die('Доступ запрещен');
}

$categories = get_terms(array(
    'taxonomy' => 'product_category',
    'hide_empty' => false,
));
?>

<style>
.wpa-wrap { padding: 20px 0; }
.wpa-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #eee; }
.wpa-title { font-size: 28px; font-weight: 600; color: #1d2327; margin: 0; }
.wpa-btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: #2271b1; color: #fff; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; transition: background 0.2s; }
.wpa-btn:hover { background: #135e96; }
.wpa-btn-primary { background: #C96744; }
.wpa-btn-primary:hover { background: #b55a3a; }
.wpa-table-wrap { background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; }
.wpa-table { width: 100%; border-collapse: collapse; }
.wpa-table th { background: #f6f7f7; padding: 16px; text-align: left; font-weight: 600; color: #1d2327; border-bottom: 1px solid #eee; }
.wpa-table td { padding: 16px; border-bottom: 1px solid #eee; vertical-align: middle; }
.wpa-table tr:hover { background: #f6f7f7; }
.wpa-table img { width: 60px; height: 40px; object-fit: cover; border-radius: 4px; }
.wpa-table .images-cell { display: flex; gap: 4px; flex-wrap: wrap; }
.wpa-table .images-cell img { width: 40px; height: 30px; }
.wpa-actions { display: flex; gap: 8px; }
.wpa-btn-sm { padding: 6px 12px; border: none; border-radius: 4px; font-size: 12px; cursor: pointer; transition: opacity 0.2s; }
.wpa-btn-sm:hover { opacity: 0.8; }
.wpa-btn-edit { background: #2271b1; color: #fff; }
.wpa-btn-delete { background: #dc3232; color: #fff; }
.wpa-modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 100000; justify-content: center; align-items: center; }
.wpa-modal-overlay.active { display: flex; }
.wpa-modal { background: #fff; border-radius: 12px; width: 95%; max-width: 650px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
.wpa-modal-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid #eee; }
.wpa-modal-header h2 { margin: 0; font-size: 20px; font-weight: 600; color: #1d2327; }
.wpa-modal-close { background: none; border: none; font-size: 28px; cursor: pointer; color: #646970; padding: 0; line-height: 1; }
.wpa-modal-close:hover { color: #1d2327; }
.wpa-modal-body { padding: 24px; max-height: calc(90vh - 160px); overflow-y: auto; }
.wpa-form-group { margin-bottom: 20px; }
.wpa-form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #1d2327; }
.wpa-form-group label span { color: #dc3232; }
.wpa-form-input { width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; transition: border-color 0.2s, box-shadow 0.2s; box-sizing: border-box; }
.wpa-form-input:focus { outline: none; border-color: #2271b1; box-shadow: 0 0 0 2px rgba(34,113,177,0.2); }
.wpa-form-input::placeholder { color: #a7aaad; }
.wpa-form-textarea { resize: vertical; min-height: 100px; }
.wpa-form-select { width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: #fff; cursor: pointer; }
.wpa-form-footer { display: flex; justify-content: flex-end; gap: 12px; padding: 20px 24px; border-top: 1px solid #eee; background: #f6f7f7; }
.wpa-btn-cancel { background: #f6f7f7; color: #1d2327; border: 1px solid #ddd; }
.wpa-btn-cancel:hover { background: #fff; }
.wpa-notice { padding: 16px 20px; border-radius: 6px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; }
.wpa-notice-success { background: #00a32a; color: #fff; }
.wpa-notice-error { background: #dc3232; color: #fff; }
.wpa-gallery { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-top: 10px; }
.wpa-gallery-item { position: relative; aspect-ratio: 1; border-radius: 8px; overflow: hidden; border: 2px solid #eee; cursor: move; }
.wpa-gallery-item img { width: 100%; height: 100%; object-fit: cover; }
.wpa-gallery-item.main { border-color: #C96744; }
.wpa-gallery-item.main::after { content: 'Главное'; position: absolute; top: 4px; left: 4px; background: #C96744; color: #fff; font-size: 10px; padding: 2px 6px; border-radius: 4px; }
.wpa-gallery-remove { position: absolute; top: 4px; right: 4px; width: 24px; height: 24px; background: #dc3232; color: #fff; border: none; border-radius: 50%; cursor: pointer; font-size: 14px; line-height: 1; opacity: 0; transition: opacity 0.2s; }
.wpa-gallery-item:hover .wpa-gallery-remove { opacity: 1; }
.wpa-gallery-add { aspect-ratio: 1; border: 2px dashed #ddd; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; font-size: 32px; color: #a7aaad; }
.wpa-gallery-add:hover { border-color: #2271b1; color: #2271b1; background: #f0f7ff; }
.wpa-uploading { display: flex; align-items: center; gap: 10px; padding: 10px; background: #f0f7ff; border-radius: 6px; margin-top: 10px; }
.wpa-uploading-spinner { width: 20px; height: 20px; border: 2px solid #2271b1; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>

<div class="wrap wpa-wrap">
    <div class="wpa-header">
        <h1 class="wpa-title">Управление товарами</h1>
        <button class="wpa-btn wpa-btn-primary" id="btn-add-product">
            <span>+</span> Добавить товар
        </button>
    </div>

    <div id="wpa-notice"></div>

    <div class="wpa-table-wrap">
        <table class="wpa-table">
            <thead>
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th>Название</th>
                    <th style="width: 100px;">Категория</th>
                    <th style="width: 130px;">Цена</th>
                    <th style="width: 100px;">Фото</th>
                    <th style="width: 120px;">Действия</th>
                </tr>
            </thead>
            <tbody id="products-list">
                <tr><td colspan="6" style="text-align: center; padding: 40px;">Загрузка...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Product Modal -->
<div id="product-modal" class="wpa-modal-overlay">
    <div class="wpa-modal">
        <div class="wpa-modal-header">
            <h2 id="modal-title">Новый товар</h2>
            <button class="wpa-modal-close" id="modal-close-btn">&times;</button>
        </div>
        <form id="product-form">
            <div class="wpa-modal-body">
                <input type="hidden" id="product-id" value="">
                
                <div class="wpa-form-group">
                    <label for="product-title">Название товара <span>*</span></label>
                    <input type="text" id="product-title" class="wpa-form-input" placeholder="Например: Беседка 6м2" required>
                </div>
                
                <div class="wpa-form-group">
                    <label for="product-price">Цена</label>
                    <input type="text" id="product-price" class="wpa-form-input" placeholder="от 126 000 ₽">
                </div>
                
                <div class="wpa-form-group">
                    <label for="product-category">Категория</label>
                    <select id="product-category" class="wpa-form-select">
                        <option value="">Выберите категорию</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat->term_id; ?>"><?php echo esc_html($cat->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="wpa-form-group">
                    <label>Фотографии товара</label>
                    <div class="wpa-gallery" id="product-gallery">
                        <div class="wpa-gallery-add" id="add-image-btn">+</div>
                    </div>
                    <input type="file" id="image-input" accept="image/*" multiple style="display: none;">
                    <div id="uploading-indicator" class="wpa-uploading" style="display: none;">
                        <div class="wpa-uploading-spinner"></div>
                        <span>Загрузка...</span>
                    </div>
                </div>
                
                <div class="wpa-form-group">
                    <label for="product-content">Описание</label>
                    <textarea id="product-content" class="wpa-form-input wpa-form-textarea" placeholder="Описание товара..."></textarea>
                </div>
            </div>
            <div class="wpa-form-footer">
                <button type="button" class="wpa-btn wpa-btn-cancel" id="btn-cancel">Отмена</button>
                <button type="submit" class="wpa-btn wpa-btn-primary">Сохранить</button>
            </div>
        </form>
    </div>
</div>

<script>
(function() {
    const API_URL = '<?php echo rest_url('wp-awnings/v1'); ?>';
    let products = [];
    let images = [];
    
    // DOM Elements
    const productsList = document.getElementById('products-list');
    const noticeEl = document.getElementById('wpa-notice');
    const modal = document.getElementById('product-modal');
    const modalTitle = document.getElementById('modal-title');
    const form = document.getElementById('product-form');
    const gallery = document.getElementById('product-gallery');
    const addImageBtn = document.getElementById('add-image-btn');
    const imageInput = document.getElementById('image-input');
    const uploadingIndicator = document.getElementById('uploading-indicator');
    
    // Show notice
    function showNotice(message, type = 'success') {
        noticeEl.innerHTML = `<div class="wpa-notice wpa-notice-${type}">${message}</div>`;
        setTimeout(() => noticeEl.innerHTML = '', 4000);
    }
    
    // Render products list
    function renderProducts() {
        if (products.length === 0) {
            productsList.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 40px;">Товары не найдены</td></tr>';
            return;
        }
        
        productsList.innerHTML = products.map(p => {
            const imageUrls = p.image_url ? (typeof p.image_url === 'string' ? [p.image_url] : p.image_url) : [];
            const thumbs = imageUrls.slice(0, 3).map(url => `<img src="${escapeHtml(url)}" alt="">`).join('');
            const moreCount = imageUrls.length > 3 ? imageUrls.length - 3 : 0;
            const categoryDisplay = p.category_name || '-';
            
            return `
            <tr data-id="${p.id}">
                <td>${p.id}</td>
                <td><strong>${escapeHtml(p.title)}</strong></td>
                <td>${categoryDisplay}</td>
                <td style="color: #C96744; font-weight: 500;">${p.price || '-'}</td>
                <td>
                    <div class="images-cell">
                        ${thumbs}
                        ${moreCount > 0 ? `<span style="font-size: 11px; color: #646970;">+${moreCount}</span>` : ''}
                    </div>
                </td>
                <td class="wpa-actions">
                    <button class="wpa-btn-sm wpa-btn-edit" data-edit="${p.id}">Изменить</button>
                    <button class="wpa-btn-sm wpa-btn-delete" data-delete="${p.id}">Удалить</button>
                </td>
            </tr>
        `}).join('');
    }
    
    // Escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Render gallery
    function renderGallery() {
        const items = images.map((url, index) => `
            <div class="wpa-gallery-item ${index === 0 ? 'main' : ''}" data-index="${index}" draggable="true">
                <img src="${escapeHtml(url)}" alt="">
                <button class="wpa-gallery-remove" data-remove="${index}">×</button>
            </div>
        `).join('');
        
        gallery.innerHTML = items + '<div class="wpa-gallery-add" id="add-image-btn">+</div>';
        
        // Re-attach events
        document.getElementById('add-image-btn').addEventListener('click', () => imageInput.click());
        document.querySelectorAll('.wpa-gallery-remove').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const index = parseInt(btn.dataset.remove);
                images.splice(index, 1);
                renderGallery();
            });
        });
        
        // Drag and drop reorder
        document.querySelectorAll('.wpa-gallery-item').forEach(item => {
            item.addEventListener('dragstart', (e) => {
                e.dataTransfer.setData('text/plain', item.dataset.index);
            });
            item.addEventListener('dragover', (e) => {
                e.preventDefault();
                item.style.opacity = '0.5';
            });
            item.addEventListener('dragleave', () => {
                item.style.opacity = '1';
            });
            item.addEventListener('drop', (e) => {
                e.preventDefault();
                item.style.opacity = '1';
                const fromIndex = parseInt(e.dataTransfer.getData('text/plain'));
                const toIndex = parseInt(item.dataset.index);
                const [moved] = images.splice(fromIndex, 1);
                images.splice(toIndex, 0, moved);
                renderGallery();
            });
            item.addEventListener('dragend', () => {
                item.style.opacity = '1';
            });
        });
    }
    
    // Open modal
    function openModal(product = null) {
        document.getElementById('product-id').value = product ? product.id : '';
        document.getElementById('product-title').value = product ? product.title : '';
        document.getElementById('product-price').value = product ? (product.price || '') : '';
        document.getElementById('product-category').value = product ? (product.category_id || '') : '';
        document.getElementById('product-content').value = product ? (product.content || '') : '';
        
        // Load images
        if (product && product.image_url) {
            images = typeof product.image_url === 'string' ? [product.image_url] : product.image_url;
        } else {
            images = [];
        }
        renderGallery();
        
        modalTitle.textContent = product ? 'Редактировать товар' : 'Новый товар';
        modal.classList.add('active');
    }
    
    // Close modal
    function closeModal() {
        modal.classList.remove('active');
        form.reset();
        images = [];
        renderGallery();
    }
    
    // Upload single image
    async function uploadImage(file) {
        const formData = new FormData();
        formData.append('file', file);
        
        uploadingIndicator.style.display = 'flex';
        
        try {
            const response = await fetch(API_URL + '/upload', {
                method: 'POST',
                body: formData
            });
            
            uploadingIndicator.style.display = 'none';
            
            const data = await response.json();
            
            if (response.ok && data.url) {
                images.push(data.url);
                renderGallery();
                return true;
            } else {
                console.error('Upload error:', data);
                showNotice('Ошибка загрузки: ' + (data.message || 'Неизвестная ошибка'), 'error');
                return false;
            }
        } catch (error) {
            uploadingIndicator.style.display = 'none';
            console.error('Upload exception:', error);
            showNotice('Ошибка загрузки: ' + error.message, 'error');
            return false;
        }
    }
    
    // Handle multiple file upload in parallel
    async function handleFileUpload(files) {
        if (!files || files.length === 0) return;
        
        uploadingIndicator.style.display = 'flex';
        uploadingIndicator.querySelector('span').textContent = 'Загрузка 0/' + files.length + '...';
        
        const imageFiles = Array.from(files).filter(f => f.type.startsWith('image/'));
        let completed = 0;
        
        // Upload all at once
        const promises = imageFiles.map(file => {
            const formData = new FormData();
            formData.append('file', file);
            
            return fetch(API_URL + '/upload', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                completed++;
                uploadingIndicator.querySelector('span').textContent = 'Загрузка ' + completed + '/' + imageFiles.length + '...';
                
                if (data.url) {
                    images.push(data.url);
                    renderGallery();
                    return true;
                }
                return false;
            })
            .catch(error => {
                completed++;
                console.error('Upload error:', error);
                return false;
            });
        });
        
        Promise.all(promises).then(results => {
            uploadingIndicator.style.display = 'none';
            const failed = results.filter(r => !r).length;
            if (failed > 0) {
                showNotice('Не удалось загрузить ' + failed + ' файл(ов)', 'error');
            }
        });
    }
    
    // Event listeners for image upload
    imageInput.addEventListener('change', function(e) {
        if (this.files && this.files.length > 0) {
            handleFileUpload(this.files);
            this.value = '';
        }
    });
    
    document.getElementById('add-image-btn').addEventListener('click', () => imageInput.click());
    
    // Load products
    async function loadProducts() {
        try {
            const response = await fetch(API_URL + '/products');
            products = await response.json();
            renderProducts();
        } catch (error) {
            showNotice('Ошибка загрузки товаров', 'error');
        }
    }
    
    // Delete product
    async function deleteProduct(id) {
        if (!confirm('Удалить этот товар?')) return;
        
        try {
            const response = await fetch(API_URL + '/products/' + id, { method: 'DELETE' });
            
            if (response.ok) {
                products = products.filter(p => p.id !== id);
                renderProducts();
                showNotice('Товар удален');
            } else {
                showNotice('Ошибка удаления', 'error');
            }
        } catch (error) {
            showNotice('Ошибка удаления', 'error');
        }
    }
    
    // Save product
    async function saveProduct(data, id) {
        const url = id ? API_URL + '/products/' + id : API_URL + '/products';
        const method = id ? 'PUT' : 'POST';
        
        try {
            const response = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
            if (response.ok) {
                const result = await response.json();
                
                if (id) {
                    products = products.map(p => p.id === id ? result : p);
                    showNotice('Товар обновлен');
                } else {
                    products.push(result);
                    showNotice('Товар создан');
                }
                
                renderProducts();
                closeModal();
            } else {
                showNotice('Ошибка сохранения', 'error');
            }
        } catch (error) {
            showNotice('Ошибка сохранения', 'error');
        }
    }
    
    // Event listeners
    document.getElementById('btn-add-product').addEventListener('click', () => openModal());
    document.getElementById('modal-close-btn').addEventListener('click', closeModal);
    document.getElementById('btn-cancel').addEventListener('click', closeModal);
    
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });
    
    productsList.addEventListener('click', (e) => {
        const editBtn = e.target.closest('[data-edit]');
        const deleteBtn = e.target.closest('[data-delete]');
        
        if (editBtn) {
            const id = parseInt(editBtn.dataset.edit);
            const product = products.find(p => p.id === id);
            if (product) openModal(product);
        }
        
        if (deleteBtn) {
            const id = parseInt(deleteBtn.dataset.delete);
            deleteProduct(id);
        }
    });
    
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const id = document.getElementById('product-id').value ? parseInt(document.getElementById('product-id').value) : null;
        const data = {
            title: document.getElementById('product-title').value.trim(),
            price: document.getElementById('product-price').value,
            category_id: document.getElementById('product-category').value,
            image_url: images,
            content: document.getElementById('product-content').value
        };
        
        if (!data.title) {
            showNotice('Введите название товара', 'error');
            return;
        }
        
        saveProduct(data, id);
    });
    
    // Escape key closes modal
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });
    
    // Initial load
    loadProducts();
})();
</script>