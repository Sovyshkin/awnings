<template>
    <section class="delivery-regions" :style="sectionStyle">
        <div class="wrap-title">
            <h1>{{ regionData.block_title || 'Регионы доставки' }}</h1>
            <p class="subtitle">{{ regionData.block_text || 'Осуществляем доставку и установку по всей территории России' }}</p>
        </div>
        <ul class="regions">
            <li class="region" v-for="(region, index) in regions" :key="index">{{ region }}</li>
        </ul>
    </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { fetchContentBlocks } from '../services/api'

const regionData = ref({
    block_title: '',
    block_text: '',
    block_image: ''
})

const regions = ref([])
const sectionStyle = ref({})

function resolveImagePath(path) {
    if (!path) return ''
    if (path.startsWith('http') || path.startsWith('/')) return path
    if (path.startsWith('wp-content/')) return `/${path}`
    return ''
}

async function loadRegionsData() {
    const blocks = await fetchContentBlocks('delivery')
    const regionsBlock = blocks.find(b => (b.block_name || '').includes('Регионы')) ||
        blocks.find(b => b.block_type === 'regions')
    if (regionsBlock) {
        regionData.value.block_title = regionsBlock.block_title || ''
        regionData.value.block_text = regionsBlock.block_text || ''
        regionData.value.block_image = regionsBlock.block_image || ''
        const bg = resolveImagePath(regionData.value.block_image)
        if (bg) {
            sectionStyle.value = { '--regions-bg': `url("${bg}")` }
        }
        if (regionsBlock.block_data) {
            let data = regionsBlock.block_data
            if (typeof data === 'string') {
                try {
                    data = JSON.parse(data)
                } catch (e) {
                    return
                }
            }
            if (Array.isArray(data)) {
                regions.value = data
            }
        }
    }
}

onMounted(() => {
    loadRegionsData()
})
</script>

<style scoped>
.delivery-regions {
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 80px;
    overflow: hidden;
}

.delivery-regions::after {
    content: '';
    position: absolute;
    right: -150px;
    top: 50%;
    transform: translateY(-50%);
    width: 700px;
    height: 700px;
    background: var(--regions-bg, url('../assets/delivery-regions.png')) center/contain no-repeat;
    pointer-events: none;
    z-index: -1;
}

.wrap-title {
    display: flex;
    flex-direction: column;
    gap: 20px;
    max-width: 442px;
}

h1 {
    color: #000000;
    font-size: 44px;
    font-weight: 400;

}

.subtitle {
    color: #000000;
    font-size: 20px;
    font-weight: 300;
    opacity: 0.8;
}

.regions {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.region {
    width: calc((100% - 60px) / 4);
    color: #000000;
    font-size: 20px;
    font-weight: 300;
    opacity: 0.8;
    position: relative;
    padding-left: 30px;
}

.region::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 8px;
    height: 8px;
    background-color: #C96744;
    border-radius: 50%;
}

@media (max-width: 1200px) {
    .delivery-regions {
        gap: 56px;
    }

    h1 {
        font-size: 40px;
    }

    .subtitle {
        font-size: 18px;
    }

    .region {
        font-size: 18px;
    }
}

@media (max-width: 1024px) {
    .delivery-regions {
        gap: 48px;
    }

    h1 {
        font-size: 36px;
    }

    .subtitle {
        font-size: 16px;
    }

    .region {
        font-size: 16px;
        width: calc((100% - 40px) / 4);
    }
}

@media (max-width: 768px) {
    .delivery-regions {
        gap: 40px;
    }

    h1 {
        font-size: 28px;
    }

    .subtitle {
        font-size: 14px;
    }

    .regions {
        padding-left: 0;
    }

    .region {
        width: calc((100% - 20px) / 2);
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .delivery-regions {
        gap: 32px;
    }

    h1 {
        font-size: 24px;
    }

    .subtitle {
        font-size: 12px;
    }

    .region {
        width: 100%;
        font-size: 14px;
    }
}
</style>
