<template>
  <section class="contacts-page">
    <div class="header">
      <div class="breadcrumbs">
        <router-link to="/">Главная</router-link>
        <span>/</span>
        <router-link to="/news-articles">Контакты</router-link>
      </div>
    </div>
    <div class="contacts-content">
      <div class="info">
        <div class="cards">
          <div class="card">
            <div class="wrap-img">
              <img src="../assets/contact-1.svg" alt="" />
            </div>
            <div class="card-text">
              <span class="text-title">Телефон</span>
              <span class="text-value">+7 (900) 123-45-67</span>
            </div>
          </div>
          <div class="card">
            <div class="wrap-img">
              <img src="../assets/contact-2.svg" alt="" />
            </div>
            <div class="card-text">
              <span class="text-title">Email</span>
              <span class="text-value">info@navesstroy.ru</span>
            </div>
          </div>
          <div class="card">
            <div class="wrap-img">
              <img src="../assets/contact-3.svg" alt="" />
            </div>
            <div class="card-text">
              <span class="text-title">Адрес</span>
              <span class="text-value"
                >г. Екатеринбург, ул. Промышленная, д. 4, стр. 2</span
              >
            </div>
          </div>
          <div class="card">
            <div class="wrap-img">
              <img src="../assets/contact-4.svg" alt="" />
            </div>
            <div class="card-text">
              <span class="text-title">Режим работы</span>
              <span class="text-value">Пн-Вс: 9:00-18:00</span>
            </div>
          </div>
        </div>
        <div class="map" id="YMapsID"></div>
      </div>
      <div class="form">
        <div class="form-wrap-title">
            <h2 class="form-title">Остались вопросы?</h2>
            <p class="form-subtitle">Оставьте заявку и наш менеджер свяжется с вами, что бы ответить на ваши вопросы!</p>
        </div>
        <form action="">
            <div class="group-input">
                <label for="">Ваше имя</label>
                <input type="text" placeholder="Введите ваше имя">
            </div>
            <div class="group-input">
                <label for="">Ваш телефон для связи</label>
                <input type="text" placeholder="Введите ваше номер телефона">
            </div>
            <div class="group-input">
                <label for="">Ваш вопрос</label>
                <input type="text" placeholder="Напишите интересующий вас вопрос">
            </div>
            <div class="group-check">
                <input type="checkbox">
                <label for="">Я даю согласие на обработку персональных данных</label>
            </div>
            <button class="btn">Отправить</button>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted } from 'vue'

onMounted(() => {
  const script = document.createElement('script')
  script.src = 'https://api-maps.yandex.ru/2.1/?apikey=YOUR_API_KEY&lang=ru_RU'
  script.type = 'text/javascript'
  script.async = true
  script.onload = () => {
    if (window.ymaps) {
      window.ymaps.ready(() => {
        const mapContainer = document.getElementById('YMapsID')
        const map = new window.ymaps.Map(mapContainer, {
          center: [56.837435, 60.597636],
          zoom: 15
        })
        map.controls.remove('trafficControl')
        map.controls.remove('typeSelector')
        map.controls.remove('searchControl')
        map.controls.remove('zoomControl')
        map.controls.remove('geolocationControl')
        map.controls.remove('rulerControl')
        map.controls.remove('fullscreenControl')
        map.geoObjects.removeAll()
        const placemark = new window.ymaps.Placemark(
          [56.837435, 60.597636],
          {
            balloonContent: 'г. Екатеринбург, ул. Промышленная, д. 4, стр. 2'
          },
          {
            preset: 'islands#redIcon'
          }
        )
        map.geoObjects.add(placemark)
        map.container.fitToViewport()
      })
    }
  }
  document.head.appendChild(script)
})
</script>

<style scoped>
.contacts-page {
  display: flex;
  flex-direction: column;
  gap: 70px;
  padding: 180px 40px 0 40px;
}

.header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 34px;
}

.breadcrumbs {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}

.breadcrumbs a,
.breadcrumbs span:not(:last-child) {
  color: #000000;
  font-size: 16px;
  font-weight: 600;
  text-decoration: none;
  transition: color 0.3s ease;
}

.breadcrumbs a:hover {
  color: #c96744;
}

.breadcrumbs span:last-child {
  color: #000000;
  opacity: 0.5;
  font-size: 16px;
  font-weight: 600;
}

h1 {
  font-size: 44px;
  font-weight: 400;
  color: #000000;
  text-align: center;
}

.contacts-content {
    display: flex;
    gap: 70px;
}

.info {
    width: 47%;
    display: flex;
    flex-direction: column;
    gap: 40px;
}

.cards {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.card {
    display: flex;
    gap: 20px;
    width: calc(50% - 10px);
    height: 125px;
    background: #FFFFFF;
    border-radius: 4px;
    padding: 9px;
    box-sizing: border-box;
    align-items: center;
}

.wrap-img {
    width: 106px;
    height: 106px;
    background-color: #C96744;
    border-radius: 4px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-shrink: 0;
}

.wrap-img img {
    width: 60px;
    height: 60px;
}

.card-text {
    width: 200px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.text-title {
    color: #4B4B4B;
    font-weight: 500;
    font-size: 24px;
}

.text-value {
    color: #000000;
    font-weight: 300;
    font-size: 18px;
    opacity: 0.8;
}

.map {
    width: 100%;
    height: 570px;
    border-radius: 8px;
    overflow: hidden;
    position: relative;
}

.map::before {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.15);
    pointer-events: none;
    z-index: 1;
}

.map :deep(.ymaps-2-1-78-map) {
    width: 100% !important;
    height: 100% !important;
}

.form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-wrap-title {
    display: flex;
    flex-direction: column;
    gap: 40px;
    height: 285px;
    max-width: 440px;
}

.form-title {
    color: #000000;
    font-weight: 400;
    font-size: 44px;
}

.form-subtitle {
    color: rgba(0, 0, 0, 0.6);
    font-weight: 300;
    font-size: 18px;
    opacity: 0.8;
}

form {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 30px;
    height: 570px;
    justify-content: space-between;
}

.group-input {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.group-input label {
    color: #4B4B4B;
    font-weight: 500;
    font-size: 24px;
}

.group-input input {
    width: 100%;
    padding: 10px 0;
    border: none;
    border-bottom: 1px solid #000000;
    font-size: 18px;
    font-weight: 400;
    color: #000000;
    outline: none;
    background: transparent;
}

.group-input input::placeholder {
    color: rgba(0, 0, 0, 0.6);
    font-weight: 300;
    font-size: 18px;
}

.group-check {
    display: flex;
    align-items: center;
    gap: 24px;
}

.group-check input {
    width: 34px;
    height: 34px;
    border: 1px solid #9F9F9F;
    border-radius: 7px;
    cursor: pointer;
    background: transparent;
    color: #C96744;
    accent-color: #C96744;
    appearance: none;
    -webkit-appearance: none;
    padding: 0;
    position: relative;
}

.group-check input:checked {
    background: #C96744;
    border-color: #C96744;
}

.group-check input:checked::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(45deg);
    width: 6px;
    height: 12px;
    border: solid white;
    border-width: 0 3px 3px 0;
}

.group-check label {
    color: rgba(0, 0, 0, 0.6);
    font-weight: 300;
    font-size: 18px;
    opacity: 0.8;
}

.btn {
    width: fit-content;
    background-color: #C96744;
    padding: 19px 66px;
    border-radius: 44px;
    color: #FFFFFF;
    font-weight: 600;
    font-size: 16px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn:hover {
    background-color: #B35A3A;
}

@media (max-width: 1024px) {
    .contacts-page {
        padding: 140px 24px 0 24px;
        gap: 50px;
    }

    .contacts-content {
        gap: 40px;
    }

    .info {
        width: 50%;
    }

    .form {
        width: 50%;
    }

    .form-title {
        font-size: 36px;
    }

    .form-wrap-title {
        height: auto;
        gap: 24px;
    }

    .form-subtitle {
        font-size: 16px;
    }

    form {
        height: auto;
        gap: 24px;
    }

    .group-input label {
        font-size: 20px;
    }

    .group-input input {
        font-size: 16px;
    }

    .group-check label {
        font-size: 16px;
    }

    .text-title {
        font-size: 20px;
    }

    .text-value {
        font-size: 16px;
    }

    .wrap-img {
        width: 80px;
        height: 80px;
    }

    .wrap-img img {
        width: 45px;
        height: 45px;
    }

    .map {
        height: 350px;
    }
}

@media (max-width: 768px) {
    .contacts-page {
        padding: 110px 16px 0 16px;
        gap: 40px;
    }

    .header {
        gap: 20px;
    }

    .breadcrumbs a,
    .breadcrumbs span {
        font-size: 14px;
    }

    .contacts-content {
        flex-direction: column;
        gap: 30px;
    }

    .info {
        width: 100%;
    }

    .form {
        width: 100%;
    }

    .cards {
        gap: 12px;
    }

    .card {
        width: 100%;
        height: auto;
        min-height: 100px;
        padding: 16px;
    }

    .wrap-img {
        width: 70px;
        height: 70px;
        flex-shrink: 0;
    }

    .wrap-img img {
        width: 40px;
        height: 40px;
    }

    .card-text {
        width: auto;
        flex: 1;
    }

    .text-title {
        font-size: 18px;
    }

    .text-value {
        font-size: 14px;
    }

    .map {
        height: 300px;
    }

    .form-title {
        font-size: 28px;
    }

    .form-subtitle {
        font-size: 16px;
    }

    .form-wrap-title {
        gap: 16px;
    }

    form {
        gap: 20px;
    }

    .group-input label {
        font-size: 18px;
    }

    .group-input input {
        font-size: 16px;
        padding: 8px 0;
    }

    .group-check {
        gap: 16px;
    }

    .group-check input {
        width: 28px;
        height: 28px;
    }

    .group-check label {
        font-size: 14px;
    }

    .btn {
        width: 100%;
        padding: 16px 40px;
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .contacts-page {
        padding: 100px 12px 0 12px;
        gap: 32px;
    }

    .header {
        gap: 14px;
    }

    .breadcrumbs a,
    .breadcrumbs span {
        font-size: 12px;
    }

    .cards {
        gap: 10px;
    }

    .card {
        padding: 12px;
        gap: 12px;
    }

    .wrap-img {
        width: 56px;
        height: 56px;
    }

    .wrap-img img {
        width: 32px;
        height: 32px;
    }

    .card-text {
        gap: 4px;
    }

    .text-title {
        font-size: 16px;
    }

    .text-value {
        font-size: 12px;
    }

    .map {
        height: 250px;
    }

    .form-title {
        font-size: 24px;
    }

    .form-subtitle {
        font-size: 14px;
    }

    .form-wrap-title {
        gap: 12px;
    }

    form {
        gap: 16px;
    }

    .group-input label {
        font-size: 16px;
    }

    .group-input input {
        font-size: 14px;
        padding: 6px 0;
    }

    .group-check input {
        width: 24px;
        height: 24px;
        border-radius: 5px;
    }

    .group-check input:checked::after {
        width: 4px;
        height: 8px;
        border-width: 0 2px 2px 0;
    }

    .group-check label {
        font-size: 12px;
    }

    .btn {
        padding: 14px 32px;
        font-size: 13px;
        border-radius: 30px;
    }
}
</style>