<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const params = [
  { name: 'Ширина', value: '6 м.' },
  { name: 'Длина', value: '6 м.' },
  { name: 'Скат крыши (ферма)', value: 'Односкатная' },
  { name: 'Стеновой комплект', value: 'Клееный брус' },
  { name: 'Материал кровли', value: 'ПВХ мембрана' },
  { name: 'Столбы (мм.)', value: '150x150 мм.' },
  { name: 'Стропильная система (мм.)', value: '190x40 мм.' },
  { name: 'Хозблок', value: 'Нет' },
  { name: 'Отделка стен', value: 'Имитация бруса лиственница' },
  { name: 'Тип остекления', value: 'Холодное' },
  { name: 'Остекление', value: 'ПВХ Slidors' },
  { name: 'Терраса', value: 'Нет' },
  { name: 'Стандартная высота', value: '2.5 м.' },
  { name: 'Название модели', value: 'Деревянная угловая беседка №62' },
  { name: 'Окрас конструкции', value: 'Все элементы конструкции окрашиваются в 2 слоя с промежуточной и финишной шлифовкой. Стандартная краска Teknos Akrylin (цвет на выбор) по каталогу RAL Classic – более 1000 цветов. Масло или другие производители красок – рассчитываются индивидуально.' }
];

const card = computed(() => {
  const id = route.params.id
  return {
    title: 'Беседка 6м2',
    params: params,
    data: [{ name: 'Основа', items: [{name: 'Пакет база', desc: '', price: 0}, {name: 'Пакет Основа +', desc: 'Усиленный каркас', price: 35000} ] },
    {name: 'Кровля', items: [{name: 'Пакет Небо', desc: 'Базовая кровля', price: 49000} ] },
    {name: 'Контур', items: [{name: 'Пакет Уют', desc: 'Пол + полная обшивка', price: 99000} ] },
    {name: 'Мангальная зона', items: [{name: 'Пакет Огонь', desc: '', price: 119000} ] },
    {name: 'Мебель', items: [{name: 'Пакет', desc: 'Стол + 2 скамьи + 2 стула', price: 69000} ] }
  ],
  price: 189000
  }
})

</script>

<template>
  <section class="card-page">
    <div class="header">
      <div class="breadcrumbs">
        <router-link to="/">Главная</router-link>
        <span>/</span>
        <router-link to="/catalog">Каталог</router-link>
        <span>/</span>
        <span>{{ card.title }}</span>
      </div>
      <h1>{{ card.title }}</h1>
    </div>
    <main class="card-content">
        <div class="wrap-card">
            <div class="wrap-images">
                <div class="main-image">
                    <img src="../assets/card-page-1.png" alt="">
                </div>
                <div class="other-images">
                    <img src="../assets/card-page-2.png" alt="">
                    <img src="../assets/card-page-3.png" alt="">
                    <img src="../assets/card-page-4.png" alt="">
                </div>
            </div>
            <div class="card-info">
                <h2>Конфигуратор конструкции</h2>
                <div class="group" v-for="item in card.data">
                    <h3>{{item.name}}</h3>
                    <div class="item-group" v-for="i in item.items">
                        <div class="item-name">
                            <span class="name">{{ i.name }}</span>
                            <span class="desc">{{ i.desc }}</span>
                        </div>
                        <div class="item-price">
                            <span class="price">+ {{ i.price }} ₽</span>
                            <input type="radio">
                        </div>
                    </div>
                </div>
                <div class="card-price">
                    <span class="price">{{card.price}} ₽</span>
                    <button class="btn">Заказать</button>
                </div>
            </div>
        </div>
    </main>
    </section>
</template>

<style scoped>
.card-page {
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

.card-content {
    display: flex;
    flex-direction: column;
    gap: 40px;
}

.wrap-card {
    display: flex;
    gap: 20px;
}

.wrap-images {
    width: 60%;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.main-image {
    width: 100%;
    height: 694px;
}

.main-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 4px;
}

.other-images {
    display: flex;
    gap: 20px;
}

.other-images img {
    width: 33%;
    height: 203px;
    border-radius: 4px;
}

.card-info {
    width: 40%;
    background-color: #E2E2E2;
    border-radius: 4px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.group {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

h2 {
    color: #000000;
    font-size: 24px;
    font-weight: 600;
}

h3 {
    color: #000000;
    font-size: 16px;
    font-weight: 600;
}

.item-group {
    width: 100%;
    background-color: #fff;
    border-radius: 4px;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.item-name {
    display: flex;
    align-items: center;
    gap: 50px;
}

.item-price {
    display: flex;
    align-items: center;
    gap: 20px;
}

.name {
    color: #000000;
    font-size: 16px;
    font-weight: 400;
}

.desc {
    color: #C6C6C6;
    font-size: 16px;
    font-weight: 400;
}

.price {
    color: #000000;
    font-size: 16px;
    font-weight: 600;
}

input {
    border: 3px solid #D9D9D9;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    accent-color: #C96744;
}

.card-price {
    padding-top: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.card-price .price {
    color: #000;
    font-size: 52px;
    font-weight: 600;
}

.btn {
    width: 210px;
    padding: 20px 60px;
    border-radius: 44px;
    border: none;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    color: #fff;
    background-color: #C96744;
}
</style>
