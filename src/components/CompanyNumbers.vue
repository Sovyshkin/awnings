<template>
  <section class="company-numbers">
    <h2>Компания в цифрах</h2>
    <div class="cards">
      <div v-for="(card, index) in cards" :key="index" class="card">
        <div class="card-wrap-title">
          <span class="card-title">{{ card.title }}</span>
          <div class="card-rectangle"></div>
          <span class="card-subtitle">{{ card.subtitle }}</span>
        </div>
        <div class="card-desc">
          <span>{{ card.desc }}</span>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { fetchContentBlocks } from '../services/api'

const cards = ref([
  {
    title: '15',
    subtitle: 'лет на рынке',
    desc: 'проектируем и устанавливаем конструкции, которые выдерживают реальные условия эксплуатации'
  },
  {
    title: '3 200+',
    subtitle: 'установленных навесов',
    desc: 'Отработали десятки сценариев: частные участки, коммерческие объекты, нестандартные решения'
  },
  {
    title: '52',
    subtitle: 'города доставки',
    desc: 'Организуем логистику и монтаж так, чтобы вы получили готовый результат без срывов и "по месту разберёмся"'
  }
])

async function loadCompanyNumbers() {
  const blocks = await fetchContentBlocks('home')
  const numbersBlock = blocks.find(b => b.block_type === 'features' && b.block_name === 'Компания в цифрах')
  if (numbersBlock && numbersBlock.block_data) {
    let data = numbersBlock.block_data
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

onMounted(() => {
  loadCompanyNumbers()
})
</script>

<style scoped>
.company-numbers {
  padding: 0 40px;
  display: flex;
  flex-direction: column;
  gap: 70px;
}

h2 {
  color: #000;
  font-size: 44px;
  font-weight: 400;
}

.cards {
  display: flex;
  gap: 20px;
}

.card {
  position: relative;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  gap: 20px;
  width: 100%;
  height: 403px;
  background-size: cover;
  background-position: center;
  padding: 13px 33px;
  box-sizing: border-box;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  cursor: pointer;
}

.card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.card::before {
  content: '';
  position: absolute;
  inset: 0;
  background: rgba(255, 255, 255, 0.9);
  z-index: -1;
  border-radius: 4px;
}

.card-wrap-title {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.card-title {
  font-size: 64px;
  font-weight: 600;
  color: #000;
  transition: color 0.3s ease;
}

.card:hover .card-title {
  color: #C96744;
}

.card-rectangle {
  width: 100%;
  height: 4px;
  background-color: #C96744;
}

.card-subtitle {
  font-size: 20px;
  font-weight: 500;
  color: #000;
}

.card-desc {
  width: 100%;
}

.card-desc span {
  font-size: 16px;
  font-weight: 300;
  color: #000;
  opacity: 0.8;
  line-height: 1.5;
}

@media (max-width: 1200px) {
  .company-numbers {
    gap: 56px;
    padding: 0 32px;
  }

  h2 {
    font-size: 36px;
  }

  .card {
    height: 340px;
    padding: 10px 24px;
  }

  .card-title {
    font-size: 52px;
  }

  .card-subtitle {
    font-size: 18px;
  }

  .card-desc span {
    font-size: 14px;
  }
}

@media (max-width: 1024px) {
  .company-numbers {
    gap: 48px;
    padding: 0 24px;
  }

  h2 {
    font-size: 32px;
  }

  .cards {
    gap: 16px;
  }

  .card {
    height: 280px;
    padding: 10px 20px;
  }

  .card-title {
    font-size: 44px;
  }

  .card-subtitle {
    font-size: 16px;
  }
}

@media (max-width: 768px) {
  .company-numbers {
    gap: 40px;
    padding: 0 16px;
  }

  h2 {
    font-size: 28px;
  }

  .cards {
    flex-direction: column;
    gap: 20px;
  }

  .card {
    height: auto;
    min-height: 200px;
    padding: 16px 20px;
    flex-direction: row;
    flex-wrap: wrap;
    align-content: flex-start;
  }

  .card-title {
    font-size: 36px;
  }

  .card-subtitle {
    font-size: 14px;
  }

  .card-desc {
    width: 100%;
  }

  .card-desc span {
    font-size: 13px;
  }
}

@media (max-width: 480px) {
  .company-numbers {
    gap: 32px;
    padding: 0 12px;
  }

  h2 {
    font-size: 24px;
  }

  .card {
    min-height: 160px;
    padding: 12px 16px;
  }

  .card-title {
    font-size: 28px;
  }

  .card-subtitle {
    font-size: 12px;
  }

  .card-rectangle {
    height: 2px;
  }

  .card-desc span {
    font-size: 12px;
  }
}
</style>