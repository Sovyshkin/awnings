<?php
/**
 * Admin page for managing leads
 *
 * @package wp-awnings
 */

// Only allow access to admins
if (!current_user_can('manage_options')) {
    wp_die('Доступ запрещен');
}

// Get nonce for API calls
$nonce = wp_create_nonce('wp_rest');

// Get new leads count
$new_leads_count = 0;
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
$new_leads_count = $query->found_posts;
wp_reset_postdata();
?>

<style>
.wpa-leads-container {
    padding: 24px;
    max-width: 1400px;
}

.wpa-leads-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 2px solid #eee;
}

.wpa-leads-title {
    font-size: 28px;
    font-weight: 600;
    color: #1d2327;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.wpa-leads-title .badge {
    background: #C96744;
    color: #fff;
    font-size: 14px;
    padding: 4px 12px;
    border-radius: 20px;
    font-weight: 600;
}

.wpa-leads-stats {
    display: flex;
    gap: 20px;
    margin-bottom: 24px;
}

.wpa-stat-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px 28px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    gap: 16px;
}

.wpa-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.wpa-stat-icon.new { background: #FFF3CD; color: #856404; }
.wpa-stat-icon.processed { background: #D4EDDA; color: #155724; }
.wpa-stat-icon.total { background: #E8E8FD; color: #4F46E5; }

.wpa-stat-info h3 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
    color: #1d2327;
}

.wpa-stat-info p {
    margin: 4px 0 0 0;
    font-size: 13px;
    color: #646970;
}

.wpa-leads-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 20px;
}

.wpa-lead-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
}

.wpa-lead-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}

.wpa-lead-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
    border-bottom: 1px solid #eee;
}

.wpa-lead-id {
    font-size: 13px;
    color: #646970;
    font-weight: 500;
}

.wpa-lead-status {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.wpa-lead-status.new {
    background: #FFF3CD;
    color: #856404;
}

.wpa-lead-status.processed {
    background: #D4EDDA;
    color: #155724;
}

.wpa-lead-body {
    padding: 24px;
}

.wpa-lead-contact {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
}

.wpa-lead-avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, #C96744 0%, #b55a3a 100%);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: 600;
}

.wpa-lead-info h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #1d2327;
}

.wpa-lead-info a {
    color: #C96744;
    font-size: 15px;
    font-weight: 500;
    text-decoration: none;
}

.wpa-lead-info a:hover {
    text-decoration: underline;
}

.wpa-lead-message {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 20px;
}

.wpa-lead-message-label {
    font-size: 12px;
    color: #646970;
    margin-bottom: 8px;
    text-transform: uppercase;
    font-weight: 600;
}

.wpa-lead-message p {
    margin: 0;
    color: #1d2327;
    font-size: 14px;
    line-height: 1.5;
}

.wpa-lead-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 16px;
    border-top: 1px solid #eee;
}

.wpa-lead-date {
    font-size: 13px;
    color: #646970;
    display: flex;
    align-items: center;
    gap: 6px;
}

.wpa-lead-actions {
    display: flex;
    gap: 10px;
}

.wpa-lead-btn {
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}

.wpa-lead-btn.process {
    background: #C96744;
    color: #fff;
}

.wpa-lead-btn.process:hover {
    background: #b55a3a;
}

.wpa-lead-btn.delete {
    background: #fff;
    color: #dc3232;
    border: 1px solid #dc3232;
}

.wpa-lead-btn.delete:hover {
    background: #dc3232;
    color: #fff;
}

.wpa-empty-state {
    text-align: center;
    padding: 60px 40px;
    background: #fff;
    border-radius: 16px;
    grid-column: 1 / -1;
}

.wpa-empty-icon {
    font-size: 64px;
    margin-bottom: 20px;
}

.wpa-empty-state h2 {
    margin: 0 0 12px 0;
    color: #1d2327;
    font-size: 24px;
}

.wpa-empty-state p {
    margin: 0;
    color: #646970;
    font-size: 15px;
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

.wpa-leads-filter {
    display: flex;
    gap: 12px;
    margin-bottom: 24px;
}

.wpa-filter-btn {
    padding: 10px 20px;
    border-radius: 8px;
    border: 1px solid #ddd;
    background: #fff;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s;
}

.wpa-filter-btn:hover,
.wpa-filter-btn.active {
    background: #1d2327;
    color: #fff;
    border-color: #1d2327;
}

@media (max-width: 768px) {
    .wpa-leads-grid {
        grid-template-columns: 1fr;
    }
    
    .wpa-leads-stats {
        flex-direction: column;
    }
    
    .wpa-leads-header {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
}
</style>

<div class="wrap wpa-leads-container">
    <div class="wpa-leads-header">
        <h1 class="wpa-leads-title">
            Заявки с сайта
            <?php if ($new_leads_count > 0): ?>
            <span class="badge"><?php echo $new_leads_count; ?> новых</span>
            <?php endif; ?>
        </h1>
    </div>

    <div id="wpa-notice"></div>

    <div class="wpa-leads-stats" id="stats-container">
        <div class="wpa-stat-card">
            <div class="wpa-stat-icon new">!</div>
            <div class="wpa-stat-info">
                <h3 id="stat-new"><?php echo $new_leads_count; ?></h3>
                <p>Новых заявок</p>
            </div>
        </div>
        <div class="wpa-stat-card">
            <div class="wpa-stat-icon processed">✓</div>
            <div class="wpa-stat-info">
                <h3 id="stat-processed">0</h3>
                <p>Обработанных</p>
            </div>
        </div>
        <div class="wpa-stat-card">
            <div class="wpa-stat-icon total">📋</div>
            <div class="wpa-stat-info">
                <h3 id="stat-total">0</h3>
                <p>Всего заявок</p>
            </div>
        </div>
    </div>

    <div class="wpa-leads-filter">
        <button class="wpa-filter-btn active" data-filter="all">Все</button>
        <button class="wpa-filter-btn" data-filter="new">Новые</button>
        <button class="wpa-filter-btn" data-filter="processed">Обработанные</button>
    </div>

    <div class="wpa-leads-grid" id="leads-grid">
        <div class="wpa-empty-state">
            <div class="wpa-empty-icon">📭</div>
            <h2>Загрузка заявок...</h2>
            <p>Пожалуйста, подождите</p>
        </div>
    </div>
</div>

<script>
(function() {
    const API_URL = '<?php echo rest_url('wp-awnings/v1'); ?>';
    const NONCE = '<?php echo $nonce; ?>';
    let allLeads = [];
    let currentFilter = 'all';
    
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
    
    function getInitials(name) {
        return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2) || '?';
    }
    
    function formatDate(dateStr) {
        if (!dateStr) return 'Неизвестно';
        const date = new Date(dateStr);
        return date.toLocaleDateString('ru-RU', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    
    function updateStats() {
        const newCount = allLeads.filter(l => l.status === 'new').length;
        const processedCount = allLeads.filter(l => l.status === 'processed').length;
        const totalCount = allLeads.length;
        
        document.getElementById('stat-new').textContent = newCount;
        document.getElementById('stat-processed').textContent = processedCount;
        document.getElementById('stat-total').textContent = totalCount;
        
        const title = document.querySelector('.wpa-leads-title');
        const existingBadge = title.querySelector('.badge');
        if (newCount > 0) {
            if (existingBadge) {
                existingBadge.textContent = newCount + ' новых';
            } else {
                const badge = document.createElement('span');
                badge.className = 'badge';
                badge.textContent = newCount + ' новых';
                title.appendChild(badge);
            }
        } else if (existingBadge) {
            existingBadge.remove();
        }
    }
    
    function renderLeads() {
        const grid = document.getElementById('leads-grid');
        let filteredLeads = allLeads;
        
        if (currentFilter !== 'all') {
            filteredLeads = allLeads.filter(l => l.status === currentFilter);
        }
        
        if (filteredLeads.length === 0) {
            grid.innerHTML = '<div class="wpa-empty-state"><div class="wpa-empty-icon">📭</div><h2>Заявки не найдены</h2><p>' + 
                (currentFilter !== 'all' ? 'В этой категории нет заявок' : 'Заявки появятся после отправки формы на сайте') + '</p></div>';
            return;
        }
        
        grid.innerHTML = filteredLeads.map(lead => {
            const statusLabel = lead.status === 'processed' ? 'Обработано' : 'Новая';
            const processBtn = lead.status !== 'processed' ? '<button class="wpa-lead-btn process" data-process="' + lead.id + '">Обработать</button>' : '';
            
            return '<div class="wpa-lead-card" data-id="' + lead.id + '">' +
                '<div class="wpa-lead-header">' +
                '<span class="wpa-lead-id">#' + lead.id + '</span>' +
                '<span class="wpa-lead-status ' + (lead.status || 'new') + '">' + statusLabel + '</span>' +
                '</div>' +
                '<div class="wpa-lead-body">' +
                '<div class="wpa-lead-contact">' +
                '<div class="wpa-lead-avatar">' + getInitials(escapeHtml(lead.name)) + '</div>' +
                '<div class="wpa-lead-info"><h3>' + escapeHtml(lead.name) + '</h3><a href="tel:' + escapeHtml(lead.phone) + '">' + escapeHtml(lead.phone) + '</a></div>' +
                '</div>' +
                (lead.message ? '<div class="wpa-lead-message"><div class="wpa-lead-message-label">Сообщение</div><p>' + escapeHtml(lead.message) + '</p></div>' : '') +
                '<div class="wpa-lead-meta">' +
                '<span class="wpa-lead-date">📅 ' + formatDate(lead.date) + '</span>' +
                '<div class="wpa-lead-actions">' + processBtn + '<button class="wpa-lead-btn delete" data-delete="' + lead.id + '">Удалить</button></div>' +
                '</div></div></div>';
        }).join('');
        
        document.querySelectorAll('.wpa-lead-btn.process').forEach(btn => {
            btn.addEventListener('click', () => processLead(parseInt(btn.dataset.process)));
        });
        
        document.querySelectorAll('.wpa-lead-btn.delete').forEach(btn => {
            btn.addEventListener('click', () => deleteLead(parseInt(btn.dataset.delete)));
        });
    }
    
    async function loadLeads() {
        try {
            const response = await fetch(API_URL + '/leads', {
                headers: {
                    'X-WP-Nonce': NONCE
                }
            });
            if (!response.ok) throw new Error('Failed to load');
            
            allLeads = await response.json();
            updateStats();
            renderLeads();
        } catch (error) {
            console.error('Error loading leads:', error);
            document.getElementById('leads-grid').innerHTML = '<div class="wpa-empty-state"><div class="wpa-empty-icon">⚠️</div><h2>Ошибка загрузки</h2><p>Не удалось загрузить заявки. Проверьте подключение к API.</p></div>';
        }
    }
    
    async function processLead(id) {
        try {
            const response = await fetch(API_URL + '/leads/' + id + '/process', {
                method: 'POST',
                headers: { 'X-WP-Nonce': NONCE }
            });
            
            if (response.ok) {
                const lead = allLeads.find(l => l.id === id);
                if (lead) lead.status = 'processed';
                updateStats();
                renderLeads();
                showNotice('Заявка обработана');
            }
        } catch (error) {
            showNotice('Ошибка обработки', 'error');
        }
    }
    
    async function deleteLead(id) {
        if (!confirm('Удалить эту заявку?')) return;
        
        try {
            const response = await fetch(API_URL + '/leads/' + id, {
                method: 'DELETE',
                headers: { 'X-WP-Nonce': NONCE }
            });
            
            if (response.ok) {
                allLeads = allLeads.filter(l => l.id !== id);
                updateStats();
                renderLeads();
                showNotice('Заявка удалена');
            } else {
                showNotice('Ошибка удаления', 'error');
            }
        } catch (error) {
            showNotice('Ошибка удаления', 'error');
        }
    }
    
    document.querySelectorAll('.wpa-filter-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.wpa-filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentFilter = btn.dataset.filter;
            renderLeads();
        });
    });
    
    loadLeads();
})();
</script>
</final_file_content>