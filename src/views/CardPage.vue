<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const images = ref([
  { id: 1, src: '/src/assets/card-page-1.png', alt: 'Изображение 1' },
  { id: 2, src: '/src/assets/card-page-2.png', alt: 'Изображение 2' },
  { id: 3, src: '/src/assets/card-page-3.png', alt: 'Изображение 3' },
  { id: 4, src: '/src/assets/card-page-4.png', alt: 'Изображение 4' }
])

const currentImageIndex = ref(0)
const currentImage = computed(() => images.value[currentImageIndex.value])
const isFullscreen = ref(false)

const selectImage = (index) => {
  currentImageIndex.value = index
}

const nextImage = () => {
  currentImageIndex.value = (currentImageIndex.value + 1) % images.value.length
}

const prevImage = () => {
  currentImageIndex.value = (currentImageIndex.value - 1 + images.value.length) % images.value.length
}

const openFullscreen = () => {
  isFullscreen.value = true
  document.body.style.overflow = 'hidden'
}

const closeFullscreen = () => {
  isFullscreen.value = false
  document.body.style.overflow = ''
}

const handleKeydown = (e) => {
  if (e.key === 'Escape' && isFullscreen.value) {
    closeFullscreen()
  } else if (e.key === 'ArrowRight' && isFullscreen.value) {
    nextImage()
  } else if (e.key === 'ArrowLeft' && isFullscreen.value) {
    prevImage()
  }
}

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

// Track selected options per group - null means no selection, string means selected item name
const selectedOptions = ref({
  'Основа': 'Пакет база',
  'Кровля': null,  // null for single-item groups means not selected
  'Контур': null,
  'Мангальная зона': null,
  'Мебель': null
})

const toggleOption = (groupName, itemName, itemPrice) => {
  const group = card.value.data.find(g => g.name === groupName)
  if (!group) return
  
  const hasBaseOption = group.items.some(i => i.price === 0)
  
  // If clicking on already selected option
  if (selectedOptions.value[groupName] === itemName) {
    if (itemPrice > 0) {
      // For paid options - switch to base option (price: 0) if exists, otherwise deselect
      if (hasBaseOption) {
        const baseOption = group.items.find(i => i.price === 0)
        if (baseOption) {
          selectedOptions.value[groupName] = baseOption.name
        }
      } else {
        // No base option - deselect
        selectedOptions.value[groupName] = null
      }
    } else {
      // For base options with price 0 - deselect
      selectedOptions.value[groupName] = null
    }
  } else {
    // Select this option
    selectedOptions.value[groupName] = itemName
  }
  calculateTotalPrice()
}

const isOptionSelected = (groupName, itemName) => {
  return selectedOptions.value[groupName] === itemName
}

const hasSelection = (groupName) => {
  return selectedOptions.value[groupName] !== null
}

// Calculate total price based on selected options
const totalPrice = ref(189000)

const calculateTotalPrice = () => {
  const basePrice = 189000
  let additionalPrice = 0
  
  for (const group of card.value.data) {
    const selectedItemName = selectedOptions.value[group.name]
    const selectedItem = group.items.find(i => i.name === selectedItemName)
    if (selectedItem && selectedItem.price > 0) {
      additionalPrice += selectedItem.price
    }
  }
  
  totalPrice.value = basePrice + additionalPrice
}

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
  <section class="card-page" @keydown="handleKeydown" tabindex="0">
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
                <div class="main-image" @click="openFullscreen">
                    <img :src="currentImage.src" :alt="currentImage.alt">
                    <div class="fullscreen-hint">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15 3H21V9M21 3L14 10M9 21H3V15M3 21L10 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
                <div class="other-images">
                    <div 
                        v-for="(image, index) in images" 
                        :key="image.id"
                        class="thumbnail"
                        :class="{ active: currentImageIndex === index }"
                        @click="selectImage(index)"
                    >
                        <img :src="image.src" :alt="image.alt">
                    </div>
                </div>
            </div>
            <div class="card-info">
                <h2>Конфигуратор конструкции</h2>
                <div class="group" v-for="item in card.data">
                    <h3>{{item.name}}</h3>
                    <div class="item-group" 
                         v-for="i in item.items" 
                         :key="i.name"
                         :class="{ 'selected': isOptionSelected(item.name, i.name) }"
                         @click="toggleOption(item.name, i.name, i.price)">
                        <div class="item-name">
                            <span class="name">{{ i.name }}</span>
                            <span class="desc">{{ i.desc }}</span>
                        </div>
                        <div class="item-price">
                            <span class="price" v-if="i.price > 0">+ {{ i.price.toLocaleString() }} ₽</span>
                            <span class="price" v-else>+ 0 ₽</span>
                            <div class="radio-circle" :class="{ 'active': isOptionSelected(item.name, i.name) }">
                                <div class="radio-dot" v-if="isOptionSelected(item.name, i.name)"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-price">
                    <span class="price">{{ totalPrice.toLocaleString() }} ₽</span>
                    <button class="btn">Заказать</button>
                </div>
            </div>
        </div>
        <div class="card-params">
            
        </div>
    </main>

    <!-- Fullscreen Modal -->
    <Teleport to="body">
        <div v-if="isFullscreen" class="fullscreen-overlay" @click.self="closeFullscreen">
            <button class="fullscreen-close" @click="closeFullscreen">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
            <button class="fullscreen-nav prev" @click="prevImage" v-if="images.length > 1">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <div class="fullscreen-image-container">
                <img :src="currentImage.src" :alt="currentImage.alt">
            </div>
            <button class="fullscreen-nav next" @click="nextImage" v-if="images.length > 1">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 6L15 12L9 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <div class="fullscreen-counter">{{ currentImageIndex + 1 }} / {{ images.length }}</div>
        </div>
    </Teleport>
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
    align-items: stretch;
}

.wrap-images {
    width: 60%;
    display: flex;
    flex-direction: column;
    gap: 20px;
    flex: 1;
}

.card-info {
    width: 40%;
    background-color: #E2E2E2;
    border-radius: 4px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    flex: 1;
}

.main-image {
    width: 100%;
    flex: 1;
    min-height: 400px;
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

/* Thumbnail styles */
.thumbnail {
    width: 33%;
    height: 203px;
    cursor: pointer;
    border-radius: 4px;
    overflow: hidden;
    border: 3px solid transparent;
    transition: border-color 0.3s ease, opacity 0.3s ease;
    opacity: 0.7;
}

.thumbnail:hover {
    opacity: 1;
}

.thumbnail.active {
    border-color: #C96744;
    opacity: 1;
}

.thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Main image fullscreen hint */
.main-image {
    position: relative;
    cursor: pointer;
}

.main-image:hover .fullscreen-hint {
    opacity: 1;
}

.fullscreen-hint {
    position: absolute;
    bottom: 20px;
    right: 20px;
    width: 50px;
    height: 50px;
    background-color: rgba(0, 0, 0, 0.6);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.fullscreen-hint svg {
    color: #fff;
}

/* Fullscreen overlay styles */
.fullscreen-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.95);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.fullscreen-close {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 50px;
    height: 50px;
    background-color: rgba(255, 255, 255, 0.1);
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.3s ease;
    z-index: 10001;
}

.fullscreen-close:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

.fullscreen-close svg {
    color: #fff;
}

.fullscreen-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 60px;
    height: 60px;
    background-color: rgba(255, 255, 255, 0.1);
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.3s ease;
    z-index: 10001;
}

.fullscreen-nav:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

.fullscreen-nav svg {
    color: #fff;
}

.fullscreen-nav.prev {
    left: 20px;
}

.fullscreen-nav.next {
    right: 20px;
}

.fullscreen-image-container {
    max-width: 90vw;
    max-height: 90vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.fullscreen-image-container img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    border-radius: 4px;
}

.fullscreen-counter {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    color: #fff;
    font-size: 16px;
    font-weight: 500;
    background-color: rgba(0, 0, 0, 0.5);
    padding: 8px 20px;
    border-radius: 20px;
}

/* Item group selected state */
.item-group {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

/* Radio circle styles */
.radio-circle {
    width: 36px;
    height: 36px;
    border: 3px solid #D9D9D9;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.radio-circle.active {
    border-color: #C96744;
}

.radio-dot {
    width: 16px;
    height: 16px;
    background-color: #C96744;
    border-radius: 50%;
    animation: scaleIn 0.2s ease;
}

@keyframes scaleIn {
    from {
        transform: scale(0);
    }
    to {
        transform: scale(1);
    }
}

/* Price styles */
.price.included {
    color: #888888;
    font-weight: 400;
}
</style>
