<template>
  <section class="how-work">
    <div class="wrap-title">
      <h2>Наши проекты</h2>
      <div class="actions">
        <button class="btn arrow-left" @click="prevStep">
          <img src="../assets/arrow-left.svg" alt="" />
        </button>
        <button class="btn arrow-right" @click="nextStep">
          <img src="../assets/arrow-right.svg" alt="" />
        </button>
      </div>
    </div>
      <div class="cards">
        <div class="card">
          <div class="wrap-img">
            <img src="../assets/card.png" alt="" />
          </div>
          <span class="card-title">Беседка 6м2</span>
          <span class="card-price">от 126 000 ₽</span>
          <button class="card-btn">В конфигуратор модели <div class="wrap-btn-img"><img class="btn-img" src="../assets/arrow-up-right.svg" alt=""></div></button>
        </div>
        <div class="card">
          <div class="wrap-img">
            <img src="../assets/card.png" alt="" />
          </div>
          <span class="card-title">Мангальная зона Стандарт</span>
          <span class="card-price">от 126 000 ₽</span>
          <button class="card-btn">В конфигуратор модели <div class="wrap-btn-img"><img class="btn-img" src="../assets/arrow-up-right.svg" alt=""></div></button>
        </div>
        <div class="card">
          <div class="wrap-img">
            <img src="../assets/card.png" alt="" />
          </div>
          <span class="card-title">Навес для автомобиля 6м2</span>
          <span class="card-price">от 126 000 ₽</span>
          <button class="card-btn">В конфигуратор модели <div class="wrap-btn-img"><img class="btn-img" src="../assets/arrow-up-right.svg" alt=""></div></button>
        </div>
      </div>
      <div class="wrap-catalog">
        <button class="btn-catalog">Каталог</button>
      </div>
  </section>
</template>

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
    transition: transform 0.5s ease;
}

.card:hover .wrap-img img {
    transform: scale(1.05);
}

.card-title {
    color: #000000;
    font-size: 24px;
    font-weight: 400;
    transition: color 0.3s ease;
}

.card:hover .card-title {
    color: #C96744;
}

.card-price {
    color: #C96744;
    font-size: 28px;
    font-weight: 400;
    line-height: 100%;
    transition: transform 0.3s ease;
}

.card:hover .card-price {
    transform: scale(1.05);
}

.card-btn {
  padding: 9px 9px 9px 45px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  background-color: #fff;
  border-radius: 44px;
  border: none;
  height: 81px;
  width: 100%;
  color: #000000;
  font-weight: 600;
  font-size: 16px;
  backdrop-filter: blur(20.899999618530273px);
  box-shadow: 0px 4px 4px 0px #00000040;
  cursor: pointer;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card-btn:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
}

.card-btn:active {
  transform: translateY(0);
}

.wrap-btn-img {
  padding: 19px 17px 16px 17px;
  background-color: #000;
  border-radius: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.3s ease, transform 0.3s ease;
}

.card-btn:hover .wrap-btn-img {
  background-color: #C96744;
  transform: rotate(15deg);
}

.wrap-catalog {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.btn-catalog {
    height: 56px;
    border-radius: 44px;
    padding: 19px 65px;
    font-weight: 600;
    font-size: 16px;
    background-color: #000;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
}

.btn-catalog:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
  background-color: #333;
}

.btn-catalog:active {
  transform: translateY(0);
}

@media (max-width: 1200px) {
  .how-work {
    gap: 56px;
    padding: 0 32px;
  }

  h2 {
    font-size: 36px;
  }

  .wrap-img {
    height: 340px;
  }

  .card-title {
    font-size: 20px;
  }

  .card-price {
    font-size: 24px;
  }

  .card-btn {
    height: 72px;
    font-size: 14px;
  }

  .btn-catalog {
    height: 48px;
    padding: 14px 48px;
    font-size: 14px;
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

  .cards {
    gap: 16px;
  }

  .wrap-img {
    height: 280px;
  }

  .card {
    gap: 16px;
  }

  .card-title {
    font-size: 18px;
  }

  .card-price {
    font-size: 22px;
  }

  .card-btn {
    padding: 8px 8px 8px 32px;
    height: 64px;
    font-size: 13px;
  }

  .wrap-btn-img {
    padding: 14px 12px 12px 12px;
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
    display: none;
  }

  .cards {
    flex-direction: column;
    gap: 24px;
  }

  .wrap-img {
    height: 240px;
  }

  .card-price {
    font-size: 20px;
  }

  .card-btn {
    height: 56px;
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

  .wrap-img {
    height: 200px;
  }

  .card-title {
    font-size: 16px;
  }

  .card-price {
    font-size: 18px;
  }

  .card-btn {
    padding: 6px 6px 6px 20px;
    height: 48px;
    font-size: 11px;
  }

  .wrap-btn-img {
    padding: 10px 8px 8px 8px;
  }

  .btn-img {
    width: 16px;
    height: 16px;
  }

  .btn-catalog {
    height: 44px;
    padding: 12px 36px;
    font-size: 12px;
    width: 100%;
  }
}
</style>
