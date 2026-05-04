import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import CatalogView from '../views/CatalogView.vue'
import GarantView from '../views/GarantView.vue'
import DeliveryPayment from '../views/DeliveryPayment.vue'
import NewsArticles from '../views/NewsArticles.vue'
import ArticlePage from '../views/ArticlePage.vue'
import ContactPage from '../views/ContactPage.vue'
import AboutCompany from '../views/AboutCompany.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', name: 'home', component: HomeView },
    { path: '/catalog', name: 'catalog', component: CatalogView },
    { path: '/garant', name: 'garant', component: GarantView },
    { path: '/delivery-and-payment', name: 'delivery-payment', component: DeliveryPayment },
    { path: '/news-articles', name: 'news-articles', component: NewsArticles },
    { path: '/news-articles/:id', name: 'article', component: ArticlePage },
    { path: '/contacts', name: 'contacts', component: ContactPage },
    { path: '/about-company', name: 'about-company', component: AboutCompany },
  ]
})

router.afterEach(() => {
  window.scrollTo({ top: 0, behavior: 'smooth' })
})

export default router
