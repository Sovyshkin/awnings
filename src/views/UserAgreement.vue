<script setup>
import { ref, onMounted } from 'vue'
import { fetchContentBlocks } from '../services/api'

const pageTitle = ref('Пользовательское соглашение')
const pageText = ref('')

async function loadContent() {
  const blocks = await fetchContentBlocks('agreement')
  const mainBlock = blocks.find((b) => b.block_type === 'legal' || (b.block_name || '').includes('Пользовательское соглашение'))
  if (mainBlock) {
    pageTitle.value = mainBlock.block_title || pageTitle.value
    pageText.value = mainBlock.block_text || ''
  }
}

onMounted(() => {
  loadContent()
})
</script>

<template>
  <section class="legal-page">
    <div class="header">
      <div class="breadcrumbs">
        <router-link to="/">Главная</router-link>
        <span>/</span>
        <router-link to="/user-agreement">Пользовательское соглашение</router-link>
      </div>
      <h1>{{ pageTitle }}</h1>
    </div>

    <article class="legal-content">{{ pageText }}</article>
  </section>
</template>

<style scoped>
.legal-page {
  display: flex;
  flex-direction: column;
  gap: 36px;
  padding: 180px 40px 0 40px;
}

.header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 24px;
}

.breadcrumbs {
  display: flex;
  align-items: center;
  gap: 12px;
}

.breadcrumbs a,
.breadcrumbs span {
  color: #000000;
  font-size: 16px;
  font-weight: 600;
  text-decoration: none;
}

h1 {
  font-size: 44px;
  font-weight: 400;
  color: #000000;
  text-align: center;
}

.legal-content {
  color: #111111;
  opacity: 0.9;
  font-size: 18px;
  line-height: 1.7;
  white-space: pre-wrap;
}

@media (max-width: 768px) {
  .legal-page {
    padding: 90px 16px 0 16px;
  }

  h1 {
    font-size: 30px;
  }

  .legal-content {
    font-size: 15px;
  }
}
</style>
