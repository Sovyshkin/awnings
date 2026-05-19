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
?>

<style>
.wpa-content-container { padding: 24px; max-width: 1600px; }
.wpa-content-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 2px solid #eee; }
.wpa-content-title { font-size: 28px; font-weight: 600; color: #1d2327; margin: 0; }
.wpa-content-tabs { display: flex; gap: 8px; margin-bottom: 24px; border-bottom: 2px solid #eee; padding-bottom: 16px; flex-wrap: wrap; }
.wpa-tab-btn { padding: 10px 20px; border: none; background: #f6f7f7; border-radius: 8px 8px 0 0; cursor: pointer; font-size: 14px; font-weight: 500; color: #646970; transition: all 0.2s; }
.wpa-tab-btn:hover { background: #e2e4e7; }
.wpa-tab-btn.active { background: #2271b1; color: #fff; }
.wpa-page-section { display: none; }
.wpa-page-section.active { display: block; }
.wpa-blocks-grid { display: grid; gap: 20px; }
.wpa-block-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #e2e4e7; }
.wpa-block-header { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%); border-bottom: 1px solid #eee; cursor: pointer; }
.wpa-block-header:hover { background: #f0f0f0; }
.wpa-block-info h3 { margin: 0; font-size: 16px; font-weight: 600; color: #1d2327; }
.wpa-block-info p { margin: 4px 0 0 0; font-size: 12px; color: #646970; }
.wpa-block-actions { display: flex; gap: 8px; }
.wpa-block-btn { padding: 8px 16px; border-radius: 6px; border: none; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.2s; }
.wpa-block-btn.edit { background: #2271b1; color: #fff; }
.wpa-block-btn.edit:hover { background: #135e96; }
.wpa-block-body { padding: 20px; }
.wpa-block-preview { display: flex; flex-direction: column; gap: 8px; }
.wpa-preview-row { display: flex; gap: 16px; align-items: center; }
.wpa-preview-label { min-width: 100px; font-size: 12px; color: #646970; font-weight: 500; }
.wpa-preview-value { font-size: 14px; color: #1d2327; }
.wpa-preview-image { width: 80px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #eee; }
.wpa-preview-text { font-size: 13px; color: #1d2327; line-height: 1.4; max-width: 500px; }
.wpa-edit-form { display: none; padding: 20px; background: #f9f9f9; border-top: 1px solid #eee; }
.wpa-edit-form.active { display: block; }
.wpa-form-group { margin-bottom: 16px; }
.wpa-form-group label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 13px; color: #1d2327; }
.wpa-form-group input, .wpa-form-group textarea, .wpa-form-group select { width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; box-sizing: border-box; }
.wpa-form-group input:focus, .wpa-form-group textarea:focus, .wpa-form-group select:focus { outline: none; border-color: #2271b1; box-shadow: 0 0 0 2px rgba(34,113,177,0.2); }
.wpa-form-group textarea { min-height: 80px; resize: vertical; }
.wpa-form-actions { display: flex; gap: 12px; padding-top: 16px; border-top: 1px solid #ddd; margin-top: 16px; }
.wpa-form-btn { padding: 10px 20px; border-radius: 6px; border: none; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
.wpa-form-btn.save { background: #2271b1; color: #fff; }
.wpa-form-btn.save:hover { background: #135e96; }
.wpa-form-btn.cancel { background: #f6f7f7; color: #1d2327; border: 1px solid #ddd; }
.wpa-form-btn.cancel:hover { background: #e2e4e7; }
.wpa-notice { padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; font-weight: 500; }
.wpa-notice.success { background: #D4EDDA; color: #155724; }
.wpa-notice.error { background: #F8D7DA; color: #721c24; }
.wpa-image-upload { display: flex; align-items: center; gap: 16px; }
.wpa-image-preview { width: 100px; height: 70px; object-fit: cover; border-radius: 8px; border: 2px solid #eee; }
.wpa-image-placeholder { width: 100px; height: 70px; display: flex; align-items: center; justify-content: center; background: #f6f7f7; border-radius: 8px; color: #646970; font-size: 11px; border: 2px dashed #ddd; }
.wpa-visual-editor { background: #fff; border: 1px solid #e2e4e7; border-radius: 8px; padding: 16px; margin-bottom: 16px; }
.wpa-visual-editor-title { font-size: 14px; font-weight: 600; color: #1d2327; margin-bottom: 16px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
.wpa-feature-item { background: #f6f7f7; padding: 16px; border-radius: 8px; margin-bottom: 12px; border: 1px solid #e2e4e7; }
.wpa-feature-item-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
.wpa-feature-item-number { background: #2271b1; color: #fff; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; }
.wpa-icon-selector { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 8px; }
.wpa-icon-option { width: 40px; height: 40px; border: 2px solid #ddd; border-radius: 6px; cursor: pointer; padding: 4px; transition: all 0.2s; }
.wpa-icon-option:hover { border-color: #2271b1; }
.wpa-icon-option.selected { border-color: #2271b1; background: #e7f1fa; }
.wpa-icon-option img { width: 100%; height: 100%; object-fit: contain; }
.wpa-items-list { max-height: 400px; overflow-y: auto; padding-right: 8px; }
.wpa-reset-section { margin-top: 30px; padding-top: 20px; border-top: 2px solid #eee; }
.wpa-reset-btn { background: #dc3232; color: #fff; padding: 12px 24px; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; }
.wpa-reset-btn:hover { background: #b32829; }
.wpa-faq-item { background: #f6f7f7; padding: 16px; border-radius: 8px; margin-bottom: 12px; border: 1px solid #e2e4e7; }
.wpa-faq-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
.wpa-faq-number { background: #2271b1; color: #fff; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; }
.wpa-gallery-items { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 16px; }
.wpa-gallery-item { background: #f6f7f7; padding: 12px; border-radius: 8px; border: 1px solid #e2e4e7; }
.wpa-gallery-item img { width: 100%; height: 100px; object-fit: cover; border-radius: 6px; margin-bottom: 8px; }
.wpa-upload-btn { background: #2271b1; color: #fff; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; }
.wpa-upload-btn:hover { background: #135e96; }
.wpa-remove-btn { background: #dc3232; color: #fff; padding: 4px 8px; border: none; border-radius: 4px; cursor: pointer; font-size: 11px; margin-left: 8px; }
</style>

<div class="wpa-content-container">
    <div class="wpa-content-header">
        <h1 class="wpa-content-title">Управление контентом</h1>
        <button id="wpa-reset-btn" class="wpa-reset-btn">Пересоздать блоки контента</button>
    </div>
    
    <div id="wpa-notice"></div>
    
    <div class="wpa-content-tabs">
        <button class="wpa-tab-btn active" data-page="home">Главная</button>
        <button class="wpa-tab-btn" data-page="about">О компании</button>
        <button class="wpa-tab-btn" data-page="faq">FAQ</button>
        <button class="wpa-tab-btn" data-page="contacts">Контакты</button>
        <button class="wpa-tab-btn" data-page="delivery">Доставка</button>
        <button class="wpa-tab-btn" data-page="garant">Гарантия</button>
        <button class="wpa-tab-btn" data-page="footer">Футер</button>
    </div>
    
    <div id="wpa-blocks-container"></div>
</div>

<script>
const AJAX_URL = '<?php echo admin_url('admin-ajax.php'); ?>';
const NONCE = '<?php echo $nonce; ?>';
const THEME_ASSETS = '<?php echo esc_js(get_template_directory_uri()); ?>/assets';

// Icon mappings для Hero секции
const heroIcons = {
    'group-1': THEME_ASSETS + '/group-1.svg',
    'group-2': THEME_ASSETS + '/group-2.svg',
    'group-3': THEME_ASSETS + '/group-3.svg',
};

// Icon mappings для карточек
const cardIcons = {
    'card-icon-1': THEME_ASSETS + '/card-icon-1.svg',
    'card-icon-2': THEME_ASSETS + '/card-icon-2.svg',
    'card-icon-3': THEME_ASSETS + '/card-icon-3.svg',
};

// Icon mappings для Why Us
const whyUsIcons = {
    'why-us-1': THEME_ASSETS + '/why-us-1.svg',
    'why-us-2': THEME_ASSETS + '/why-us-2.svg',
    'why-us-3': THEME_ASSETS + '/why-us-3.svg',
    'why-us-4': THEME_ASSETS + '/why-us-4.svg',
};

// Icon mappings для Payment
const paymentIcons = {
    'payment-1': THEME_ASSETS + '/payment-1.svg',
    'payment-2': THEME_ASSETS + '/payment-2.svg',
    'payment-3': THEME_ASSETS + '/payment-3.svg',
    'payment-4': THEME_ASSETS + '/payment-4.svg',
};

let currentPage = 'home';

function showNotice(message, type = 'success') {
    const notice = document.getElementById('wpa-notice');
    notice.innerHTML = '<div class="wpa-notice ' + type + '">' + message + '</div>';
    setTimeout(() => { notice.innerHTML = ''; }, 3000);
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

async function loadBlocks(page) {
    const container = document.getElementById('wpa-blocks-container');
    container.innerHTML = '<p>Загрузка...</p>';
    
    try {
        const response = await fetch(AJAX_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                action: 'wp_awnings_get_content_blocks',
                nonce: NONCE,
                page: page
            }).toString()
        });

        if (!response.ok) {
            throw new Error('Ошибка загрузки (HTTP ' + response.status + ')');
        }

        const result = await response.json();
        if (!result.success) {
            throw new Error(result?.data?.message || 'Ошибка загрузки');
        }

        const blocks = result.data || [];
        
        if (blocks.length === 0) {
            container.innerHTML = '<p>Нет блоков для этой страницы</p>';
            return;
        }
        
        let html = '<div class="wpa-blocks-grid">';
        
        blocks.forEach(block => {
            const blockData = block.block_data ? (typeof block.block_data === 'string' ? JSON.parse(block.block_data) : block.block_data) : {};
            
            html += '<div class="wpa-block-card" data-id="' + block.id + '">';
            html += '<div class="wpa-block-header" onclick="toggleBlockForm(' + block.id + ')">';
            html += '<div class="wpa-block-info"><h3>' + escapeHtml(block.block_name || 'Блок') + '</h3><p>' + escapeHtml(block.block_type || '') + '</p></div>';
            html += '<div class="wpa-block-actions" onclick="event.stopPropagation()">';
            html += '<button class="wpa-block-btn edit" onclick="toggleBlockForm(' + block.id + ')">Изменить</button>';
            html += '</div></div>';
            
            // Preview
            html += '<div class="wpa-block-body"><div class="wpa-block-preview">';
            if (block.block_title) {
                html += '<div class="wpa-preview-row"><span class="wpa-preview-label">Заголовок:</span><span class="wpa-preview-value">' + escapeHtml(block.block_title) + '</span></div>';
            }
            if (block.block_text) {
                const textPreview = block.block_text.length > 80 ? block.block_text.substring(0, 80) + '...' : block.block_text;
                html += '<div class="wpa-preview-row"><span class="wpa-preview-label">Текст:</span><span class="wpa-preview-text">' + escapeHtml(textPreview) + '</span></div>';
            }
            if (blockData.features && Array.isArray(blockData.features)) {
                html += '<div class="wpa-preview-row"><span class="wpa-preview-label">Элементов:</span><span class="wpa-preview-value">' + blockData.features.length + '</span></div>';
            } else if (Array.isArray(blockData)) {
                html += '<div class="wpa-preview-row"><span class="wpa-preview-label">Элементов:</span><span class="wpa-preview-value">' + blockData.length + '</span></div>';
            }
            html += '</div></div>';
            
            // Edit form
            html += '<div class="wpa-edit-form" id="edit-form-' + block.id + '">';
            html += '<div class="wpa-form-group"><label>Название блока</label><input type="text" id="name-' + block.id + '" value="' + escapeHtml(block.block_name || '') + '"></div>';
            html += '<div class="wpa-form-group"><label>Заголовок</label><input type="text" id="title-' + block.id + '" value="' + escapeHtml(block.block_title || '') + '"></div>';
            html += '<div class="wpa-form-group"><label>Текст</label><textarea id="text-' + block.id + '">' + escapeHtml(block.block_text || '') + '</textarea></div>';
            
            // Visual editor based on type
            if (block.block_type === 'hero') {
                html += createHeroEditor(block, blockData);
            } else if (block.block_type === 'features') {
                html += createFeaturesEditor(block, blockData);
            } else if (block.block_type === 'faq') {
                html += createFaqEditor(block, blockData);
            } else if (block.block_type === 'gallery') {
                html += createGalleryEditor(block, blockData);
            } else if (block.block_type === 'section') {
                html += createSectionEditor(block, blockData);
            } else if (block.block_type === 'contact') {
                html += createContactEditor(block, blockData);
            } else if (block.block_type === 'footer') {
                if (block.block_name && block.block_name.includes('Основная')) {
                    html += createFooterMainEditor(block, blockData);
                } else {
                    html += createFooterLinksEditor(block, blockData);
                }
            } else if (block.block_type === 'header') {
                html += createHeaderEditor(block, blockData);
            } else if (block.block_type === 'icon-cards') {
                html += createIconCardsEditor(block, blockData);
            } else if (block.block_type === 'regions') {
                html += createRegionsEditor(block, blockData);
            } else {
                html += '<div class="wpa-visual-editor"><p>JSON данные:</p><textarea id="raw-data-' + block.id + '" style="width:100%;height:150px;">' + escapeHtml(JSON.stringify(blockData, null, 2)) + '</textarea></div>';
            }
            
            html += '<input type="hidden" id="data-' + block.id + '" value="">';
            html += '<div class="wpa-form-actions">';
            html += '<button class="wpa-form-btn save" onclick="saveBlock(' + block.id + ')">Сохранить</button>';
            html += '<button class="wpa-form-btn cancel" onclick="toggleBlockForm(' + block.id + ')">Отмена</button>';
            html += '</div></div></div>';
        });
        
        html += '</div>';
        container.innerHTML = html;
        
        // Initialize icon selectors and upload buttons
        initIconSelectors();
        initUploadButtons();
        
    } catch (error) {
        container.innerHTML = '<p style="color:red;">Ошибка: ' + error.message + '</p>';
    }
}

function createHeroEditor(block, blockData) {
    let html = '<div class="wpa-visual-editor"><div class="wpa-visual-editor-title">Баннер - элементы</div>';
    
    const features = blockData.features || [];
    html += '<div class="wpa-items-list" id="hero-items-' + block.id + '">';
    
    features.forEach((item, index) => {
        html += '<div class="wpa-feature-item" data-index="' + index + '">';
        html += '<div class="wpa-feature-item-header"><span class="wpa-feature-item-number">' + (index + 1) + '</span></div>';
        
        // Icon selector
        html += '<div class="wpa-form-group"><label>Иконка</label>';
        html += '<div class="wpa-icon-selector">';
        for (const [iconKey, iconUrl] of Object.entries(heroIcons)) {
            const selected = item.icon === iconKey ? 'selected' : '';
            html += '<div class="wpa-icon-option ' + selected + '" data-icon="' + iconKey + '" onclick="selectIcon(this, ' + index + ', \'hero\')">';
            html += '<img src="' + iconUrl + '" alt="' + iconKey + '">';
            html += '</div>';
        }
        html += '</div><input type="hidden" class="hero-icon-input" data-index="' + index + '" value="' + escapeHtml(item.icon || '') + '"></div>';
        const heroCustomId = 'hero-icon-custom-' + block.id + '-' + index;
        html += '<div class="wpa-form-group"><label>Или свой URL иконки</label><input type="text" id="' + heroCustomId + '" class="hero-icon-custom" data-index="' + index + '" value="' + (item.icon && !heroIcons[item.icon] ? escapeHtml(item.icon) : '') + '"><button type="button" class="wpa-upload-btn wpa-upload-media" data-target="#' + heroCustomId + '">Выбрать из медиатеки</button></div>';
        
        html += '<div class="wpa-form-group"><label>Заголовок</label><input type="text" class="hero-feature-title" data-index="' + index + '" value="' + escapeHtml(item.title || '') + '"></div>';
        html += '<div class="wpa-form-group"><label>Описание</label><input type="text" class="hero-feature-text" data-index="' + index + '" value="' + escapeHtml(item.text || '') + '"></div>';
        html += '</div>';
    });
    
    html += '</div></div>';
    html += '<div class="wpa-form-group"><label>Текст кнопки</label><input type="text" id="hero-btn-text-' + block.id + '" value="' + escapeHtml(blockData.button_text || '') + '"></div>';
    html += '<div class="wpa-form-group"><label>Ссылка кнопки</label><input type="text" id="hero-btn-link-' + block.id + '" value="' + escapeHtml(blockData.button_link || '') + '"></div>';
    
    return html;
}

function createFeaturesEditor(block, blockData) {
    let html = '<div class="wpa-visual-editor"><div class="wpa-visual-editor-title">Карточки</div>';
    html += '<div class="wpa-items-list" id="features-items-' + block.id + '">';
    
    const items = Array.isArray(blockData) ? blockData : [];
    const blockName = block.block_name || '';
    
    items.forEach((item, index) => {
        html += '<div class="wpa-feature-item" data-index="' + index + '">';
        html += '<div class="wpa-feature-item-header"><span class="wpa-feature-item-number">Карточка ' + (index + 1) + '</span></div>';
        
        // Cards with image
        if (item.image !== undefined) {
            const featureImageId = 'feature-image-' + block.id + '-' + index;
            html += '<div class="wpa-form-group"><label>URL изображения</label><input type="text" id="' + featureImageId + '" class="feature-image" data-index="' + index + '" value="' + escapeHtml(item.image || '') + '"><button type="button" class="wpa-upload-btn wpa-upload-media" data-target="#' + featureImageId + '">Выбрать изображение</button></div>';
        }

        if (item.category !== undefined) {
            html += '<div class="wpa-form-group"><label>Категория</label><input type="text" class="feature-category" data-index="' + index + '" value="' + escapeHtml(item.category || '') + '"></div>';
        }
        
        // Cards with subtitle/desc (Компания в цифрах)
        if (item.subtitle !== undefined) {
            html += '<div class="wpa-form-group"><label>Число/Заголовок</label><input type="text" class="feature-title-num" data-index="' + index + '" value="' + escapeHtml(item.title || '') + '"></div>';
            html += '<div class="wpa-form-group"><label>Подзаголовок</label><input type="text" class="feature-subtitle" data-index="' + index + '" value="' + escapeHtml(item.subtitle || '') + '"></div>';
            html += '<div class="wpa-form-group"><label>Описание</label><textarea class="feature-desc" data-index="' + index + '">' + escapeHtml(item.desc || '') + '</textarea></div>';
        } else if (item.image === undefined) {
            // Icon selector for all non-image cards
            let iconSet = cardIcons;
            let iconType = 'feature';
            let inputClass = 'feature-icon-input';

            if (blockName.includes('Способы оплаты') || blockName.includes('Оплата') || blockName.includes('Условия гарантии') || blockName.includes('Обслуживание')) {
                iconSet = paymentIcons;
                iconType = 'payment';
                inputClass = 'payment-icon-input';
            } else if (blockName.includes('Почему выбирают') || blockName.includes('Почему вы')) {
                iconSet = whyUsIcons;
                iconType = 'whyus';
                inputClass = 'whyus-icon-input';
            }

            html += '<div class="wpa-form-group"><label>Иконка</label><div class="wpa-icon-selector">';
            for (const [iconKey, iconUrl] of Object.entries(iconSet)) {
                const selected = item.icon === iconKey ? 'selected' : '';
                html += '<div class="wpa-icon-option ' + selected + '" data-icon="' + iconKey + '" onclick="selectIcon(this, ' + index + ', \'' + iconType + '\')">';
                html += '<img src="' + iconUrl + '" alt="' + iconKey + '"></div>';
            }
            html += '</div><input type="hidden" class="' + inputClass + '" data-index="' + index + '" value="' + escapeHtml(item.icon || '') + '"></div>';
            const featureCustomId = 'feature-icon-custom-' + block.id + '-' + index;
            html += '<div class="wpa-form-group"><label>Или свой URL иконки</label><input type="text" id="' + featureCustomId + '" class="feature-icon-custom" data-index="' + index + '" value="' + (item.icon && !iconSet[item.icon] ? escapeHtml(item.icon) : '') + '"><button type="button" class="wpa-upload-btn wpa-upload-media" data-target="#' + featureCustomId + '">Выбрать из медиатеки</button></div>';

            if (item.title !== undefined) {
                html += '<div class="wpa-form-group"><label>Заголовок</label><input type="text" class="feature-title" data-index="' + index + '" value="' + escapeHtml(item.title || '') + '"></div>';
            }
            if (item.text !== undefined) {
                html += '<div class="wpa-form-group"><label>Описание</label><textarea class="feature-text" data-index="' + index + '">' + escapeHtml(item.text || '') + '</textarea></div>';
            }
        }

        // For image cards (e.g. "Что мы делаем"), render title/text without icon selector
        if (item.subtitle === undefined && item.image !== undefined) {
            if (item.title !== undefined) {
                html += '<div class="wpa-form-group"><label>Заголовок</label><input type="text" class="feature-title" data-index="' + index + '" value="' + escapeHtml(item.title || '') + '"></div>';
            }
            if (item.text !== undefined) {
                html += '<div class="wpa-form-group"><label>Описание</label><textarea class="feature-text" data-index="' + index + '">' + escapeHtml(item.text || '') + '</textarea></div>';
            }
        }
        
        html += '</div>';
    });
    
    html += '</div></div>';
    return html;
}

function createGalleryEditor(block, blockData) {
    let html = '<div class="wpa-visual-editor"><div class="wpa-visual-editor-title">Галерея проектов</div>';
    html += '<div class="wpa-items-list" id="gallery-items-' + block.id + '">';
    
    const items = Array.isArray(blockData) ? blockData : [];
    
    items.forEach((item, index) => {
        html += '<div class="wpa-feature-item" data-index="' + index + '">';
        html += '<div class="wpa-feature-item-header"><span class="wpa-feature-item-number">Проект ' + (index + 1) + '</span></div>';
        
        const galleryImageId = 'gallery-image-' + block.id + '-' + index;
        html += '<div class="wpa-form-group"><label>URL изображения</label><input type="text" id="' + galleryImageId + '" class="gallery-image" data-index="' + index + '" value="' + escapeHtml(item.image || '') + '"><button type="button" class="wpa-upload-btn wpa-upload-media" data-target="#' + galleryImageId + '">Выбрать изображение</button></div>';
        html += '<div class="wpa-form-group"><label>Название</label><input type="text" class="gallery-title" data-index="' + index + '" value="' + escapeHtml(item.title || '') + '"></div>';
        html += '<div class="wpa-form-group"><label>Цена</label><input type="text" class="gallery-price" data-index="' + index + '" value="' + escapeHtml(item.price || '') + '"></div>';
        html += '</div>';
    });
    
    html += '</div></div>';
    return html;
}

function createFaqEditor(block, blockData) {
    let html = '<div class="wpa-visual-editor"><div class="wpa-visual-editor-title">Вопросы и ответы</div>';
    html += '<div class="wpa-items-list" id="faq-items-' + block.id + '">';
    
    const items = Array.isArray(blockData) ? blockData : [];
    
    items.forEach((item, index) => {
        html += '<div class="wpa-faq-item" data-index="' + index + '">';
        html += '<div class="wpa-faq-header"><span class="wpa-faq-number">Вопрос ' + (index + 1) + '</span></div>';
        html += '<div class="wpa-form-group"><label>Вопрос</label><input type="text" class="faq-question" data-index="' + index + '" value="' + escapeHtml(item.question || '') + '"></div>';
        html += '<div class="wpa-form-group"><label>Ответ</label><textarea class="faq-answer" data-index="' + index + '">' + escapeHtml(item.answer || '') + '</textarea></div>';
        html += '</div>';
    });
    
    html += '</div></div>';
    return html;
}

function createSectionEditor(block, blockData) {
    let html = '<div class="wpa-visual-editor"><div class="wpa-visual-editor-title">Секция</div>';
    html += '<div class="wpa-form-group"><label>Изображение (URL)</label><input type="text" id="section-image-' + block.id + '" value="' + escapeHtml(block.block_image || '') + '"></div>';
    html += '<button type="button" class="wpa-upload-btn wpa-upload-media" data-target="#section-image-' + block.id + '">Выбрать изображение</button>';
    html += '<p style="margin:8px 0;color:#646970;font-size:12px;">Введите полный URL изображения.</p>';
    html += '</div>';
    return html;
}

function createContactEditor(block, blockData) {
    let data = typeof blockData === 'string' ? JSON.parse(blockData) : blockData;
    data = data || {};
    
    let html = '<div class="wpa-visual-editor"><div class="wpa-visual-editor-title">Контактная информация</div>';
    html += '<div class="wpa-form-group"><label>Телефон</label><input type="text" id="contact-phone-' + block.id + '" value="' + escapeHtml(data.phone || '') + '"></div>';
    html += '<div class="wpa-form-group"><label>Email</label><input type="text" id="contact-email-' + block.id + '" value="' + escapeHtml(data.email || '') + '"></div>';
    html += '<div class="wpa-form-group"><label>Адрес</label><textarea id="contact-address-' + block.id + '">' + escapeHtml(data.address || '') + '</textarea></div>';
    html += '<div class="wpa-form-group"><label>Режим работы</label><input type="text" id="contact-schedule-' + block.id + '" value="' + escapeHtml(data.schedule || '') + '"></div>';
    html += '</div>';
    return html;
}

function createFooterMainEditor(block, blockData) {
    let data = typeof blockData === 'string' ? JSON.parse(blockData) : blockData;
    data = data || {};
    
    let html = '<div class="wpa-visual-editor"><div class="wpa-visual-editor-title">Футер - Основная информация</div>';
    html += '<div class="wpa-form-group"><label>Копирайт</label><input type="text" id="footer-copyright-' + block.id + '" value="' + escapeHtml(data.copyright || '') + '"></div>';
    html += '<div class="wpa-form-group"><label>Политика конфиденциальности</label><input type="text" id="footer-privacy-' + block.id + '" value="' + escapeHtml(data.privacy || '') + '"></div>';
    html += '<div class="wpa-form-group"><label>Пользовательское соглашение</label><input type="text" id="footer-agreement-' + block.id + '" value="' + escapeHtml(data.agreement || '') + '"></div>';
    html += '</div>';
    return html;
}

function createFooterLinksEditor(block, blockData) {
    if (block.block_name && block.block_name.includes('Контакты')) {
        let data = typeof blockData === 'string' ? JSON.parse(blockData) : blockData;
        data = data || {};
        let html = '<div class="wpa-visual-editor"><div class="wpa-visual-editor-title">Контакты футера</div>';
        html += '<div class="wpa-form-group"><label>Телефон</label><input type="text" id="footer-phone-' + block.id + '" value="' + escapeHtml(data.phone || '') + '"></div>';
        html += '<div class="wpa-form-group"><label>Email</label><input type="text" id="footer-email-' + block.id + '" value="' + escapeHtml(data.email || '') + '"></div>';
        html += '<div class="wpa-form-group"><label>Адрес</label><textarea id="footer-address-' + block.id + '">' + escapeHtml(data.address || '') + '</textarea></div>';
        html += '</div>';
        return html;
    }

    let html = '<div class="wpa-visual-editor"><div class="wpa-visual-editor-title"> Ссылки футера</div>';
    html += '<div class="wpa-items-list" id="footer-links-' + block.id + '">';
    
    const items = Array.isArray(blockData) ? blockData : [];
    
    items.forEach((item, index) => {
        html += '<div class="wpa-faq-item" data-index="' + index + '">';
        html += '<div class="wpa-faq-header"><span class="wpa-faq-number">Ссылка ' + (index + 1) + '</span></div>';
        html += '<div class="wpa-form-group"><label>Текст ссылки</label><input type="text" class="footer-link-text" data-index="' + index + '" value="' + escapeHtml(item.text || '') + '"></div>';
        html += '<div class="wpa-form-group"><label>URL ссылки</label><input type="text" class="footer-link-url" data-index="' + index + '" value="' + escapeHtml(item.link || '') + '"></div>';
        html += '</div>';
    });
    
    html += '</div></div>';
    return html;
}

function createHeaderEditor(block, blockData) {
    let html = '<div class="wpa-visual-editor"><div class="wpa-visual-editor-title">Шапка сайта</div>';
    let data = typeof blockData === 'string' ? JSON.parse(blockData) : blockData;
    data = data || {};

    if (Array.isArray(data)) {
        html += '<div class="wpa-items-list" id="header-links-' + block.id + '">';
        data.forEach((item, index) => {
            html += '<div class="wpa-faq-item" data-index="' + index + '">';
            html += '<div class="wpa-faq-header"><span class="wpa-faq-number">Пункт ' + (index + 1) + '</span></div>';
            html += '<div class="wpa-form-group"><label>Текст</label><input type="text" class="header-link-text" data-index="' + index + '" value="' + escapeHtml(item.text || '') + '"></div>';
            html += '<div class="wpa-form-group"><label>Ссылка</label><input type="text" class="header-link-url" data-index="' + index + '" value="' + escapeHtml(item.link || '') + '"></div>';
            html += '</div>';
        });
        html += '</div>';
    } else {
        html += '<div class="wpa-form-group"><label>Адрес</label><input type="text" id="header-address-' + block.id + '" value="' + escapeHtml(data.address || '') + '"></div>';
        html += '<div class="wpa-form-group"><label>Телефон</label><input type="text" id="header-phone-' + block.id + '" value="' + escapeHtml(data.phone || '') + '"></div>';
        html += '<div class="wpa-form-group"><label>Изображение (URL)</label><input type="text" id="header-image-' + block.id + '" value="' + escapeHtml(data.image || '') + '"></div>';
        html += '<button type="button" class="wpa-upload-btn wpa-upload-media" data-target="#header-image-' + block.id + '">Выбрать изображение</button>';
    }

    html += '</div>';
    return html;
}

// Новый редактор для icon-cards (карточки с иконками)
function createIconCardsEditor(block, blockData) {
    let html = '<div class="wpa-visual-editor"><div class="wpa-visual-editor-title">Карточки с иконками</div>';
    html += '<div class="wpa-items-list" id="iconcards-items-' + block.id + '">';
    
    const items = Array.isArray(blockData) ? blockData : [];
    const blockName = block.block_name || '';
    
    // Определяем набор иконок
    let iconSet = cardIcons;
    if (blockName.includes('Производство')) {
        // Используем cardIcons для производства
    }
    
    items.forEach((item, index) => {
        html += '<div class="wpa-feature-item" data-index="' + index + '">';
        html += '<div class="wpa-feature-item-header"><span class="wpa-feature-item-number">Карточка ' + (index + 1) + '</span></div>';
        
        // Icon selector
        html += '<div class="wpa-form-group"><label>Иконка</label>';
        html += '<div class="wpa-icon-selector">';
        for (const [iconKey, iconUrl] of Object.entries(iconSet)) {
            const selected = item.icon === iconKey ? 'selected' : '';
            html += '<div class="wpa-icon-option ' + selected + '" data-icon="' + iconKey + '" onclick="selectIcon(this, ' + index + ', \'iconcard\')">';
            html += '<img src="' + iconUrl + '" alt="' + iconKey + '">';
            html += '</div>';
        }
        html += '</div><input type="hidden" class="iconcard-icon-input" data-index="' + index + '" value="' + escapeHtml(item.icon || '') + '"></div>';
        const iconCardCustomId = 'iconcard-icon-custom-' + block.id + '-' + index;
        html += '<div class="wpa-form-group"><label>Или свой URL иконки</label><input type="text" id="' + iconCardCustomId + '" class="iconcard-icon-custom" data-index="' + index + '" value="' + (item.icon && !iconSet[item.icon] ? escapeHtml(item.icon) : '') + '"><button type="button" class="wpa-upload-btn wpa-upload-media" data-target="#' + iconCardCustomId + '">Выбрать из медиатеки</button></div>';
        
        html += '<div class="wpa-form-group"><label>Заголовок</label><input type="text" class="iconcard-title" data-index="' + index + '" value="' + escapeHtml(item.title || '') + '"></div>';
        html += '<div class="wpa-form-group"><label>Описание</label><textarea class="iconcard-text" data-index="' + index + '">' + escapeHtml(item.text || '') + '</textarea></div>';
        html += '</div>';
    });
    
    html += '</div></div>';
    return html;
}

// Новый редактор для regions (регионы доставки)
function createRegionsEditor(block, blockData) {
    let html = '<div class="wpa-visual-editor"><div class="wpa-visual-editor-title">Регионы доставки</div>';
    html += '<div class="wpa-form-group"><label>Фоновое изображение (URL)</label><input type="text" id="section-image-' + block.id + '" value="' + escapeHtml(block.block_image || '') + '"></div>';
    html += '<button type="button" class="wpa-upload-btn wpa-upload-media" data-target="#section-image-' + block.id + '">Выбрать изображение</button>';
    html += '<div class="wpa-items-list" id="regions-items-' + block.id + '">';
    
    const items = Array.isArray(blockData) ? blockData : [];
    
    items.forEach((item, index) => {
        html += '<div class="wpa-faq-item" data-index="' + index + '">';
        html += '<div class="wpa-faq-header"><span class="wpa-faq-number">Регион ' + (index + 1) + '</span></div>';
        html += '<div class="wpa-form-group"><label>Название региона</label><input type="text" class="region-name" data-index="' + index + '" value="' + escapeHtml(typeof item === 'string' ? item : (item.name || item.region || '')) + '"></div>';
        html += '</div>';
    });
    
    html += '</div></div>';
    return html;
}

function initIconSelectors() {
    document.querySelectorAll('.wpa-icon-option').forEach(opt => {
        opt.addEventListener('click', function() {
            const parent = this.closest('.wpa-feature-item');
            parent.querySelectorAll('.wpa-icon-option').forEach(o => o.classList.remove('selected'));
            this.classList.add('selected');
        });
    });
}

function initUploadButtons() {
    document.querySelectorAll('.wpa-upload-media').forEach(button => {
        button.addEventListener('click', function() {
            const targetSelector = this.dataset.target;
            const target = document.querySelector(targetSelector);
            if (!target || typeof wp === 'undefined' || !wp.media) return;

            const frame = wp.media({
                title: 'Выберите файл',
                button: { text: 'Использовать' },
                multiple: false
            });

            frame.on('select', function() {
                const attachment = frame.state().get('selection').first().toJSON();
                target.value = attachment.url || '';
            });

            frame.open();
        });
    });
}

function toggleBlockForm(id) {
    const form = document.getElementById('edit-form-' + id);
    form.classList.toggle('active');
}

function selectIcon(element, index, type) {
    const parent = element.closest('.wpa-feature-item');
    const icon = element.dataset.icon;
    
    parent.querySelectorAll('.wpa-icon-option').forEach(o => o.classList.remove('selected'));
    element.classList.add('selected');
    
    if (type === 'hero') {
        parent.querySelector('.hero-icon-input').value = icon;
    } else if (type === 'payment') {
        parent.querySelector('.payment-icon-input').value = icon;
    } else if (type === 'whyus') {
        parent.querySelector('.whyus-icon-input').value = icon;
    } else if (type === 'iconcard') {
        parent.querySelector('.iconcard-icon-input').value = icon;
    } else {
        parent.querySelector('.feature-icon-input').value = icon;
    }
}

async function saveBlock(id) {
    const blockCard = document.querySelector('.wpa-block-card[data-id="' + id + '"]');
    const blockTypeText = blockCard.querySelector('.wpa-block-info p')?.textContent || '';
    const blockName = blockCard.querySelector('.wpa-block-info h3')?.textContent || '';
    
    let blockData = {};
    
    // Check if raw data editor exists
    const rawDataEl = document.getElementById('raw-data-' + id);
    if (rawDataEl) {
        try {
            blockData = JSON.parse(rawDataEl.value);
        } catch (e) {
            showNotice('Ошибка JSON: ' + e.message, 'error');
            return;
        }
    } else if (blockTypeText.includes('Баннер') || blockTypeText === 'hero') {
        // Hero block
        const features = [];
        blockCard.querySelectorAll('#hero-items-' + id + ' .wpa-feature-item').forEach((item, index) => {
            const selectedIcon = item.querySelector('.hero-icon-input')?.value || '';
            const customIcon = item.querySelector('.hero-icon-custom')?.value || '';
            features.push({
                icon: customIcon || selectedIcon,
                title: item.querySelector('.hero-feature-title')?.value || '',
                text: item.querySelector('.hero-feature-text')?.value || ''
            });
        });
        blockData = {
            features: features,
            button_text: document.getElementById('hero-btn-text-' + id)?.value || '',
            button_link: document.getElementById('hero-btn-link-' + id)?.value || ''
        };
    } else if (blockTypeText === 'features') {
        // Features block
        const items = [];
        blockCard.querySelectorAll('#features-items-' + id + ' .wpa-feature-item').forEach((item, index) => {
            const itemData = {};

            if (item.querySelector('.feature-title-num')) {
                itemData.title = item.querySelector('.feature-title-num')?.value || '';
                itemData.subtitle = item.querySelector('.feature-subtitle')?.value || '';
                itemData.desc = item.querySelector('.feature-desc')?.value || '';
            }

            if (item.querySelector('.feature-title')) {
                itemData.title = item.querySelector('.feature-title')?.value || '';
            }
            if (item.querySelector('.feature-text')) {
                itemData.text = item.querySelector('.feature-text')?.value || '';
            }
            if (item.querySelector('.feature-image')) {
                itemData.image = item.querySelector('.feature-image')?.value || '';
            }
            if (item.querySelector('.feature-category')) {
                itemData.category = item.querySelector('.feature-category')?.value || '';
            }

            if (item.querySelector('.feature-icon-input')) {
                itemData.icon = item.querySelector('.feature-icon-input')?.value || '';
            } else if (item.querySelector('.payment-icon-input')) {
                itemData.icon = item.querySelector('.payment-icon-input')?.value || '';
            } else if (item.querySelector('.whyus-icon-input')) {
                itemData.icon = item.querySelector('.whyus-icon-input')?.value || '';
            }
            if (item.querySelector('.feature-icon-custom')?.value) {
                itemData.icon = item.querySelector('.feature-icon-custom').value;
            }
            
            items.push(itemData);
        });
        blockData = items;
    } else if (blockTypeText === 'gallery') {
        // Gallery block
        const items = [];
        blockCard.querySelectorAll('#gallery-items-' + id + ' .wpa-feature-item').forEach((item, index) => {
            items.push({
                image: item.querySelector('.gallery-image')?.value || '',
                title: item.querySelector('.gallery-title')?.value || '',
                price: item.querySelector('.gallery-price')?.value || ''
            });
        });
        blockData = items;
    } else if (blockTypeText === 'faq') {
        // FAQ block
        const items = [];
        blockCard.querySelectorAll('#faq-items-' + id + ' .wpa-faq-item').forEach((item, index) => {
            items.push({
                question: item.querySelector('.faq-question')?.value || '',
                answer: item.querySelector('.faq-answer')?.value || ''
            });
        });
        blockData = items;
    } else if (blockTypeText === 'section') {
        // Section block
        blockData = {};
    } else if (blockTypeText === 'contact') {
        // Contact block
        blockData = {
            phone: document.getElementById('contact-phone-' + id)?.value || '',
            email: document.getElementById('contact-email-' + id)?.value || '',
            address: document.getElementById('contact-address-' + id)?.value || '',
            schedule: document.getElementById('contact-schedule-' + id)?.value || ''
        };
    } else if (blockTypeText === 'footer') {
        // Footer blocks
        if (blockName.includes('Основная')) {
            blockData = {
                copyright: document.getElementById('footer-copyright-' + id)?.value || '',
                privacy: document.getElementById('footer-privacy-' + id)?.value || '',
                agreement: document.getElementById('footer-agreement-' + id)?.value || ''
            };
        } else if (blockName.includes('Контакты')) {
            blockData = {
                phone: document.getElementById('footer-phone-' + id)?.value || '',
                email: document.getElementById('footer-email-' + id)?.value || '',
                address: document.getElementById('footer-address-' + id)?.value || ''
            };
        } else {
            // Links blocks
            const items = [];
            blockCard.querySelectorAll('#footer-links-' + id + ' .wpa-faq-item').forEach((item, index) => {
                items.push({
                    text: item.querySelector('.footer-link-text')?.value || '',
                    link: item.querySelector('.footer-link-url')?.value || ''
                });
            });
            blockData = items;
        }
    } else if (blockTypeText === 'header') {
        // Header blocks
        if (blockCard.querySelector('#header-links-' + id)) {
            const items = [];
            blockCard.querySelectorAll('#header-links-' + id + ' .wpa-faq-item').forEach((item, index) => {
                items.push({
                    text: item.querySelector('.header-link-text')?.value || '',
                    link: item.querySelector('.header-link-url')?.value || ''
                });
            });
            blockData = items;
        } else {
            blockData = {
                address: document.getElementById('header-address-' + id)?.value || '',
                phone: document.getElementById('header-phone-' + id)?.value || '',
                image: document.getElementById('header-image-' + id)?.value || ''
            };
        }
    } else if (blockTypeText === 'icon-cards') {
        // Icon cards block
        const items = [];
        blockCard.querySelectorAll('#iconcards-items-' + id + ' .wpa-feature-item').forEach((item, index) => {
            const selectedIcon = item.querySelector('.iconcard-icon-input')?.value || '';
            const customIcon = item.querySelector('.iconcard-icon-custom')?.value || '';
            items.push({
                icon: customIcon || selectedIcon,
                title: item.querySelector('.iconcard-title')?.value || '',
                text: item.querySelector('.iconcard-text')?.value || ''
            });
        });
        blockData = items;
    } else if (blockTypeText === 'regions') {
        // Regions block
        const items = [];
        blockCard.querySelectorAll('#regions-items-' + id + ' .wpa-faq-item').forEach((item, index) => {
            items.push(item.querySelector('.region-name')?.value || '');
        });
        blockData = items;
    }
    
    const data = {
        block_name: document.getElementById('name-' + id)?.value || '',
        block_title: document.getElementById('title-' + id)?.value || '',
        block_text: document.getElementById('text-' + id)?.value || '',
        block_image: document.getElementById('section-image-' + id)?.value || '',
        block_data: JSON.stringify(blockData)
    };
    
    try {
        const response = await fetch(AJAX_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'wp_awnings_update_content_block',
                nonce: NONCE,
                id: String(id),
                block_name: data.block_name || '',
                block_title: data.block_title || '',
                block_text: data.block_text || '',
                block_image: data.block_image || '',
                block_data: data.block_data || '{}'
            }).toString()
        });

        if (!response.ok) {
            showNotice('Ошибка сохранения (HTTP ' + response.status + ')', 'error');
            return;
        }

        const result = await response.json();
        if (result && result.success) {
            showNotice('Сохранено!');
            toggleBlockForm(id);
            loadBlocks(currentPage);
        } else {
            showNotice('Ошибка: ' + (result?.data?.message || 'Не удалось сохранить'), 'error');
        }
    } catch (error) {
        showNotice('Ошибка: ' + error.message, 'error');
    }
}

// Tab switching
document.querySelectorAll('.wpa-tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.wpa-tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.wpa-page-section').forEach(s => s.classList.remove('active'));
        btn.classList.add('active');
        currentPage = btn.dataset.page;
        loadBlocks(currentPage);
    });
});

// Reset blocks
document.getElementById('wpa-reset-btn').addEventListener('click', async () => {
    if (!confirm('Удалить все блоки и пересоздать? Это действие необратимо.')) return;
    
    try {
        const response = await fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=wp_awnings_reset_content&nonce=<?php echo wp_create_nonce('wp_rest'); ?>'
        });
        
        const result = await response.json();
        if (result.success) {
            showNotice('Блоки пересозданы!');
            loadBlocks(currentPage);
        } else {
            showNotice('Ошибка: ' + (result.data?.message || 'Неизвестная ошибка'), 'error');
        }
    } catch (error) {
        showNotice('Ошибка: ' + error.message, 'error');
    }
});

// Initial load
loadBlocks(currentPage);
</script>
