<template>
  <div class="home">
    <HeroSection />
    <WhatDoing />
    <HowWork :isMobile="isMobile" />
    <CompanyNumbers />
    <OurProjects />
    <WhyUs />
    <Faq />
  </div>
</template>

<script setup>
import HeroSection from '../components/HeroSection.vue'
import WhatDoing from '../components/WhatDoing.vue'
import HowWork from '../components/HowWork.vue'
import CompanyNumbers from '../components/CompanyNumbers.vue'
import OurProjects from '../components/OurProjects.vue'
import WhyUs from '../components/WhyUs.vue'
import Faq from '../components/FaqBlock.vue'
import { ref, onMounted, onUnmounted } from 'vue'

const isMobile = ref(false)

function checkMobile() {
  isMobile.value = window.innerWidth <= 768
}

onMounted(() => {
  checkMobile()
  window.addEventListener('resize', checkMobile)
})

onUnmounted(() => {
  window.removeEventListener('resize', checkMobile)
})
</script>

<style scoped>
.home {
  display: flex;
  flex-direction: column;
  gap: 70px;
}
</style>