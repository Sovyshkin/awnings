<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { fetchContentBlocks } from '../services/api'
import defaultImage from '../assets/company-card-1.png'

const router = useRouter()
const visibleCount = ref(9)
const totalCards = ref(0)
const pageTitle = ref('Новости и статьи')
const pageSubtitle = ref('Полезная информация о навесах, материалах и уходе')
const cards = ref([])

function resolveImage(path) {
  if (!path) return defaultImage
  if (path.startsWith('http') || path.startsWith('/')) return path
  if (path.startsWith('wp-content/')) return `/${path}`
  return `/wp-content/themes/wp-awnings/assets/${path.split('/').pop()}`
}

function showMore() {
  visibleCount.value += 9
}

function goToArticle(id) {
  router.push(`/news-articles/${id}`)
}

async function loadNews() {
  const blocks = await fetchContentBlocks('news')
  const header = blocks.find(b => (b.block_name || '').includes('Заголовок')) || blocks.find(b => b.block_type === 'section')
  if (header) {
    if (header.block_title) pageTitle.value = header.block_title
    if (header.block_text) pageSubtitle.value = header.block_text
  }

  const cardsBlock = blocks.find(b => b.block_type === 'news-cards') || blocks.find(b => (b.block_name || '').includes('Карточки'))
  if (cardsBlock && cardsBlock.block_data) {
    let data = cardsBlock.block_data
    if (typeof data === 'string') {
      try { data = JSON.parse(data) } catch (e) { data = [] }
    }
    if (Array.isArray(data)) {
      cards.value = data.map((item, idx) => ({
        id: idx + 1,
        title: item.title || '',
        description: item.description || '',
        date: item.date || '',
        image: resolveImage(item.image),
        link: item.link || `/news-articles/${idx + 1}`
      }))
      totalCards.value = cards.value.length
    }
  }
}

onMounted(() => {
  loadNews()
})
</script>

<template>
  <section class="news-articles">
    <div class="header">
      <div class="breadcrumbs">
        <router-link to="/">Главная</router-link>
        <span>/</span>
        <router-link to="/news-articles">Новости и статьи</router-link>
      </div>
      <h1>{{ pageTitle }}</h1>
      <p>{{ pageSubtitle }}</p>
    </div>
    <div class="cards">
      <div class="card" v-for="item in cards.slice(0, visibleCount)" :key="item.id" @click="goToArticle(item.id)">
        <div class="wrap-img">
          <img :src="item.image" alt="">
        </div>
        <div class="card-text">
          <span class="card-title">{{ item.title }}</span>
          <p class="card-description">{{ item.description }}</p>
          <span class="card-date">{{ item.date }}</span>
        </div>
      </div>
    </div>
    <button class="btn" v-if="visibleCount < totalCards" @click="showMore">Показать больше</button>
  </section>
</template>
<style scoped>
.news-articles {
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


.cards {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 28px 20px;
}

.card {
    display: flex;
    flex-direction: column;
    gap: 18px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
}

.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.1);
}

.wrap-img {
    background-color: #fff;
    border-radius: 4px;
    width: 100%;
    height: 257px;
    overflow: hidden;
    transition: box-shadow 0.3s ease;
    position: relative;
}

.wrap-img::before {
    content: '';
    position: absolute;
    inset: 0;
    background: #00000080;
    pointer-events: none;
    z-index: 1;
    transition: background 0.3s ease;
}

.card:hover .wrap-img::before {
    background: #00000060;
}

.card:hover .wrap-img {
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.wrap-img img {
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
    gap: 10px;
}

.card-title {
    color: #4B4B4B;
    font-size: 22px;
    font-weight: 500;
    line-height: 1.2;
}

.card-description {
    color: #000000;
    font-size: 18px;
    font-weight: 300;
    opacity: 0.8;
    line-height: 1.45;
}

.card-date {
    color: #C96744;
    font-size: 16px;
    font-weight: 300;
    opacity: 0.8;
}

.btn {
    background-color: #C96744;
    padding: 19px 66px 18px 65px;
    border-radius: 44px;
    color: #fff;
    font-weight: 600;
    font-size: 16px;
    margin: 0 auto;
    border: none;
    cursor: pointer;
}

@media (max-width: 1024px) {
    .news-articles {
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
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px 16px;
    }

    .wrap-img {
        height: 220px;
    }

    .card-title {
        font-size: 20px;
    }

    .card-description {
        font-size: 16px;
        line-height: 1.4;
    }

    .card-date {
        font-size: 14px;
    }
}

@media (max-width: 768px) {
    .news-articles {
        padding: 110px 16px 0 16px;
        gap: 40px;
    }

    .header {
        gap: 18px;
    }

    .breadcrumbs {
        gap: 8px;
    }

    h1 {
        font-size: 28px;
    }

    .cards {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .card {
        gap: 14px;
    }

    .wrap-img {
        width: 100%;
        height: 210px;
    }

    .card-title {
        font-size: 18px;
    }

    .card-description {
        font-size: 14px;
        line-height: 1.4;
    }

    .card-date {
        font-size: 13px;
    }

    .btn {
        padding: 14px 40px;
        height: 48px;
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .news-articles {
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
        gap: 14px;
    }

    .card {
        gap: 12px;
    }

    .wrap-img {
        width: 100%;
        height: 180px;
    }

    .card-title {
        font-size: 16px;
    }

    .card-description {
        font-size: 13px;
        line-height: 1.35;
    }

    .card-date {
        font-size: 12px;
    }

    .btn {
        padding: 12px 32px;
        height: 40px;
        font-size: 13px;
        border-radius: 30px;
    }
}
</style>
