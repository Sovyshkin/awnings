<?php
/**
 * Admin page for managing categories
 *
 * @package wp-awnings
 */

if (!current_user_can('manage_options')) {
    wp_die('Доступ запрещен');
}
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
.wpa-count-badge { display: inline-block; padding: 4px 10px; background: #f0f0f1; border-radius: 12px; font-size: 12px; color: #646970; }
.wpa-actions { display: flex; gap: 8px; }
.wpa-btn-sm { padding: 6px 12px; border: none; border-radius: 4px; font-size: 12px; cursor: pointer; transition: opacity 0.2s; }
.wpa-btn-sm:hover { opacity: 0.8; }
.wpa-btn-edit { background: #2271b1; color: #fff; }
.wpa-btn-delete { background: #dc3232; color: #fff; }
.wpa-modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 100000; justify-content: center; align-items: center; }
.wpa-modal-overlay.active { display: flex; }
.wpa-modal { background: #fff; border-radius: 12px; width: 95%; max-width: 450px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
.wpa-modal-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid #eee; }
.wpa-modal-header h2 { margin: 0; font-size: 20px; font-weight: 600; color: #1d2327; }
.wpa-modal-close { background: none; border: none; font-size: 28px; cursor: pointer; color: #646970; padding: 0; line-height: 1; }
.wpa-modal-close:hover { color: #1d2327; }
.wpa-modal-body { padding: 24px; }
.wpa-form-group { margin-bottom: 20px; }
.wpa-form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #1d2327; }
.wpa-form-group label span { color: #dc3232; }
.wpa-form-input { width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; transition: border-color 0.2s, box-shadow 0.2s; box-sizing: border-box; }
.wpa-form-input:focus { outline: none; border-color: #2271b1; box-shadow: 0 0 0 2px rgba(34,113,177,0.2); }
.wpa-form-input::placeholder { color: #a7aaad; }
.wpa-form-footer { display: flex; justify-content: flex-end; gap: 12px; padding: 20px 24px; border-top: 1px solid #eee; background: #f6f7f7; }
.wpa-btn-cancel { background: #f6f7f7; color: #1d2327; border: 1px solid #ddd; }
.wpa-btn-cancel:hover { background: #fff; }
.wpa-notice { padding: 16px 20px; border-radius: 6px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; }
.wpa-notice-success { background: #00a32a; color: #fff; }
.wpa-notice-error { background: #dc3232; color: #fff; }
</style>

<div class="wrap wpa-wrap">
    <div class="wpa-header">
        <h1 class="wpa-title">Управление категориями</h1>
        <button class="wpa-btn wpa-btn-primary" id="btn-add-category">
            <span>+</span> Добавить категорию
        </button>
    </div>

    <div id="wpa-notice"></div>

    <div class="wpa-table-wrap">
        <table class="wpa-table">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Название</th>
                    <th style="width: 100px;">Товаров</th>
                    <th style="width: 150px;">Действия</th>
                </tr>
            </thead>
            <tbody id="categories-list">
                <tr><td colspan="4" style="text-align: center; padding: 40px;">Загрузка...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Category Modal -->
<div id="category-modal" class="wpa-modal-overlay">
    <div class="wpa-modal">
        <div class="wpa-modal-header">
            <h2 id="modal-title">Новая категория</h2>
            <button class="wpa-modal-close" id="modal-close-btn">&times;</button>
        </div>
        <form id="category-form">
            <div class="wpa-modal-body">
                <input type="hidden" id="category-id" value="">
                
                <div class="wpa-form-group">
                    <label for="category-name">Название категории <span>*</span></label>
                    <input type="text" id="category-name" class="wpa-form-input" placeholder="Например: Беседки" required>
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
    const API_URL = '<?php echo rest_url('wp-awnings/v1/product-categories'); ?>';
    let categories = [];
    
    // DOM Elements
    const categoriesList = document.getElementById('categories-list');
    const noticeEl = document.getElementById('wpa-notice');
    const modal = document.getElementById('category-modal');
    const modalTitle = document.getElementById('modal-title');
    const form = document.getElementById('category-form');
    const categoryIdInput = document.getElementById('category-id');
    const categoryNameInput = document.getElementById('category-name');
    
    // Show notice
    function showNotice(message, type = 'success') {
        noticeEl.innerHTML = `<div class="wpa-notice wpa-notice-${type}">${message}</div>`;
        setTimeout(() => noticeEl.innerHTML = '', 4000);
    }
    
    // Render categories list
    function renderCategories() {
        if (categories.length === 0) {
            categoriesList.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 40px;">Категории не найдены</td></tr>';
            return;
        }
        
        categoriesList.innerHTML = categories.map(c => `
            <tr data-id="${c.id}">
                <td>${c.id}</td>
                <td><strong>${escapeHtml(c.name)}</strong></td>
                <td><span class="wpa-count-badge">${c.count || 0}</span></td>
                <td class="wpa-actions">
                    <button class="wpa-btn-sm wpa-btn-edit" data-edit="${c.id}">Изменить</button>
                    <button class="wpa-btn-sm wpa-btn-delete" data-delete="${c.id}">Удалить</button>
                </td>
            </tr>
        `).join('');
    }
    
    // Escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Open modal
    function openModal(id = null, name = '') {
        categoryIdInput.value = id || '';
        categoryNameInput.value = name;
        modalTitle.textContent = id ? 'Редактировать категорию' : 'Новая категория';
        modal.classList.add('active');
        categoryNameInput.focus();
    }
    
    // Close modal
    function closeModal() {
        modal.classList.remove('active');
        form.reset();
        categoryIdInput.value = '';
    }
    
    // Load categories
    async function loadCategories() {
        try {
            const response = await fetch(API_URL, {
                headers: {
                    'X-WP-Nonce': '<?php echo wp_create_nonce('wp_rest'); ?>'
                },
                credentials: 'include'
            });
            categories = await response.json();
            renderCategories();
        } catch (error) {
            showNotice('Ошибка загрузки категорий', 'error');
        }
    }
    
    // Delete category
    async function deleteCategory(id) {
        if (!confirm('Удалить эту категорию?')) return;
        
        try {
            const response = await fetch(`${API_URL}/${id}`, { 
                method: 'DELETE',
                headers: {
                    'X-WP-Nonce': '<?php echo wp_create_nonce('wp_rest'); ?>'
                },
                credentials: 'include'
            });
            
            if (response.ok) {
                categories = categories.filter(c => c.id !== id);
                renderCategories();
                showNotice('Категория удалена');
            } else {
                showNotice('Ошибка удаления', 'error');
            }
        } catch (error) {
            showNotice('Ошибка удаления', 'error');
        }
    }
    
    // Save category
    async function saveCategory(data, id) {
        const url = id ? `${API_URL}/${id}` : API_URL;
        const method = id ? 'PUT' : 'POST';
        
        try {
            const response = await fetch(url, {
                method: method,
                headers: { 
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': '<?php echo wp_create_nonce('wp_rest'); ?>'
                },
                credentials: 'include',
                body: JSON.stringify(data)
            });
            
            if (response.ok) {
                const result = await response.json();
                
                if (id) {
                    categories = categories.map(c => c.id === id ? result : c);
                    showNotice('Категория обновлена');
                } else {
                    categories.push(result);
                    showNotice('Категория создана');
                }
                
                renderCategories();
                closeModal();
            } else {
                const error = await response.json();
                showNotice(error.message || 'Ошибка сохранения', 'error');
            }
        } catch (error) {
            showNotice('Ошибка сохранения', 'error');
        }
    }
    
    // Event listeners
    document.getElementById('btn-add-category').addEventListener('click', () => openModal());
    document.getElementById('modal-close-btn').addEventListener('click', closeModal);
    document.getElementById('btn-cancel').addEventListener('click', closeModal);
    
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });
    
    categoriesList.addEventListener('click', (e) => {
        const editBtn = e.target.closest('[data-edit]');
        const deleteBtn = e.target.closest('[data-delete]');
        
        if (editBtn) {
            const id = parseInt(editBtn.dataset.edit);
            const cat = categories.find(c => c.id === id);
            if (cat) openModal(cat.id, cat.name);
        }
        
        if (deleteBtn) {
            const id = parseInt(deleteBtn.dataset.delete);
            deleteCategory(id);
        }
    });
    
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const id = categoryIdInput.value ? parseInt(categoryIdInput.value) : null;
        const data = { name: categoryNameInput.value.trim() };
        
        if (!data.name) {
            showNotice('Введите название категории', 'error');
            return;
        }
        
        saveCategory(data, id);
    });
    
    // Escape key closes modal
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });
    
    // Initial load
    loadCategories();
})();
</script>