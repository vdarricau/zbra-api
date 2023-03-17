# Zbra API

API of the web application and mobile application Zbra, where you can send Zbras to your Zbros.

## Init

Requires php 8.1

Download dependencies:
```bash
composer install
```

Create schema and seed db:
```bash
php artisan migrate:fresh --seed
```

Start local development server:
```bash
php artisan serve
```