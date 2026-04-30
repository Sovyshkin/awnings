<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const visibleCount = ref(9)
const totalCards = 20

function showMore() {
  visibleCount.value += 9
}

function goToArticle(id) {
  router.push(`/news-articles/${id}`)
}
</script>

<template>
  <section class="news-articles">
    <div class="header">
      <div class="breadcrumbs">
        <router-link to="/">Главная</router-link>
        <span>/</span>
        <router-link to="/news-articles">Новости и статьи</router-link>
      </div>
      <h1>Новости и статьи</h1>
      <p>
        Полезная информация о навесах, материалах и уходе
      </p>
    </div>
    <div class="cards">
      <div class="card" v-for="n in visibleCount" :key="n" @click="goToArticle(n)">
        <div class="wrap-img">
          <img src="../assets/company-card-1.png" alt="">
        </div>
        <div class="card-text">
          <span class="card-title">Уход за металлическим навесом: советы по обслуживанию</span>
          <p class="card-description">
            Регулярный уход продлевает жизнь навеса. Узнайте, как правильно чистить и обслуживать конструкцию.
          </p>
          <span class="card-date">15 марта 2025 г.</span>
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
  display: flex;
  flex-wrap: wrap;
  row-gap: 50px;
  column-gap: 20px;
}

.card {
    width: calc(33.333% - 20px);
    display: flex;
    flex-direction: column;
    gap: 30px;
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-8px);
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
    gap: 14px;
}

.card-title {
    color: #4B4B4B;
    font-size: 24px;
    font-weight: 500;
}

.card-desc {
    color: #000000;
    font-size: 20px;
    font-weight: 300;
    opacity: 0.8;
}

.card-date {
    color: #C96744;
    font-size: 20px;
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
        gap: 16px;
    }

    .card {
        width: calc(33.333% - 16px);
        gap: 20px;
    }

    .wrap-img {
        height: 200px;
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
        flex-direction: column;
        gap: 12px;
    }

    .card {
        flex: none;
        width: 100%;
        min-width: 100%;
        flex-direction: row;
        align-items: stretch;
        gap: 16px;
    }

    .wrap-img {
        width: 140px;
        height: auto;
        flex-shrink: 0;
    }

    .card-text {
        gap: 10px;
        justify-content: center;
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
        gap: 10px;
    }

    .card {
        gap: 12px;
        align-items: stretch;
    }

    .wrap-img {
        width: 100px;
        height: auto;
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

    .btn {
        padding: 12px 32px;
        height: 40px;
        font-size: 13px;
        border-radius: 30px;
    }
}
</style>