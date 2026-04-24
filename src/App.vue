<template>
  <div class="app">
    <AppHeader />
    <HeroSection />
    <WhatDoing />
    <HowWork />
    <CompanyNumbers />
    <OurProjects />
    <WhyUs />
    <Faq />
    <AppFooter />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import AppHeader from './components/AppHeader.vue'
import HeroSection from './components/HeroSection.vue'
import WhatDoing from './components/WhatDoing.vue'
import HowWork from './components/HowWork.vue'
import CompanyNumbers from './components/CompanyNumbers.vue'
import OurProjects from './components/OurProjects.vue'
import WhyUs from './components/WhyUs.vue'
import Faq from './components/FaqBlock.vue'
import AppFooter from './components/AppFooter.vue'

const menuOpen = ref(false)
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Inter', sans-serif;
}

body {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
  background-color: #E9E9E9;
  overflow-x: hidden;
}

.app {
  min-height: 100vh;
  width: 100%;
  overflow-x: hidden;
  display: flex;
  flex-direction: column;
  gap: 70px;
}

@media (max-width: 1200px) {
  .app {
    gap: 56px;
  }
}

@media (max-width: 1024px) {
  .app {
    gap: 48px;
  }
}

@media (max-width: 768px) {
  .app {
    gap: 40px;
  }
}

@media (max-width: 480px) {
  .app {
    gap: 32px;
  }
}
</style>
