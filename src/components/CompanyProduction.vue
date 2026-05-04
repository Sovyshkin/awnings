<template>
  <section class="how-work">
    <div class="wrap-title">
      <h2>Наше производство</h2>
    </div>
    <div class="content-wrapper">
      <div class="cards">
        <div class="card">
          <div class="card-text">
            <span class="card-title">Современное оборудование</span>
            <p class="card-desc">
              Станки с ЧПУ и профессиональный инструмент
            </p>
          </div>
          <div class="wrap-icon">
            <img src="../assets/card-icon-1.svg" alt="" class="icon" />
          </div>
        </div>
        <div class="card">
          <div class="card-text">
            <span class="card-title">Контроль качества</span>
            <p class="card-desc">
              Проверка на каждом этапе производства
            </p>
          </div>
          <div class="wrap-icon">
            <img src="../assets/card-icon-2.svg" alt="" class="icon" />
          </div>
        </div>
        <div class="card">
          <div class="card-text">
            <span class="card-title">Собственный склад</span>
            <p class="card-desc">
              Всегда в наличии популярные материалы
            </p>
          </div>
          <div class="wrap-icon">
            <img src="../assets/card-icon-3.svg" alt="" class="icon" />
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.how-work {
  display: flex;
  flex-direction: column;
  gap: 70px;
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

.content-wrapper {
  display: flex;
  flex-direction: column;
  gap: 40px;
}

.cards {
  display: flex;
  gap: 20px;
  align-items: flex-end;
  justify-content: center;
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

@media (max-width: 1200px) {
  .how-work {
    gap: 56px;
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

  .card-title {
    font-size: 24px;
  }

  .card-desc {
    font-size: 18px;
  }

  .wrap-icon {
    width: 72px;
    height: 50px;
    padding: 12px 24px;
  }

  .wrap-icon img {
    width: 28px;
    height: 28px;
  }
}

@media (max-width: 1024px) {
  .how-work {
    gap: 48px;
  }

  h2 {
    font-size: 32px;
  }

  .cards {
    flex-wrap: wrap;
    justify-content: center;
  }

  .card {
    width: calc(50% - 10px);
    max-width: 300px;
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
  }

  h2 {
    font-size: 28px;
  }

  .cards {
    flex-direction: column;
    align-items: center;
    gap: 16px;
  }

  .card {
    width: 100%;
    max-width: 400px;
    height: auto !important;
    min-height: 180px;
    flex-direction: row;
    align-items: center;
    padding: 20px;
    gap: 20px;
  }

  .card-text {
    flex: 1;
    gap: 12px;
  }

  .card-title {
    font-size: 20px;
  }

  .card-desc {
    font-size: 16px;
  }

  .wrap-icon {
    width: 80px;
    height: 80px;
    padding: 16px;
    flex-shrink: 0;
  }

  .wrap-icon img {
    width: 48px;
    height: 48px;
  }
}

@media (max-width: 480px) {
  .how-work {
    gap: 32px;
  }

  h2 {
    font-size: 24px;
  }

  .cards {
    gap: 12px;
  }

  .card {
    flex-direction: row;
    align-items: center;
    max-width: 100%;
    min-height: 120px;
    padding: 16px;
    gap: 16px;
  }

  .card-text {
    flex: 1;
    gap: 8px;
  }

  .card-title {
    font-size: 16px;
  }

  .card-desc {
    font-size: 14px;
  }

  .wrap-icon {
    width: 64px;
    height: 64px;
    padding: 12px;
    flex-shrink: 0;
  }

  .wrap-icon img {
    width: 40px;
    height: 40px;
  }
}
</style>
