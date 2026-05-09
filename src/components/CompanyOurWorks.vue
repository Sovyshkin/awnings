<template>
    <section class="our-works">
        <h1>Наши работы</h1>
        <div class="cards">
          <div class="card" v-for="(project, index) in projects" :key="index" @click="goToArticle(index + 1)">
            <div class="wrap-img">
              <img :src="project.image || defaultImage" alt="">
            </div>
          </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { fetchContentBlocks } from '../services/api'
import defaultImage from '../assets/company-card-1.png'

const projects = ref(Array(9).fill(null).map(() => ({ image: '' })))

function goToArticle(index) {
  // Можно добавить навигацию
  console.log('Go to article', index)
}

async function loadProjects() {
  const blocks = await fetchContentBlocks('home')
  const projectsBlock = blocks.find(b => b.block_type === 'gallery' && b.block_name === 'Наши проекты')
  if (projectsBlock && projectsBlock.block_data) {
    let data = projectsBlock.block_data
    if (typeof data === 'string') {
      try {
        data = JSON.parse(data)
      } catch (e) {
        return
      }
    }
    if (Array.isArray(data) && data.length > 0) {
      projects.value = data.slice(0, 9) // максимум 9 проектов
    }
  }
}

onMounted(() => {
  loadProjects()
})
</script>

<style scoped>
.our-works {
  display: flex;
  flex-direction: column;
  gap: 82px;
}
.cards {
  display: flex;
  flex-wrap: wrap;
  row-gap: 50px;
  column-gap: 20px;
}

.card {
    width: calc(33.333% - 20px);
    display: flex;
    flex-direction: column;
    gap: 30px;
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-8px);
}

.wrap-img {
    background-color: #fff;
    border-radius: 4px;
    width: 100%;
    height: 315px;
    overflow: hidden;
    transition: box-shadow 0.3s ease;
    position: relative;
}

.wrap-img::before {
    content: '';
    position: absolute;
    inset: 0;
    background: #00000080;
    pointer-events: none;
    z-index: 1;
    transition: background 0.3s ease;
}

.card:hover .wrap-img::before {
    background: #00000060;
}

.wrap-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.card:hover .wrap-img {
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

@media (max-width: 1200px) {
    .our-works {
        gap: 64px;
    }

    .cards {
        row-gap: 40px;
    }

    .card {
        width: calc(33.333% - 14px);
    }

    .wrap-img {
        height: 260px;
    }
}

@media (max-width: 1024px) {
    .our-works {
        gap: 56px;
    }

    .cards {
        row-gap: 32px;
        column-gap: 16px;
    }

    .card {
        width: calc(33.333% - 12px);
    }

    .wrap-img {
        height: 220px;
    }
}

@media (max-width: 768px) {
    .our-works {
        gap: 48px;
    }

    .cards {
        row-gap: 24px;
    }

    .card {
        width: calc(50% - 8px);
    }

    .wrap-img {
        height: 200px;
    }
}

@media (max-width: 480px) {
    .our-works {
        gap: 40px;
    }

    .cards {
        row-gap: 20px;
        column-gap: 12px;
    }

    .card {
        width: calc(50% - 6px);
    }

    .wrap-img {
        height: 160px;
    }
}
</style>