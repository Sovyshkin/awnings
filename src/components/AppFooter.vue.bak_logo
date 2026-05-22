<template>
    <footer class="footer">
        <div class="footer-main">
            <div class="footer-name">
                <h2>{{ footerTitle || 'Название' }}</h2>
                <p>{{ footerDescription || 'Производство и продажа металлических навесов в Екатеринбурге и Свердловской области. Доставка и монтаж по всей России.' }}</p>
            </div>
            <div class="footer-nav">
                <div class="catalog">
                    <h3>Каталог</h3>
                    <ul class="list-nav">
                        <li v-for="(item, index) in catalogLinks" :key="index" class="item-nav">
                            <router-link class="footer-link" :to="item.link">{{ item.text }}</router-link>
                        </li>
                    </ul>
                </div>
                <div class="client">
                    <h3>Покупателям</h3>
                    <ul class="list-nav">
                        <li v-for="(item, index) in clientLinks" :key="index" class="item-nav">
                            <router-link class="footer-link" :to="item.link">{{ item.text }}</router-link>
                        </li>
                    </ul>
                </div>
                <div class="contact">
                    <h3>Контакты</h3>
                    <ul class="list-nav">
                        <li class="item-nav">
                            <a class="footer-link" :href="phoneHref">{{ footerPhone || '+7 (900) 123-45-67' }}</a>
                        </li>
                        <li class="item-nav">
                            <a class="footer-link" :href="emailHref">{{ footerEmail || 'info@navesstroy.ru' }}</a>
                        </li>
                        <li class="item-nav">{{ footerAddress || 'г. Екатеринбург, ул. Промышленная, д. 4, стр. 2' }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-sub">
            <span class="copy">{{ footerCopyright || '© 2026 Название. Все права защищены.' }}</span>
            <div class="docs">
                <router-link class="footer-link" to="/privacy-policy">{{ footerPrivacy || 'Политика конфиденциальности' }}</router-link>
                <router-link class="footer-link" to="/user-agreement">{{ footerAgreement || 'Пользовательское соглашение' }}</router-link>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { fetchContentBlocks } from '../services/api'

const footerTitle = ref('')
const footerDescription = ref('')
const footerCopyright = ref('')
const footerPrivacy = ref('')
const footerAgreement = ref('')
const footerPhone = ref('')
const footerEmail = ref('')
const footerAddress = ref('')
const catalogLinks = ref([
    { text: 'Беседки', link: '/catalog?category=besedka' },
    { text: 'Мангальные зоны', link: '/catalog?category=mangal' },
    { text: 'Навесы для авто', link: '/catalog?category=naves' },
])
const clientLinks = ref([
    { text: 'О компании', link: '/about-company' },
    { text: 'Новости и статьи', link: '/news-articles' },
    { text: 'Доставка и оплата', link: '/delivery-and-payment' },
    { text: 'Гарантия', link: '/garant' },
    { text: 'Контакты', link: '/contacts' },
])

const defaultCatalogLinksMap = {
    'беседки': '/catalog?category=besedka',
    'мангальные зоны': '/catalog?category=mangal',
    'навесы для авто': '/catalog?category=naves',
    'навесы для автомобилей': '/catalog?category=naves'
}

const defaultClientLinksMap = {
    'о компании': '/about-company',
    'новости и статьи': '/news-articles',
    'новости': '/news-articles',
    'доставка и оплата': '/delivery-and-payment',
    'гарантия': '/garant',
    'контакты': '/contacts',
    'faq': '/garant'
}

const phoneHref = ref('tel:+79001234567')
const emailHref = ref('mailto:info@navesstroy.ru')

function normalizePhone(phone) {
    return String(phone || '').replace(/[^\d+]/g, '')
}

function normalizeLinks(items, map, fallback = '/') {
    if (!Array.isArray(items)) return []
    return items.map((item) => {
        const text = (item?.text || '').trim()
        const directLink = (item?.link || '').trim()
        const mapped = map[text.toLowerCase()] || fallback
        return {
            text: text || 'Ссылка',
            link: directLink || mapped
        }
    })
}

async function loadFooterData() {
    const blocks = await fetchContentBlocks('footer')
    
    const mainBlock = blocks.find(b => b.block_name.includes('Основная информация'))
    if (mainBlock) {
        footerTitle.value = mainBlock.block_title || ''
        footerDescription.value = mainBlock.block_text || ''
        if (mainBlock.block_data) {
            let data = mainBlock.block_data
            if (typeof data === 'string') {
                try {
                    data = JSON.parse(data)
                } catch (e) {
                    data = {}
                }
            }
            footerCopyright.value = data.copyright || ''
            footerPrivacy.value = data.privacy || ''
            footerAgreement.value = data.agreement || ''
        }
    }
    
    const catalogBlock = blocks.find(b => b.block_name.includes('Каталог'))
    if (catalogBlock && catalogBlock.block_data) {
        let data = catalogBlock.block_data
        if (typeof data === 'string') {
            try {
                data = JSON.parse(data)
            } catch (e) {
                return
            }
        }
        if (Array.isArray(data)) {
            catalogLinks.value = normalizeLinks(data, defaultCatalogLinksMap, '/catalog')
        }
    }
    
    const clientBlock = blocks.find(b => b.block_name.includes('Покупателям'))
    if (clientBlock && clientBlock.block_data) {
        let data = clientBlock.block_data
        if (typeof data === 'string') {
            try {
                data = JSON.parse(data)
            } catch (e) {
                return
            }
        }
        if (Array.isArray(data)) {
            clientLinks.value = normalizeLinks(data, defaultClientLinksMap, '/')
        }
    }
    
    const contactBlock = blocks.find(b => b.block_name.includes('Контакты'))
    if (contactBlock && contactBlock.block_data) {
        let data = contactBlock.block_data
        if (typeof data === 'string') {
            try {
                data = JSON.parse(data)
            } catch (e) {
                return
            }
        }
        footerPhone.value = data.phone || ''
        footerEmail.value = data.email || ''
        footerAddress.value = data.address || ''
        phoneHref.value = 'tel:' + (normalizePhone(footerPhone.value) || '+79001234567')
        emailHref.value = 'mailto:' + (footerEmail.value || 'info@navesstroy.ru')
    }
}

onMounted(() => {
    loadFooterData()
})
</script>
<style scoped>
.footer {
    width: 100%;
    display: flex;
    flex-direction: column;
    padding: 40px;
    gap: 40px;
    background-color: #000;
    border-radius: 4px 4px 0 0;
}

.footer-main {
    display: flex;
    justify-content: space-between;
    gap: 40px;
}

.footer-name {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

h2 {
    color: #FFFFFF;
    font-size: 36px;
    font-weight: 400;
}

p {
    color: #FFFFFF;
    opacity: 0.8;
    font-size: 20px;
    font-weight: 300;
    max-width: 442px;
}

.footer-nav {
    width: 60%;
    display: flex;
    gap: 20px;
    justify-content: space-between;
}

.catalog, .client, .contact {
    display: flex;
    flex-direction: column;
    gap: 49px;
    max-width: 300px;
}

h3 {
    color: #C96744;
    font-size: 28px;
    font-weight: 500;
    transition: color 0.3s ease;
}

h3:hover {
    color: #e07a56;
}

.list-nav {
    display: flex;
    flex-direction: column;
    gap: 24px;
    list-style: none;
}

.item-nav {
    color: #FFFFFF;
    opacity: 0.8;
    font-size: 20px;
    font-weight: 300;
    transition: opacity 0.3s ease, color 0.3s ease, transform 0.3s ease;
    cursor: pointer;
}

.footer-link {
    color: inherit;
    text-decoration: inherit;
}

.item-nav:hover {
    opacity: 1;
    color: #C96744;
    transform: translateX(4px);
}

.contact .item-nav {
    text-decoration: underline;
}

.contact .item-nav:nth-child(3) {
    text-decoration: none;
    cursor: default;
}

.contact .item-nav:nth-child(3):hover {
    transform: none;
    color: #FFFFFF;
    opacity: 0.8;
}

.footer-sub {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.copy {
    color: #FFFFFF;
    opacity: 0.8;
    font-size: 20px;
    font-weight: 300;
}

.docs {
    display: flex;
    align-items: center;
    gap: 40px;
}

.docs span,
.docs .footer-link {
    color: #FFFFFF;
    opacity: 0.8;
    font-size: 20px;
    font-weight: 300;
    cursor: pointer;
    transition: opacity 0.3s ease, color 0.3s ease;
}

.docs span:hover,
.docs .footer-link:hover {
    opacity: 1;
    color: #C96744;
}

@media (max-width: 1200px) {
    .footer {
        padding: 32px;
        gap: 32px;
    }

    h2 {
        font-size: 32px;
    }

    p {
        font-size: 18px;
        max-width: 360px;
    }

    .catalog, .client, .contact {
        max-width: 240px;
        gap: 40px;
    }

    h3 {
        font-size: 24px;
    }

    .item-nav {
        font-size: 18px;
    }

    .copy {
        font-size: 18px;
    }

    .docs span,
    .docs .footer-link {
        font-size: 18px;
    }
}

@media (max-width: 1024px) {
    .footer {
        padding: 24px;
        gap: 24px;
    }

    .footer-main {
        flex-direction: column;
        gap: 32px;
    }

    .footer-name {
        width: 100%;
    }

    p {
        max-width: 100%;
    }

    .footer-nav {
        width: 100%;
        gap: 16px;
    }

    .catalog, .client, .contact {
        max-width: 200px;
        gap: 32px;
    }

    h3 {
        font-size: 22px;
    }

    .item-nav {
        font-size: 16px;
        gap: 20px;
    }
}

@media (max-width: 768px) {
    .footer {
        padding: 20px 16px;
        gap: 20px;
    }

    h2 {
        font-size: 28px;
    }

    p {
        font-size: 16px;
    }

    .footer-nav {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 32px;
    }

    .catalog, .client {
        max-width: 100%;
        gap: 24px;
    }

    .contact {
        grid-column: 1 / -1;
        grid-row: 2;
        max-width: 100%;
        gap: 24px;
    }

    h3 {
        font-size: 20px;
    }

    .list-nav {
        gap: 16px;
    }

    .item-nav {
        font-size: 14px;
    }

    .footer-sub {
        flex-direction: column;
        gap: 16px;
        text-align: center;
    }

    .copy {
        font-size: 14px;
    }

    .docs {
        flex-direction: column;
        gap: 12px;
    }

    .docs span,
    .docs .footer-link {
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .footer {
        padding: 16px 12px;
        gap: 16px;
    }

    h2 {
        font-size: 24px;
    }

    p {
        font-size: 14px;
    }

    .footer-nav {
        gap: 16px;
    }

    h3 {
        font-size: 18px;
    }

    .item-nav {
        font-size: 13px;
    }

    .copy {
        font-size: 12px;
    }

    .docs span,
    .docs .footer-link {
        font-size: 12px;
    }
}
</style>
