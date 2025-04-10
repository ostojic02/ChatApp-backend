# Laravel Application

A web application built with Laravel framework.

## Requirements

- PHP >= 8.2
- Composer
- Node.js & NPM
- Redis (optional)

## Installation

1. Clone the repository
2. Install PHP dependencies:
```bash
composer install
```

3. Install NPM dependencies:
```bash
npm install
```

4. Create environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Create SQLite database:
```bash
touch database/database.sqlite
```

7. Run migrations:
```bash
php artisan migrate
```

## Development

Start the development server:

```bash
composer dev
```

This will concurrently run:
- Laravel development server
- Queue worker
- Pail log viewer
- Vite development server

## Building Assets

To build assets for production:

```bash
npm run build
```

## Testing

Run tests using PHPUnit:

```bash
php artisan test
```

## Code Style

This project uses Laravel Pint for PHP code styling. To format code:

```bash
./vendor/bin/pint
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
