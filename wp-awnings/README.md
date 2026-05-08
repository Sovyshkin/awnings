# WordPress Theme: wp-awnings

Кастомная тема WordPress для сайта компании по производству навесов и беседок.

## Структура темы

```
wp-awnings/
├── style.css              # Основные стили темы
├── functions.php           # Функции темы и регистрация API
├── index.php               # Основной шаблон
├── header.php              # Шапка сайта
├── footer.php              # Подвал сайта
├── front-page.php          # Страница каталога
├── admin/
│   ├── admin-menu.php      # Регистрация меню админки
│   ├── products-admin.php  # Управление товарами
│   └── leads-admin.php    # Управление заявками
└── api-documentation.md    # Документация API
```

## Установка

1. Скопируйте папку `wp-awnings` в директорию `wp-content/themes/` вашего WordPress сайта
2. Активируйте тему в панели администратора WordPress (Внешний вид → Темы)
3. После активации в меню админки появится раздел "Товары" и "Заявки"

## API Endpoints

### Products API

| Метод | Endpoint | Описание |
|-------|----------|----------|
| GET | `/wp-json/wp-awnings/v1/products` | Получить все товары |
| GET | `/wp-json/wp-awnings/v1/products?category={slug}` | Фильтр по категории |
| GET | `/wp-json/wp-awnings/v1/products/{id}` | Получить товар по ID |
| POST | `/wp-json/wp-awnings/v1/products` | Создать товар (admin) |
| PUT | `/wp-json/wp-awnings/v1/products/{id}` | Обновить товар (admin) |
| DELETE | `/wp-json/wp-awnings/v1/products/{id}` | Удалить товар (admin) |

### Categories API

| Метод | Endpoint | Описание |
|-------|----------|----------|
| GET | `/wp-json/wp-awnings/v1/categories` | Получить все категории |

### Leads API

| Метод | Endpoint | Описание |
|-------|----------|----------|
| POST | `/wp-json/wp-awnings/v1/leads` | Отправить заявку |
| GET | `/wp-json/wp-awnings/v1/leads` | Получить все заявки (admin) |
| DELETE | `/wp-json/wp-awnings/v1/leads/{id}` | Удалить заявку (admin) |

## Настройка интеграции с Vue.js

В файле `src/services/api.js` настройте URL вашего WordPress сайта:

```javascript
const WP_API_URL = 'http://your-domain.com/wp-json/wp-awnings/v1'
```

Или создайте файл `.env` в корне проекта:

```
VITE_WP_API_URL=http://your-domain.com/wp-json/wp-awnings/v1
```

## Добавление товаров

1. Перейдите в админку WordPress → Товары
2. Нажмите "Добавить товар"
3. Заполните поля:
   - Название товара
   - Цена (meta: product_price)
   - URL изображения (meta: product_image_url)
   - Описание
   - Категория
4. Сохраните товар

## Добавление категорий

1. Перейдите в админку WordPress → Товары → Категории
2. Создайте категории с ярлыками:
   - `besedka` - Беседки
   - `mangal` - Мангальные зоны
   - `naves` - Навесы для авто

## CORS

Тема настроена для работы с фронтендом на другом домене. REST API имеет заголовки CORS для доступа с любого origin.

## Требования

- WordPress 5.0+
- PHP 7.4+
- Vue.js 3.x (для фронтенда)

## Поддержка

Для вопросов и предложений создавайте issue в репозитории.