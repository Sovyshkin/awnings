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
        @click="setCategory(cat.id)"
      >
        {{ cat.name }}
      </button>
    </div>
    <div class="cards" v-if="!loading">
      <div class="card" v-for="item in products" :key="item.id" @click="openProductModal(item)">
        <div class="wrap-img">
          <img v-if="getProductImage(item)" :src="getProductImage(item)" alt="" />
          <img v-else :src="cardImg" alt="" />
        </div>
        <span class="card-title">{{ item.title }}</span>
        <span class="card-price">{{ item.price || 'Цена по запросу' }}</span>
        <button class="card-btn" @click.stop="openLeadForm(item)">
          В конфигуратор модели 
          <div class="wrap-btn-img">
            <img class="btn-img" :src="arrowUpRight" alt="">
          </div>
        </button>
      </div>
      <div v-if="products.length === 0 && !loading" class="no-products">
        Товары не найдены
      </div>
    </div>
    <div v-if="loading" class="loading">
      Загрузка...
    </div>
    <WhyUs />
    <Faq />
    
    <!-- Lead Form Modal -->
    <div v-if="showLeadForm" class="modal-overlay" @click.self="closeLeadForm">
      <div class="modal-content">
        <div class="modal-header">
          <h2>Оставить заявку</h2>
          <button class="modal-close" @click="closeLeadForm">×</button>
        </div>
        <form @submit.prevent="submitLeadForm">
          <div class="form-group">
            <label for="lead-name">Ваше имя</label>
            <input type="text" id="lead-name" v-model="leadForm.name" placeholder="Введите ваше имя" required>
          </div>
          <div class="form-group">
            <label for="lead-phone">Ваш телефон</label>
            <input type="tel" id="lead-phone" v-model="leadForm.phone" placeholder="+7 (___) ___-__-__" required>
          </div>
          <div class="form-group">
            <label for="lead-message">Сообщение</label>
            <textarea id="lead-message" v-model="leadForm.message" placeholder="Ваш вопрос"></textarea>
          </div>
          <div class="form-checkbox">
            <input type="checkbox" id="lead-agree" v-model="leadForm.agree" required>
            <label for="lead-agree">Я даю согласие на обработку персональных данных</label>
          </div>
          <button type="submit" class="btn" :disabled="submitting">
            {{ submitting ? 'Отправка...' : 'Отправить' }}
          </button>
        </form>
        <div v-if="formMessage" :class="['form-notice', formMessageType]">
          {{ formMessage }}
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import WhyUs from '../components/WhyUs.vue'
import Faq from '../components/FaqBlock.vue'
import cardImg from '../assets/card.png'
import arrowUpRight from '../assets/arrow-up-right.svg'
import { fetchProducts, fetchCategories, submitLead } from '../services/api'

const route = useRoute()

const categories = ref([
  { id: 'all', name: 'Все' },
  { id: 'besedka', name: 'Беседки' },
  { id: 'mangal', name: 'Мангальные зоны' },
  { id: 'naves', name: 'Навесы для авто' },
])

const activeCategory = ref('all')
const products = ref([])
const loading = ref(false)

// Lead form state
const showLeadForm = ref(false)
const selectedProduct = ref(null)
const leadForm = ref({
  name: '',
  phone: '',
  message: '',
  agree: false
})
const submitting = ref(false)
const formMessage = ref('')
const formMessageType = ref('')

// Load categories from API (with "Все" option)
const loadCategories = async () => {
  try {
    const apiCategories = await fetchCategories()
    if (apiCategories && apiCategories.length > 0) {
      // Add "Все" as first option
      categories.value = [
        { id: '', name: 'Все', slug: '' },
        ...apiCategories.map(cat => ({
          id: cat.id,
          name: cat.name,
          slug: cat.slug
        }))
      ]
      console.log('Categories loaded:', categories.value)
    }
  } catch (error) {
    console.log('Using local categories')
  }
}

// Load products from API
const loadProducts = async (categorySlug = '') => {
  loading.value = true
  try {
    products.value = await fetchProducts(categorySlug)
    console.log('Products loaded:', products.value.length)
  } catch (error) {
    console.error('Error loading products:', error)
    products.value = []
  } finally {
    loading.value = false
  }
}

// Set category and reload products
const setCategory = (categoryId) => {
  const cat = categories.value.find(c => c.id === categoryId)
  activeCategory.value = categoryId
  loadProducts(cat?.slug || '')
}

// Open lead form for a product
const openLeadForm = (product) => {
  selectedProduct.value = product
  leadForm.value = {
    name: '',
    phone: '',
    message: '',
    agree: false
  }
  formMessage.value = ''
  showLeadForm.value = true
}

// Close lead form
const closeLeadForm = () => {
  showLeadForm.value = false
  selectedProduct.value = null
}

// Submit lead form
const submitLeadForm = async () => {
  if (!leadForm.value.agree) {
    formMessage.value = 'Необходимо согласиться на обработку персональных данных'
    formMessageType.value = 'error'
    return
  }
  
  submitting.value = true
  formMessage.value = ''
  
  try {
    const response = await submitLead({
      name: leadForm.value.name,
      phone: leadForm.value.phone,
      message: leadForm.value.message,
      product_id: selectedProduct.value?.id || 0,
      agree: true
    })
    
    formMessage.value = response.message || 'Заявка успешно отправлена!'
    formMessageType.value = 'success'
    
    // Reset form and close after success
    setTimeout(() => {
      closeLeadForm()
    }, 2000)
    
  } catch (error) {
    formMessage.value = error.message || 'Ошибка отправки заявки'
    formMessageType.value = 'error'
  } finally {
    submitting.value = false
  }
}

// Open product modal (for future use with product details page)
const openProductModal = (product) => {
  // Can be extended to show product details
  console.log('Product clicked:', product)
}

// Get product image (handles both array and single image)
const getProductImage = (product) => {
  if (!product.image_url) return null
  if (Array.isArray(product.image_url)) {
    return product.image_url[0] || null
  }
  return product.image_url
}

onMounted(async () => {
  // Check for category in route query
  if (route.query.category) {
    activeCategory.value = route.query.category
  }
  
  // Load data - start with all products (empty category slug)
  await loadCategories()
  await loadProducts('')
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
  cursor: pointer;
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

.loading, .no-products {
  text-align: center;
  font-size: 20px;
  color: #666;
  padding: 40px;
}

/* Modal styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: #fff;
  padding: 40px;
  border-radius: 8px;
  max-width: 500px;
  width: 90%;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
}

.modal-header h2 {
  font-size: 28px;
  font-weight: 400;
}

.modal-close {
  background: none;
  border: none;
  font-size: 32px;
  cursor: pointer;
  color: #000;
}

.modal-close:hover {
  color: #C96744;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  font-size: 16px;
  font-weight: 500;
  margin-bottom: 8px;
  color: #4B4B4B;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 12px;
  border: none;
  border-bottom: 1px solid #000;
  font-size: 16px;
  background: transparent;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-bottom-color: #C96744;
}

.form-group textarea {
  resize: vertical;
  min-height: 80px;
}

.form-checkbox {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  margin: 20px 0;
}

.form-checkbox input {
  width: 24px;
  height: 24px;
  flex-shrink: 0;
  margin-top: 2px;
}

.form-checkbox label {
  font-size: 14px;
  color: rgba(0, 0, 0, 0.6);
  line-height: 1.4;
}

.btn {
  background-color: #C96744;
  color: #fff;
  padding: 19px 66px;
  border: none;
  border-radius: 44px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  width: 100%;
  transition: background 0.3s ease;
}

.btn:hover:not(:disabled) {
  background: #b55a3a;
}

.btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.form-notice {
  margin-top: 20px;
  padding: 12px;
  border-radius: 4px;
  text-align: center;
}

.form-notice.success {
  background: #d4edda;
  color: #155724;
}

.form-notice.error {
  background: #f8d7da;
  color: #721c24;
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
  
  .modal-content {
    padding: 24px;
  }
  
  .modal-header h2 {
    font-size: 24px;
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