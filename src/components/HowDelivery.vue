<template>
  <section class="how-work">
    <div class="wrap-title">
      <h2>Как происходит доставка</h2>
      <div class="actions">
        <button class="btn arrow-left" @click="prevStep">
          <img src="../assets/arrow-left.svg" alt="" />
        </button>
        <button class="btn arrow-right" @click="nextStep">
          <img src="../assets/arrow-right.svg" alt="" />
        </button>
      </div>
    </div>
    <div class="content-wrapper"
        @touchstart="onTouchStart"
        @touchmove="onTouchMove"
        @touchend="onTouchEnd"
      >
      <div
        ref="cardsContainer"
        class="cards"
        :class="{ 'cards-mobile': !isDesktop }"
        :style="isDesktop ? { transform: `translateX(-${cardOffset}px)` } : {}"
      >
        <div class="card">
          <div class="card-text">
            <span class="card-title">Оформление заказа</span>
            <p class="card-desc">
              Выбираете товары и оставляете заявку
            </p>
          </div>
          <div class="wrap-icon">
            <img src="../assets/card-icon-1.svg" alt="" class="icon" />
          </div>
        </div>
        <div class="card">
          <div class="card-text">
            <span class="card-title">Подтверждение</span>
            <p class="card-desc">
              Менеджер связывается для уточнения
            </p>
          </div>
          <div class="wrap-icon">
            <img src="../assets/card-icon-2.svg" alt="" class="icon" />
          </div>
        </div>
        <div class="card">
          <div class="card-text">
            <span class="card-title">Производство</span>
            <p class="card-desc">
              Изготавливаем изделие под ваш заказ
            </p>
          </div>
          <div class="wrap-icon">
            <img src="../assets/card-icon-3.svg" alt="" class="icon" />
          </div>
        </div>
        <div class="card">
          <div class="card-text">
            <span class="card-title">Производство</span>
            <p class="card-desc">
              Изготавливаем изделие под ваш заказ
            </p>
          </div>
          <div class="wrap-icon">
            <img src="../assets/card-icon-3.svg" alt="" class="icon" />
          </div>
        </div>
      </div>
      <div class="steps-wrapper">
        <div class="rectangle"></div>
        <div class="steps-track">
          <div
            v-for="n in totalSteps"
            :key="n"
            class="step-bead"
            :class="{ active: n === activeStep }"
            :style="{ left: isDesktop ? `${getStepLeft(n)}px` : undefined }"
            @click="goToStep(n)"
          >
            {{ n }}
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  isMobile: {
    type: Boolean,
    default: false
  }
})

const activeStep = ref(1)
const totalSteps = 4
const cardsContainer = ref(null)

const cardWidth = 440
const cardGap = 20

const cardOffset = computed(() => (activeStep.value - 1) * (cardWidth + cardGap))

const isDesktop = computed(() => !props.isMobile)

function getStepLeft(stepNum) {
  if (stepNum === activeStep.value) {
    return 346
  }
  if (stepNum > activeStep.value) {
    return window.innerWidth + 100
  }
  return -200
}

function scrollToCard(step) {
  if (cardsContainer.value && !isDesktop.value) {
    const cardEl = cardsContainer.value.querySelector('.card')
    if (cardEl) {
      const cardWidth = cardEl.offsetWidth
      const gap = 20
      cardsContainer.value.scrollTo({ left: (step - 1) * (cardWidth + gap), behavior: 'smooth' })
    }
  }
}

function nextStep() {
  if (activeStep.value < totalSteps) {
    activeStep.value++
    scrollToCard(activeStep.value)
  }
}

function prevStep() {
  if (activeStep.value > 1) {
    activeStep.value--
    scrollToCard(activeStep.value)
  }
}

let touchStartX = 0
let touchEndX = 0

function onTouchStart(e) {
  touchStartX = e.touches[0].clientX
}

function onTouchMove(e) {
  touchEndX = e.touches[0].clientX
}

function onTouchEnd() {
  if (!isDesktop.value) return
  const diff = touchStartX - touchEndX
  if (Math.abs(diff) > 50) {
    if (diff > 0) {
      nextStep()
    } else {
      prevStep()
    }
  }
}

function onScroll() {
  if (!cardsContainer.value || isDesktop.value) return
  const cardEl = cardsContainer.value.querySelector('.card')
  if (!cardEl) return
  const cardWidth = cardEl.offsetWidth
  const gap = 20
  const scrollLeft = cardsContainer.value.scrollLeft
  const newStep = Math.round(scrollLeft / (cardWidth + gap)) + 1
  if (newStep >= 1 && newStep <= totalSteps && newStep !== activeStep.value) {
    activeStep.value = newStep
  }
}

function goToStep(step) {
  activeStep.value = step
  if (cardsContainer.value && !isDesktop.value) {
    const cardEl = cardsContainer.value.querySelector('.card')
    if (cardEl) {
      const cardWidth = cardEl.offsetWidth
      const gap = 20
      cardsContainer.value.scrollTo({ left: (step - 1) * (cardWidth + gap), behavior: 'smooth' })
    }
  }
}

onMounted(() => {
  if (cardsContainer.value) {
    cardsContainer.value.addEventListener('scroll', onScroll, { passive: true })
  }
})

onUnmounted(() => {
  if (cardsContainer.value) {
    cardsContainer.value.removeEventListener('scroll', onScroll)
  }
})
</script>

<style scoped>
.how-work {
  display: flex;
  flex-direction: column;
  gap: 70px;
  padding: 0 40px;
}

.wrap-title {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

h2 {
  font-size: 44px;
  font-weight: 400;
  color: #000000;
}

.actions {
  display: flex;
  align-items: center;
  gap: 34px;
}

.btn {
  width: 81px;
  height: 56px;
  border-radius: 44px;
  padding: 8px 20px;
  border: none;
  outline: none;
  cursor: pointer;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.btn img {
  width: 24px;
  height: 24px;
  transition: transform 0.3s ease;
}

.btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

.btn:hover img {
  transform: scale(1.1);
}

.btn:active {
  transform: translateY(0);
}

.arrow-left {
  background-color: #fff;
}

.arrow-right {
  background-color: #000000;
}

.arrow-right:hover {
  background-color: #333;
}

.content-wrapper {
  overflow: hidden;
  display: flex;
  flex-direction: column;
  gap: 40px;
}

.cards {
  display: flex;
  gap: 20px;
  align-items: flex-end;
  padding-left: 346px;
  transition: transform 0.5s ease;
}

.cards-mobile {
  overflow-x: auto;
  scroll-snap-type: x mandatory;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: none;
  padding-left: 0;
  justify-content: flex-start;
}

.cards-mobile::-webkit-scrollbar {
  display: none;
}

.card {
  width: 440px;
  padding: 25px 41px 31px 25px;
  box-shadow: 0px 0px 6.8px 0px #00000040;
  border-radius: 4px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  background-color: #fff;
  flex-shrink: 0;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
  transform: translateY(-8px);
  box-shadow: 0px 12px 30px 0px #00000050;
}

.card:nth-child(1) {
  height: 382px;
}
.card:nth-child(2) {
  height: 406px;
}
.card:nth-child(3) {
  height: 430px;
}
.card:nth-child(4) {
  height: 454px;
}

.card-text {
  display: flex;
  flex-direction: column;
  gap: 17px;
}

.card-title {
  font-weight: 500;
  font-size: 28px;
  color: #4B4B4B;
  transition: color 0.3s ease;
}

.card:hover .card-title {
  color: #C96744;
}

.card-desc {
  color: #000000;
  opacity: 0.8;
  font-weight: 300;
  font-size: 20px;
}

.wrap-icon {
  width: 81px;
  height: 56px;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #e9e9e9;
  border-radius: 16px;
  padding: 16px 28px 16px 29px;
  transition: background-color 0.3s ease, transform 0.3s ease;
}

.wrap-icon img {
  width: 32px;
  height: 32px;
}

.card:hover .wrap-icon {
  transform: scale(1.1);
}

.steps-wrapper {
  position: relative;
  height: 56px;
  overflow: hidden;
}

.rectangle {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  left: 346px;
  right: 0;
  background-color: #ececec;
  height: 7px;
}

.steps-track {
  position: relative;
  height: 56px;
}

.step-bead {
  position: absolute;
  top: 0;
  width: 81px;
  height: 56px;
  border-radius: 44px;
  color: #000000;
  font-weight: 400;
  font-size: 24px;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #ffffff;
  transition: left 0.5s ease, background-color 0.5s ease, color 0.5s ease, transform 0.3s ease;
  cursor: pointer;
}

.step-bead:hover {
  transform: scale(1.1);
}

.step-bead.active {
  background-color: #000000;
  color: #ffffff;
  z-index: 10;
}

@media (max-width: 1200px) {
  .how-work {
    gap: 56px;
    padding: 0 32px;
  }

  h2 {
    font-size: 36px;
  }

  .card {
    width: 340px;
  }

  .card:nth-child(1) {
    height: 320px;
  }
  .card:nth-child(2) {
    height: 340px;
  }
  .card:nth-child(3) {
    height: 360px;
  }
  .card:nth-child(4) {
    height: 380px;
  }

  .rectangle {
    left: 0;
  }

  .step-bead {
    width: 64px;
    height: 48px;
    font-size: 20px;
  }

  .step-bead.active {
    left: 0 !important;
  }
}

@media (max-width: 1024px) {
  .how-work {
    gap: 48px;
    padding: 0 24px;
  }

  h2 {
    font-size: 32px;
  }

  .btn {
    width: 64px;
    height: 48px;
    padding: 8px 16px;
  }

  .btn img {
    width: 20px;
    height: 20px;
  }

  .card {
    width: 300px;
    padding: 20px 32px 24px 20px;
  }

  .card:nth-child(1) {
    height: 280px;
  }
  .card:nth-child(2) {
    height: 300px;
  }
  .card:nth-child(3) {
    height: 320px;
  }
  .card:nth-child(4) {
    height: 340px;
  }

  .card-title {
    font-size: 24px;
  }

  .card-desc {
    font-size: 18px;
  }

  .wrap-icon img {
    width: 28px;
    height: 28px;
  }
}

@media (max-width: 768px) {
  .how-work {
    gap: 40px;
    padding: 0 16px;
  }

  h2 {
    font-size: 28px;
  }

  .actions {
    gap: 12px;
  }

  .btn {
    width: 48px;
    height: 40px;
    padding: 6px 12px;
    border-radius: 20px;
  }

  .btn img {
    width: 16px;
    height: 16px;
  }

  .card {
    scroll-snap-align: start;
    width: 280px;
  }

  .card:nth-child(1) {
    height: 260px;
  }
  .card:nth-child(2) {
    height: 280px;
  }
  .card:nth-child(3) {
    height: 300px;
  }
  .card:nth-child(4) {
    height: 320px;
  }

  .card-title {
    font-size: 20px;
  }

  .card-desc {
    font-size: 16px;
  }

  .wrap-icon {
    width: 56px;
    height: 44px;
    padding: 10px 16px;
  }

  .wrap-icon img {
    width: 24px;
    height: 24px;
  }

  .rectangle {
    display: none;
  }

  .steps-wrapper {
    height: 40px;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .steps-track {
    height: 40px;
    display: flex;
    gap: 10px;
    justify-content: center;
    width: 100%;
  }

  .step-bead {
    position: relative;
    left: auto !important;
    width: 48px;
    height: 40px;
    font-size: 16px;
  }
}

@media (max-width: 480px) {
  .how-work {
    gap: 32px;
    padding: 0 12px;
  }

  h2 {
    font-size: 24px;
  }

  .actions {
    gap: 8px;
  }

  .btn {
    width: 40px;
    height: 36px;
    padding: 4px 10px;
    border-radius: 18px;
  }

  .btn img {
    width: 14px;
    height: 14px;
  }

  .card {
    scroll-snap-align: start;
    width: 260px;
    padding: 16px 24px 20px 16px;
  }

  .card:nth-child(1) {
    height: 240px;
  }
  .card:nth-child(2) {
    height: 260px;
  }
  .card:nth-child(3) {
    height: 280px;
  }
  .card:nth-child(4) {
    height: 300px;
  }

  .card-title {
    font-size: 18px;
  }

  .card-desc {
    font-size: 14px;
  }

  .wrap-icon {
    width: 48px;
    height: 40px;
    padding: 8px 12px;
    border-radius: 12px;
  }

  .wrap-icon img {
    width: 20px;
    height: 20px;
  }

  .steps-track {
    gap: 8px;
  }

  .step-bead {
    width: 40px;
    height: 36px;
    font-size: 14px;
  }
}
</style>
