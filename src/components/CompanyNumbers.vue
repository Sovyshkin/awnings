<template>
  <section class="company-numbers">
    <h2>Компания в цифрах</h2>
    <div class="cards">
      <div class="card">
        <div class="card-wrap-title">
          <span class="card-title">15</span>
          <div class="card-rectangle"></div>
          <span class="card-subtitle">лет на рынке</span>
        </div>
        <div class="card-desc">
          <span
            >проектируем и устанавливаем конструкции, которые выдерживают
            реальные условия эксплуатации</span
          >
        </div>
      </div>
      <div class="card">
        <div class="card-wrap-title">
          <span class="card-title">3 200+</span>
          <div class="card-rectangle"></div>
          <span class="card-subtitle">установленных навесов</span>
        </div>
        <div class="card-desc">
          <span
            >Отработали десятки сценариев: частные участки, коммерческие
            объекты, нестандартные решения</span
          >
        </div>
      </div>
      <div class="card">
        <div class="card-wrap-title">
          <span class="card-title">52</span>
          <div class="card-rectangle"></div>
          <span class="card-subtitle">города доставки</span>
        </div>
        <div class="card-desc">
          <span
            >Организуем логистику и монтаж так, чтобы вы получили готовый
            результат без срывов и "по месту разберёмся"</span
          >
        </div>
      </div>
    </div>
  </section>
</template>

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
  max-width: 440px;
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

.card:nth-child(1) {
  background-image: url("../assets/company-card-1.png");
}
.card:nth-child(2) {
  background-image: url("../assets/company-card-2.png");
}
.card:nth-child(3) {
  background-image: url("../assets/company-card-3.jpg");
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
    max-width: 360px;
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

  .card {
    height: 300px;
    max-width: 300px;
  }

  .card-title {
    font-size: 64px;
  }

  .card-subtitle {
    font-size: 18px;
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
    gap: 24px;
  }

  .card {
    height: 280px;
    max-width: 100%;
  }

  .card-title {
    font-size: 56px;
  }

  .card-subtitle {
    font-size: 16px;
  }

  .card-desc {
    height: 64px;
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
    height: 240px;
  }

  .card-title {
    font-size: 48px;
  }

  .card-subtitle {
    font-size: 14px;
  }

  .card-desc {
    height: 56px;
    padding: 10px 14px 10px 24px;
  }

  .card-desc span {
    font-size: 12px;
  }
}
</style>