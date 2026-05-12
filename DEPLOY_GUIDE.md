# Руководство по развёртыванию проекта Awnings

## Обзор архитектуры

Проект состоит из двух частей:
- **Frontend**: Vue 3 + Vite приложение (каталог `src/`)
- **Backend**: WordPress тема с кастомным REST API (каталог `wp-awnings/`)

Nginx обслуживает статику фронтенда и проксирует API запросы к WordPress.

---

## 1. Подготовка сервера

### 1.1 Обновление системы
```bash
sudo apt update && sudo apt upgrade -y
```

### 1.2 Установка базовых пакетов
```bash
sudo apt install -y curl wget git unzip software-properties-common
```

---

## 2. Установка Nginx

```bash
sudo apt install -y nginx
sudo systemctl enable nginx
sudo systemctl start nginx
```

Проверка:
```bash
sudo systemctl status nginx
curl -I http://localhost
```

---

## 3. Установка PHP и WordPress

### 3.1 Установка репозитория PHP
```bash
sudo apt install -y software-properties-common
sudo add-apt-repository -y ppa:ondrej/php
sudo apt update
```

### 3.2 Установка PHP 8.2
```bash
sudo apt install -y php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-imagick php8.2-intl
```

### 3.2 Установка MySQL
```bash
sudo apt install -y mysql-server
sudo systemctl enable mysql
sudo systemctl start mysql
```

### 3.3 Настройка MySQL
```bash
sudo mysql_secure_installation
```

Создание базы данных:
```bash
sudo mysql
```

```sql
CREATE DATABASE awnings_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    CREATE USER 'awnings_user'@'localhost' IDENTIFIED BY 'YOUR_STRONG_PASSWORD';
GRANT ALL PRIVILEGES ON awnings_db.* TO 'awnings_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3.4 Установка WordPress
```bash
cd /var/www
sudo wget https://wordpress.org/latest.tar.gz
sudo tar -xzf latest.tar.gz
sudo rm latest.tar.gz
sudo chown -R www-data:www-data /var/www/wordpress
sudo chmod -R 755 /var/www/wordpress
```

### 3.5 Установка WordPress CLI (опционально, для управления)
```bash
cd /tmp
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
sudo mv wp-cli.phar /usr/local/bin/wp
```

### 3.6 Конфигурация WordPress
```bash
cd /var/www/wordpress
sudo -u www-data wp config create --dbname=awnings_db --dbuser=awnings_user --dbpass='YOUR_STRONG_PASSWORD' --dbhost=localhost
```

---

## 4. Установка WordPress темы

### 4.1 Копирование темы
```bash
sudo mkdir -p /var/www/wordpress/wp-content/themes/wp-awnings
sudo cp -r /path/to/your/project/wp-awnings/* /var/www/wordpress/wp-content/themes/wp-awnings/
sudo chown -R www-data:www-data /var/www/wordpress/wp-content/themes/wp-awnings
```

### 4.2 Конфигурация Nginx для WordPress
```bash
sudo nano /etc/nginx/sites-available/wordpress
```

```nginx
server {
    listen 80;
    server_name api.your-domain.com;
    root /var/www/wordpress;
    index index.php index.html;

    access_log /var/log/nginx/wordpress_access.log;
    error_log /var/log/nginx/wordpress_error.log;

    client_max_body_size 50M;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|webp)$ {
        expires max;
        log_not_found off;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/wordpress /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 4.3 Завершение установки WordPress
Откройте в браузере: `http://api.your-domain.com`

Следуйте wizard установки WordPress, затем:
1. Активируйте тему **wp-awnings**
2. В админке появится меню "Товары" и "Заявки"

---

## 5. Сборка и деплой Vue.js Frontend

### 5.1 Установка Node.js 20
```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
node -v
npm -v
```

### 5.2 Сборка фронтенда
```bash
cd /path/to/your/project/awnings
npm install
```

Создайте `.env` файл:
```bash
cp .env.example .env
nano .env
```

Измените URL на ваш домен:
```
VITE_WP_API_URL=https://api.your-domain.com/wp-json/wp-awnings/v1
```

Сборка:
```bash
npm run build
```

### 5.3 Деплой статики
```bash
sudo rm -rf /var/www/awnings-frontend
sudo mkdir -p /var/www/awnings-frontend
sudo cp -r dist/* /var/www/awnings-frontend/
sudo chown -R www-data:www-data /var/www/awnings-frontend
```

---

## 6. Конфигурация Nginx для полного сайта

```bash
sudo nano /etc/nginx/sites-available/awnings
```

```nginx
# Основной сайт (Frontend)
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /var/www/awnings-frontend;
    index index.html;

    access_log /var/log/nginx/awnings_access.log;
    error_log /var/log/nginx/awnings_error.log;

    # Gzip сжатие
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied any;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/json application/xml;

    location / {
        try_files $uri $uri/ /index.html;
    }

    # Статические файлы с кэшированием
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|webp|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        log_not_found off;
    }

    # Проксирование API на WordPress
    location /wp-json/ {
        proxy_pass http://127.0.0.1:8080;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        
        # CORS заголовки
        add_header Access-Control-Allow-Origin "https://your-domain.com" always;
        add_header Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS" always;
        add_header Access-Control-Allow-Headers "Content-Type, Authorization" always;
        
        # Preflight запросы
        if ($request_method = 'OPTIONS') {
            add_header Access-Control-Allow-Origin "https://your-domain.com";
            add_header Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS";
            add_header Access-Control-Allow-Headers "Content-Type, Authorization";
            add_header Access-Control-Max-Age 1728000;
            add_header Content-Type 'text/plain charset=UTF-8';
            add_header Content-Length 0;
            return 204;
        }
    }
}

# API сервер (WordPress) - только внутренний
server {
    listen 127.0.0.1:8080;
    server_name api.your-domain.com;
    root /var/www/wordpress;
    index index.php;

    access_log /var/log/nginx/wordpress_access.log;
    error_log /var/log/nginx/wordpress_error.log;

    client_max_body_size 50M;

    # CORS заголовки для API
    add_header Access-Control-Allow-Origin "https://your-domain.com" always;
    add_header Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS" always;
    add_header Access-Control-Allow-Headers "Content-Type, Authorization" always;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/awnings /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default  # удалить дефолтный
sudo nginx -t
sudo systemctl reload nginx
```

---

## 7. SSL с Let's Encrypt

### 7.1 Установка Certbot
```bash
sudo apt install -y certbot python3-certbot-nginx
```

### 7.2 Получение сертификата
```bash
sudo certbot --nginx -d your-domain.com -d www.your-domain.com -d api.your-domain.com
```

### 7.3 Автопродление
```bash
sudo certbot renew --dry-run
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer
```

---

## 8. Структура директорий после установки

```
/var/www/
├── awnings-frontend/          # Собранный Vue.js frontend
│   ├── index.html
│   ├── assets/
│   └── ...
└── wordpress/                  # WordPress с темой wp-awnings
    ├── wp-content/
    │   └── themes/
    │       └── wp-awnings/     # Кастомная тема
    └── ...
```

---

## 9. Автоматический деплой (CI/CD)

### 9.1 Webhook для обновления фронтенда
```bash
sudo nano /var/www/awnings-frontend/update.sh
```

```bash
#!/bin/bash
cd /var/www/awnings-frontend
git pull origin main
npm install
npm run build
cp -r dist/* /var/www/awnings-frontend/
sudo chown -R www-data:www-data /var/www/awnings-frontend
```

```bash
sudo chmod +x /var/www/awnings-frontend/update.sh
```

### 9.2 Настройка Nginx для webhook
```bash
sudo nano /etc/nginx/sites-available/awnings
```

Добавьте в секцию server:
```nginx
location /deploy {
    limit_req zone=one burst=5 nodelay;
    proxy_pass http://127.0.0.1:9000;
}
```

---

## 10. Команды управления

```bash
# Перезапуск Nginx
sudo systemctl restart nginx

# Перезапуск PHP-FPM
sudo systemctl restart php8.2-fpm

# Перезапуск MySQL
sudo systemctl restart mysql

# Проверка статуса всех сервисов
sudo systemctl status nginx php8.2-fpm mysql

# Логи
sudo tail -f /var/log/nginx/awnings_access.log
sudo tail -f /var/log/nginx/wordpress_error.log
```

---

## 11. Обновление проекта

### Обновление WordPress темы
```bash
sudo cp -r /path/to/your/project/wp-awnings/* /var/www/wordpress/wp-content/themes/wp-awnings/
sudo chown -R www-data:www-data /var/www/wordpress/wp-content/themes/wp-awnings
```

### Обновление Frontend
```bash
cd /path/to/your/project/awnings
git pull
npm install
npm run build
sudo rm -rf /var/www/awnings-frontend/*
sudo cp -r dist/* /var/www/awnings-frontend/
sudo chown -R www-data:www-data /var/www/awnings-frontend
```

---

## 12. Troubleshooting

### Проблема: 502 Bad Gateway
```bash
# Проверьте, что PHP-FPM запущен
sudo systemctl status php8.2-fpm

# Проверьте сокет
ls -la /var/run/php/
```

### Проблема: CORS ошибки
```bash
# Проверьте заголовки
curl -I -X OPTIONS https://api.your-domain.com/wp-json/wp-awnings/v1/products
```
Убедитесь, что домены в `add_header Access-Control-Allow-Origin` указаны правильно.

### Проблема: Файлы не загружаются
```bash
sudo chown -R www-data:www-data /var/www/wordpress/wp-content/uploads
```

---

## Краткая шпаргалка

| Задача | Команда |
|--------|---------|
| Перезапустить всё | `sudo systemctl restart nginx php8.2-fpm mysql` |
| Обновить фронтенд | `cd /var/www/awnings-frontend && sudo -u www-data git pull && npm install && npm run build` |
| Проверить SSL | `sudo certbot renew --dry-run` |
| Лог ошибок | `sudo tail -50 /var/log/nginx/awnings_error.log` |
| Тест API | `curl https://api.your-domain.com/wp-json/wp-awnings/v1/products` |
