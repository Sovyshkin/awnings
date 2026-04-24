<template>
  <section class="hero-section">
    <h1 class="title">Современные модульные решения для участка в едином стиле</h1>
    <div class="main-content">
      <div class="list-group">
        <div class="group">
          <img class="group-img" src="../assets/group-1.svg" alt="">
          <div class="group-content">
            <span>Единый стиль участка</span>
            <p>Все решения визуально сочетаются между собой.</p>
          </div>
        </div>
        <div class="group">
          <img class="group-img" src="../assets/group-2.svg" alt="">
          <div class="group-content">
            <span>Продуманная конструкция</span>
            <p>Надёжный каркас, современные материалы, чистая геометрия.</p>
          </div>
        </div>
        <div class="group">
          <img class="group-img" src="../assets/group-3.svg" alt="">
          <div class="group-content">
            <span>Готовые комплектации</span>
            <p>Понятный выбор без лишней сложности.</p>
          </div>
        </div>
      </div>
      <div class="h2-block">
        <h2>Беседки для отдыха, мангальные зоны для встреч, навесы для авто для повседневного удобства.</h2>
        <button class="btn-h2">Посмотреть комплектации <div class="wrap-btn-img"><img class="btn-img" src="../assets/arrow-up-right.svg" alt=""></div></button>
      </div>
      
    </div>
  </section>
</template>
<style scoped>
.hero-section {
  position: relative;
  width: 100%;
  min-height: 100vh;
  margin: 0;
  padding: 250px 40px 40px 40px;
  background-image: url('../assets/hero-bg.png');
  background-size: cover;
  background-position: center;
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
  gap: 82px;
}

.hero-section::before {
  content: '';
  position: absolute;
  inset: 0;
  background: #00000080;
  pointer-events: none;
  z-index: 0;
}

.hero-section > * {
  position: relative;
  z-index: 1;
}

.title {
  max-width: 920px;
  font-weight: 400;
  font-size: 52px;
  color: #fff;
}

.main-content {
  display: flex;
  justify-content: space-between;
  align-items: end;
  gap: 40px;
}

.list-group {
  display: flex;
  flex-direction: column;
  gap: 54px;
}

.group {
  padding: 10px 11px 11px 37px;
  height: 81px;
  width: 374px;
  display: flex;
  align-items: center;
  gap: 27px;
  box-shadow: 0px 4px 4px 0px #00000040;
  backdrop-filter: blur(20.899999618530273px);
  border-radius: 44px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  cursor: pointer;
}

.group:hover {
  transform: translateX(8px);
  box-shadow: 0px 8px 20px 0px #00000050;
}

.group-img {
  width: 32px;
  height: 32px;
  transition: transform 0.3s ease;
}

.group:hover .group-img {
  transform: scale(1.1);
}

.group-content {
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.group-content span {
  font-weight: 600;
  font-size: 16px;
  color: #fff;
  transition: color 0.3s ease;
}

.group:hover .group-content span {
  color: #C96744;
}

.group-content p {
  font-weight: 300;
  font-size: 16px;
  color: #fff;
  opacity: 0.8;
}

.h2-block {
  display: flex;
  flex-direction: column;
  gap: 47px;
  width: 60%;
}

h2 {
  color: #fff;
  font-weight: 400;
  font-size: 28px;
}

.btn-h2 {
  padding: 9px 9px 9px 45px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  background-color: #fff;
  border-radius: 44px;
  height: 81px;
  width: 374px;
  color: #000000;
  font-weight: 600;
  font-size: 16px;
  border: none;
  cursor: pointer;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.btn-h2:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
}

.btn-h2:active {
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

.btn-h2:hover .wrap-btn-img {
  background-color: #C96744;
  transform: rotate(15deg);
}

@media (max-width: 1200px) {
  .hero-section {
    padding: 200px 32px 32px 32px;
    gap: 60px;
  }

  .title {
    font-size: 42px;
    max-width: 720px;
  }

  .main-content {
    flex-direction: column;
    align-items: flex-start;
    gap: 32px;
  }

  .h2-block {
    width: 100%;
  }

  h2 {
    font-size: 24px;
  }
}

@media (max-width: 1024px) {
  .hero-section {
    padding: 180px 24px 24px 24px;
    gap: 50px;
  }

  .title {
    font-size: 36px;
    max-width: 600px;
  }

  .group {
    width: 100%;
    max-width: 340px;
    height: 72px;
  }

  .btn-h2 {
    width: 100%;
    max-width: 340px;
    height: 72px;
  }
}

@media (max-width: 768px) {
  .hero-section {
    padding: 140px 16px 20px 16px;
    gap: 40px;
  }

  .title {
    font-size: 28px;
    max-width: 100%;
  }

  .list-group {
    gap: 32px;
  }

  .group {
    padding: 8px 10px 10px 24px;
    height: 64px;
    gap: 16px;
  }

  .group-img {
    width: 28px;
    height: 28px;
  }

  .group-content span {
    font-size: 14px;
  }

  .group-content p {
    font-size: 12px;
  }

  .h2-block {
    gap: 28px;
  }

  h2 {
    font-size: 18px;
  }

  .btn-h2 {
    padding: 8px 8px 8px 28px;
    height: 64px;
    font-size: 14px;
  }

  .wrap-btn-img {
    padding: 14px 12px 12px 12px;
  }

  .btn-img {
    width: 18px;
    height: 18px;
  }
}

@media (max-width: 480px) {
  .hero-section {
    padding: 120px 12px 16px 12px;
    gap: 32px;
  }

  .title {
    font-size: 22px;
  }

  .group {
    padding: 6px 8px 8px 16px;
    height: 56px;
    gap: 12px;
  }

  .group-img {
    width: 24px;
    height: 24px;
  }

  .group-content span {
    font-size: 12px;
  }

  .group-content p {
    font-size: 10px;
  }

  h2 {
    font-size: 14px;
  }

  .btn-h2 {
    padding: 6px 6px 6px 20px;
    height: 56px;
    font-size: 12px;
  }

  .wrap-btn-img {
    padding: 12px 10px 10px 10px;
  }

  .btn-img {
    width: 14px;
    height: 14px;
  }
}
</style>