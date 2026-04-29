<template>
  <section class="garant">
    <div class="garant-header">
      <div class="breadcrumbs">
        <router-link to="/">Главная</router-link>
        <span>/</span>
        <router-link to="/garant">Гарантия</router-link>
      </div>
      <h1>Гарантия</h1>
    </div>
    <div class="garant-banner">
        <h3>Мы обеспечиваем заказчикам гарантию на срок 12 месяцев, в том числе 12 месяцев претенциозного обслуживания бесплатно</h3>
        <button class="garant-btn">Гарантийный случай</button>
    </div>
    <section class="garant-faq-green">
        <h2>На что <span class="garant-green">распространяется</span> гарантия</h2>
        <div class="garant-faq-cards">
            <div
                v-for="(item, index) in greenItems"
                :key="index"
                class="garant-faq-card"
            >
                <div class="garant-faq-question" @click="toggleGreenFaq(index)">
                    <span>{{ item.question }}</span>
                    <p class="garant-green-ans" :class="{ active: greenIndex === index }">+</p>
                </div>
                <Transition>
                    <div class="garant-faq-answer-wrapper" v-show="greenIndex === index">
                        <div class="garant-faq-answer">{{ item.answer }}</div>
                    </div>
                </Transition>
            </div>
        </div>
    </section>
    <section class="garant-faq-red">
        <h2>На что <span class="garant-red">не распространяется</span> гарантия</h2>
        <div class="garant-faq-cards">
            <div
                v-for="(item, index) in redItems"
                :key="index"
                class="garant-faq-card"
            >
                <div class="garant-faq-question" @click="toggleRedFaq(index)">
                    <span>{{ item.question }}</span>
                    <p class="garant-red-ans" :class="{ active: redIndex === index }">+</p>
                </div>
                <Transition>
                    <div class="garant-faq-answer-wrapper" v-show="redIndex === index">
                        <div class="garant-faq-answer">{{ item.answer }}</div>
                    </div>
                </Transition>
            </div>
        </div>
    </section>
    <section class="garant-how">
        <div class="garant-how-content">
            <div class="garant-how-text">
                <h2>Как воспользоваться гарантией?</h2>
            </div>
            <div class="garant-how-cards">
                <div class="garant-how-card">
                    <div class="garant-how-img">
                        01
                    </div>
                    <div class="garant-how-card-text">
                        <span class="garant-how-title">Свяжитесь с нами</span>
                        <p class="garant-how-desc">Позвоните или напишите о возникшей проблеме</p>
                    </div>
                </div>
                <div class="garant-how-card">
                    <div class="garant-how-img">
                        02
                    </div>
                    <div class="garant-how-card-text">
                        <span class="garant-how-title">Диагностика</span>
                        <p class="garant-how-desc">Наш специалист осмотрит изделие и определит причину</p>
                    </div>
                </div>
                <div class="garant-how-card">
                    <div class="garant-how-img">
                        03
                    </div>
                    <div class="garant-how-card-text">
                        <span class="garant-how-title">Устранение</span>
                        <p class="garant-how-desc">Бесплатно устраним дефект или заменим изделие</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
  </section>
</template>

<script setup>
import { ref } from 'vue'

const greenIndex = ref(null)
const redIndex = ref(null)

const greenItems = [
    {
        question: 'Дефекты материалов',
        answer: 'Figma ipsum component variant main layer. Font team duplicate edit line. Frame thumbnail group stroke team. Create frame mask team list blur text font subtract arrange. Export library selection vertical rectangle. Share subtract project text bold library. Vertical figjam image rotate variant style star. Bullet line hand move prototype device object auto. Rectangle ellipse create align pen distribute variant mask scrolling. Scrolling selection project clip draft. Horizontal rectangle team pencil team.'
    },
    {
        question: 'Деформация конструкции',
        answer: 'Figma ipsum component variant main layer. Font team duplicate edit line. Frame thumbnail group stroke team. Create frame mask team list blur text font subtract arrange. Export library selection vertical rectangle. Share subtract project text bold library. Vertical figjam image rotate variant style star. Bullet line hand move prototype device object auto. Rectangle ellipse create align pen distribute variant mask scrolling. Scrolling selection project clip draft. Horizontal rectangle team pencil team.'
    },
    {
        question: 'Производственный брак',
        answer: 'Figma ipsum component variant main layer. Font team duplicate edit line. Frame thumbnail group stroke team. Create frame mask team list blur text font subtract arrange. Export library selection vertical rectangle. Share subtract project text bold library. Vertical figjam image rotate variant style star. Bullet line hand move prototype device object auto. Rectangle ellipse create align pen distribute variant mask scrolling. Scrolling selection project clip draft. Horizontal rectangle team pencil team.'
    },
    {
        question: 'Протечки кровли',
        answer: 'Figma ipsum component variant main layer. Font team duplicate edit line. Frame thumbnail group stroke team. Create frame mask team list blur text font subtract arrange. Export library selection vertical rectangle. Share subtract project text bold library. Vertical figjam image rotate variant style star. Bullet line hand move prototype device object auto. Rectangle ellipse create align pen distribute variant mask scrolling. Scrolling selection project clip draft. Horizontal rectangle team pencil team.'
    },
    {
        question: 'Коррозия металла (при соблюдении условий эксплуатации)',
        answer: 'Figma ipsum component variant main layer. Font team duplicate edit line. Frame thumbnail group stroke team. Create frame mask team list blur text font subtract arrange. Export library selection vertical rectangle. Share subtract project text bold library. Vertical figjam image rotate variant style star. Bullet line hand move prototype device object auto. Rectangle ellipse create align pen distribute variant mask scrolling. Scrolling selection project clip draft. Horizontal rectangle team pencil team.'
    },
    {
        question: 'Неисправность механизмов',
        answer: 'Figma ipsum component variant main layer. Font team duplicate edit line. Frame thumbnail group stroke team. Create frame mask team list blur text font subtract arrange. Export library selection vertical rectangle. Share subtract project text bold library. Vertical figjam image rotate variant style star. Bullet line hand move prototype device object auto. Rectangle ellipse create align pen distribute variant mask scrolling. Scrolling selection project clip draft. Horizontal rectangle team pencil team.'
    },
]

const redItems = [
    {
        question: 'Механические повреждения',
        answer: 'Figma ipsum component variant main layer. Font team duplicate edit line. Frame thumbnail group stroke team. Create frame mask team list blur text font subtract arrange. Export library selection vertical rectangle. Share subtract project text bold library. Vertical figjam image rotate variant style star. Bullet line hand move prototype device object auto. Rectangle ellipse create align pen distribute variant mask scrolling. Scrolling selection project clip draft. Horizontal rectangle team pencil team.'
    },
    {
        question: 'Самостоятельная модификация конструкции',
        answer: 'Figma ipsum component variant main layer. Font team duplicate edit line. Frame thumbnail group stroke team. Create frame mask team list blur text font subtract arrange. Export library selection vertical rectangle. Share subtract project text bold library. Vertical figjam image rotate variant style star. Bullet line hand move prototype device object auto. Rectangle ellipse create align pen distribute variant mask scrolling. Scrolling selection project clip draft. Horizontal rectangle team pencil team.'
    },
    {
        question: 'Повреждения от стихийных бедствий',
        answer: 'Figma ipsum component variant main layer. Font team duplicate edit line. Frame thumbnail group stroke team. Create frame mask team list blur text font subtract arrange. Export library selection vertical rectangle. Share subtract project text bold library. Vertical figjam image rotate variant style star. Bullet line hand move prototype device object auto. Rectangle ellipse create align pen distribute variant mask scrolling. Scrolling selection project clip draft. Horizontal rectangle team pencil team.'
    },
    {
        question: 'Естественный износ',
        answer: 'Figma ipsum component variant main layer. Font team duplicate edit line. Frame thumbnail group stroke team. Create frame mask team list blur text font subtract arrange. Export library selection vertical rectangle. Share subtract project text bold library. Vertical figjam image rotate variant style star. Bullet line hand move prototype device object auto. Rectangle ellipse create align pen distribute variant mask scrolling. Scrolling selection project clip draft. Horizontal rectangle team pencil team.'
    },
    {
        question: 'Нарушение правил эксплуатации',
        answer: 'Figma ipsum component variant main layer. Font team duplicate edit line. Frame thumbnail group stroke team. Create frame mask team list blur text font subtract arrange. Export library selection vertical rectangle. Share subtract project text bold library. Vertical figjam image rotate variant style star. Bullet line hand move prototype device object auto. Rectangle ellipse create align pen distribute variant mask scrolling. Scrolling selection project clip draft. Horizontal rectangle team pencil team.'
    },
    {
        question: 'Повреждения от действий третьих лиц',
        answer: 'Figma ipsum component variant main layer. Font team duplicate edit line. Frame thumbnail group stroke team. Create frame mask team list blur text font subtract arrange. Export library selection vertical rectangle. Share subtract project text bold library. Vertical figjam image rotate variant style star. Bullet line hand move prototype device object auto. Rectangle ellipse create align pen distribute variant mask scrolling. Scrolling selection project clip draft. Horizontal rectangle team pencil team.'
    },
]

function toggleGreenFaq(index) {
    greenIndex.value = greenIndex.value === index ? null : index
}

function toggleRedFaq(index) {
    redIndex.value = redIndex.value === index ? null : index
}
</script>

<style scoped>
.garant {
  display: flex;
  flex-direction: column;
  gap: 70px;
  padding: 180px 40px 0 40px;
}

.garant-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 34px;
}

.breadcrumbs {
  display: flex;
  align-items: center;
  gap: 12px;
}

.breadcrumbs a {
  color: #000000;
  font-size: 16px;
  font-weight: 600;
  text-decoration: none;
  transition: color 0.3s ease;
}

.breadcrumbs a:hover {
  color: #C96744;
}

.breadcrumbs span {
  color: #000000;
  font-size: 16px;
  font-weight: 600;
}

h1 {
  font-size: 44px;
  font-weight: 400;
  color: #000000;
}

.garant-banner {
  display: flex;
  flex-direction: column;
  padding: 90px 64px;
  gap: 50px;
  height: 384px;
  border-radius: 4px;
  width: 100%;
  background-image: url(../assets/hero-bg.png);
  background-size: cover;
  background-position: center;
  position: relative;
}

.garant-banner::before {
  content: '';
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  pointer-events: none;
  z-index: 0;
}

.garant-banner h3,
.garant-banner .garant-btn {
  position: relative;
  z-index: 1;
}

.garant-banner h3 {
  font-size: 28px;
  font-weight: 400;
  color: #fff;
  max-width: 650px;
}

.garant-btn {
    width: fit-content;
  height: 56px;
  border-radius: 44px;
  padding: 19px 48px;
  font-weight: 600;
  font-size: 16px;
  background-color: #C96744;
  color: #fff;
  border: none;
  cursor: pointer;
  transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
}

.garant-faq-green,
.garant-faq-red {
  display: flex;
  flex-direction: column;
  gap: 70px;
}

.garant-faq-green h2,
.garant-faq-red h2 {
    color: #000000;
    font-size: 44px;
    font-weight: 400;
    max-width: 670px;
}

.garant-green {
    color: #0B9741;
}

.garant-red {
    color: #FF0000;
}

.garant-faq-cards {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.garant-faq-card {
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

.garant-faq-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
}

.garant-faq-question {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
}

.garant-faq-question span {
    color:#4B4B4B;
    font-size: 28px;
    font-weight: 500;
    transition: color 0.3s ease;
}

.garant-faq-card:hover .garant-faq-question span {
    color: #C96744;
}

.garant-faq-question p {
    font-size: 48px;
    font-weight: 500;
    transition: all 0.3s ease;
    transform: rotate(0deg);
}

.garant-green-ans {
    color: #0B9741;
}

.garant-red-ans {
    color: #FF0000;
}

.garant-faq-question p.active {
    transform: rotate(45deg);
    color: #C96744;
}

.garant-faq-answer-wrapper {
    overflow: hidden;
}

.garant-faq-answer-wrapper.v-enter-active,
.garant-faq-answer-wrapper.v-leave-active {
    transition: all 0.3s ease;
}

.garant-faq-answer-wrapper.v-enter-from,
.garant-faq-answer-wrapper.v-leave-to {
    opacity: 0;
    max-height: 0;
}

.garant-faq-answer-wrapper.v-enter-to,
.garant-faq-answer-wrapper.v-leave-from {
    opacity: 1;
    max-height: 300px;
}

.garant-faq-answer {
    color: #000000;
    font-size: 20px;
    font-weight: 300;
}

.garant-how {
    display: flex;
    flex-direction: column;
    gap: 70px;
}

.garant-how h1 {
    width: 920px;
    margin: 0 auto;
    text-align: center;
    font-size: 52px;
    font-weight: 400;
}

.garant-how-content {
    display: flex;
}

.garant-how-text {
    width: 49%;
    display: flex;
    flex-direction: column;
    gap: 36px;
}

.garant-how-text h2 {
    color: #000000;
    font-size: 44px;
    font-weight: 400;
    max-width: 579px;
}

.garant-how-cards {
    width: 49%;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.garant-how-card {
    display: flex;
    align-items: center;
    background-color: #FFFFFF;
    border-radius: 4px;
    padding: 8px 64px 8px 10px;
    min-height: 120px;
    gap: 20px;
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
}

.garant-how-card:hover {
    transform: translateX(8px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.garant-how-img {
    width: 120px;
    height: 120px;
    flex-shrink: 0;
    border-radius: 4px;
    overflow: hidden;
    background-color: #E2E2E2;
    transition: transform 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 52px;
    font-weight: 400;
    color: #C96744;
}

.garant-how-card:hover .garant-how-img {
    transform: scale(1.05);
}

.garant-how-card-text {
    width: 70%;
    display: flex;
    flex-direction: column;
    gap: 17px;
}

.garant-how-title {
    color: #4B4B4B;
    font-size: 28px;
    font-weight: 500;
    transition: color 0.3s ease;
}

.garant-how-card:hover .garant-how-title {
    color: #C96744;
}

.garant-how-desc {
    color: #000000;
    font-size: 20px;
    font-weight: 300;
    opacity: 0.8;
}

@media (max-width: 1200px) {
    .garant {
        padding: 160px 32px 0 32px;
        gap: 56px;
    }

    .garant-header {
        gap: 28px;
    }

    .breadcrumbs a,
    .breadcrumbs span {
        font-size: 14px;
    }

    .garant-header h1 {
        font-size: 40px;
    }

    .garant-banner {
        height: 340px;
        padding: 70px 48px;
        gap: 36px;
    }

    .garant-banner h3 {
        font-size: 24px;
        max-width: 560px;
    }

    .garant-btn {
        height: 52px;
        padding: 16px 40px;
        font-size: 15px;
    }

    .garant-faq-green,
    .garant-faq-red {
        gap: 56px;
    }

    .garant-faq-green h2,
    .garant-faq-red h2 {
        font-size: 36px;
    }

    .garant-faq-card {
        padding: 28px 48px;
    }

    .garant-faq-question span {
        font-size: 24px;
    }

    .garant-faq-question p {
        font-size: 40px;
    }

    .garant-faq-answer {
        font-size: 18px;
    }

    .garant-how-content {
        gap: 16px;
    }

    .garant-how-text h2 {
        font-size: 36px;
    }

    .garant-how-card {
        padding: 8px 48px 8px 10px;
        min-height: 100px;
    }

    .garant-how-img {
        width: 100px;
        height: 100px;
        font-size: 44px;
    }

    .garant-how-title {
        font-size: 24px;
    }

    .garant-how-desc {
        font-size: 18px;
    }
}

@media (max-width: 1024px) {
    .garant {
        padding: 130px 24px 0 24px;
        gap: 48px;
    }

    .garant-header {
        gap: 24px;
    }

    .breadcrumbs a,
    .breadcrumbs span {
        font-size: 14px;
    }

    .garant-header h1 {
        font-size: 36px;
    }

    .garant-banner {
        height: 300px;
        padding: 56px 40px;
        gap: 32px;
    }

    .garant-banner h3 {
        font-size: 22px;
        max-width: 480px;
    }

    .garant-btn {
        height: 48px;
        padding: 14px 36px;
        font-size: 14px;
    }

    .garant-faq-green,
    .garant-faq-red {
        gap: 48px;
    }

    .garant-faq-green h2,
    .garant-faq-red h2 {
        font-size: 32px;
    }

    .garant-faq-cards {
        gap: 16px;
    }

    .garant-faq-card {
        width: 100%;
        padding: 24px 32px;
    }

    .garant-faq-question span {
        font-size: 20px;
    }

    .garant-faq-question p {
        font-size: 36px;
    }

    .garant-faq-answer {
        font-size: 16px;
    }

    .garant-how {
        gap: 48px;
    }

    .garant-how-content {
        flex-direction: column;
        gap: 40px;
    }

    .garant-how-text {
        width: 100%;
    }

    .garant-how-text h2 {
        font-size: 32px;
    }

    .garant-how-cards {
        width: 100%;
    }

    .garant-how-card {
        padding: 8px 32px 8px 10px;
        min-height: 90px;
        gap: 16px;
    }

    .garant-how-img {
        width: 80px;
        height: 80px;
        font-size: 36px;
    }

    .garant-how-title {
        font-size: 20px;
    }

    .garant-how-desc {
        font-size: 16px;
    }
}

@media (max-width: 768px) {
    .garant {
        gap: 40px;
    }

    .garant-header h1 {
        font-size: 28px;
    }

    .breadcrumbs a,
    .breadcrumbs span {
        font-size: 13px;
    }

    .garant-banner {
        height: 240px;
        padding: 36px 24px;
        gap: 24px;
    }

    .garant-banner h3 {
        font-size: 18px;
        max-width: 100%;
    }

    .garant-btn {
        height: 40px;
        padding: 10px 24px;
        font-size: 13px;
    }

    .garant-faq-green,
    .garant-faq-red {
        gap: 40px;
    }

    .garant-faq-green h2,
    .garant-faq-red h2 {
        font-size: 28px;
        max-width: 100%;
    }

    .garant-faq-card {
        padding: 20px 24px;
        gap: 20px;
    }

    .garant-faq-question span {
        font-size: 18px;
    }

    .garant-faq-question p {
        font-size: 32px;
    }

    .garant-faq-answer {
        font-size: 14px;
    }

    .garant-how-content {
        gap: 32px;
    }

    .garant-how-text h2 {
        font-size: 28px;
    }

    .garant-how-card {
        padding: 8px 24px 8px 8px;
        min-height: 80px;
        gap: 16px;
    }

    .garant-how-img {
        width: 70px;
        height: 70px;
        font-size: 32px;
    }

    .garant-how-title {
        font-size: 18px;
    }

    .garant-how-desc {
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .garant {
        padding: 100px 12px 0 12px;
        gap: 32px;
    }

    .garant-header h1 {
        font-size: 24px;
    }

    .breadcrumbs a,
    .breadcrumbs span {
        font-size: 12px;
    }

    .garant-banner {
        height: 200px;
        padding: 24px 16px;
        gap: 20px;
    }

    .garant-banner h3 {
        font-size: 16px;
    }

    .garant-btn {
        height: 36px;
        padding: 8px 20px;
        font-size: 12px;
        border-radius: 36px;
    }

    .garant-faq-green,
    .garant-faq-red {
        gap: 32px;
    }

    .garant-faq-green h2,
    .garant-faq-red h2 {
        font-size: 24px;
    }

    .garant-faq-card {
        padding: 16px 20px;
        gap: 16px;
    }

    .garant-faq-question span {
        font-size: 16px;
    }

    .garant-faq-question p {
        font-size: 28px;
    }

    .garant-faq-answer {
        font-size: 13px;
    }

    .garant-how-content {
        gap: 24px;
    }

    .garant-how-text h2 {
        font-size: 24px;
    }

    .garant-how-card {
        padding: 8px 16px 8px 8px;
        min-height: 70px;
        gap: 12px;
    }

    .garant-how-img {
        width: 60px;
        height: 60px;
        font-size: 28px;
        border-radius: 4px;
    }

    .garant-how-title {
        font-size: 16px;
    }

    .garant-how-desc {
        font-size: 13px;
    }

    .garant-how-card-text {
        gap: 8px;
    }
}
</style>
