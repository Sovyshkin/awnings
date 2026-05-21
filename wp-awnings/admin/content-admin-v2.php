<?php
/**
 * Content Admin V2
 *
 * @package wp-awnings
 */

if (!current_user_can('manage_options')) {
    wp_die('Доступ запрещен');
}

wp_enqueue_media();
$nonce = wp_create_nonce('wp_rest');
?>

<style>
.wpa2-wrap { padding: 24px; max-width: 1600px; }
.wpa2-head { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
.wpa2-title { margin:0; font-size:28px; }
.wpa2-reset { background:#c62828; color:#fff; border:none; border-radius:8px; padding:10px 16px; cursor:pointer; }
.wpa2-tabs { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:20px; }
.wpa2-tab { border:1px solid #d0d5dd; background:#f8f9fb; color:#111; padding:8px 14px; border-radius:8px; cursor:pointer; }
.wpa2-tab.active { background:#2271b1; color:#fff; border-color:#2271b1; }
.wpa2-grid { display:grid; gap:16px; }
.wpa2-card { border:1px solid #e2e8f0; border-radius:12px; background:#fff; overflow:hidden; }
.wpa2-card-head { display:flex; justify-content:space-between; align-items:center; padding:14px 16px; background:#f8fafc; border-bottom:1px solid #e2e8f0; }
.wpa2-card-name { margin:0; font-size:16px; }
.wpa2-card-type { margin:2px 0 0; font-size:12px; color:#475467; }
.wpa2-btn { border:none; border-radius:8px; padding:8px 12px; cursor:pointer; }
.wpa2-btn-edit { background:#2271b1; color:#fff; }
.wpa2-body { padding:16px; }
.wpa2-form { display:none; border-top:1px solid #e2e8f0; padding:16px; background:#fcfcfd; }
.wpa2-form.open { display:block; }
.wpa2-row { margin-bottom:12px; }
.wpa2-row label { display:block; font-weight:600; margin-bottom:5px; font-size:13px; }
.wpa2-row input, .wpa2-row textarea { width:100%; box-sizing:border-box; padding:9px 12px; border:1px solid #d0d5dd; border-radius:8px; font-size:14px; }
.wpa2-row textarea { min-height:72px; resize:vertical; }
.wpa2-actions { display:flex; gap:8px; margin-top:14px; }
.wpa2-save { background:#16a34a; color:#fff; }
.wpa2-cancel { background:#e5e7eb; color:#111; }
.wpa2-note { font-size:12px; color:#475467; margin-top:4px; }
.wpa2-data-box { border:1px solid #e2e8f0; border-radius:10px; padding:12px; background:#fff; }
.wpa2-data-title { margin:0 0 8px; font-size:13px; font-weight:700; }
.wpa2-leaf { border:1px solid #e5e7eb; border-radius:8px; padding:10px; margin-bottom:8px; background:#fafafa; }
.wpa2-leaf-path { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size:11px; color:#344054; margin-bottom:6px; word-break: break-all; }
.wpa2-media { margin-top:6px; background:#fff; border:1px solid #d0d5dd; border-radius:8px; padding:6px 10px; cursor:pointer; font-size:12px; }
.wpa2-msg { margin-bottom:14px; padding:10px 12px; border-radius:8px; }
.wpa2-msg.ok { background:#dcfce7; color:#14532d; }
.wpa2-msg.err { background:#fee2e2; color:#7f1d1d; }
</style>

<div class="wpa2-wrap">
  <div class="wpa2-head">
    <h1 class="wpa2-title">Контент сайта</h1>
    <button id="wpa2-reset" class="wpa2-reset">Пересоздать блоки контента</button>
  </div>

  <div id="wpa2-msg"></div>
  <div class="wpa2-tabs" id="wpa2-tabs"></div>
  <div id="wpa2-grid" class="wpa2-grid"></div>
</div>

<script>
const WPA2 = {
  ajaxUrl: '<?php echo esc_js(admin_url('admin-ajax.php')); ?>',
  nonce: '<?php echo esc_js($nonce); ?>',
  pages: [
    { key: 'home', label: 'Главная' },
    { key: 'about', label: 'О компании' },
    { key: 'faq', label: 'FAQ' },
    { key: 'contacts', label: 'Контакты' },
    { key: 'delivery', label: 'Доставка' },
    { key: 'garant', label: 'Гарантия' },
    { key: 'footer', label: 'Футер' },
    { key: 'header', label: 'Шапка' },
    { key: 'news', label: 'Новости' }
  ],
  currentPage: 'home'
}

function msg(text, isError = false) {
  const el = document.getElementById('wpa2-msg')
  el.innerHTML = '<div class="wpa2-msg ' + (isError ? 'err' : 'ok') + '">' + escapeHtml(text) + '</div>'
  setTimeout(() => { el.innerHTML = '' }, 4500)
}

function escapeHtml(text) {
  if (text === null || text === undefined) return ''
  const div = document.createElement('div')
  div.textContent = String(text)
  return div.innerHTML
}

function parseJSONSafe(value, fallback) {
  if (!value) return fallback
  if (typeof value !== 'string') return value
  try { return JSON.parse(value) } catch (e) { return fallback }
}

function isMediaLike(path) {
  return /(image|icon|img|photo|logo|background|bg)/i.test(path)
}

function flattenLeaves(node, path = 'block_data', out = []) {
  if (node !== null && typeof node === 'object') {
    if (Array.isArray(node)) {
      if (node.length === 0) {
        out.push({ path, type: 'json', value: JSON.stringify([]) })
        return out
      }
      node.forEach((item, i) => flattenLeaves(item, `${path}[${i}]`, out))
    } else {
      const keys = Object.keys(node)
      if (keys.length === 0) {
        out.push({ path, type: 'json', value: JSON.stringify({}) })
        return out
      }
      keys.forEach((k) => flattenLeaves(node[k], `${path}.${k}`, out))
    }
    return out
  }

  const primitiveType = typeof node
  out.push({ path, type: primitiveType, value: node })
  return out
}

function parsePath(path) {
  const tokens = []
  const re = /([^.\[\]]+)|\[(\d+)\]/g
  let m
  while ((m = re.exec(path)) !== null) {
    if (m[1]) tokens.push(m[1])
    else tokens.push(Number(m[2]))
  }
  return tokens
}

function setByPath(target, path, rawValue, typeHint) {
  const tokens = parsePath(path)
  if (!tokens.length) return

  let value = rawValue
  if (typeHint === 'number') {
    const n = Number(rawValue)
    value = Number.isNaN(n) ? 0 : n
  } else if (typeHint === 'boolean') {
    value = String(rawValue) === 'true'
  } else if (typeHint === 'object') {
    value = parseJSONSafe(rawValue, {})
  }

  let cur = target
  for (let i = 0; i < tokens.length - 1; i++) {
    const t = tokens[i]
    const next = tokens[i + 1]
    if (cur[t] === undefined || cur[t] === null || typeof cur[t] !== 'object') {
      cur[t] = typeof next === 'number' ? [] : {}
    }
    cur = cur[t]
  }
  cur[tokens[tokens.length - 1]] = value
}

function openMediaForInput(inputEl) {
  if (!inputEl || typeof wp === 'undefined' || !wp.media) return
  const frame = wp.media({
    title: 'Выберите файл',
    button: { text: 'Использовать' },
    multiple: false
  })
  frame.on('select', function() {
    const attachment = frame.state().get('selection').first().toJSON()
    inputEl.value = attachment.url || ''
  })
  frame.open()
}

async function api(action, payload = {}) {
  const body = new URLSearchParams({ action, nonce: WPA2.nonce, ...payload })
  const res = await fetch(WPA2.ajaxUrl, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: body.toString()
  })
  if (!res.ok) throw new Error('HTTP ' + res.status)
  const json = await res.json()
  if (!json.success) throw new Error(json?.data?.message || 'Ошибка')
  return json.data
}

function renderTabs() {
  const tabs = document.getElementById('wpa2-tabs')
  tabs.innerHTML = WPA2.pages.map(p =>
    '<button class="wpa2-tab ' + (WPA2.currentPage === p.key ? 'active' : '') + '" data-page="' + p.key + '">' + escapeHtml(p.label) + '</button>'
  ).join('')

  tabs.querySelectorAll('.wpa2-tab').forEach(btn => {
    btn.addEventListener('click', () => {
      WPA2.currentPage = btn.dataset.page
      renderTabs()
      loadBlocks()
    })
  })
}

function renderBlockCard(block) {
  const dataObj = parseJSONSafe(block.block_data, {})
  // Ensure image fields exist for "Что мы делаем" cards so they are editable in admin.
  if ((block.block_name || '').includes('Что мы делаем') && Array.isArray(dataObj)) {
    dataObj.forEach((item) => {
      if (item && typeof item === 'object' && item.image === undefined) {
        item.image = ''
      }
    })
  }
  const leaves = flattenLeaves(dataObj)
  const isNewsCards = block.block_type === 'news-cards'
  const isGalleryBlock = block.block_type === 'gallery'
  const isFaqBlock = block.block_type === 'faq'

  let html = ''
  html += '<div class="wpa2-card" data-id="' + block.id + '">'
  html += '  <div class="wpa2-card-head">'
  html += '    <div>'
  html += '      <h3 class="wpa2-card-name">' + escapeHtml(block.block_name || 'Без названия') + '</h3>'
  html += '      <p class="wpa2-card-type">' + escapeHtml(block.block_type || '') + '</p>'
  html += '    </div>'
  html += '    <button class="wpa2-btn wpa2-btn-edit" data-edit="' + block.id + '">Редактировать</button>'
  html += '  </div>'
  html += '  <div class="wpa2-body">'
  html += '    <div><strong>Заголовок:</strong> ' + escapeHtml(block.block_title || '-') + '</div>'
  html += '    <div><strong>Текст:</strong> ' + escapeHtml((block.block_text || '').slice(0, 140) || '-') + '</div>'
  html += '  </div>'

  html += '  <div class="wpa2-form" id="wpa2-form-' + block.id + '">'
  html += '    <div class="wpa2-row"><label>Название блока</label><input type="text" data-field="block_name" value="' + escapeHtml(block.block_name || '') + '"></div>'
  html += '    <div class="wpa2-row"><label>Заголовок</label><input type="text" data-field="block_title" value="' + escapeHtml(block.block_title || '') + '"></div>'
  html += '    <div class="wpa2-row"><label>Текст</label><textarea data-field="block_text">' + escapeHtml(block.block_text || '') + '</textarea></div>'
  html += '    <div class="wpa2-row"><label>Изображение блока (block_image)</label><input type="text" data-field="block_image" value="' + escapeHtml(block.block_image || '') + '"><button type="button" class="wpa2-media" data-media-for="block_image">Выбрать файл</button></div>'
  if (isNewsCards) {
    const cards = Array.isArray(dataObj) ? dataObj : []
    html += '    <p class="wpa2-note">Удобный редактор карточек новостей.</p>'
    html += '    <div class="wpa2-data-box">'
    html += '      <p class="wpa2-data-title">Карточки новостей</p>'
    html += '      <div id="news-cards-' + block.id + '">'
    cards.forEach((item, index) => {
      const cardId = block.id + '-' + index
      html += '        <div class="wpa2-leaf news-card-item" data-index="' + index + '">'
      html += '          <div class="wpa2-leaf-path">Карточка ' + (index + 1) + '</div>'
      html += '          <div class="wpa2-row"><label>Заголовок</label><input type="text" class="news-title" value="' + escapeHtml(item.title || '') + '"></div>'
      html += '          <div class="wpa2-row"><label>Описание</label><textarea class="news-description">' + escapeHtml(item.description || '') + '</textarea></div>'
      html += '          <div class="wpa2-row"><label>Дата</label><input type="text" class="news-date" value="' + escapeHtml(item.date || '') + '"></div>'
      html += '          <div class="wpa2-row"><label>Ссылка</label><input type="text" class="news-link" value="' + escapeHtml(item.link || '') + '"></div>'
      html += '          <div class="wpa2-row"><label>Изображение</label><input type="text" id="news-image-' + cardId + '" class="news-image" value="' + escapeHtml(item.image || '') + '"><button type="button" class="wpa2-media news-media-btn" data-media-input="news-image-' + cardId + '">Выбрать файл</button></div>'
      html += '          <button type="button" class="wpa2-btn wpa2-cancel news-remove">Удалить карточку</button>'
      html += '        </div>'
    })
    html += '      </div>'
    html += '      <button type="button" class="wpa2-btn wpa2-btn-edit" data-add-news="' + block.id + '">+ Добавить карточку</button>'
    html += '    </div>'
  } else if (isGalleryBlock) {
    const projects = Array.isArray(dataObj) ? dataObj : []
    html += '    <p class="wpa2-note">Удобный редактор проектов (галерея).</p>'
    html += '    <div class="wpa2-data-box">'
    html += '      <p class="wpa2-data-title">Карточки проектов</p>'
    html += '      <div id="gallery-items-' + block.id + '">'
    projects.forEach((item, index) => {
      const cardId = block.id + '-' + index
      html += '        <div class="wpa2-leaf gallery-item" data-index="' + index + '">'
      html += '          <div class="wpa2-leaf-path">Проект ' + (index + 1) + '</div>'
      html += '          <div class="wpa2-row"><label>Название</label><input type="text" class="gallery-title" value="' + escapeHtml(item.title || '') + '"></div>'
      html += '          <div class="wpa2-row"><label>Цена</label><input type="text" class="gallery-price" value="' + escapeHtml(item.price || '') + '"></div>'
      html += '          <div class="wpa2-row"><label>Изображение</label><input type="text" id="gallery-image-' + cardId + '" class="gallery-image" value="' + escapeHtml(item.image || '') + '"><button type="button" class="wpa2-media gallery-media-btn" data-media-input="gallery-image-' + cardId + '">Выбрать файл</button></div>'
      html += '          <button type="button" class="wpa2-btn wpa2-cancel gallery-remove">Удалить проект</button>'
      html += '        </div>'
    })
    html += '      </div>'
    html += '      <button type="button" class="wpa2-btn wpa2-btn-edit" data-add-gallery="' + block.id + '">+ Добавить проект</button>'
    html += '    </div>'
  } else if (isFaqBlock) {
    const faqItems = Array.isArray(dataObj) ? dataObj : []
    html += '    <p class="wpa2-note">Удобный редактор FAQ.</p>'
    html += '    <div class="wpa2-data-box">'
    html += '      <p class="wpa2-data-title">Элементы FAQ</p>'
    html += '      <div id="faq-items-' + block.id + '">'
    faqItems.forEach((item, index) => {
      html += '        <div class="wpa2-leaf faq-item" data-index="' + index + '">'
      html += '          <div class="wpa2-leaf-path">Вопрос ' + (index + 1) + '</div>'
      html += '          <div class="wpa2-row"><label>Вопрос</label><input type="text" class="faq-question" value="' + escapeHtml(item.question || '') + '"></div>'
      html += '          <div class="wpa2-row"><label>Ответ</label><textarea class="faq-answer">' + escapeHtml(item.answer || '') + '</textarea></div>'
      html += '          <button type="button" class="wpa2-btn wpa2-cancel faq-remove">Удалить элемент</button>'
      html += '        </div>'
    })
    html += '      </div>'
    html += '      <button type="button" class="wpa2-btn wpa2-btn-edit" data-add-faq="' + block.id + '">+ Добавить элемент</button>'
    html += '    </div>'
  } else {
    html += '    <p class="wpa2-note">Ниже универсальный редактор всех полей block_data. Любые текст/иконки/изображения редактируются здесь.</p>'
    html += '    <div class="wpa2-data-box">'
    html += '      <p class="wpa2-data-title">Поля block_data</p>'

    if (!leaves.length) {
      html += '      <div class="wpa2-leaf"><div class="wpa2-leaf-path">block_data</div><textarea data-data-path="block_data" data-data-type="object">{}</textarea></div>'
    } else {
      leaves.forEach((leaf, i) => {
        const value = leaf.value === null || leaf.value === undefined ? '' : String(leaf.value)
        const isLong = value.length > 120 || /\n/.test(value)
        html += '      <div class="wpa2-leaf">'
        html += '        <div class="wpa2-leaf-path">' + escapeHtml(leaf.path) + '</div>'
        if (isLong) {
          html += '        <textarea data-data-path="' + escapeHtml(leaf.path) + '" data-data-type="' + escapeHtml(leaf.type) + '">' + escapeHtml(value) + '</textarea>'
        } else {
          html += '        <input type="text" data-data-path="' + escapeHtml(leaf.path) + '" data-data-type="' + escapeHtml(leaf.type) + '" value="' + escapeHtml(value) + '">'
        }
        if (isMediaLike(leaf.path)) {
          html += '        <button type="button" class="wpa2-media" data-media-for-path="' + escapeHtml(leaf.path) + '">Выбрать файл</button>'
        }
        html += '      </div>'
      })
    }
    html += '    </div>'
  }
  html += '    <div class="wpa2-actions">'
  html += '      <button class="wpa2-btn wpa2-save" data-save="' + block.id + '">Сохранить</button>'
  html += '      <button class="wpa2-btn wpa2-cancel" data-cancel="' + block.id + '">Отмена</button>'
  html += '    </div>'
  html += '  </div>'
  html += '</div>'

  return html
}

function bindCardEvents(blocks) {
  blocks.forEach(block => {
    const card = document.querySelector('.wpa2-card[data-id="' + block.id + '"]')
    if (!card) return

    const form = card.querySelector('#wpa2-form-' + block.id)
    card.querySelector('[data-edit="' + block.id + '"]')?.addEventListener('click', () => form.classList.toggle('open'))
    card.querySelector('[data-cancel="' + block.id + '"]')?.addEventListener('click', () => form.classList.remove('open'))

    card.querySelectorAll('.wpa2-media[data-media-for="block_image"]').forEach(btn => {
      btn.addEventListener('click', () => {
        const input = card.querySelector('[data-field="block_image"]')
        openMediaForInput(input)
      })
    })

    card.querySelectorAll('.wpa2-media[data-media-for-path]').forEach(btn => {
      btn.addEventListener('click', () => {
        const path = btn.getAttribute('data-media-for-path')
        const input = card.querySelector('[data-data-path="' + CSS.escape(path) + '"]')
        openMediaForInput(input)
      })
    })

    card.querySelectorAll('.news-media-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-media-input')
        const input = card.querySelector('#' + CSS.escape(id))
        openMediaForInput(input)
      })
    })

    card.querySelector('[data-add-news="' + block.id + '"]')?.addEventListener('click', () => {
      const list = card.querySelector('#news-cards-' + block.id)
      if (!list) return
      const idx = list.querySelectorAll('.news-card-item').length
      const cardId = block.id + '-' + idx + '-' + Date.now()
      const wrapper = document.createElement('div')
      wrapper.className = 'wpa2-leaf news-card-item'
      wrapper.setAttribute('data-index', String(idx))
      wrapper.innerHTML = ''
        + '<div class="wpa2-leaf-path">Карточка ' + (idx + 1) + '</div>'
        + '<div class="wpa2-row"><label>Заголовок</label><input type="text" class="news-title"></div>'
        + '<div class="wpa2-row"><label>Описание</label><textarea class="news-description"></textarea></div>'
        + '<div class="wpa2-row"><label>Дата</label><input type="text" class="news-date"></div>'
        + '<div class="wpa2-row"><label>Ссылка</label><input type="text" class="news-link" value="/news-articles"></div>'
        + '<div class="wpa2-row"><label>Изображение</label><input type="text" id="news-image-' + cardId + '" class="news-image"><button type="button" class="wpa2-media news-media-btn" data-media-input="news-image-' + cardId + '">Выбрать файл</button></div>'
        + '<button type="button" class="wpa2-btn wpa2-cancel news-remove">Удалить карточку</button>'
      list.appendChild(wrapper)
      wrapper.querySelector('.news-remove')?.addEventListener('click', () => wrapper.remove())
      wrapper.querySelector('.news-media-btn')?.addEventListener('click', () => {
        const input = wrapper.querySelector('.news-image')
        openMediaForInput(input)
      })
    })

    card.querySelectorAll('.news-remove').forEach(btn => {
      btn.addEventListener('click', () => btn.closest('.news-card-item')?.remove())
    })

    card.querySelectorAll('.gallery-media-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-media-input')
        const input = card.querySelector('#' + CSS.escape(id))
        openMediaForInput(input)
      })
    })

    card.querySelector('[data-add-gallery="' + block.id + '"]')?.addEventListener('click', () => {
      const list = card.querySelector('#gallery-items-' + block.id)
      if (!list) return
      const idx = list.querySelectorAll('.gallery-item').length
      const cardId = block.id + '-' + idx + '-' + Date.now()
      const wrapper = document.createElement('div')
      wrapper.className = 'wpa2-leaf gallery-item'
      wrapper.setAttribute('data-index', String(idx))
      wrapper.innerHTML = ''
        + '<div class="wpa2-leaf-path">Проект ' + (idx + 1) + '</div>'
        + '<div class="wpa2-row"><label>Название</label><input type="text" class="gallery-title"></div>'
        + '<div class="wpa2-row"><label>Цена</label><input type="text" class="gallery-price"></div>'
        + '<div class="wpa2-row"><label>Изображение</label><input type="text" id="gallery-image-' + cardId + '" class="gallery-image"><button type="button" class="wpa2-media gallery-media-btn" data-media-input="gallery-image-' + cardId + '">Выбрать файл</button></div>'
        + '<button type="button" class="wpa2-btn wpa2-cancel gallery-remove">Удалить проект</button>'
      list.appendChild(wrapper)
      wrapper.querySelector('.gallery-remove')?.addEventListener('click', () => wrapper.remove())
      wrapper.querySelector('.gallery-media-btn')?.addEventListener('click', () => {
        const input = wrapper.querySelector('.gallery-image')
        openMediaForInput(input)
      })
    })

    card.querySelectorAll('.gallery-remove').forEach(btn => {
      btn.addEventListener('click', () => btn.closest('.gallery-item')?.remove())
    })

    card.querySelector('[data-add-faq="' + block.id + '"]')?.addEventListener('click', () => {
      const list = card.querySelector('#faq-items-' + block.id)
      if (!list) return
      const idx = list.querySelectorAll('.faq-item').length
      const wrapper = document.createElement('div')
      wrapper.className = 'wpa2-leaf faq-item'
      wrapper.setAttribute('data-index', String(idx))
      wrapper.innerHTML = ''
        + '<div class="wpa2-leaf-path">Вопрос ' + (idx + 1) + '</div>'
        + '<div class="wpa2-row"><label>Вопрос</label><input type="text" class="faq-question"></div>'
        + '<div class="wpa2-row"><label>Ответ</label><textarea class="faq-answer"></textarea></div>'
        + '<button type="button" class="wpa2-btn wpa2-cancel faq-remove">Удалить элемент</button>'
      list.appendChild(wrapper)
      wrapper.querySelector('.faq-remove')?.addEventListener('click', () => wrapper.remove())
    })

    card.querySelectorAll('.faq-remove').forEach(btn => {
      btn.addEventListener('click', () => btn.closest('.faq-item')?.remove())
    })

    card.querySelector('[data-save="' + block.id + '"]')?.addEventListener('click', async () => {
      try {
        let updatedData
        if (block.block_type === 'news-cards') {
          updatedData = []
          card.querySelectorAll('.news-card-item').forEach(item => {
            updatedData.push({
              title: item.querySelector('.news-title')?.value || '',
              description: item.querySelector('.news-description')?.value || '',
              date: item.querySelector('.news-date')?.value || '',
              link: item.querySelector('.news-link')?.value || '',
              image: item.querySelector('.news-image')?.value || ''
            })
          })
        } else if (block.block_type === 'gallery') {
          updatedData = []
          card.querySelectorAll('.gallery-item').forEach(item => {
            updatedData.push({
              title: item.querySelector('.gallery-title')?.value || '',
              price: item.querySelector('.gallery-price')?.value || '',
              image: item.querySelector('.gallery-image')?.value || ''
            })
          })
        } else if (block.block_type === 'faq') {
          updatedData = []
          card.querySelectorAll('.faq-item').forEach(item => {
            updatedData.push({
              question: item.querySelector('.faq-question')?.value || '',
              answer: item.querySelector('.faq-answer')?.value || ''
            })
          })
        } else {
          const original = parseJSONSafe(block.block_data, {})
          updatedData = Array.isArray(original) ? [...original] : (original && typeof original === 'object' ? { ...original } : {})
          card.querySelectorAll('[data-data-path]').forEach(input => {
            const path = input.getAttribute('data-data-path')
            const type = input.getAttribute('data-data-type') || 'string'
            setByPath(updatedData, path.replace(/^block_data\.?/, ''), input.value, type)
          })
        }

        await api('wp_awnings_update_content_block', {
          id: String(block.id),
          block_name: card.querySelector('[data-field="block_name"]').value || '',
          block_title: card.querySelector('[data-field="block_title"]').value || '',
          block_text: card.querySelector('[data-field="block_text"]').value || '',
          block_image: card.querySelector('[data-field="block_image"]').value || '',
          block_data: JSON.stringify(updatedData)
        })

        msg('Сохранено')
        await loadBlocks()
      } catch (e) {
        msg('Ошибка сохранения: ' + e.message, true)
      }
    })
  })
}

async function loadBlocks() {
  const grid = document.getElementById('wpa2-grid')
  grid.innerHTML = '<div>Загрузка...</div>'

  try {
    const blocks = await api('wp_awnings_get_content_blocks', { page: WPA2.currentPage })
    if (!blocks.length) {
      grid.innerHTML = '<div>Нет блоков для этой страницы.</div>'
      return
    }

    grid.innerHTML = blocks.map(renderBlockCard).join('')
    bindCardEvents(blocks)
  } catch (e) {
    grid.innerHTML = '<div style="color:#b42318;">Ошибка загрузки: ' + escapeHtml(e.message) + '</div>'
  }
}

document.getElementById('wpa2-reset').addEventListener('click', async () => {
  if (!confirm('Удалить все блоки и пересоздать?')) return
  try {
    await api('wp_awnings_reset_content', {})
    msg('Контент пересоздан')
    await loadBlocks()
  } catch (e) {
    msg('Ошибка: ' + e.message, true)
  }
})

renderTabs()
loadBlocks()
</script>
