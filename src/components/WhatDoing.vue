<template>
  <section class="what-we-doing">
    <h1>
      Беседки, навесы и мангальные зоны для тех, кто ценит удобство и
      современный внешний вид
    </h1>
      <h2>Что мы делаем</h2>
      <div class="cards">
        <div class="card">
          <div class="wrap-img">
            <img src="../assets/card.png" alt="" />
          </div>
          <div class="category">
            <div class="circle"></div>
            <span class="text-category">Сад</span>
          </div>
          <span class="card-title">Беседка для отдыха</span>
        </div>
        <div class="card">
          <div class="wrap-img">
            <img src="../assets/card.png" alt="" />
          </div>
          <div class="category">
            <div class="circle"></div>
            <span class="text-category">Барбекю</span>
          </div>
          <span class="card-title">Мангальные зоны</span>
        </div>
        <div class="card">
          <div class="wrap-img">
            <img src="../assets/card.png" alt="" />
          </div>
          <div class="category">
            <div class="circle"></div>
            <span class="text-category">Авто</span>
          </div>
          <span class="card-title">Навесы для автомобилей</span>
        </div>
      </div>
  </section>
</template>
<style scoped>
.what-we-doing {
    display: flex;
    flex-direction: column;
    gap: 70px;
    padding: 0 40px;
}

h1 {
    width: 920px;
    margin: 0 auto;
    color: #000000;
    text-align: center;
    font-size: 52px;
    font-weight: 400;
    margin-bottom: 35px;
}

h2 {
   color: #000000;
   font-weight: 400;
   font-size: 44px;
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

.category {
    display: flex;
    gap: 10px;
    align-items: center;
}

.circle {
    background-color: #000000;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    transition: transform 0.3s ease, background-color 0.3s ease;
}

.card:hover .circle {
    transform: scale(1.3);
    background-color: #C96744;
}

.text-category {
    color: #BCBCBC;
    font-size: 16px;
    font-weight: 500;
    transition: color 0.3s ease;
}

.card:hover .text-category {
    color: #000000;
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

@media (max-width: 1200px) {
    .what-we-doing {
        gap: 56px;
        padding: 0 32px;
    }

    h1 {
        font-size: 42px;
        width: 720px;
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
}

@media (max-width: 1024px) {
    .what-we-doing {
        gap: 48px;
        padding: 0 24px;
    }

    h1 {
        font-size: 36px;
        width: 600px;
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

    .text-category {
        font-size: 14px;
    }

    .card-title {
        font-size: 18px;
    }
}

@media (max-width: 768px) {
    .what-we-doing {
        gap: 40px;
        padding: 0 16px;
    }

    h1 {
        font-size: 28px;
        width: 100%;
    }

    h2 {
        font-size: 28px;
    }

    .cards {
        flex-direction: column;
        gap: 24px;
    }

    .wrap-img {
        height: 240px;
    }

    .card {
        flex-direction: row;
        align-items: center;
        gap: 16px;
    }

    .card:hover {
        transform: translateX(8px);
    }

    .card > .wrap-img {
        width: 40%;
        height: 160px;
    }

    .card-title {
        font-size: 16px;
    }
}

@media (max-width: 480px) {
    .what-we-doing {
        gap: 32px;
        padding: 0 12px;
    }

    h1 {
        font-size: 22px;
    }

    h2 {
        font-size: 24px;
    }

    .wrap-img {
        height: 200px;
    }

    .card {
        flex-direction: column;
        align-items: flex-start;
    }

    .card > .wrap-img {
        width: 100%;
        height: 200px;
    }

    .text-category {
        font-size: 12px;
    }

    .card-title {
        font-size: 14px;
    }
}
</style>
