<template>
  <section class="catalog">
    <div class="wrap-title">
      <div class="breadcrumbs">
        <router-link to="/">Главная</router-link>
        <span>/</span>
        <router-link to="/catalog">Каталог</router-link>
      </div>
      <h1>Каталог</h1>
    </div>
    <div class="categories">
      <button
        v-for="cat in categories"
        :key="cat.id"
        :class="['category-btn', { active: activeCategory === cat.id }]"
        @click="activeCategory = cat.id"
      >
        {{ cat.name }}
      </button>
    </div>
    <div class="cards">
      <div class="card" v-for="item in filteredItems" :key="item.id">
        <div class="wrap-img">
          <img :src="item.image" alt="" />
        </div>
        <span class="card-title">{{ item.title }}</span>
        <span class="card-price">{{ item.price }}</span>
        <button class="card-btn">В конфигуратор модели <div class="wrap-btn-img"><img class="btn-img" :src="arrowUpRight" alt=""></div></button>
      </div>
    </div>
    <WhyUs />
    <Faq />
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import WhyUs from '../components/WhyUs.vue'
import Faq from '../components/FaqBlock.vue'
import cardImg from '../assets/card.png'
import arrowUpRight from '../assets/arrow-up-right.svg'

const route = useRoute()

const categories = [
  { id: 'all', name: 'Все' },
  { id: 'besedka', name: 'Беседки' },
  { id: 'mangal', name: 'Мангальные зоны' },
  { id: 'naves', name: 'Навесы для авто' },
]

const activeCategory = ref('all')

onMounted(() => {
  if (route.query.category) {
    activeCategory.value = route.query.category
  }
})

const allItems = [
  { id: 1, title: 'Беседка 6м2', price: 'от 126 000 ₽', image: cardImg, category: 'besedka' },
  { id: 2, title: 'Мангальная зона Стандарт', price: 'от 126 000 ₽', image: cardImg, category: 'mangal' },
  { id: 3, title: 'Навес для автомобиля 6м2', price: 'от 126 000 ₽', image: cardImg, category: 'naves' },
  { id: 4, title: 'Беседка 8м2', price: 'от 156 000 ₽', image: cardImg, category: 'besedka' },
  { id: 5, title: 'Мангальная зона Премиум', price: 'от 186 000 ₽', image: cardImg, category: 'mangal' },
  { id: 6, title: 'Навес для автомобиля 8м2', price: 'от 156 000 ₽', image: cardImg, category: 'naves' },
  { id: 7, title: 'Терраса 10м2', price: 'от 206 000 ₽', image: cardImg, category: 'terrasa' },
  { id: 8, title: 'Павильон для бассейна', price: 'от 256 000 ₽', image: cardImg, category: 'basseyn' },
  { id: 9, title: 'Навес для террасы', price: 'от 96 000 ₽', image: cardImg, category: 'naves' }
]

const filteredItems = computed(() => {
  if (activeCategory.value === 'all') return allItems
  return allItems.filter(item => item.category === activeCategory.value)
})
</script>

<style scoped>
.catalog {
  display: flex;
  flex-direction: column;
  gap: 70px;
  padding: 180px 40px 0 40px;
}

.wrap-title {
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

h1 {
  font-size: 44px;
  font-weight: 400;
  color: #000000;
}

.categories {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 24px;
}

.category-btn {
  padding: 19px 63px;
  border-radius: 44px;
  border: 1px solid #000;
  background: transparent;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
}

.category-btn:hover {
  background: #000;
  color: #fff;
}

.category-btn.active {
  background: #000;
  color: #fff;
}

.cards {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 19px;
  width: 100%;
}

.card {
  display: flex;
  flex-direction: column;
  gap: 22px;
  transition: transform 0.3s ease;
}

.card:hover {
  transform: translateY(-8px);
}

.wrap-img {
  background-color: #fff;
  border-radius: 4px;
  width: 100%;
  height: 414px;
  overflow: hidden;
  transition: box-shadow 0.3s ease;
}

.card:hover .wrap-img {
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.wrap-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.card:hover .wrap-img img {
  transform: scale(1.05);
}

.card-title {
  color: #000000;
  font-size: 24px;
  font-weight: 400;
  transition: color 0.3s ease;
}

.card:hover .card-title {
  color: #C96744;
}

.card-price {
  color: #C96744;
  font-size: 28px;
  font-weight: 400;
  line-height: 100%;
  transition: transform 0.3s ease;
}

.card:hover .card-price {
  transform: scale(1.05);
}

.card-btn {
  padding: 9px 9px 9px 45px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  background-color: #fff;
  border-radius: 44px;
  border: none;
  height: 81px;
  width: 100%;
  color: #000000;
  font-weight: 600;
  font-size: 16px;
  backdrop-filter: blur(20.899999618530273px);
  box-shadow: 0px 4px 4px 0px #00000040;
  cursor: pointer;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card-btn:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
}

.card-btn:active {
  transform: translateY(0);
}

.wrap-btn-img {
  padding: 19px 17px 16px 17px;
  background-color: #000;
  border-radius: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.3s ease, transform 0.3s ease;
}

.card-btn:hover .wrap-btn-img {
  background-color: #C96744;
  transform: rotate(15deg);
}

.center-title {
  text-align: center;
  width: 920px;
  margin: 0 auto;
}

@media (max-width: 1200px) {
  .catalog {
    gap: 56px;
    padding: 150px 32px 0 32px;
  }

  .wrap-title {
    gap: 28px;
  }

  .breadcrumbs a,
  .breadcrumbs span {
    font-size: 14px;
  }

  h1 {
    font-size: 40px;
  }

  .categories {
    gap: 20px;
  }

  .category-btn {
    padding: 16px 48px;
    font-size: 15px;
  }

  .wrap-img {
    height: 340px;
  }

  .card-title {
    font-size: 22px;
  }

  .card-price {
    font-size: 24px;
  }

  .card-btn {
    height: 72px;
    font-size: 14px;
  }

  .center-title {
    width: 720px;
    font-size: 36px;
  }
}

@media (max-width: 1024px) {
  .catalog {
    gap: 48px;
    padding: 130px 24px 0 24px;
  }

  .wrap-title {
    gap: 24px;
  }

  .breadcrumbs a,
  .breadcrumbs span {
    font-size: 14px;
  }

  h1 {
    font-size: 36px;
  }

  .categories {
    gap: 16px;
  }

  .category-btn {
    padding: 14px 36px;
    font-size: 14px;
  }

  .cards {
    gap: 16px;
  }

  .wrap-img {
    height: 280px;
  }

  .card {
    gap: 16px;
  }

  .card-title {
    font-size: 18px;
  }

  .card-price {
    font-size: 22px;
  }

  .card-btn {
    padding: 8px 8px 8px 32px;
    height: 64px;
    font-size: 13px;
  }

  .wrap-btn-img {
    padding: 14px 12px 12px 12px;
  }

  .center-title {
    width: 600px;
    font-size: 32px;
  }
}

@media (max-width: 768px) {
  .catalog {
    gap: 40px;
    padding: 90px 16px 0 16px;
  }

  h1 {
    font-size: 28px;
  }

  .breadcrumbs a,
  .breadcrumbs span {
    font-size: 13px;
  }

  .categories {
    flex-wrap: nowrap;
    overflow-x: auto;
    overflow-y: hidden;
    justify-content: flex-start;
    padding: 0 0 10px 0;
    gap: 12px;
    -ms-overflow-style: none;
    scrollbar-width: none;
  }

  .categories::-webkit-scrollbar {
    display: none;
  }

  .category-btn {
    flex-shrink: 0;
    padding: 12px 24px;
    font-size: 14px;
  }

  .cards {
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
  }

  .wrap-img {
    height: 240px;
  }

  .card-price {
    font-size: 20px;
  }

  .card-btn {
    height: 56px;
  }

  .center-title {
    width: 100%;
    font-size: 28px;
  }
}

@media (max-width: 480px) {
  .catalog {
    gap: 32px;
    padding: 100px 12px 0 12px;
  }

  h1 {
    font-size: 24px;
  }

  .breadcrumbs a,
  .breadcrumbs span {
    font-size: 12px;
  }

  .categories {
    gap: 10px;
  }

  .category-btn {
    padding: 10px 20px;
    font-size: 13px;
  }

  .cards {
    grid-template-columns: 1fr;
    gap: 24px;
  }

  .wrap-img {
    height: 200px;
  }

  .card-title {
    font-size: 16px;
  }

  .card-price {
    font-size: 18px;
  }

  .card-btn {
    padding: 6px 6px 6px 20px;
    height: 48px;
    font-size: 11px;
  }

  .wrap-btn-img {
    padding: 10px 8px 8px 8px;
  }

  .btn-img {
    width: 16px;
    height: 16px;
  }

  .center-title {
    font-size: 22px;
  }
}
</style>
