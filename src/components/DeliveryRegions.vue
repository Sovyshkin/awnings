<template>
    <section class="delivery-regions">
        <div class="wrap-title">
            <h1>Регионы доставки</h1>
            <p class="subtitle">Осуществляем доставку и установку по всей территории России</p>
        </div>
        <ul class="regions">
            <li class="region" v-for="region in regions" :key="region">{{ region }}</li>
        </ul>
    </section>
</template>

<script setup>
const regions = [
    'Московская область',
    'Краснодарский край',
    'Воронежская область',
    'Ростовская область',
    'Ленинградская область',
    'Свердловская область',
    'Волгоградская область',
    'Тульская область',
    'Республика Татарстан',
    'Новосибирская область',
    'Нижегородская область',
    'Тверская область'
]
</script>

<style scoped>
.delivery-regions {
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 80px;
    overflow: hidden;
}

.delivery-regions::after {
    content: '';
    position: absolute;
    right: -150px;
    top: 50%;
    transform: translateY(-50%);
    width: 700px;
    height: 700px;
    background: url('../assets/delivery-regions.png') center/contain no-repeat;
    pointer-events: none;
    z-index: -1;
}

.wrap-title {
    display: flex;
    flex-direction: column;
    gap: 20px;
    max-width: 442px;
}

h1 {
    color: #000000;
    font-size: 44px;
    font-weight: 400;

}

.subtitle {
    color: #000000;
    font-size: 20px;
    font-weight: 300;
    opacity: 0.8;
}

.regions {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.region {
    width: calc((100% - 60px) / 4);
    color: #000000;
    font-size: 20px;
    font-weight: 300;
    opacity: 0.8;
    position: relative;
    padding-left: 30px;
}

.region::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 8px;
    height: 8px;
    background-color: #C96744;
    border-radius: 50%;
}

@media (max-width: 1200px) {
    .delivery-regions {
        gap: 56px;
    }

    h1 {
        font-size: 40px;
    }

    .subtitle {
        font-size: 18px;
    }

    .region {
        font-size: 18px;
    }
}

@media (max-width: 1024px) {
    .delivery-regions {
        gap: 48px;
    }

    h1 {
        font-size: 36px;
    }

    .subtitle {
        font-size: 16px;
    }

    .region {
        font-size: 16px;
        width: calc((100% - 40px) / 4);
    }
}

@media (max-width: 768px) {
    .delivery-regions {
        gap: 40px;
    }

    h1 {
        font-size: 28px;
    }

    .subtitle {
        font-size: 14px;
    }

    .regions {
        padding-left: 0;
    }

    .region {
        width: calc((100% - 20px) / 2);
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .delivery-regions {
        gap: 32px;
    }

    h1 {
        font-size: 24px;
    }

    .subtitle {
        font-size: 12px;
    }

    .region {
        width: 100%;
        font-size: 14px;
    }
}
</style>