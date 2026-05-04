<template>
    <section class="our-works">
        <h1>Наши работы</h1>
        <div class="cards">
          <div class="card" v-for="i in 9" :key="i" @click="goToArticle(i)">
            <div class="wrap-img">
              <img src="../assets/company-card-1.png" alt="">
            </div>
          </div>
        </div>
    </section>
</template>
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

.card:hover .wrap-img {
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.wrap-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
    position: relative;
    z-index: 0;
}

.our-works h1 {
    font-size: 44px;
    font-weight: 400;
    color: #000000;
}

@media (max-width: 1200px) {
    .our-works h1 {
        font-size: 40px;
    }

    .cards {
        gap: 16px;
    }

    .card {
        width: calc(33.333% - 16px);
    }

    .wrap-img {
        height: 260px;
    }
}

@media (max-width: 1024px) {
    .our-works h1 {
        font-size: 36px;
    }

    .cards {
        row-gap: 40px;
        column-gap: 12px;
    }

    .card {
        width: calc(33.333% - 12px);
        gap: 20px;
    }

    .wrap-img {
        height: 200px;
    }
}

@media (max-width: 768px) {
    .our-works {
        gap: 40px;
    }

    .our-works h1 {
        font-size: 28px;
    }

    .cards {
        row-gap: 12px;
        column-gap: 12px;
    }

    .card {
        width: calc(50% - 6px);
        gap: 12px;
    }

    .wrap-img {
        height: 180px;
    }
}

@media (max-width: 480px) {
    .our-works {
        gap: 32px;
    }

    .our-works h1 {
        font-size: 24px;
    }

    .cards {
        row-gap: 10px;
        column-gap: 10px;
    }

    .card {
        width: 100%;
        gap: 10px;
    }

    .wrap-img {
        height: 180px;
    }
}
</style>