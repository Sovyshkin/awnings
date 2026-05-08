# API Documentation for wp-awnings

## Base URL
```
https://your-domain.com/wp-json/wp-awnings/v1
```

## Products API

### Get All Products
```
GET /products
GET /products?category={category_slug}
```

**Response:**
```json
[
  {
    "id": 1,
    "title": "Беседка 6м2",
    "content": "Описание товара",
    "price": "от 126 000 ₽",
    "category": "besedka",
    "image_url": "https://example.com/image.jpg",
    "slug": "besedka-6m2",
    "date": "2024-01-01 12:00:00"
  }
]
```

### Get Single Product
```
GET /products/{id}
```

**Response:**
```json
{
  "id": 1,
  "title": "Беседка 6м2",
  "content": "Описание товара",
  "price": "от 126 000 ₽",
  "category": "besedka",
  "image_url": "https://example.com/image.jpg",
  "slug": "besedka-6m2",
  "date": "2024-01-01 12:00:00"
}
```

### Create Product (Admin only)
```
POST /products

Body:
{
  "title": "Новый товар",
  "price": "от 100 000 ₽",
  "category": "besedka",
  "image_url": "https://example.com/image.jpg",
  "content": "Описание товара"
}
```

### Update Product (Admin only)
```
PUT /products/{id}

Body:
{
  "title": "Обновленный товар",
  "price": "от 150 000 ₽",
  "category": "mangal",
  "image_url": "https://example.com/new-image.jpg",
  "content": "Новое описание"
}
```

### Delete Product (Admin only)
```
DELETE /products/{id}
```

## Categories API

### Get All Categories
```
GET /categories
```

**Response:**
```json
[
  {"id": "all", "name": "Все", "slug": "all"},
  {"id": "besedka", "name": "Беседки", "slug": "besedka"},
  {"id": "mangal", "name": "Мангальные зоны", "slug": "mangal"},
  {"id": "naves", "name": "Навесы для авто", "slug": "naves"}
]
```

## Leads (Form Submissions) API

### Submit Form (Public)
```
POST /leads

Body:
{
  "name": "Иван",
  "phone": "+79001234567",
  "message": "Хочу узнать больше о товаре",
  "product_id": 1,
  "agree": true
}
```

**Response:**
```json
{
  "success": true,
  "message": "Заявка успешно отправлена!",
  "lead_id": 123
}
```

### Get All Leads (Admin only)
```
GET /leads
```

**Response:**
```json
[
  {
    "id": 1,
    "name": "Иван",
    "phone": "+79001234567",
    "message": "Хочу узнать больше",
    "product_id": 1,
    "date": "2024-01-01 12:00:00",
    "status": "new"
  }
]
```

### Delete Lead (Admin only)
```
DELETE /leads/{id}
```

## Vue.js Integration Example

### Fetch Products
```javascript
const API_URL = 'https://your-domain.com/wp-json/wp-awnings/v1'

// Get all products
const response = await fetch(`${API_URL}/products`)
const products = await response.json()

// Filter by category
const response = await fetch(`${API_URL}/products?category=besedka`)
const products = await response.json()
```

### Submit Form
```javascript
const response = await fetch(`${API_URL}/leads`, {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    name: 'Иван',
    phone: '+79001234567',
    message: 'Хочу заказать',
    product_id: 1,
    agree: true
  })
})
const data = await response.json()