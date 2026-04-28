<template>
    <section class="why-choose-us">
        <h1>Практичность, современный вид 
и продуманная функция.</h1>
        <div class="wrap-content">
            <div class="text">
                <h2>Почему вы выбираете нас?</h2>
                <p>Быстрые сроки и высокое качество работы, а так же конфигуратор моделей под любой бюджет</p>
            </div>
            <div class="cards">
                <div class="card">
                    <div class="wrap-img">
                        <img src="../assets/why-us-1.svg" alt="">
                    </div>
                    <div class="card-text">
                        <span class="card-title">Гарантия до 15 лет</span>
                        <p class="card-desc">Даём письменную гарантию на конструкцию и монтаж
</p>
                    </div>
                </div>
                <div class="card">
                    <div class="wrap-img">
                        <img src="../assets/why-us-2.svg" alt="">
                    </div>
                    <div class="card-text">
                        <span class="card-title">Доставка по России</span>
                        <p class="card-desc">Отправим в любой регион — транспортной компанией или своим транспортом</p>
                    </div>
                </div>
                <div class="card">
                    <div class="wrap-img">
                        <img src="../assets/why-us-3.svg" alt="">
                    </div>
                    <div class="card-text">
                        <span class="card-title">Монтаж под ключ</span>
                        <p class="card-desc">Наша бригада установит навес за 1–2 дня без вашего участия
</p>
                    </div>
                </div>
                <div class="card">
                    <div class="wrap-img">
                        <img src="../assets/why-us-4.svg" alt="">
                    </div>
                    <div class="card-text">
                        <span class="card-title">Консультация бесплатно</span>
                        <p class="card-desc">Позвоните или оставьте заявку — подберём модель под ваши задачи</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<style scoped>
.why-choose-us {
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

.wrap-content {
    display: flex;
    gap: 20px;
}

.text {
    width: 50%;
    display: flex;
    flex-direction: column;
    gap: 36px;
}

h2 {
    color: #000000;
    font-size: 44px;
    font-weight: 400;
    max-width: 442px;
}

.text p {
    color: #000000;
    font-size: 20px;
    font-weight: 300;
    opacity: 0.8;
    max-width: 442px;
}

.cards {
    width: 50%;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.card {
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

.card:hover {
    transform: translateX(8px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
}

.wrap-img {
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
}

.card:hover .wrap-img {
    transform: scale(1.05);
}

.wrap-img img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    max-width: 100%;
    max-height: 100%;
}

.card-text {
    width: 70%;
    display: flex;
    flex-direction: column;
    gap: 17px;
}

.card-title {
    color: #4B4B4B;
    font-size: 28px;
    font-weight: 500;
    transition: color 0.3s ease;
}

.card:hover .card-title {
    color: #C96744;
}

.card-desc {
    color: #000000;
    font-size: 20px;
    font-weight: 300;
    opacity: 0.8;
}

@media (max-width: 1200px) {
    .why-choose-us {
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

    .card {
        padding: 8px 48px 8px 8px;
        min-height: 140px;
    }

    .wrap-img {
        width: 110px;
        height: 110px;
    }

    .card-title {
        font-size: 24px;
    }

    .card-desc {
        font-size: 18px;
    }
}

@media (max-width: 1024px) {
    .why-choose-us {
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

    .text p {
        font-size: 18px;
    }

    .card {
        padding: 8px 32px 8px 8px;
        min-height: 120px;
    }

    .wrap-img {
        width: 100px;
        height: 100px;
    }

    .card-title {
        font-size: 20px;
    }

    .card-desc {
        font-size: 16px;
    }
}

@media (max-width: 768px) {
    .why-choose-us {
        gap: 40px;
        padding: 0 16px;
    }

    h1 {
        font-size: 28px;
        width: 100%;
    }

    .wrap-content {
        flex-direction: column;
        gap: 40px;
    }

    .text {
        width: 100%;
    }

    h2 {
        font-size: 28px;
        max-width: 100%;
    }

    .cards {
        width: 100%;
    }

    .card {
        padding: 8px 24px 8px 8px;
        height: 120px;
        gap: 12px;
    }

    .wrap-img {
        width: 100px;
        height: 100px;
    }

    .card-text {
        width: 65%;
    }

    .card-title {
        font-size: 18px;
    }

    .card-desc {
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .why-choose-us {
        gap: 32px;
        padding: 0 12px;
    }

    h1 {
        font-size: 22px;
    }

    h2 {
        font-size: 24px;
    }

    .text p {
        font-size: 16px;
    }

    .card {
        flex-direction: row;
        padding: 12px;
        height: 100px;
        gap: 12px;
    }

    .card:hover {
        transform: translateX(4px);
    }

    .wrap-img {
        width: 80px;
        height: 80px;
        flex-shrink: 0;
    }

    .card-text {
        width: 100%;
    }

    .card-title {
        font-size: 16px;
    }

    .card-desc {
        font-size: 14px;
    }
}
</style>