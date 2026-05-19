# Content Blocks Guide

## Структура контентных блоков

### 1. Главная страница (home)

#### 1.1 Главный баннер (Hero Section)
- **block_type:** `hero`
- **block_name:** `Главный баннер`
- **block_title:** Заголовок (строка)
- **block_text:** Описание (строка)
- **block_data:** JSON
```json
{
  "button_text": "Посмотреть комплектации",
  "button_link": "/catalog",
  "features": [
    { "icon": "group-1", "title": "Единый стиль участка", "text": "Все решения визуально сочетаются..." },
    { "icon": "group-2", "title": "Продуманная конструкция", "text": "Надёжный каркас..." },
    { "icon": "group-3", "title": "Готовые комплектации", "text": "Понятный выбор..." }
  ]
}
```

#### 1.2 Преимущества (Features)
- **block_type:** `features`
- **block_name:** `Преимущества`
- **block_data:** JSON array
```json
[
  { "icon": "group-1", "title": "Единый стиль участка", "text": "..." },
  { "icon": "group-2", "title": "Продуманная конструкция", "text": "..." },
  { "icon": "group-3", "title": "Готовые комплектации", "text": "..." }
]
```

#### 1.3 Почему выбирают нас (4 карточки)
- **block_type:** `features`
- **block_name:** `Почему выбирают нас`
- **block_data:** JSON array
```json
[
  { "icon": "why-us-1", "title": "Гарантия до 15 лет", "text": "Даём письменную гарантию..." },
  { "icon": "why-us-2", "title": "Доставка по России", "text": "Отправим в любой регион..." },
  { "icon": "why-us-3", "title": "Монтаж под ключ", "text": "Наша бригада установит..." },
  { "icon": "why-us-4", "title": "Консультация бесплатно", "text": "Позвоните или оставьте заявку..." }
]
```

#### 1.4 Что мы делаем (3 карточки с изображениями)
- **block_type:** `features`
- **block_name:** `Что мы делаем`
- **block_title:** Заголовок секции (строка)
- **block_data:** JSON array
```json
[
  { "image": "card.png", "category": "Сад", "title": "Беседка для отдыха" },
  { "image": "card.png", "category": "Барбекю", "title": "Мангальные зоны" },
  { "image": "card.png", "category": "Авто", "title": "Навесы для автомобилей" }
]
```

#### 1.5 Компания в цифрах
- **block_type:** `features`
- **block_name:** `Компания в цифрах`
- **block_data:** JSON array
```json
[
  { "title": "15", "subtitle": "лет на рынке", "desc": "проектируем и устанавливаем...", "image": "company-card-1.png" },
  { "title": "3 200+", "subtitle": "установленных навесов", "desc": "Отработали десятки сценариев...", "image": "company-card-2.png" },
  { "title": "52", "subtitle": "города доставки", "desc": "Организуем логистику и монтаж...", "image": "company-card-3.jpg" }
]
```

#### 1.6 Как мы работаем
- **block_type:** `features`
- **block_name:** `Как мы работаем`
- **block_data:** JSON array
```json
[
  { "icon": "card-icon-1", "title": "Оставьте заявку", "text": "Заполните форму..." },
  { "icon": "card-icon-2", "title": "Получите расчёт", "text": "Менеджер уточнит..." },
  { "icon": "card-icon-3", "title": "Подпишите договор", "text": "Фиксируем цену..." },
  { "icon": "card-icon-4", "title": "Монтаж и сдача", "text": "Установим конструкцию..." }
]
```

#### 1.7 Наши проекты (Галерея)
- **block_type:** `gallery`
- **block_name:** `Наши проекты`
- **block_title:** Заголовок секции
- **block_data:** JSON array
```json
[
  { "image": "project-1.jpg", "title": "Беседка 6м2", "price": "126 000" },
  { "image": "project-2.jpg", "title": "Мангальная зона Стандарт", "price": "126 000" },
  { "image": "project-3.jpg", "title": "Навес для автомобиля 6м2", "price": "126 000" }
]
```

---

### 2. Страница FAQ (faq)

#### 2.1 Заголовок FAQ
- **block_type:** `section`
- **block_name:** `FAQ - Заголовок`
- **block_title:** Заголовок секции

#### 2.2 Вопросы и ответы
- **block_type:** `faq`
- **block_name:** `FAQ - Вопросы и ответы`
- **block_data:** JSON array
```json
[
  { "question": "Какие конструкции Вы изготавливаете?", "answer": "Мы изготавливаем навесы..." },
  { "question": "Подходят ли конструкции для круглогодичного использования?", "answer": "Да, все наши конструкции..." },
  { "question": "Можно ли выбрать размер конструкции?", "answer": "Да, мы изготавливаем..." },
  { "question": "Можно ли заказать мангальную зону как отдельное решение?", "answer": "Да, мангальные зоны..." },
  { "question": "Из каких материалов изготавливаются конструкции?", "answer": "Каркас из стального профиля..." }
]
```

---

### 3. Страница О компании (about)

#### 3.1 О компании (вступление)
- **block_type:** `section`
- **block_name:** `О компании`
- **block_title:** Заголовок
- **block_text:** Описание

#### 3.2 Компания в цифрах
- **block_type:** `features`
- **block_name:** `Компания в цифрах` (about)
- **block_data:** JSON array с изображениями
```json
[
  { "title": "15", "subtitle": "лет на рынке", "desc": "...", "image": "company-card-1.png" },
  { "title": "3 200+", "subtitle": "установленных навесов", "desc": "...", "image": "company-card-2.png" },
  { "title": "52", "subtitle": "города доставки", "desc": "...", "image": "company-card-3.jpg" }
]
```

#### 3.3 Почему мы
- **block_type:** `features`
- **block_name:** `Почему мы`
- **block_data:** JSON array
```json
[
  { "icon": "card-icon-1", "title": "Собственное производство", "text": "Производим конструкции..." },
  { "icon": "card-icon-2", "title": "Гарантия качества", "text": "Используем сертифицированные..." },
  { "icon": "card-icon-3", "title": "Индивидуальный подход", "text": "Разрабатываем проекты..." }
]
```

#### 3.4 Производство
- **block_type:** `section`
- **block_name:** `Производство`
- **block_title:** Заголовок
- **block_text:** Описание
- **block_image:** URL изображения

#### 3.5 Наши работы (галерея)
- **block_type:** `gallery`
- **block_name:** `Наши работы`
- **block_title:** Заголовок
- **block_data:** JSON array изображений

#### 3.6 Наша история
- **block_type:** `section`
- **block_name:** `Наша история`
- **block_title:** Заголовок
- **block_text:** Описание

---

### 4. Страница Контакты (contacts)

#### 4.1 Контактная информация
- **block_type:** `contact`
- **block_name:** `Контактная информация`
- **block_data:** JSON
```json
{
  "phone": "+7 (900) 123-45-67",
  "email": "info@navesstroy.ru",
  "address": "г. Екатеринбург, ул. Промышленная, д. 4, стр. 2",
  "schedule": "Пн-Вс: 9:00-18:00"
}
```

#### 4.2 Форма обратной связи
- **block_type:** `section`
- **block_name:** `Форма обратной связи`
- **block_title:** Заголовок
- **block_text:** Описание

---

### 5. Страница Доставка (delivery)

#### 5.1 Регионы доставки
- **block_type:** `section`
- **block_name:** `Регионы доставки`
- **block_title:** Заголовок
- **block_text:** Описание
- **block_image:** Изображение карты

#### 5.2 Способы оплаты
- **block_type:** `features`
- **block_name:** `Способы оплаты`
- **block_data:** JSON array
```json
[
  { "icon": "payment-1", "title": "Наличные", "text": "Оплата наличными при получении" },
  { "icon": "payment-2", "title": "Карта", "text": "Оплата картой онлайн..." },
  { "icon": "payment-3", "title": "Рассрочка", "text": "Беспроцентная рассрочка..." },
  { "icon": "payment-4", "title": "Безнал", "text": "Оплата по счёту для юрлиц" }
]
```

---

### 6. Страница Гарантия (garant)

#### 6.1 Гарантия (вступление)
- **block_type:** `section`
- **block_name:** `Гарантия`
- **block_title:** Заголовок
- **block_text:** Описание

#### 6.2 Условия гарантии
- **block_type:** `features`
- **block_name:** `Условия гарантии`
- **block_data:** JSON array

#### 6.3 Обслуживание
- **block_type:** `features`
- **block_name:** `Обслуживание`
- **block_data:** JSON array

#### 6.4 FAQ по гарантии
- **block_type:** `faq`
- **block_name:** `Частые вопросы по гарантии`
- **block_data:** JSON array

---

### 7. Футер (footer)

#### 7.1 Основная информация
- **block_type:** `footer`
- **block_name:** `Футер - Основная информация`
- **block_title:** Название компании
- **block_text:** Описание компании
- **block_data:** JSON
```json
{
  "copyright": "© 2026 Название. Все права защищены.",
  "privacy": "Политика конфиденциальности",
  "agreement": "Пользовательское соглашение"
}
```

#### 7.2 Каталог (ссылки)
- **block_type:** `footer`
- **block_name:** `Футер - Каталог`
- **block_data:** JSON array
```json
[
  { "text": "Беседки", "link": "/catalog/besedki" },
  { "text": "Мангальные зоны", "link": "/catalog/mangal" },
  { "text": "Навесы для авто", "link": "/catalog/navesy" }
]
```

#### 7.3 Покупателям (ссылки)
- **block_type:** `footer`
- **block_name:** `Футер - Покупателям`
- **block_data:** JSON array
```json
[
  { "text": "О компании", "link": "/about" },
  { "text": "Новости и статьи", "link": "/news" },
  { "text": "Доставка и оплата", "link": "/delivery" },
  { "text": "Гарантия", "link": "/garant" },
  { "text": "Контакты", "link": "/contacts" }
]
```

#### 7.4 Контакты футера
- **block_type:** `footer`
- **block_name:** `Футер - Контакты`
- **block_data:** JSON
```json
{
  "phone": "+7 (900) 123-45-67",
  "email": "info@navesstroy.ru",
  "address": "г. Екатеринбург, ул. Промышленная, д. 4, стр. 2"
}
```

---

## Доступные иконки

### Hero иконки:
- `group-1` - group-1.svg
- `group-2` - group-2.svg
- `group-3` - group-3.svg

### Why Us иконки:
- `why-us-1` - why-us-1.svg
- `why-us-2` - why-us-2.svg
- `why-us-3` - why-us-3.svg
- `why-us-4` - why-us-4.svg

### Card иконки:
- `card-icon-1` - card-icon-1.svg
- `card-icon-2` - card-icon-2.svg
- `card-icon-3` - card-icon-3.svg
- `card-icon-4` - card-icon-4.svg

### Payment иконки:
- `payment-1` - payment-1.svg
- `payment-2` - payment-2.svg
- `payment-3` - payment-3.svg
- `payment-4` - payment-4.svg

---

## API Endpoints

### Получить блоки по странице:
```
GET /wp-json/wp-awnings/v1/content-blocks?page=home
```

### Обновить блок:
```
POST /wp-json/wp-awnings/v1/content-blocks/{id}
```

### Параметры для создания/обновления:
- `block_name` - название блока
- `block_type` - тип блока (hero, features, faq, gallery, section, footer, contact)
- `block_page` - страница (home, about, faq, contacts, delivery, garant, footer, news)
- `block_title` - заголовок
- `block_text` - текст
- `block_image` - URL изображения
- `block_data` - JSON строка с данными
- `block_order` - порядковый номер