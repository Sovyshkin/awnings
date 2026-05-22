<template>
  <section class="company-numbers">
    <h2>Компания в цифрах</h2>
    <div class="cards">
      <div v-for="(card, index) in cards" :key="index" class="card" :style="cardStyle(card, index)">
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
import companyCard1 from '../assets/company-card-1.png'
import companyCard2 from '../assets/company-card-2.png'
import companyCard3 from '../assets/company-card-3.jpg'

const cards = ref([
  {
    title: '15',
    subtitle: 'лет на рынке',
    desc: 'проектируем и устанавливаем конструкции, которые выдерживают реальные условия эксплуатации',
    image: companyCard1
  },
  {
    title: '3 200+',
    subtitle: 'установленных навесов',
    desc: 'Отработали десятки сценариев: частные участки, коммерческие объекты, нестандартные решения',
    image: companyCard2
  },
  {
    title: '52',
    subtitle: 'города доставки',
    desc: 'Организуем логистику и монтаж так, чтобы вы получили готовый результат без срывов и "по месту разберёмся"',
    image: companyCard3
  }
])

const cardImages = [companyCard1, companyCard2, companyCard3]

function getImageUrl(imagePath, fallback) {
  if (!imagePath) return fallback
  if (imagePath.startsWith('http')) return imagePath
  if (imagePath.startsWith('/')) return imagePath
  return fallback
}

function cardStyle(card, index) {
  return {
    backgroundImage: `url("${card.image || cardImages[index] || cardImages[0]}")`
  }
}

async function loadCompanyNumbersData() {
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
      cards.value = data.map((card, index) => ({
        title: card.title || '',
        subtitle: card.subtitle || '',
        desc: card.desc || '',
        image: card.image ? getImageUrl(card.image, cardImages[index] || cardImages[0]) : cardImages[index] || cardImages[0]
      }))
    }
  }
}

onMounted(() => {
  loadCompanyNumbersData()
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
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
}

.card::before {
  content: '';
  position: absolute;
  inset: 0;
  background: #00000080;
  pointer-events: none;
  z-index: 0;
  transition: background 0.3s ease;
}

.card:hover::before {
  background: #00000060;
}

.card > * {
  position: relative;
  z-index: 1;
}

.card-wrap-title {
  display: flex;
  flex-direction: column;
  gap: 18px;
}

.card-title {
  color: #ffffff;
  font-size: 96px;
  font-weight: 400;
  transition: transform 0.3s ease;
}

.card:hover .card-title {
  transform: scale(1.05);
}

.card-rectangle {
  height: 4px;
  background-color: #ffffff;
  margin-left: -33px;
  margin-right: -33px;
  transition: background-color 0.3s ease;
}

.card:hover .card-rectangle {
  background-color: #C96744;
}

.card-subtitle {
  color: #ffffff;
  font-size: 24px;
  font-weight: 400;
  transition: color 0.3s ease;
}

.card:hover .card-subtitle {
  color: #C96744;
}

.card-desc {
  box-shadow: 0px 4px 4px 0px #00000040;
  backdrop-filter: blur(20.899999618530273px);
  width: calc(100% + 66px);
  padding: 12px 17px 12px 31px;
  border-radius: 44px;
  box-sizing: border-box;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  margin: 0 -33px 13px -33px;
}

.card:hover .card-desc {
  transform: translateY(4px);
  box-shadow: 0px 8px 20px 0px #00000050;
}

.card-desc span {
  color: #ffffff;
  font-size: 16px;
  font-weight: 300;
  opacity: 0.8;
}

@media (max-width: 1200px) {
  .company-numbers {
    gap: 56px;
    padding: 0 32px;
  }

  h2 {
    font-size: 36px;
  }

  .cards {
    gap: 16px;
  }

  .card {
    height: 340px;
    min-width: 280px;
    flex: 1;
  }

  .card-title {
    font-size: 72px;
  }

  .card-subtitle {
    font-size: 20px;
  }

  .card-desc {
    height: 72px;
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
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
  }

  .card {
    height: 280px;
    min-width: auto;
    flex: none;
  }

  .card-title {
    font-size: 56px;
  }

  .card-subtitle {
    font-size: 18px;
  }

  .card-desc {
    height: 68px;
    padding: 10px 14px 10px 24px;
  }

  .card-rectangle {
    margin-left: -24px;
    margin-right: -24px;
  }
}

@media (max-width: 900px) {
  .cards {
    grid-template-columns: repeat(2, 1fr);
  }

  .card {
    height: 260px;
  }

  .card-title {
    font-size: 48px;
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
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
  }

  .card {
    height: auto;
    min-height: 260px;
    max-width: 100%;
    padding: 16px 20px;
  }

  .card-title {
    font-size: 48px;
    line-height: 1;
  }

  .card-subtitle {
    font-size: 18px;
  }

  .card-desc {
    width: calc(100% + 40px);
    margin: 0 -20px 8px -20px;
    border-radius: 24px;
    padding: 10px 14px 10px 18px;
  }

  .card-desc span {
    font-size: 14px;
    line-height: 1.4;
  }

  .card-rectangle {
    margin-left: -20px;
    margin-right: -20px;
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
    min-height: 220px;
    padding: 14px 16px;
  }

  .card-title {
    font-size: 40px;
  }

  .card-subtitle {
    font-size: 14px;
  }

  .card-desc {
    width: calc(100% + 32px);
    margin: 0 -16px 6px -16px;
    padding: 8px 12px 8px 14px;
    border-radius: 18px;
  }

  .card-desc span {
    font-size: 12px;
    line-height: 1.35;
  }

  .card-rectangle {
    margin-left: -16px;
    margin-right: -16px;
  }
}
</style>
