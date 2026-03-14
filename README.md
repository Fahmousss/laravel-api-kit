# Laravel Web Kit

A production-ready Laravel 12 starter kit for building modern, responsive web applications. Featuring a powerful frontend stack with Tailwind CSS v4 and Alpine.js v3, alongside session-based authentication and best-in-class code quality tools.

[![PHP Version](https://img.shields.io/badge/PHP-8.3%2B-blue)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-red)](https://laravel.com)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)

## Features

### 🎨 Modern Frontend

- **Tailwind CSS v4** - The latest utility-first CSS framework with Vite integration.
- **Alpine.js v3** - Lightweight, reactive client-side logic for interactive components.
- **Icon Libraries** - Built-in support for **Heroicons** and **PhosphorIcons**.
- **Vite** - Lightning-fast frontend bundling and HMR.

### 🔐 Web Authentication

- **Session-based Auth** - Secure cookie-based authentication via Laravel's native web guard.
- **Complete Auth Flow** - Login, Registration, Password Reset, and Email Verification.
- **Dashboard** - Ready-to-use protected user dashboard.

### 🛠️ Core & Quality

- **Modern Testing** - Pest PHP for feature and unit testing.
- **Code Quality** - PHPStan (max level), Rector, and Pint with strict rules.
- **DDD Inspired Architecture** - Organized for scalability across Application, Domain, Infrastructure, and Presentation layers.
- **Docker Ready** - Full development environment with Docker Compose.

## Requirements

- Docker & Docker Compose
- Or: PHP 8.3+, Composer 2.x, Node.js & npm

## Quick Start

### With Docker (Recommended)

```bash
# Clone the repository
mkdir laravel-web
cd laravel-web
git clone -b laravel-web https://github.com/fahmousss/laravel-api-kit.git .

# Copy environment file
cp .env.example .env

# Build and start containers
docker compose build
docker compose up -d

# Install dependencies & Build assets
docker compose run --rm app composer install
docker compose run --rm app npm install
docker compose run --rm app npm run build

# Generate application key
docker compose run --rm app php artisan key:generate

# Run migrations
docker compose run --rm app php artisan migrate

# Run tests
docker compose run --rm app ./vendor/bin/pest
```

### Without Docker

```bash
# Clone and install
mkdir laravel-web
cd laravel-web
git clone -b laravel-web https://github.com/fahmousss/laravel-api-kit.git .
composer install
npm install
npm run build

# Configure
cp .env.example .env
php artisan key:generate

# Database (SQLite by default)
touch database/database.sqlite
php artisan migrate

# Verify
./vendor/bin/pest
```

## Authentication

This kit uses standard Laravel session-based authentication.

- **Register**: `http://localhost:8080/register`
- **Login**: `http://localhost:8080/login`
- **Dashboard**: `http://localhost:8080/dashboard` (Authenticated only)
- **Password Reset**: `http://localhost:8080/forgot-password`

## Web Routes

| Method | Endpoint         | Auth | Description            |
| ------ | ---------------- | ---- | ---------------------- |
| GET    | /                | No   | Welcome Home Page      |
| GET    | /login           | No   | Login Form             |
| GET    | /register        | No   | Registration Form      |
| GET    | /dashboard       | Yes  | User Dashboard         |
| POST   | /logout          | Yes  | Logout (Session)       |
| GET    | /verify-email    | Yes  | Email Verification UI  |
| GET    | /forgot-password | No   | Password Recovery Form |

## Project Structure

```
laravel-api-kit/
├── app/
│   ├── Application/                # Use Cases, Handlers, DTOs
│   ├── Domain/                     # Entities and Contracts
│   ├── Infrastructure/             # Repositories and Service Providers
│   └── Presentation/
│       ├── Controllers/Web/        # Web Controllers
│       ├── Requests/               # Form Validation
│       └── ViewModels/             # View logic and data preparation
├── resources/
│   ├── css/                        # Tailwind CSS v4
│   ├── js/                         # Alpine.js & Scripts
│   └── views/                      # Blade templates
├── routes/
│   ├── web.php                     # Web routes entry
│   └── console.php                 # Console commands
├── tests/
│   ├── Feature/                    # Web Feature tests
│   └── Unit/                       # Unit tests
├── docker-compose.yml
├── vite.config.js                  # Frontend build config
└── CLAUDE.md                       # AI instructions
```

## Testing

This kit uses [Pest PHP](https://pestphp.com/) for testing:

```bash
# Run all tests
docker compose run --rm app ./vendor/bin/pest

# Run specific test file
docker compose run --rm app ./vendor/bin/pest tests/Feature/Web/AuthTest.php

# Run with coverage
docker compose run --rm app ./vendor/bin/pest --coverage

# Run in parallel
docker compose run --rm app ./vendor/bin/pest --parallel
```

### Writing Tests

```php
// tests/Feature/Web/UserTest.php
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows the dashboard for authenticated user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertStatus(200)
        ->assertSee('Dashboard');
});

it('redirects unauthenticated user from dashboard', function () {
    $this->get('/dashboard')
        ->assertRedirect('/login');
});
```

## Code Quality

This kit includes strict code quality tools configured following [nunomaduro/laravel-starter-kit](https://github.com/nunomaduro/laravel-starter-kit) standards.

### Tools

| Tool                                                                               | Purpose                     | Config         |
| ---------------------------------------------------------------------------------- | --------------------------- | -------------- |
| [PHPStan](https://phpstan.org/) + [Larastan](https://github.com/larastan/larastan) | Static analysis (level max) | `phpstan.neon` |
| [Rector](https://getrector.com/)                                                   | Automated refactoring       | `rector.php`   |
| [Pint](https://laravel.com/docs/pint)                                              | Code style (strict rules)   | `pint.json`    |

### Composer Scripts

```bash
# Apply fixes (Rector + Pint)
composer lint

# Check without fixing (CI mode)
composer test:lint

# Static analysis only
composer test:types

# Unit tests only
composer test:unit

# Full test suite (lint + types + unit)
composer test
```

### With Docker

```bash
docker compose exec app composer lint
docker compose exec app composer test
```

### Strict Rules Applied

- `declare(strict_types=1)` on all files
- `final` classes by default
- Type declarations enforced
- Dead code removal
- Early returns
- Strict comparisons

### GitHub Actions

Tests run automatically on push/PR to `main` via `.github/workflows/tests.yml`.

## Development Commands

```bash
# List all routes
docker compose run --rm app php artisan route:list

# Clear all caches
docker compose run --rm app php artisan optimize:clear

# Generate IDE helper files (if using Laravel IDE Helper)
docker compose run --rm app php artisan ide-helper:generate
docker compose run --rm app php artisan ide-helper:models -N
```

### DDD Scaffolding Commands

Use the custom commands developed for this architecture to scaffold classes in their appropriate layers:

```bash
# Scaffold a new domain (Entity, RepositoryInterface, Exception)
php artisan make:domain <domain> <entity>

# Scaffold an Infrastructure layer (Model, Migration, Factory, Repository, Provider Binding)
php artisan make:infrastructure <domain> <entity>

# Scaffold an Eloquent repository
php artisan make:repository <domain> <entity>

# Scaffold a CQRS use-case (DTO + Handler)
php artisan make:use-case <domain> <name> [--command|--query]

# Scaffold a typed DTO in the Application layer
php artisan make:data <domain> <name>
```

## Environment Configuration

Key `.env` variables:

```env
# Application
APP_NAME="Laravel Web Kit"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8080

# Database (SQLite for development)
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/database/database.sqlite

# For MySQL/PostgreSQL
# DB_CONNECTION=mysql
# DB_HOST=mysql
# DB_PORT=3306
# DB_DATABASE=laravel_api_kit
# DB_USERNAME=laravel
# DB_PASSWORD=secret
```

## Deployment

### Production Checklist

- [ ] Set `APP_ENV=production` and `APP_DEBUG=false`
- [ ] Configure proper database (MySQL/PostgreSQL)
- [ ] Run `npm run build` for production assets
- [ ] Set `APP_URL` to your production URL
- [ ] Review and tighten CORS settings in `config/cors.php`
- [ ] Set up proper caching (Redis recommended)
- [ ] Set up queue worker for background jobs
- [ ] Enable HTTPS

### Docker Production

```dockerfile
# Example production Dockerfile additions
FROM php:8.3-fpm-alpine

# Install opcache for performance
RUN docker-php-ext-install opcache

# Production PHP settings
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/
COPY docker/php/php.ini /usr/local/etc/php/conf.d/
```

## Extending the Kit

This kit follows the same structural patterns for Web features as it does for the API core.

### Adding a New Web Feature

1. **Scaffold Domain**: `php artisan make:domain Blog Post`
2. **Infrastructure**: `php artisan make:infrastructure Blog Post`
3. **Application**: `php artisan make:use-case Blog CreatePost --command`
4. **Presentation**:
    - Create a Web Controller: `php artisan make:controller Presentation/Controllers/Web/Blog/PostController`
    - Create a View: `resources/views/blog/create.blade.php`
    - Create a ViewModel: `app/Presentation/ViewModels/Blog/PostViewModel.php`
5. **Routes**: Register in `routes/web.php`.

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## Credits

- [Laravel](https://laravel.com) - The PHP Framework
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS
- [Alpine.js](https://alpinejs.dev) - Lightweight JavaScript
- [Pest PHP](https://pestphp.com) - Testing Framework
- [sheaf/cli](https://github.com/sheaf-php/cli) - CLI tooling

## Support

- [Issues](https://github.com/fahmousss/laravel-api-kit/issues)
- [Discussions](https://github.com/fahmousss/laravel-api-kit/discussions)
