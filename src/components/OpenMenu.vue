<template>
    <div class="open-menu">
        <div class="content">
            <div class="nav-menu">
                <div class="list-group">
                    <a href="#" class="list-group-item">Беседки</a>
                    <a href="#" class="list-group-item">Мангальные зоны</a>
                    <a href="#" class="list-group-item">Навесы для авто</a>
                </div>
                <div class="wrap-list">
                    <div class="list-group">
                    <a href="#" class="list-group-item">О компании</a>
                    <a href="#" class="list-group-item">Новости</a>
                    <a href="#" class="list-group-item">Контакты</a>
                </div>
                <div class="list-group">
                    <a href="#" class="list-group-item">Гарантия</a>
                    <a href="#" class="list-group-item">Доставка</a>
                </div>
                </div>
            </div>
            <div class="menu-footer">
                <span>г. Екатеринбург, ул. Промышленная, д. 4, стр. 2</span>
                <span>+7 (900) 123-45-67</span>
            </div>
        </div>
        <div class="wrap-img">
            <img src="../assets/company-card-1.png" alt="">
        </div>
    </div>
</template>
<style scoped>
.open-menu {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 40px;
    padding: 200px 120px 60px 120px;
    background-color: black;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 99;
}

.content {
    width: 70%;
    display: flex;
    flex-direction: column;
    gap: 100px;
}

.wrap-img {
    width: 30%;
    border-radius: 15px;
    height: 307px;
    overflow: hidden;
    position: relative;
}

.wrap-img::before {
    content: '';
    position: absolute;
    inset: 0;
    background: #00000080;
    pointer-events: none;
    z-index: 0;
}

.wrap-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.nav-menu {
    display: flex;
    justify-content: space-between;
}

.list-group {
    display: flex;
    flex-direction: column;
    gap: 48px;
}

.list-group-item {
    font-size: 20px;
    font-weight: 600;
    color: #ffffff;
    text-decoration: none;
}

.wrap-list {
    display: flex;
    opacity: 0.8;
    gap: 58px;
}

.wrap-list .list-group-item {
    font-weight: 300;
}

.menu-footer {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 10px;
    border-top: 1px solid #ffffff;
}

.menu-footer span {
    font-size: 18px;
    font-weight: 400;
    color: #FFFFFF91;
}

@media (max-width: 1024px) {
    .open-menu {
        padding: 160px 60px 50px 60px;
    }

    .content {
        gap: 60px;
    }

    .list-group {
        gap: 32px;
    }

    .list-group-item {
        font-size: 18px;
    }

    .wrap-list {
        gap: 40px;
    }

    .menu-footer span {
        font-size: 16px;
    }
}

@media (max-width: 768px) {
    .open-menu {
        padding: 140px 32px 40px 32px;
        flex-direction: column;
        gap: 32px;
    }

    .content {
        width: 100%;
        gap: 48px;
    }

    .wrap-img {
        display: none;
    }

    .nav-menu {
        flex-direction: column;
        gap: 40px;
    }

    .wrap-list {
        flex-direction: column;
        gap: 32px;
    }

    .list-group {
        gap: 24px;
    }

    .list-group-item {
        font-size: 16px;
    }

    .menu-footer {
        flex-direction: column;
        gap: 16px;
        text-align: center;
    }

    .menu-footer span {
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .open-menu {
        padding: 120px 24px 32px 24px;
        gap: 24px;
    }

    .content {
        gap: 32px;
    }

    .nav-menu {
        gap: 32px;
    }

    .list-group {
        gap: 20px;
    }

    .list-group-item {
        font-size: 15px;
    }

    .menu-footer span {
        font-size: 13px;
    }
}
</style>