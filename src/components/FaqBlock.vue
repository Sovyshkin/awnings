<template>
    <section class="faq">
        <h2>{{ faqTitle }}</h2>
        <div class="cards">
            <div
                v-for="(item, index) in faqItems"
                :key="index"
                class="card"
            >
                <div class="question" @click="toggleFaq(index)">
                    <span>{{ item.question }}</span>
                    <p :class="{ active: openIndex === index }">+</p>
                </div>
                <Transition>
                    <div class="answer-wrapper" v-show="openIndex === index">
                        <div class="answer">{{ item.answer }}</div>
                    </div>
                </Transition>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { fetchContentBlocks } from '../services/api'

const openIndex = ref(null)
const faqTitle = ref('Самые популярные вопросы')

const faqItems = ref([
    {
        question: 'Какие конструкции Вы изготавливаете?',
        answer: 'Мы изготавливаем навесы, беседки, мангальные зоны и террасы из металла с различными типами кровли.'
    },
    {
        question: 'Подходят ли конструкции для круглогодичного использования?',
        answer: 'Да, все наши конструкции рассчитаны на эксплуатацию в любое время года.'
    },
    {
        question: 'Можно ли выбрать размер конструкции?',
        answer: 'Да, мы изготавливаем конструкции по индивидуальным размерам под ваши задачи.'
    },
    {
        question: 'Можно ли заказать мангальную зону как отдельное решение?',
        answer: 'Да, мангальные зоны доступны как отдельные конструкции.'
    },
    {
        question: 'Из каких материалов изготавливаются конструкции?',
        answer: 'Каркас из стального профиля, кровля из поликарбоната или металлочерепицы.'
    },
])

async function loadFaqData() {
    const blocks = await fetchContentBlocks('faq')
    const headerBlock = blocks.find(b => (b.block_name || '').includes('Заголовок')) || blocks.find(b => b.block_type === 'section')
    if (headerBlock && headerBlock.block_title) {
        faqTitle.value = headerBlock.block_title
    }
    const faqBlock = blocks.find(b => b.block_type === 'faq')
    if (faqBlock && faqBlock.block_data) {
        let data = faqBlock.block_data
        if (typeof data === 'string') {
            try {
                data = JSON.parse(data)
            } catch (e) {
                return
            }
        }
        if (Array.isArray(data) && data.length > 0) {
            faqItems.value = data
        }
    }
}

function toggleFaq(index) {
    openIndex.value = openIndex.value === index ? null : index
}

onMounted(() => {
    loadFaqData()
})
</script>

<style scoped>
.faq {
  padding: 0 40px;
  display: flex;
  flex-direction: column;
  gap: 70px;
}

h2 {
    color: #000000;
    font-size: 44px;
    font-weight: 400;
    max-width: 524px;
}

.cards {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.card {
    width: 48%;
    display: flex;
    flex-direction: column;
    gap: 30px;
    background-color: #FFFFFF;
    border-radius: 4px;
    padding: 34px 65px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
}

.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
}

.question {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
}

.question span {
    color:#4B4B4B;
    font-size: 28px;
    font-weight: 500;
    transition: color 0.3s ease;
}

.card:hover .question span {
    color: #C96744;
}

.question p {
    font-size: 48px;
    font-weight: 500;
    color: #4B4B4B;
    transition: all 0.3s ease;
    transform: rotate(0deg);
}

.question p.active {
    transform: rotate(45deg);
    color: #C96744;
}

.answer-wrapper {
    overflow: hidden;
}

.answer-wrapper.v-enter-active,
.answer-wrapper.v-leave-active {
    transition: all 0.3s ease;
}

.answer-wrapper.v-enter-from,
.answer-wrapper.v-leave-to {
    opacity: 0;
    max-height: 0;
}

.answer-wrapper.v-enter-to,
.answer-wrapper.v-leave-from {
    opacity: 1;
    max-height: 300px;
}

.answer {
    color: #000000;
    font-size: 20px;
    font-weight: 300;
}

@media (max-width: 1200px) {
    .faq {
        gap: 56px;
        padding: 0 32px;
    }

    h2 {
        font-size: 36px;
    }

    .card {
        padding: 28px 48px;
    }

    .question span {
        font-size: 24px;
    }

    .question p {
        font-size: 40px;
    }

    .answer {
        font-size: 18px;
    }
}

@media (max-width: 1024px) {
    .faq {
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
        width: 100%;
        padding: 24px 32px;
    }

    .question span {
        font-size: 20px;
    }

    .question p {
        font-size: 36px;
    }

    .answer {
        font-size: 16px;
    }
}

@media (max-width: 768px) {
    .faq {
        gap: 40px;
        padding: 0 16px;
    }

    h2 {
        font-size: 28px;
        max-width: 100%;
    }

    .card {
        padding: 20px 24px;
        gap: 20px;
    }

    .question span {
        font-size: 18px;
    }

    .question p {
        font-size: 32px;
    }

    .answer {
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .faq {
        gap: 32px;
        padding: 0 12px;
    }

    h2 {
        font-size: 24px;
    }

    .card {
        padding: 16px 20px;
        gap: 16px;
    }

    .question span {
        font-size: 16px;
    }

    .question p {
        font-size: 28px;
    }

    .answer {
        font-size: 13px;
    }
}
</style>
