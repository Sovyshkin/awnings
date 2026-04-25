<template>
    <footer class="footer">
        <div class="footer-main">
            <div class="footer-name">
                <h2>Название</h2>
                <p>Производство и продажа металлических навесов в Екатеринбурге и Свердловской области. Доставка и монтаж по всей России.</p>
            </div>
            <div class="footer-nav">
                <div class="catalog">
                    <h3>
                        Каталог
                    </h3>
                    <ul class="list-nav">
                        <li class="item-nav">Беседки</li>
                        <li class="item-nav">Мангальные зоны</li>
                        <li class="item-nav">Навесы для авто</li>
                    </ul>
                </div>
                <div class="client">
                    <h3>Покупателям</h3>
                    <ul class="list-nav">
                        <li class="item-nav">О компании</li>
                        <li class="item-nav">Новости и статьи</li>
                        <li class="item-nav">Доставка и оплата</li>
                        <li class="item-nav">Гарантия</li>
                        <li class="item-nav">Контакты</li>
                    </ul>
                </div>
                <div class="contact">
                    <h3>Контакты</h3>
                    <ul class="list-nav">
                        <li class="item-nav">+7 (900) 123-45-67</li>
                        <li class="item-nav">info@navesstroy.ru</li>
                        <li class="item-nav">г. Екатеринбург, ул. Промышленная, д. 4, стр. 2</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-sub">
            <span class="copy">© 2026 Название. Все права защищены.</span>
            <div class="docs">
                <span>Политика конфиденциальности</span>
                <span>Пользовательское соглашение</span>
            </div>
        </div>
    </footer>
</template>
<style scoped>
.footer {
    width: 100%;
    display: flex;
    flex-direction: column;
    padding: 40px;
    gap: 40px;
    background-color: #000;
    border-radius: 4px 4px 0 0;
}

.footer-main {
    display: flex;
    justify-content: space-between;
    gap: 40px;
}

.footer-name {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

h2 {
    color: #FFFFFF;
    font-size: 36px;
    font-weight: 400;
}

p {
    color: #FFFFFF;
    opacity: 0.8;
    font-size: 20px;
    font-weight: 300;
    max-width: 442px;
}

.footer-nav {
    width: 60%;
    display: flex;
    gap: 20px;
    justify-content: space-between;
}

.catalog, .client, .contact {
    display: flex;
    flex-direction: column;
    gap: 49px;
    max-width: 300px;
}

h3 {
    color: #C96744;
    font-size: 28px;
    font-weight: 500;
    transition: color 0.3s ease;
}

h3:hover {
    color: #e07a56;
}

.list-nav {
    display: flex;
    flex-direction: column;
    gap: 24px;
    list-style: none;
}

.item-nav {
    color: #FFFFFF;
    opacity: 0.8;
    font-size: 20px;
    font-weight: 300;
    transition: opacity 0.3s ease, color 0.3s ease, transform 0.3s ease;
    cursor: pointer;
}

.item-nav:hover {
    opacity: 1;
    color: #C96744;
    transform: translateX(4px);
}

.contact .item-nav {
    text-decoration: underline;
}

.contact .item-nav:nth-child(3) {
    text-decoration: none;
    cursor: default;
}

.contact .item-nav:nth-child(3):hover {
    transform: none;
    color: #FFFFFF;
    opacity: 0.8;
}

.footer-sub {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.copy {
    color: #FFFFFF;
    opacity: 0.8;
    font-size: 20px;
    font-weight: 300;
}

.docs {
    display: flex;
    align-items: center;
    gap: 40px;
}

.docs span {
    color: #FFFFFF;
    opacity: 0.8;
    font-size: 20px;
    font-weight: 300;
    cursor: pointer;
    transition: opacity 0.3s ease, color 0.3s ease;
}

.docs span:hover {
    opacity: 1;
    color: #C96744;
}

@media (max-width: 1200px) {
    .footer {
        padding: 32px;
        gap: 32px;
    }

    h2 {
        font-size: 32px;
    }

    p {
        font-size: 18px;
        max-width: 360px;
    }

    .catalog, .client, .contact {
        max-width: 240px;
        gap: 40px;
    }

    h3 {
        font-size: 24px;
    }

    .item-nav {
        font-size: 18px;
    }

    .copy {
        font-size: 18px;
    }

    .docs span {
        font-size: 18px;
    }
}

@media (max-width: 1024px) {
    .footer {
        padding: 24px;
        gap: 24px;
    }

    .footer-main {
        flex-direction: column;
        gap: 32px;
    }

    .footer-name {
        width: 100%;
    }

    p {
        max-width: 100%;
    }

    .footer-nav {
        width: 100%;
        gap: 16px;
    }

    .catalog, .client, .contact {
        max-width: 200px;
        gap: 32px;
    }

    h3 {
        font-size: 22px;
    }

    .item-nav {
        font-size: 16px;
        gap: 20px;
    }
}

@media (max-width: 768px) {
    .footer {
        padding: 20px 16px;
        gap: 20px;
    }

    h2 {
        font-size: 28px;
    }

    p {
        font-size: 16px;
    }

    .footer-nav {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 32px;
    }

    .catalog, .client {
        max-width: 100%;
        gap: 24px;
    }

    .contact {
        grid-column: 1 / -1;
        grid-row: 2;
        max-width: 100%;
        gap: 24px;
    }

    h3 {
        font-size: 20px;
    }

    .list-nav {
        gap: 16px;
    }

    .item-nav {
        font-size: 14px;
    }

    .footer-sub {
        flex-direction: column;
        gap: 16px;
        text-align: center;
    }

    .copy {
        font-size: 14px;
    }

    .docs {
        flex-direction: column;
        gap: 12px;
    }

    .docs span {
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .footer {
        padding: 16px 12px;
        gap: 16px;
    }

    h2 {
        font-size: 24px;
    }

    p {
        font-size: 14px;
    }

    .footer-nav {
        gap: 16px;
    }

    h3 {
        font-size: 18px;
    }

    .item-nav {
        font-size: 13px;
    }

    .copy {
        font-size: 12px;
    }

    .docs span {
        font-size: 12px;
    }
}
</style>