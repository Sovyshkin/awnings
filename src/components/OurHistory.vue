<template>
<div class="container">
    <div class="left">
        <h2>
            Наша история
        </h2>
    </div>
    <div class="right">
        <p>Компания Название была основана в 2010 году группой энтузиастов, которые хотели создавать качественные и красивые конструкции для загородных участков.</p>

        <p>Начав с небольшой мастерской, мы постепенно расширяли производство и географию поставок. Сегодня мы работаем с клиентами по всей России и имеем собственное производство площадью более 2000 квадратных метров.</p>

        <p>Мы гордимся каждым реализованным проектом и стремимся к тому, чтобы наши изделия служили долгие годы, принося радость и комфорт нашим клиентам.</p>
    </div>
</div>
</template>

<style scoped>
.container {
    display: flex;
    gap: 20px;
}

.left, .right {
    width: 50%;
}

h2 {
    color: #000000;
    font-size: 44px;
    font-weight: 400;
}

.right {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.right p {
    color: #000000;
    font-size: 20px;
    font-weight: 300;
    opacity: 0.8;
}

@media (max-width: 1024px) {
    .container {
        flex-direction: column;
        gap: 40px;
    }

    .left, .right {
        width: 100%;
    }

    h2 {
        font-size: 36px;
    }
}

@media (max-width: 768px) {
    h2 {
        font-size: 28px;
    }

    .right p {
        font-size: 16px;
    }
}

@media (max-width: 480px) {
    h2 {
        font-size: 24px;
    }

    .right p {
        font-size: 14px;
    }
}
</style>