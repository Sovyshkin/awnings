<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const articles = ref([
  { id: 1, title: 'Уход за металлическим навесом: советы по обслуживанию', description: 'Регулярный уход продлевает жизнь навеса. Узнайте, как правильно чистить и обслуживать конструкцию.', date: '15 марта 2025 г.', image: '../assets/company-card-1.png' },
  { id: 2, title: 'Как выбрать навес для автомобиля', description: 'Разбираемся в материалах, размерах и конструкциях навесов для вашего авто.', date: '10 марта 2025 г.', image: '../assets/company-card-1.png' },
  { id: 3, title: 'Навесы из поликарбоната: преимущества и недостатки', description: 'Всё о самом популярном материале для навесов.', date: '5 марта 2025 г.', image: '../assets/company-card-1.png' },
  { id: 4, title: 'Монтаж навеса своими руками', description: 'Пошаговая инструкция по установке навеса.', date: '1 марта 2025 г.', image: '../assets/company-card-1.png' },
  { id: 5, title: 'Виды кровельных материалов для навесов', description: 'Сравниваем профлист, поликарбонат, металлочерепицу.', date: '25 февраля 2025 г.', image: '../assets/company-card-1.png' },
  { id: 6, title: 'Навес для террасы: идеи и решения', description: 'Создаём уютное пространство на открытом воздухе.', date: '20 февраля 2025 г.', image: '../assets/company-card-1.png' },
  { id: 7, title: 'Зимние навесы: особенности эксплуатации', description: 'Как эксплуатировать навес в холодное время года.', date: '15 февраля 2025 г.', image: '../assets/company-card-1.png' },
  { id: 8, title: 'Навесы для бизнеса', description: 'Использование навесов в коммерческих целях.', date: '10 февраля 2025 г.', image: '../assets/company-card-1.png' },
])

const currentArticleId = computed(() => parseInt(route.params.id) || 1)
const currentArticle = computed(() => articles.value.find(a => a.id === currentArticleId.value) || articles.value[0])

const otherArticles = computed(() => {
  const currentIndex = articles.value.findIndex(a => a.id === currentArticleId.value)
  const result = [...articles.value]
  if (currentIndex > -1) {
    result.splice(currentIndex, 1)
  }
  return result
})

const visibleCount = ref(6)
const showMoreVisible = computed(() => visibleCount.value < otherArticles.value.length)
const scrollPosition = ref(0)
let touchStartX = 0
let touchEndX = 0

function showMore() {
  visibleCount.value += 6
}

function goToArticle(id) {
  router.push(`/news-articles/${id}`)
}

function prevStep() {
  const container = document.querySelector('.other-articles .cards')
  if (container) {
    scrollPosition.value = Math.max(0, scrollPosition.value - 1)
    container.scrollTo({ left: scrollPosition.value * (container.offsetWidth / 3), behavior: 'smooth' })
  }
}

function nextStep() {
  const container = document.querySelector('.other-articles .cards')
  if (container) {
    scrollPosition.value = Math.min(3, scrollPosition.value + 1)
    container.scrollTo({ left: scrollPosition.value * (container.offsetWidth / 3), behavior: 'smooth' })
  }
}

function handleTouchStart(e) {
  touchStartX = e.touches[0].clientX
}

function handleTouchEnd(e) {
  touchEndX = e.changedTouches[0].clientX
  const diff = touchStartX - touchEndX
  if (Math.abs(diff) > 50) {
    if (diff > 0) {
      nextStep()
    } else {
      prevStep()
    }
  }
}
</script>

<template>
  <section class="article-page">
    <div class="header">
      <div class="breadcrumbs">
        <router-link to="/">Главная</router-link>
        <span>/</span>
        <router-link to="/news-articles">Новости и статьи</router-link>
        <span>/</span>
        <span>{{ currentArticle.title }}</span>
      </div>
      <h1>{{ currentArticle.title }}</h1>
    </div>
    <div class="article-content">
      <div class="wrap-img">
        <img src="../assets/company-card-1.png" alt="" />
      </div>
      <p>
        Регулярный уход продлевает срок службы любой конструкции, и навесы — не
        исключение. В этой статье мы расскажем о том, как правильно обслуживать
        металлический навес, чтобы он прослужил вам максимально долго.
      </p>
      <div class="group-text">
        <h2>Первый месяц после установки</h2>
        <p>
          Сразу после монтажа навеса необходимо проверить все соединения и
          крепежи. Убедитесь, что болты хорошо затянуты и нет люфтов. В течение
          первого месяца рекомендуется проверить конструкцию несколько раз, так
          как при эксплуатации возможны небольшие смещения.
        </p>
      </div>
      <div class="group-text">
        <h2>Регулярная очистка</h2>
        <p>
          Один раз в месяц проводите визуальный осмотр. Если навес находится в
          регионе с частыми осадками, проверяйте его после сильных дождей или
          снегопадов. Удаляйте листья, ветки и другой мусор, который может
          скапливаться на кровле. <br />
          Для очистки используйте мягкую щётку или тряпку. Не применяйте
          абразивные материалы, так как они могут повредить поверхность
          поликарбоната или профлиста.
        </p>
      </div>
      <div class="group-text">
        <h2>Зимний уход</h2>
        <p>
          В зимний период особенно важно следить за скоплением снега и льда на
          крыше. Избыток снега может привести к деформации конструкции.
          Аккуратно удаляйте снег с помощью мягкого скребка, стараясь не
          повредить кровлю.
        </p>
      </div>
      <div class="group-text">
        <h2>Проверка и ремонт</h2>
        <p>
          Один раз в год проводите тщательный осмотр всех компонентов: -
          Проверьте состояние кровельного материала - Осмотрите металлические
          элементы на предмет коррозии - Убедитесь в целостности крепежа и
          сварных швов <br />
          При обнаружении проблем свяжитесь с нашей командой — мы поможем с
          ремонтом и обслуживанием.
        </p>
      </div>
      <div class="group-text">
        <h2>Профилактическая покраска</h2>
        <p>
          Раз в 3-5 лет рекомендуется проводить профилактическую покраску
          металлических элементов навеса. Это продлит срок их службы и сохранит
          внешний вид конструкции.
        </p>
      </div>
    </div>
    <div class="other-articles">
      <div class="wrap-title">
        <h2>Другие статьи</h2>
        <div class="actions">
          <button class="btn arrow-left" @click="prevStep">
            <img src="../assets/arrow-left.svg" alt="" />
          </button>
          <button class="btn arrow-right" @click="nextStep">
            <img src="../assets/arrow-right.svg" alt="" />
          </button>
        </div>
      </div>
      <div class="cards" ref="cardsWrapper" @touchstart="handleTouchStart" @touchend="handleTouchEnd">
        <div class="card" v-for="article in otherArticles.slice(0, visibleCount)" :key="article.id" @click="goToArticle(article.id)">
          <div class="card-wrap-img">
            <img :src="article.image" alt="">
          </div>
          <div class="card-text">
            <span class="card-title">{{ article.title }}</span>
            <p class="card-description">{{ article.description }}</p>
            <span class="card-date">{{ article.date }}</span>
          </div>
        </div>
      </div>
      <div class="mobile-nav">
        <button class="btn arrow-left" @click="prevStep">
          <img src="../assets/arrow-left.svg" alt="" />
        </button>
        <button class="btn arrow-right" @click="nextStep">
          <img src="../assets/arrow-right.svg" alt="" />
        </button>
      </div>
      <button class="btn-more" v-if="showMoreVisible" @click="showMore">Показать ещё</button>
    </div>
  </section>
</template>

<style scoped>
.article-page {
  display: flex;
  flex-direction: column;
  gap: 70px;
  padding: 180px 40px 0 40px;
}

.header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 34px;
}

.breadcrumbs {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}

.breadcrumbs a,
.breadcrumbs span:not(:last-child) {
  color: #000000;
  font-size: 16px;
  font-weight: 600;
  text-decoration: none;
  transition: color 0.3s ease;
}

.breadcrumbs a:hover {
  color: #c96744;
}

.breadcrumbs span:last-child {
  color: #000000;
  opacity: 0.5;
  font-size: 16px;
  font-weight: 600;
}

h1 {
  font-size: 44px;
  font-weight: 400;
  color: #000000;
  text-align: center;
}

.article-content {
  display: flex;
  flex-direction: column;
  gap: 50px;
  max-width: 1200px;
  margin: 0 auto;
  width: 100%;
  box-sizing: border-box;
}

.wrap-img {
    width: 100%;
    height: 525px;
    border-radius: 4px;
    overflow: hidden;
}

.wrap-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.group-text {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

h2 {
    color: #4B4B4B;
    font-weight: 500;
    font-size: 24px;
}

p {
    color: #000;
    font-size: 20px;
    font-weight: 300;
    opacity: 0.8;
    text-align: justify;
    line-height: 1.6;
}

.other-articles {
  display: flex;
  flex-direction: column;
  gap: 30px;
}

.other-articles-title {
    color: #000;
    font-size: 44px;
    font-weight: 400;
}

.wrap-title {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.cards {
  display: flex;
  gap: 20px;
  overflow-x: auto;
  scroll-behavior: smooth;
  scrollbar-width: none;
  -ms-overflow-style: none;
  padding-bottom: 10px;
}

.cards::-webkit-scrollbar {
  display: none;
}

.card {
  min-width: calc(33.333% - 20px);
  display: flex;
  flex-direction: column;
  gap: 30px;
  transition: transform 0.3s ease;
  cursor: pointer;
  flex: 1;
}

.card:hover {
  transform: translateY(-8px);
}

.card-wrap-img {
  background-color: #fff;
  border-radius: 4px;
  width: 100%;
  overflow: hidden;
  transition: box-shadow 0.3s ease;
  position: relative;
  flex-shrink: 0;
}

.card-wrap-img::before {
  content: "";
  position: absolute;
  inset: 0;
  background: #00000080;
  pointer-events: none;
  z-index: 1;
  transition: background 0.3s ease;
}

.card:hover .card-wrap-img::before {
  background: #00000060;
}

.card:hover .card-wrap-img {
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.card-wrap-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
  position: relative;
  z-index: 0;
}

.card-text {
  display: flex;
  flex-direction: column;
  gap: 14px;
  justify-content: space-between;
  flex: 1;
}

.card-title {
  color: #4b4b4b;
  font-size: 24px;
  font-weight: 500;
}

.card-description {
  color: #000000;
  font-size: 20px;
  font-weight: 300;
  opacity: 0.8;
  margin: 0;
}

.card-date {
  color: #c96744;
  font-size: 20px;
  font-weight: 300;
  opacity: 0.8;
}

.actions {
  display: flex;
  align-items: center;
  gap: 34px;
}

.btn {
  width: 81px;
  height: 56px;
  border-radius: 44px;
  padding: 8px 20px;
  border: none;
  outline: none;
  cursor: pointer;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.btn img {
  width: 24px;
  height: 24px;
  transition: transform 0.3s ease;
}

.btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

.btn:hover img {
  transform: scale(1.1);
}

.btn:active {
  transform: translateY(0);
}

.arrow-left {
  background-color: #fff;
}

.arrow-right {
  background-color: #000000;
}

.arrow-right:hover {
  background-color: #333;
}

.btn-more {
  background-color: #c96744;
  padding: 19px 66px 18px 65px;
  border-radius: 44px;
  color: #fff;
  font-weight: 600;
  font-size: 16px;
  margin: 0 auto;
  border: none;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn-more:hover {
  background-color: #b85a3a;
}

.mobile-nav {
  display: none;
}

@media (max-width: 768px) {
  .article-page {
    padding: 110px 16px 0 16px;
    gap: 40px;
  }

  .header {
    gap: 18px;
  }

  .breadcrumbs {
    gap: 8px;
    justify-content: center;
  }

  h1 {
    font-size: 28px;
  }

  .actions {
    display: none;
  }

  .mobile-nav {
    display: flex;
    justify-content: center;
    gap: 34px;
    margin-top: 20px;
  }

  .mobile-nav .btn {
    width: 64px;
    height: 44px;
  }

  .cards {
    gap: 12px;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
  }

  .card {
    min-width: calc(100% - 40px);
    gap: 16px;
    scroll-snap-align: start;
  }

  .card-title {
    font-size: 16px;
  }

  .card-description {
    font-size: 14px;
  }

  .card-date {
    font-size: 14px;
  }

  .btn-more {
    padding: 14px 40px;
    height: 48px;
    font-size: 14px;
  }
}

@media (max-width: 480px) {
  .article-page {
    padding: 100px 12px 0 12px;
    gap: 32px;
  }

  .header {
    gap: 14px;
  }

  .breadcrumbs a,
  .breadcrumbs span {
    font-size: 12px;
  }

  h1 {
    font-size: 24px;
  }

  .mobile-nav {
    gap: 20px;
  }

  .mobile-nav .btn {
    width: 48px;
    height: 36px;
    padding: 6px 12px;
    border-radius: 24px;
  }

  .mobile-nav .btn img {
    width: 16px;
    height: 16px;
  }

  .cards {
    gap: 10px;
  }

  .card {
    min-width: calc(100% - 24px);
    gap: 12px;
  }

  .card-title {
    font-size: 14px;
  }

  .card-description {
    font-size: 12px;
  }

  .card-date {
    font-size: 12px;
  }

  .btn-more {
    padding: 12px 32px;
    height: 40px;
    font-size: 13px;
    border-radius: 30px;
  }
}

.btn-more:hover {
  background-color: #b85a3a;
}

@media (max-width: 1024px) {
  .article-page {
    padding: 140px 24px 0 24px;
    gap: 50px;
  }

  .header {
    gap: 24px;
  }

  h1 {
    font-size: 36px;
  }

  .cards {
    gap: 16px;
  }

  .card {
    min-width: calc(33.333% - 16px);
    gap: 20px;
  }

  .card-title {
    font-size: 20px;
  }

  .card-description {
    font-size: 16px;
  }

  .card-date {
    font-size: 16px;
  }

  .btn {
    width: 64px;
    height: 44px;
  }
}

@media (max-width: 768px) {
  .article-page {
    padding: 110px 16px 0 16px;
    gap: 40px;
  }

  .header {
    gap: 18px;
  }

  .breadcrumbs {
    gap: 8px;
    justify-content: center;
  }

  h1 {
    font-size: 28px;
  }

  .actions {
    display: none;
  }

  .cards {
    gap: 12px;
  }

  .card {
    min-width: calc(50% - 6px);
    gap: 20px;
  }

  .card-title {
    font-size: 16px;
  }

  .card-description {
    font-size: 14px;
  }

  .card-date {
    font-size: 14px;
  }

  .btn-more {
    padding: 14px 40px;
    height: 48px;
    font-size: 14px;
  }
}

@media (max-width: 480px) {
  .article-page {
    padding: 100px 12px 0 12px;
    gap: 32px;
  }

  .header {
    gap: 14px;
  }

  .breadcrumbs a,
  .breadcrumbs span {
    font-size: 12px;
  }

  h1 {
    font-size: 24px;
  }

  .cards {
    gap: 10px;
  }

  .card {
    min-width: calc(100% - 10px);
    gap: 12px;
  }

  .card-title {
    font-size: 14px;
  }

  .card-description {
    font-size: 12px;
  }

  .card-date {
    font-size: 12px;
  }

  .btn-more {
    padding: 12px 32px;
    height: 40px;
    font-size: 13px;
    border-radius: 30px;
  }
}
</style>