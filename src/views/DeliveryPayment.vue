<template>
  <section class="delivery-and-payment">
    <div class="delivery-header">
      <div class="breadcrumbs">
        <router-link to="/">Главная</router-link>
        <span>/</span>
        <router-link to="/delivery-and-payment">Доставка</router-link>
      </div>
      <h1>{{ pageTitle }}</h1>
      <p>{{ pageSubtitle }}</p>
    </div>
    <div class="delivery-cards">
      <div class="delivery-card" v-for="(card, idx) in deliveryCards" :key="idx">
        <div class="delivery-card-wrap-title">
          <span class="delivery-card-title">{{ card.title }}</span>
          <div class="delivery-card-rectangle"></div>
          <span class="delivery-card-subtitle">{{ card.subtitle }}</span>
        </div>
        <ul class="delivery-card-desc">
          <li v-for="(item, itemIdx) in card.items" :key="itemIdx">{{ item }}</li>
        </ul>
      </div>
    </div>


    <div class="wrap-content">
            <div class="text">
                <h2>{{ paymentTitle }}</h2>
                <p>{{ paymentSubtitle }}</p>
            </div>
            <div class="payment-cards">
                <div class="payment-card" v-for="(item, index) in paymentCards" :key="index">
                    <div class="wrap-img">
                        <img :src="resolveIcon(item.icon)" alt="">
                    </div>
                    <div class="payment-card-text">
                        <span class="payment-card-title">{{ item.title }}</span>
                        <p class="payment-card-desc">{{ item.text }}</p>
                    </div>
                </div>
            </div>
        </div>
        <HowDelivery :isMobile="isMobile"/>
        <FaqBlock/>
  </section>
</template>
<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import FaqBlock from '../components/FaqBlock.vue'
import HowDelivery from '../components/HowDelivery.vue'
import { fetchContentBlocks } from '../services/api'
import payment1 from '../assets/payment-1.svg'
import payment2 from '../assets/payment-2.svg'
import payment3 from '../assets/payment-3.svg'
import payment4 from '../assets/payment-4.svg'

const isMobile = ref(false)
const pageTitle = ref('Доставка и оплата')
const pageSubtitle = ref('Доставляем по всей России. Несколько способов оплаты для вашего удобства.')
const paymentTitle = ref('Способы оплаты')
const paymentSubtitle = ref('Быстрые сроки и высокое качество работы, а так же конфигуратор моделей под любой бюджет')
const deliveryCards = ref([
  {
    title: 'Доставка по Екатеринбургу и области',
    subtitle: 'от 5000 ₽',
    items: ['Бесплатно при заказе от 200 000 ₽', 'Разгрузка включена', 'Согласование времени']
  },
  {
    title: 'Доставка по России',
    subtitle: 'Рассчитывается индивидуально',
    items: ['Любой регион РФ', 'Отслеживание груза', 'Страхование']
  },
  {
    title: 'Самовывоз',
    subtitle: 'Бесплатно',
    items: ['Ежедневно 9:00-18:00', 'Предварительная запись', 'Помощь с погрузкой']
  }
])
const paymentCards = ref([
  { icon: 'payment-1', title: 'Безналичный расчет', text: 'Для юридических лиц и ИП. Выставляем счет, работаем с НДС и без.' },
  { icon: 'payment-2', title: 'Банковский перевод', text: 'Перевод на расчетный счет компании.' },
  { icon: 'payment-3', title: 'Наличные', text: 'Оплата при получении или в офисе компании.' },
  { icon: 'payment-4', title: 'Рассрочка', text: 'Возможна рассрочка платежа на срок до 6 месяцев.' }
])

const iconMap = {
  'payment-1': payment1,
  'payment-2': payment2,
  'payment-3': payment3,
  'payment-4': payment4,
  'payment-1.svg': payment1,
  'payment-2.svg': payment2,
  'payment-3.svg': payment3,
  'payment-4.svg': payment4
}

function resolveIcon(icon) {
  if (!icon) return payment1
  if (icon.startsWith('http') || icon.startsWith('/')) return icon
  if (icon.startsWith('wp-content/')) return `/${icon}`
  return iconMap[icon] || payment1
}

async function loadDeliveryContent() {
  const blocks = await fetchContentBlocks('delivery')
  const regionsBlock = blocks.find((b) => (b.block_name || '').includes('Регионы')) || blocks.find((b) => b.block_type === 'regions')
  if (regionsBlock) {
    pageTitle.value = regionsBlock.block_title || pageTitle.value
    pageSubtitle.value = regionsBlock.block_text || pageSubtitle.value
  }

  const payBlock = blocks.find((b) => (b.block_name || '').includes('Способы оплаты')) || blocks.find((b) => b.block_type === 'features')
  if (payBlock) {
    paymentTitle.value = payBlock.block_title || paymentTitle.value
    paymentSubtitle.value = payBlock.block_text || paymentSubtitle.value
    if (payBlock.block_data) {
      let data = payBlock.block_data
      if (typeof data === 'string') {
        try { data = JSON.parse(data) } catch (e) { data = [] }
      }
      if (Array.isArray(data) && data.length) {
        paymentCards.value = data.map((item) => ({
          icon: item.icon || 'payment-1',
          title: item.title || '',
          text: item.text || ''
        }))
      }
    }
  }

  const cardsBlock = blocks.find((b) => b.block_type === 'delivery-cards') || blocks.find((b) => (b.block_name || '').includes('Карточки доставки'))
  if (cardsBlock && cardsBlock.block_data) {
    let data = cardsBlock.block_data
    if (typeof data === 'string') {
      try { data = JSON.parse(data) } catch (e) { data = [] }
    }
    if (Array.isArray(data) && data.length) {
      deliveryCards.value = data.map((item) => ({
        title: item.title || '',
        subtitle: item.subtitle || '',
        items: Array.isArray(item.items) ? item.items : []
      }))
    }
  }
}

function checkMobile() {
  isMobile.value = window.innerWidth <= 768
}

onMounted(() => {
  loadDeliveryContent()
  checkMobile()
  window.addEventListener('resize', checkMobile)
})

onUnmounted(() => {
  window.removeEventListener('resize', checkMobile)
})
</script>
<style scoped>
.delivery-and-payment {
  display: flex;
  flex-direction: column;
  gap: 70px;
  padding: 180px 40px 0 40px;
}

.delivery-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 34px;
}

.breadcrumbs {
  display: flex;
  align-items: center;
  gap: 12px;
}

.breadcrumbs a {
  color: #000000;
  font-size: 16px;
  font-weight: 600;
  text-decoration: none;
  transition: color 0.3s ease;
}

.breadcrumbs a:hover {
  color: #C96744;
}

.breadcrumbs span {
  color: #000000;
  font-size: 16px;
  font-weight: 600;
}

.breadcrumbs p {
    color: #000000;
    opacity: 0.8;
    font-size: 20px;
    font-weight: 300;
    max-width: 495px;
    text-align: center;
}

h1 {
  font-size: 44px;
  font-weight: 400;
  color: #000000;
}
.delivery-cards {
  display: flex;
  gap: 20px;
}

.delivery-card {
  position: relative;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  gap: 20px;
  width: 100%;
  height: 403px;
  background-size: cover;
  background-position: center;
  padding: 13px 33px;
  box-sizing: border-box;
  transition:
    transform 0.3s ease,
    box-shadow 0.3s ease;
  cursor: pointer;
}

.delivery-card:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
}

.delivery-card::before {
  content: "";
  position: absolute;
  inset: 0;
  background: #0000008d;
  pointer-events: none;
  z-index: 0;
  transition: background 0.3s ease;
}

.delivery-card:hover::before {
  background: #00000060;
}

.delivery-card > * {
  position: relative;
  z-index: 1;
}

.delivery-card-wrap-title {
  display: flex;
  flex-direction: column;
  gap: 18px;
}

.delivery-card-title {
  color: #ffffff;
  font-size: 24px;
  font-weight: 400;
  height: 110px;
  display: flex;
  align-items: center;
  justify-content: start;
  transition: transform 0.3s ease;
}

.delivery-card:hover .delivery-card-title {
  transform: scale(1.05);
}

.delivery-card-rectangle {
  height: 4px;
  background-color: #ffffff;
  margin-left: -33px;
  margin-right: -33px;
  transition: background-color 0.3s ease;
}

.delivery-card:hover .delivery-card-rectangle {
  background-color: #c96744;
}

.delivery-card-subtitle {
  color: #ffffff;
  font-size: 32px;
  font-weight: 400;
  transition: color 0.3s ease;
}

.delivery-card:hover .delivery-card-subtitle {
  color: #c96744;
}

.delivery-card-desc {
    margin: 0 auto;
  box-shadow: 0px 4px 4px 0px #00000040;
  backdrop-filter: blur(20.899999618530273px);
  width: 100%;
  padding: 12px 17px 12px 51px;
  border-radius: 44px;
  box-sizing: border-box;
  color: #fff;
  opacity: 0.8;
  transition:
    transform 0.3s ease,
    box-shadow 0.3s ease;
}

.delivery-card:hover .delivery-card-desc {
  transform: translateY(4px);
  box-shadow: 0px 8px 20px 0px #00000050;
}

.delivery-card-desc span {
  color: #ffffff;
  font-size: 16px;
  font-weight: 300;
  opacity: 0.8;
}

.delivery-card:nth-child(1) {
  background-image: url("../assets/company-card-1.png");
}
.delivery-card:nth-child(2) {
  background-image: url("../assets/company-card-1.png");
}
.delivery-card:nth-child(3) {
  background-image: url("../assets/company-card-1.png");
}


.wrap-content {
    display: flex;
    gap: 20px;
}

.text {
    width: 50%;
    display: flex;
    flex-direction: column;
    gap: 36px;
}

h2 {
    color: #000000;
    font-size: 44px;
    font-weight: 400;
    max-width: 442px;
}

.text p {
    color: #000000;
    font-size: 20px;
    font-weight: 300;
    opacity: 0.8;
    max-width: 442px;
}

.payment-cards {
    width: 50%;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.payment-card {
    display: flex;
    align-items: center;
    background-color: #FFFFFF;
    border-radius: 4px;
    padding: 8px 64px 8px 10px;
    min-height: 120px;
    gap: 20px;
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
}

.payment-card:hover {
    transform: translateX(8px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.wrap-img {
    width: 165px;
    height: 165px;
    flex-shrink: 0;
    border-radius: 4px;
    overflow: hidden;
    background-color: #E2E2E2;
    transition: transform 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.payment-card:hover .wrap-img {
    transform: scale(1.05);
}

.wrap-img img {
        width: 110px;
        height: 110px;
    }

.payment-card-text {
    width: 70%;
    display: flex;
    flex-direction: column;
    gap: 17px;
}

.payment-card-title {
    color: #4B4B4B;
    font-size: 28px;
    font-weight: 500;
    transition: color 0.3s ease;
}

.payment-card:hover .payment-card-title {
    color: #C96744;
}

.payment-card-desc {
    color: #000000;
    font-size: 20px;
    font-weight: 300;
    opacity: 0.8;
}

@media (max-width: 1024px) {
    .delivery-and-payment {
        padding: 140px 24px 0 24px;
        gap: 50px;
    }

    .delivery-header {
        gap: 24px;
    }

    h1 {
        font-size: 36px;
    }

    .delivery-cards {
        gap: 16px;
    }

    .delivery-card {
        height: 340px;
        padding: 13px 24px;
    }

    .delivery-card-title {
        font-size: 20px;
        height: 80px;
    }

    .delivery-card-subtitle {
        font-size: 26px;
    }

    .delivery-card-desc {
        padding: 10px 14px 10px 40px;
    }

    .delivery-card-rectangle {
        margin-left: -24px;
        margin-right: -24px;
    }

    .wrap-content {
        flex-direction: column;
        gap: 30px;
    }

    .text {
        width: 100%;
        gap: 20px;
    }

    h2 {
        font-size: 36px;
        max-width: 100%;
    }

    .text p {
        font-size: 18px;
        max-width: 100%;
    }

    .payment-cards {
        width: 100%;
        gap: 10px;
    }

    .payment-card {
        padding: 6px 30px 6px 8px;
        min-height: 100px;
        gap: 16px;
    }

    .wrap-img {
        width: 130px;
        height: 130px;
    }

    .wrap-img img {
        width: 90px;
        height: 90px;
    }

    .payment-card-title {
        font-size: 22px;
    }

    .payment-card-desc {
        font-size: 16px;
    }
}

@media (max-width: 768px) {
    .delivery-and-payment {
        padding: 110px 16px 0 16px;
        gap: 40px;
    }

    .delivery-header {
        gap: 18px;
    }

    .breadcrumbs {
        gap: 8px;
        font-size: 14px;
    }

    h1 {
        font-size: 28px;
    }

    .delivery-cards {
        flex-direction: column;
        gap: 12px;
    }

    .delivery-card {
        height: auto;
        min-height: 240px;
        padding: 16px 20px;
    }

    .delivery-card-title {
        font-size: 18px;
        height: auto;
        min-height: 50px;
    }

    .delivery-card-subtitle {
        font-size: 22px;
    }

    .delivery-card-desc {
        padding: 8px 12px 8px 32px;
        font-size: 14px;
    }

    .delivery-card-rectangle {
        margin-left: -20px;
        margin-right: -20px;
    }

    .wrap-content {
        flex-direction: column;
        gap: 40px;
    }

    .text {
        width: 100%;
        gap: 16px;
    }

    h2 {
        font-size: 28px;
        max-width: 100%;
    }

    .text p {
        font-size: 16px;
    }

    .payment-cards {
        width: 100%;
        gap: 8px;
    }

    .payment-card {
        padding: 8px 24px 8px 8px;
        height: 120px;
        gap: 12px;
    }

    .wrap-img {
        width: 60px;
        height: 60px;
    }

    .wrap-img img {
        width: 40px;
        height: 40px;
    }

    .payment-card-text {
        width: 65%;
        gap: 8px;
    }

    .payment-card-title {
        font-size: 18px;
    }

    .payment-card-desc {
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .delivery-and-payment {
        padding: 100px 12px 0 12px;
        gap: 32px;
    }

    .delivery-header {
        gap: 14px;
    }

    .breadcrumbs {
        gap: 6px;
    }

    .breadcrumbs a,
    .breadcrumbs span {
        font-size: 12px;
    }

    h1 {
        font-size: 24px;
    }

    .delivery-cards {
        gap: 10px;
    }

    .delivery-card {
        min-height: 200px;
        padding: 12px 16px;
    }

    .delivery-card-title {
        font-size: 16px;
        min-height: 40px;
    }

    .delivery-card-subtitle {
        font-size: 18px;
    }

    .delivery-card-desc {
        padding: 6px 10px 6px 24px;
        font-size: 12px;
    }

    .delivery-card-rectangle {
        margin-left: -16px;
        margin-right: -16px;
    }

    h2 {
        font-size: 24px;
    }

    .text p {
        font-size: 16px;
    }

    .payment-card {
        flex-direction: row;
        padding: 12px;
        height: 100px;
        gap: 12px;
    }

    .payment-card:hover {
        transform: translateX(4px);
    }

    .wrap-img {
        width: 80px;
        height: 80px;
        flex-shrink: 0;
    }

    .payment-card-text {
        width: 100%;
    }

    .payment-card-title {
        font-size: 16px;
    }

    .payment-card-desc {
        font-size: 14px;
    }
}
</style>
