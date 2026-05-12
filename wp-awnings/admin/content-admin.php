<?php
/**
 * Admin page for managing site content
 *
 * @package wp-awnings
 */

if (!current_user_can('manage_options')) {
    wp_die('Доступ запрещен');
}

// Подключаем медиабиблиотеку WordPress
wp_enqueue_media();

$nonce = wp_create_nonce('wp_rest');

// Get all content blocks grouped by page
$pages = array(
    'home' => 'Главная страница',
    'about' => 'О компании',
    'catalog' => 'Каталог',
    'contacts' => 'Контакты',
    'delivery' => 'Доставка и оплата',
    'garant' => 'Гарантия',
    'footer' => 'Футер',
    'header' => 'Шапка',
    'news' => 'Новости',
    'faq' => 'Частые вопросы'
);

$content_blocks = array();
foreach ($pages as $page_key => $page_name) {
    $args = array(
        'post_type' => 'content_block',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_key' => 'block_page',
        'meta_value' => $page_key
    );
    $query = new WP_Query($args);
    $content_blocks[$page_key] = $query->posts;
    wp_reset_postdata();
}
?>

<style>
.wpa-content-container {
    padding: 24px;
    max-width: 1400px;
}

.wpa-content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 2px solid #eee;
}

.wpa-content-title {
    font-size: 28px;
    font-weight: 600;
    color: #1d2327;
    margin: 0;
}

.wpa-content-tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 24px;
    border-bottom: 2px solid #eee;
    padding-bottom: 16px;
}

.wpa-tab-btn {
    padding: 12px 24px;
    border: none;
    background: #f6f7f7;
    border-radius: 8px 8px 0 0;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    color: #646970;
    transition: all 0.2s;
}

.wpa-tab-btn:hover {
    background: #e2e4e7;
}

.wpa-tab-btn.active {
    background: #2271b1;
    color: #fff;
}

.wpa-page-section {
    display: none;
}

.wpa-page-section.active {
    display: block;
}

.wpa-blocks-grid {
    display: grid;
    gap: 20px;
}

.wpa-block-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    overflow: hidden;
}

.wpa-block-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
    border-bottom: 1px solid #eee;
    cursor: pointer;
}

.wpa-block-info h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #1d2327;
}

.wpa-block-info p {
    margin: 4px 0 0 0;
    font-size: 13px;
    color: #646970;
}

.wpa-block-actions {
    display: flex;
    gap: 8px;
}

.wpa-block-btn {
    padding: 8px 16px;
    border-radius: 6px;
    border: none;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.wpa-block-btn.edit {
    background: #2271b1;
    color: #fff;
}

.wpa-block-btn.edit:hover {
    background: #135e96;
}

.wpa-block-body {
    padding: 24px;
}

.wpa-block-preview {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.wpa-preview-row {
    display: flex;
    gap: 16px;
    align-items: center;
}

.wpa-preview-label {
    min-width: 100px;
    font-size: 13px;
    color: #646970;
    font-weight: 500;
}

.wpa-preview-value {
    font-size: 14px;
    color: #1d2327;
}

.wpa-preview-image {
    width: 80px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #eee;
}

.wpa-preview-text {
    font-size: 14px;
    color: #1d2327;
    line-height: 1.5;
    max-width: 500px;
}

.wpa-edit-form {
    display: none;
}

.wpa-edit-form.active {
    display: block;
}

.wpa-form-group {
    margin-bottom: 20px;
}

.wpa-form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    font-size: 14px;
    color: #1d2327;
}

.wpa-form-group input,
.wpa-form-group textarea,
.wpa-form-group select {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    box-sizing: border-box;
}

.wpa-form-group input:focus,
.wpa-form-group textarea:focus,
.wpa-form-group select:focus {
    outline: none;
    border-color: #2271b1;
    box-shadow: 0 0 0 2px rgba(34,113,177,0.2);
}

.wpa-form-group textarea {
    min-height: 100px;
    resize: vertical;
}

.wpa-form-actions {
    display: flex;
    gap: 12px;
    padding-top: 16px;
    border-top: 1px solid #eee;
}

.wpa-form-btn {
    padding: 12px 24px;
    border-radius: 8px;
    border: none;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.wpa-form-btn.save {
    background: #2271b1;
    color: #fff;
}

.wpa-form-btn.save:hover {
    background: #135e96;
}

.wpa-form-btn.cancel {
    background: #f6f7f7;
    color: #1d2327;
    border: 1px solid #ddd;
}

.wpa-form-btn.cancel:hover {
    background: #e2e4e7;
}

.wpa-notice {
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 500;
}

.wpa-notice.success {
    background: #D4EDDA;
    color: #155724;
}

.wpa-notice.error {
    background: #F8D7DA;
    color: #721c24;
}

.wpa-add-block-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 28px;
    background: #2271b1;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 16px;
}

.wpa-add-block-btn:hover {
    background: #135e96;
}

.wpa-image-upload {
    display: flex;
    align-items: center;
    gap: 16px;
}

.wpa-image-preview {
    width: 120px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #eee;
}

.wpa-image-placeholder {
    width: 120px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f6f7f7;
    border-radius: 8px;
    color: #646970;
    font-size: 12px;
}
</style>

    <div class="wrap wpa-content-container">
    <div class="wpa-content-header">
        <h1 class="wpa-content-title">Управление контентом сайта</h1>
        <button id="wpa-reset-btn" class="button button-primary">Пересоздать блоки контента</button>
    </div>

    <div id="wpa-notice"></div>

    <div class="wpa-content-tabs">
        <?php foreach ($pages as $page_key => $page_name): ?>
        <button class="wpa-tab-btn <?php echo $page_key === 'home' ? 'active' : ''; ?>" data-page="<?php echo $page_key; ?>">
            <?php echo $page_name; ?>
        </button>
        <?php endforeach; ?>
    </div>

    <?php foreach ($pages as $page_key => $page_name): ?>
    <div class="wpa-page-section <?php echo $page_key === 'home' ? 'active' : ''; ?>" data-page="<?php echo $page_key; ?>">
        <div class="wpa-blocks-grid" id="blocks-<?php echo $page_key; ?>">
            <div class="wpa-empty-state" style="padding: 40px; text-align: center; color: #646970;">
                Загрузка блоков...
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<script>
(function() {
    const API_URL = '<?php echo rest_url('wp-awnings/v1'); ?>';
    const NONCE = '<?php echo $nonce; ?>';
    let currentPage = 'home';
    
    function showNotice(message, type = 'success') {
        const notice = document.getElementById('wpa-notice');
        notice.innerHTML = '<div class="wpa-notice wpa-notice-' + type + '">' + message + '</div>';
        setTimeout(() => notice.innerHTML = '', 4000);
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    async function loadBlocks(page) {
        const container = document.getElementById('blocks-' + page);
        try {
            const response = await fetch(API_URL + '/content-blocks/page/' + page, {
                headers: { 'X-WP-Nonce': NONCE }
            });
            
            if (!response.ok) throw new Error('Failed to load');
            
            const blocks = await response.json();
            
            if (blocks.length === 0) {
                container.innerHTML = '<div style="padding: 40px; text-align: center; color: #646970;">Блоки не найдены для этой страницы.</div>';
                return;
            }
            
            container.innerHTML = blocks.map(block => createBlockCard(block)).join('');
            attachBlockEvents();
        } catch (error) {
            container.innerHTML = '<div style="padding: 40px; text-align: center; color: #dc3232;">Ошибка загрузки блоков</div>';
        }
    }
    
    function createBlockCard(block) {
        const blockTypeLabels = {
            'hero': 'Главный баннер',
            'features': 'Преимущества',
            'section': 'Секция',
            'text': 'Текстовый блок',
            'image': 'Изображение',
            'contact': 'Контактная информация',
            'footer': 'Футер',
            'header': 'Шапка',
            'faq': 'FAQ',
            'gallery': 'Галерея'
        };
        
        let previewHTML = '';
        
        // Parse block_data if it's a string
        let blockData = block.block_data;
        if (typeof blockData === 'string') {
            try {
                blockData = JSON.parse(blockData);
            } catch (e) {
                blockData = null;
            }
        }
        
        // Show block_title and block_text
        if (block.block_title) {
            previewHTML += '<div class="wpa-preview-row"><span class="wpa-preview-label">Заголовок:</span><span class="wpa-preview-value">' + escapeHtml(block.block_title) + '</span></div>';
        }
        if (block.block_text) {
            previewHTML += '<div class="wpa-preview-row"><span class="wpa-preview-label">Описание:</span><span class="wpa-preview-text">' + escapeHtml(block.block_text.substring(0, 150)) + (block.block_text.length > 150 ? '...' : '') + '</span></div>';
        }
        if (block.block_image) {
            previewHTML += '<div class="wpa-preview-row"><span class="wpa-preview-label">Изображение:</span><img class="wpa-preview-image" src="' + escapeHtml(block.block_image) + '" alt=""></div>';
        }
        
        // Display all items from block_data array
        if (blockData && Array.isArray(blockData)) {
            previewHTML += '<div class="wpa-preview-section" style="margin-top: 12px; border-top: 1px solid #eee; padding-top: 12px;"><span class="wpa-preview-label">Элементы блока:</span>';
            blockData.forEach(function(item, index) {
                if (item.title) {
                    previewHTML += '<div class="wpa-preview-row"><span class="wpa-preview-label">[' + (index + 1) + '] Заголовок:</span><span class="wpa-preview-value">' + escapeHtml(item.title) + '</span></div>';
                }
                if (item.text) {
                    previewHTML += '<div class="wpa-preview-row"><span class="wpa-preview-label">[' + (index + 1) + '] Текст:</span><span class="wpa-preview-text">' + escapeHtml(item.text.substring(0, 80)) + (item.text.length > 80 ? '...' : '') + '</span></div>';
                }
                if (item.desc) {
                    previewHTML += '<div class="wpa-preview-row"><span class="wpa-preview-label">[' + (index + 1) + '] Описание:</span><span class="wpa-preview-text">' + escapeHtml(item.desc.substring(0, 80)) + (item.desc.length > 80 ? '...' : '') + '</span></div>';
                }
                if (item.question) {
                    previewHTML += '<div class="wpa-preview-row"><span class="wpa-preview-label">[' + (index + 1) + '] Вопрос:</span><span class="wpa-preview-text">' + escapeHtml(item.question) + '</span></div>';
                }
                if (item.answer) {
                    previewHTML += '<div class="wpa-preview-row"><span class="wpa-preview-label">[' + (index + 1) + '] Ответ:</span><span class="wpa-preview-text">' + escapeHtml(item.answer.substring(0, 80)) + (item.answer.length > 80 ? '...' : '') + '</span></div>';
                }
                if (item.subtitle) {
                    previewHTML += '<div class="wpa-preview-row"><span class="wpa-preview-label">[' + (index + 1) + '] Подзаголовок:</span><span class="wpa-preview-value">' + escapeHtml(item.subtitle) + '</span></div>';
                }
                if (item.link) {
                    previewHTML += '<div class="wpa-preview-row"><span class="wpa-preview-label">[' + (index + 1) + '] Ссылка:</span><span class="wpa-preview-value">' + escapeHtml(item.link) + '</span></div>';
                }
                if (item.icon) {
                    previewHTML += '<div class="wpa-preview-row"><span class="wpa-preview-label">[' + (index + 1) + '] Иконка:</span><span class="wpa-preview-value">' + escapeHtml(item.icon) + '</span></div>';
                }
                if (item.image) {
                    previewHTML += '<div class="wpa-preview-row"><span class="wpa-preview-label">[' + (index + 1) + '] Картинка:</span><span class="wpa-preview-value">' + escapeHtml(item.image) + '</span></div>';
                }
            });
            previewHTML += '</div>';
        }
        
        // Display object data (like contact info, footer)
        if (blockData && typeof blockData === 'object' && !Array.isArray(blockData)) {
            previewHTML += '<div class="wpa-preview-section" style="margin-top: 12px; border-top: 1px solid #eee; padding-top: 12px;"><span class="wpa-preview-label">Данные:</span>';
            for (let key in blockData) {
                if (blockData.hasOwnProperty(key)) {
                    let value = blockData[key];
                    if (typeof value === 'string') {
                        previewHTML += '<div class="wpa-preview-row"><span class="wpa-preview-label">' + escapeHtml(key) + ':</span><span class="wpa-preview-value">' + escapeHtml(value) + '</span></div>';
                    }
                }
            }
            previewHTML += '</div>';
        }
        
        // Check if this is a features block with images that needs visual editor
        let dataEditorHTML = '';
        if (block.block_type === 'features' && blockData && Array.isArray(blockData) && blockData.length > 0 && blockData[0].image !== undefined) {
            // Visual editor for features with images (like "Что мы делаем")
            dataEditorHTML = renderFeaturesWithImagesEditor(block, blockData);
        } else if (block.block_type === 'header' && blockData && typeof blockData === 'object' && !Array.isArray(blockData)) {
            // Visual editor for header blocks with address, phone, image
            dataEditorHTML = renderHeaderBlockEditor(block, blockData);
        } else {
            // Standard JSON editor
            dataEditorHTML = '<div class="wpa-form-group"><label>JSON данные блока (редактируйте внимательно!)</label><textarea id="data-' + block.id + '" style="min-height: 200px; font-family: monospace; font-size: 12px;">' + escapeHtml(JSON.stringify(blockData || {}, null, 2)) + '</textarea></div>';
        }
        
        let blockImageSection = '';
        if (block.block_type !== 'gallery' && block.block_type !== 'faq' && block.block_type !== 'footer' && block.block_type !== 'header') {
            blockImageSection = '<div class="wpa-form-group"><label>Изображение</label>' +
                '<div class="wpa-image-upload-wrapper" style="display: flex; flex-direction: column; gap: 12px;">' +
                '<div style="display: flex; align-items: center; gap: 16px;">' +
                (block.block_image ? '<img class="wpa-image-preview" src="' + escapeHtml(block.block_image) + '" alt="">' : '<div class="wpa-image-placeholder">Нет изображения</div>') +
                '<button type="button" class="wpa-upload-image-btn button" data-target="image-' + block.id + '" style="padding: 8px 16px;">Выбрать изображение</button>' +
                '<button type="button" class="wpa-remove-image-btn button" data-target="image-' + block.id + '" style="padding: 8px 16px; color: #dc3232;">Удалить</button>' +
                '</div>' +
                '<input type="text" id="image-' + block.id + '" value="' + escapeHtml(block.block_image || '') + '" placeholder="Или введите URL вручную" style="flex: 1;">' +
                '</div></div>';
        }
        
        return '<div class="wpa-block-card" data-id="' + block.id + '">' +
            '<div class="wpa-block-header">' +
            '<div class="wpa-block-info"><h3>' + escapeHtml(block.block_name || 'Блок без названия') + '</h3><p>' + (blockTypeLabels[block.block_type] || block.block_type) + '</p></div>' +
            '<div class="wpa-block-actions"><button class="wpa-block-btn edit" data-edit="' + block.id + '">Изменить</button></div>' +
            '</div>' +
            '<div class="wpa-block-body">' +
            '<div class="wpa-block-preview">' + previewHTML + '</div>' +
            '</div>' +
            '<div class="wpa-edit-form" id="edit-form-' + block.id + '">' +
            '<div class="wpa-form-group"><label>Название блока</label><input type="text" id="name-' + block.id + '" value="' + escapeHtml(block.block_name || '') + '"></div>' +
            '<div class="wpa-form-group"><label>Заголовок (title)</label><input type="text" id="title-' + block.id + '" value="' + escapeHtml(block.block_title || '') + '"></div>' +
            '<div class="wpa-form-group"><label>Текст</label><textarea id="text-' + block.id + '">' + escapeHtml(block.block_text || '') + '</textarea></div>' +
            blockImageSection +
            dataEditorHTML +
            '<div class="wpa-form-actions"><button class="wpa-form-btn save" data-save="' + block.id + '">Сохранить</button><button class="wpa-form-btn cancel" data-cancel="' + block.id + '">Отмена</button></div>' +
            '</div>' +
            '</div>';
    }
    
    function attachBlockEvents() {
        document.querySelectorAll('.wpa-block-btn.edit').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.edit;
                const form = document.getElementById('edit-form-' + id);
                document.querySelectorAll('.wpa-edit-form').forEach(f => f.classList.remove('active'));
                form.classList.add('active');
            });
        });
        
        document.querySelectorAll('.wpa-form-btn.cancel').forEach(btn => {
            btn.addEventListener('click', () => {
                btn.closest('.wpa-edit-form').classList.remove('active');
            });
        });
        
        document.querySelectorAll('.wpa-form-btn.save').forEach(btn => {
            btn.addEventListener('click', () => saveBlock(btn.dataset.save));
        });
    }
    
    async function saveBlock(id) {
        const data = {
            block_name: document.getElementById('name-' + id).value,
            block_title: document.getElementById('title-' + id).value,
            block_text: document.getElementById('text-' + id).value,
            block_image: document.getElementById('image-' + id).value,
            block_data: document.getElementById('data-' + id).value
        };
        
        try {
            const response = await fetch(API_URL + '/content-blocks/' + id, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': NONCE
                },
                body: JSON.stringify(data)
            });
            
            if (response.ok) {
                showNotice('Блок сохранен');
                loadBlocks(currentPage);
            } else {
                showNotice('Ошибка сохранения', 'error');
            }
        } catch (error) {
            showNotice('Ошибка сохранения', 'error');
        }
    }
    
    // Tab switching
    document.querySelectorAll('.wpa-tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.wpa-tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.wpa-page-section').forEach(s => s.classList.remove('active'));
            btn.classList.add('active');
            currentPage = btn.dataset.page;
            document.querySelector('.wpa-page-section[data-page="' + currentPage + '"]').classList.add('active');
            loadBlocks(currentPage);
        });
    });
    
    // Reset content blocks
    document.getElementById('wpa-reset-btn').addEventListener('click', async () => {
        if (!confirm('Удалить все блоки и пересоздать стандартные?')) return;
        
        try {
            const response = await fetch(ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=wp_awnings_reset_content&nonce=' + NONCE
            });
            
            if (response.ok) {
                showNotice('Контент пересоздан!');
                loadBlocks(currentPage);
            } else {
                showNotice('Ошибка пересоздания', 'error');
            }
        } catch (error) {
            showNotice('Ошибка пересоздания', 'error');
        }
    });
    
    // Upload image button handler
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('wpa-upload-image-btn')) {
            const targetId = e.target.dataset.target;
            const input = document.getElementById(targetId);
            
            // Create WordPress media frame
            const frame = wp.media({
                title: 'Выберите изображение',
                button: { text: 'Использовать' },
                multiple: false
            });
            
            frame.on('select', function() {
                const attachment = frame.state().get('selection').first().toJSON();
                input.value = attachment.url;
                
                // Update preview
                const wrapper = input.closest('.wpa-image-upload-wrapper');
                const preview = wrapper.querySelector('.wpa-image-preview');
                const placeholder = wrapper.querySelector('.wpa-image-placeholder');
                
                if (preview) {
                    preview.src = attachment.url;
                } else if (placeholder) {
                    const img = document.createElement('img');
                    img.className = 'wpa-image-preview';
                    img.src = attachment.url;
                    img.alt = '';
                    placeholder.replaceWith(img);
                }
            });
            
            frame.open();
        }
        
        if (e.target.classList.contains('wpa-remove-image-btn')) {
            const targetId = e.target.dataset.target;
            const input = document.getElementById(targetId);
            input.value = '';
            
            // Update preview
            const wrapper = input.closest('.wpa-image-upload-wrapper');
            const preview = wrapper.querySelector('.wpa-image-preview');
            const placeholder = wrapper.querySelector('.wpa-image-placeholder');
            
            if (preview) {
                const newPlaceholder = document.createElement('div');
                newPlaceholder.className = 'wpa-image-placeholder';
                newPlaceholder.textContent = 'Нет изображения';
                preview.replaceWith(newPlaceholder);
            }
        }
        
        // Upload feature image button
        if (e.target.classList.contains('wpa-upload-feature-image-btn')) {
            const blockId = e.target.dataset.block;
            const index = parseInt(e.target.dataset.index);
            
            const frame = wp.media({
                title: 'Выберите изображение',
                button: { text: 'Использовать' },
                multiple: false
            });
            
            frame.on('select', function() {
                const attachment = frame.state().get('selection').first().toJSON();
                const imageInput = document.querySelector('#features-editor-' + blockId + ' .wpa-feature-image[data-index="' + index + '"]');
                
                if (imageInput) {
                    imageInput.value = attachment.url;
                    
                    // Update preview image
                    const featureItem = imageInput.closest('.wpa-feature-item');
                    let previewImg = featureItem.querySelector('.wpa-feature-preview-img');
                    
                    if (previewImg) {
                        previewImg.src = attachment.url;
                    } else {
                        const img = document.createElement('img');
                        img.className = 'wpa-feature-preview-img';
                        img.src = attachment.url;
                        img.alt = '';
                        img.style.cssText = 'width: 80px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd; margin-top: 8px;';
                        e.target.parentElement.appendChild(img);
                    }
                    
                    // Update the JSON data
                    let blockData = JSON.parse(document.getElementById('data-' + blockId).value);
                    if (blockData[index]) {
                        blockData[index].image = attachment.url;
                        document.getElementById('data-' + blockId).value = JSON.stringify(blockData, null, 2);
                    }
                }
            });
            
            frame.open();
        }
        
        // Upload header image button
        if (e.target.classList.contains('wpa-upload-header-image-btn')) {
            const blockId = e.target.dataset.block;
            
            const frame = wp.media({
                title: 'Выберите изображение',
                button: { text: 'Использовать' },
                multiple: false
            });
            
            frame.on('select', function() {
                const attachment = frame.state().get('selection').first().toJSON();
                const imageInput = document.getElementById('header-image-' + blockId);
                
                if (imageInput) {
                    imageInput.value = attachment.url;
                    
                    // Update preview
                    const editorDiv = document.getElementById('header-editor-' + blockId);
                    let previewImg = editorDiv.querySelector('.wpa-header-preview-img');
                    
                    if (previewImg) {
                        previewImg.src = attachment.url;
                    } else {
                        const img = document.createElement('img');
                        img.className = 'wpa-header-preview-img';
                        img.src = attachment.url;
                        img.alt = '';
                        img.style.cssText = 'width: 120px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;';
                        e.target.parentElement.insertBefore(img, e.target);
                    }
                    
                    // Update the JSON data
                    let blockData = JSON.parse(document.getElementById('data-' + blockId).value);
                    blockData.image = attachment.url;
                    document.getElementById('data-' + blockId).value = JSON.stringify(blockData, null, 2);
                }
            });
            
            frame.open();
        }
    });
    
    // Visual editor for features with images (like "Что мы делаем")
    function renderFeaturesWithImagesEditor(block, blockData) {
        let html = '<div class="wpa-features-editor" id="features-editor-' + block.id + '">';
        html += '<div class="wpa-form-group"><label>Элементы блока</label></div>';
        
        blockData.forEach(function(item, index) {
            html += '<div class="wpa-feature-item" style="background: #f6f7f7; padding: 16px; border-radius: 8px; margin-bottom: 16px; border: 1px solid #ddd;">';
            
            // Title
            html += '<div class="wpa-form-group" style="margin-bottom: 12px;">';
            html += '<label>Название [' + (index + 1) + ']</label>';
            html += '<input type="text" class="wpa-feature-title" data-index="' + index + '" value="' + escapeHtml(item.title || '') + '" placeholder="Название элемента">';
            html += '</div>';
            
            // Category/Subtitle
            html += '<div class="wpa-form-group" style="margin-bottom: 12px;">';
            html += '<label>Категория [' + (index + 1) + ']</label>';
            html += '<input type="text" class="wpa-feature-subtitle" data-index="' + index + '" value="' + escapeHtml(item.category || item.subtitle || '') + '" placeholder="Категория">';
            html += '</div>';
            
            // Image
            html += '<div class="wpa-form-group" style="margin-bottom: 12px;">';
            html += '<label>Изображение [' + (index + 1) + ']</label>';
            html += '<div style="display: flex; align-items: center; gap: 12px;">';
            if (item.image) {
                html += '<img src="' + escapeHtml(item.image) + '" alt="" style="width: 80px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd;">';
            }
            html += '<button type="button" class="wpa-upload-feature-image-btn button" data-block="' + block.id + '" data-index="' + index + '">Выбрать изображение</button>';
            html += '</div>';
            html += '<input type="hidden" class="wpa-feature-image" data-index="' + index + '" value="' + escapeHtml(item.image || '') + '">';
            html += '</div>';
            
            html += '</div>';
        });
        
        html += '</div>';
        
        // Hidden JSON field with updated data
        html += '<div class="wpa-form-group" style="margin-top: 16px;">';
        html += '<label>JSON данные (автообновляется)</label>';
        html += '<textarea id="data-' + block.id + '" style="min-height: 150px; font-family: monospace; font-size: 12px;">' + escapeHtml(JSON.stringify(blockData, null, 2)) + '</textarea>';
        html += '</div>';
        
        // Add event listeners for dynamic updates
        setTimeout(function() {
            document.querySelectorAll('#features-editor-' + block.id + ' .wpa-feature-title').forEach(function(input) {
                input.addEventListener('input', function() {
                    updateFeaturesData(block.id, blockData);
                });
            });
            document.querySelectorAll('#features-editor-' + block.id + ' .wpa-feature-subtitle').forEach(function(input) {
                input.addEventListener('input', function() {
                    updateFeaturesData(block.id, blockData);
                });
            });
        }, 100);
        
        return html;
    }
    
    // Visual editor for header blocks with address, phone, image
    function renderHeaderBlockEditor(block, blockData) {
        let html = '<div class="wpa-header-editor" id="header-editor-' + block.id + '">';
        
        // Address
        html += '<div class="wpa-form-group">';
        html += '<label>Адрес</label>';
        html += '<input type="text" id="header-address-' + block.id + '" value="' + escapeHtml(blockData.address || '') + '" placeholder="г. Екатеринбург, ул. Промышленная, д. 4, стр. 2">';
        html += '</div>';
        
        // Phone
        html += '<div class="wpa-form-group">';
        html += '<label>Телефон</label>';
        html += '<input type="text" id="header-phone-' + block.id + '" value="' + escapeHtml(blockData.phone || '') + '" placeholder="+7 (900) 123-45-67">';
        html += '</div>';
        
        // Image
        html += '<div class="wpa-form-group">';
        html += '<label>Изображение</label>';
        html += '<div style="display: flex; align-items: center; gap: 16px;">';
        if (blockData.image) {
            html += '<img src="' + escapeHtml(blockData.image) + '" alt="" style="width: 120px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">';
        }
        html += '<button type="button" class="wpa-upload-header-image-btn button" data-block="' + block.id + '">Выбрать изображение</button>';
        html += '</div>';
        html += '<input type="hidden" id="header-image-' + block.id + '" value="' + escapeHtml(blockData.image || '') + '">';
        html += '</div>';
        
        html += '</div>';
        
        // Hidden JSON field that updates automatically
        html += '<div class="wpa-form-group" style="margin-top: 16px;">';
        html += '<label>JSON данные (автообновляется)</label>';
        html += '<textarea id="data-' + block.id + '" style="min-height: 100px; font-family: monospace; font-size: 12px;">' + escapeHtml(JSON.stringify(blockData, null, 2)) + '</textarea>';
        html += '</div>';
        
        // Add event listeners for auto-update
        setTimeout(function() {
            const addressInput = document.getElementById('header-address-' + block.id);
            const phoneInput = document.getElementById('header-phone-' + block.id);
            const imageInput = document.getElementById('header-image-' + block.id);
            
            if (addressInput) {
                addressInput.addEventListener('input', function() {
                    blockData.address = addressInput.value;
                    document.getElementById('data-' + block.id).value = JSON.stringify(blockData, null, 2);
                });
            }
            
            if (phoneInput) {
                phoneInput.addEventListener('input', function() {
                    blockData.phone = phoneInput.value;
                    document.getElementById('data-' + block.id).value = JSON.stringify(blockData, null, 2);
                });
            }
        }, 100);
        
        return html;
    }
    
    function updateFeaturesData(blockId, blockData) {
        // Collect all values
        const titles = document.querySelectorAll('#features-editor-' + blockId + ' .wpa-feature-title');
        const subtitles = document.querySelectorAll('#features-editor-' + blockId + ' .wpa-feature-subtitle');
        const images = document.querySelectorAll('#features-editor-' + blockId + ' .wpa-feature-image');
        
        titles.forEach(function(input, index) {
            if (blockData[index]) {
                blockData[index].title = input.value;
            }
        });
        
        subtitles.forEach(function(input, index) {
            if (blockData[index]) {
                blockData[index].category = input.value;
            }
        });
        
        images.forEach(function(input, index) {
            if (blockData[index]) {
                blockData[index].image = input.value;
            }
        });
        
        // Update JSON textarea
        document.getElementById('data-' + blockId).value = JSON.stringify(blockData, null, 2);
    }
    
    // Initial load
    loadBlocks('home');
})();
</script>
</final_file_content>