<template>
  <div class="home">
    <HeroSection />
    <div class="reveal"><WhatDoing /></div>
    <div class="reveal"><HowWork :isMobile="isMobile" /></div>
    <div class="reveal"><CompanyNumbers /></div>
    <div class="reveal"><OurProjects /></div>
    <div class="reveal"><WhyUs /></div>
    <div class="reveal"><Faq /></div>
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
  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible')
          observer.unobserve(entry.target)
        }
      })
    }, { threshold: 0.12, rootMargin: '0px 0px -50px 0px' })
    document.querySelectorAll('.home > .reveal').forEach(el => observer.observe(el))
  } else {
    document.querySelectorAll('.home > .reveal').forEach(el => el.classList.add('visible'))
  }
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

.reveal {
  opacity: 0;
  transform: translateY(40px);
  transition: opacity 0.8s ease-out, transform 0.8s ease-out;
  will-change: opacity, transform;
}
.reveal.visible {
  opacity: 1;
  transform: translateY(0);
}
@media (prefers-reduced-motion: reduce) {
  .reveal { opacity: 1; transform: none; transition: none; }
}
</style>