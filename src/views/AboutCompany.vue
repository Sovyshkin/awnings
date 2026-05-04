<template>
  <section class="about-company-page">
    <div class="header">
      <div class="breadcrumbs">
        <router-link to="/">Главная</router-link>
        <span>/</span>
        <router-link to="/about-company">О компании</router-link>
      </div>
      <div class="wrap-title">
        <h1>О компании</h1>
        <p class="subtite">Мы производим и устанавливаем навесы с 2010 года. За это время мы реализовали более 2500 проектов по всей России и заслужили доверие тысяч клиентов.</p>
      </div>
    </div>
    <CompanyBlock style="margin-bottom: 50px;"/>
    <OurHistory />
    <WhyUs :main="false" />
    <CompanyProduction />
    <CompanyOurWorks />
    <DeliveryRegions />
  </section>
</template>
<script setup>
import CompanyBlock from '../components/CompanyBlock.vue'
import OurHistory from '../components/OurHistory.vue'
import WhyUs from '../components/CompanyWhyUs.vue'
import CompanyProduction from '../components/CompanyProduction.vue'
import CompanyOurWorks from '../components/CompanyOurWorks.vue'
import DeliveryRegions from '../components/DeliveryRegions.vue'
</script>
<style scoped>
.about-company-page {
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

.wrap-title {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 20px;
  max-width: 847px;

}

h1 {
  font-size: 44px;
  font-weight: 400;
  color: #000000;
  text-align: center;
}

.subtite {
  font-size: 20px;
  font-weight: 300;
  color: #000000;
  text-align: center;
  opacity: 0.8;
}
@media (max-width: 1200px) {
  .about-company-page {
    gap: 56px;
    padding: 150px 32px 0 32px;
  }

  h1 {
    font-size: 40px;
  }

  .subtite {
    font-size: 18px;
  }
}

@media (max-width: 1024px) {
  .about-company-page {
    gap: 48px;
    padding: 130px 24px 0 24px;
  }

  h1 {
    font-size: 36px;
  }

  .subtite {
    font-size: 16px;
  }

  .wrap-title {
    max-width: 720px;
  }
}

@media (max-width: 768px) {
  .about-company-page {
    gap: 40px;
    padding: 90px 16px 0 16px;
  }

  h1 {
    font-size: 28px;
  }

  .subtite {
    font-size: 14px;
  }

  .breadcrumbs a,
  .breadcrumbs span {
    font-size: 13px;
  }

  .wrap-title {
    gap: 16px;
    max-width: 100%;
  }
}

@media (max-width: 480px) {
  .about-company-page {
    gap: 32px;
    padding: 80px 12px 0 12px;
  }

  h1 {
    font-size: 24px;
  }

  .subtite {
    font-size: 12px;
  }

  .breadcrumbs a,
  .breadcrumbs span {
    font-size: 12px;
  }
}
</style>