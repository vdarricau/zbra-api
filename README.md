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
and websocket server:
```bash
php artisan websockets:serve
```

Both should be running at the same time for the application to run.

## Commands

### Tests
Run tests
```bash
php artisan test
```

### Duster

[Duster](https://github.com/tighten/duster) is a combination of the following linters: TLing, PHP_CodeSniffer, PHP CS Fixer, Pint, [Larastan](https://laravel-news.com/running-phpstan-on-max-with-laravel).

Run Linter
```bash
composer duster
```

Run fixer
```bash
composer duster-fix
```