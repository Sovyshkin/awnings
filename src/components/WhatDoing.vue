<template>
  <section class="what-we-doing">
    <h1>{{ sectionTitle || 'Беседки, навесы и мангальные зоны для тех, кто ценит удобство и современный внешний вид' }}</h1>
      <h2>Что мы делаем</h2>
      <div class="cards">
        <div v-for="(card, index) in cards" :key="index" class="card">
          <div class="wrap-img">
            <img :src="card.image || cardImage" :alt="card.title" />
          </div>
          <div class="category">
            <div class="circle"></div>
            <span class="text-category">{{ card.category }}</span>
          </div>
          <span class="card-title">{{ card.title }}</span>
        </div>
      </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { fetchContentBlocks } from '../services/api'
import cardImage from '../assets/card.png'

const sectionTitle = ref('')
const cards = ref([
  { title: 'Беседка для отдыха', category: 'Сад', image: '' },
  { title: 'Мангальные зоны', category: 'Барбекю', image: '' },
  { title: 'Навесы для автомобилей', category: 'Авто', image: '' }
])

async function loadWhatDoingData() {
  const blocks = await fetchContentBlocks('home')
  const whatDoingBlock = blocks.find(b => b.block_type === 'features' && b.block_name === 'Что мы делаем')
  if (whatDoingBlock) {
    sectionTitle.value = whatDoingBlock.block_title || ''
    if (whatDoingBlock.block_data) {
      let data = whatDoingBlock.block_data
      if (typeof data === 'string') {
        try {
          data = JSON.parse(data)
        } catch (e) {
          return
        }
      }
      if (Array.isArray(data) && data.length > 0) {
        cards.value = data
      }
    }
  }
}

onMounted(() => {
  loadWhatDoingData()
})
</script>

<style scoped>
.what-we-doing {
    display: flex;
    flex-direction: column;
    gap: 70px;
    padding: 0 40px;
}

h1 {
    width: 920px;
    margin: 0 auto;
    color: #000000;
    text-align: center;
    font-size: 52px;
    font-weight: 400;
    margin-bottom: 35px;
}

h2 {
   color: #000000;
   font-weight: 400;
   font-size: 44px;
}

.cards {
    display: flex;
    gap: 19px;
    width: 100%;
}

.card {
    flex: 1;
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
}

.category {
    display: flex;
    align-items: center;
    gap: 8px;
}

.circle {
    width: 8px;
    height: 8px;
    background-color: #C96744;
    border-radius: 50%;
}

.text-category {
    color: #C96744;
    font-size: 14px;
    font-weight: 500;
}

.card-title {
    font-size: 28px;
    font-weight: 500;
    color: #000000;
    transition: color 0.3s ease;
}

.card:hover .card-title {
    color: #C96744;
}

@media (max-width: 1200px) {
    .what-we-doing {
        gap: 56px;
        padding: 0 32px;
    }

    h1 {
        font-size: 42px;
        width: 720px;
    }

    h2 {
        font-size: 36px;
    }

    .wrap-img {
        height: 340px;
    }

    .card-title {
        font-size: 24px;
    }
}

@media (max-width: 1024px) {
    .what-we-doing {
        gap: 48px;
        padding: 0 24px;
    }

    h1 {
        font-size: 36px;
        width: 600px;
    }

    h2 {
        font-size: 32px;
    }

    .cards {
        gap: 16px;
    }

    .wrap-img {
        height: 280px;
    }

    .card-title {
        font-size: 20px;
    }
}

@media (max-width: 768px) {
    .what-we-doing {
        gap: 40px;
        padding: 0 16px;
    }

    h1 {
        font-size: 28px;
        width: 100%;
    }

    h2 {
        font-size: 28px;
    }

    .cards {
        flex-direction: column;
        gap: 24px;
    }

    .wrap-img {
        height: 320px;
    }

    .card-title {
        font-size: 18px;
    }
}

@media (max-width: 480px) {
    .what-we-doing {
        gap: 32px;
        padding: 0 12px;
    }

    h1 {
        font-size: 22px;
    }

    h2 {
        font-size: 24px;
    }

    .wrap-img {
        height: 240px;
    }

    .card-title {
        font-size: 16px;
    }

    .text-category {
        font-size: 12px;
    }
}
</style>