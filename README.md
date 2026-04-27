# Laravel API Kit

A production-ready, API-only Laravel 12 starter kit following the 2024-2025 REST API ecosystem best practices. No frontend dependencies - purely headless API for mobile apps, SPAs, or microservices.

[![PHP Version](https://img.shields.io/badge/PHP-8.5%2B-blue)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-red)](https://laravel.com)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)

## Features

- **API-Only** - No Blade, Vite, or frontend assets
- **Token Authentication** - JWT for mobile/SPA auth
- **API Versioning** - URI-based versioning (e.g., `/api/v1`) using standard Laravel routing
- **Query Building** - Filtering, sorting, includes via [spatie/laravel-query-builder](https://github.com/spatie/laravel-query-builder)
- **Data Objects** - Type-safe DTOs via [spatie/laravel-data](https://github.com/spatie/laravel-data)
- **Auto Documentation** - Zero-annotation OpenAPI 3.1 via [dedoc/scramble](https://github.com/dedoc/scramble)
- **Modern Testing** - Pest PHP with Laravel HTTP testing
- **Code Quality** - PHPStan (max level), Rector, and Pint with strict rules
- **Docker Production Ready** - Optimized Dockerfile and Compose setup for production
- **Rate Limiting** - Configurable per-route rate limiters
- **Reusable Middleware** - ForceJsonResponse, LogApiRequests
- **Standardized Responses** - Consistent JSON response format
- **Optional: API Idempotency** - RFC-compliant idempotency via [grazulex/laravel-api-idempotency](https://github.com/Grazulex/laravel-api-idempotency)
- **Optional: Smart Rate Limiting** - Plan-aware throttling with quotas via [grazulex/laravel-api-throttle-smart](https://github.com/Grazulex/laravel-api-throttle-smart)

## Requirements

- Docker & Docker Compose
- Or: PHP 8.5+, Composer 2.x

## Quick Start

### With Docker (Recommended)

The project includes a `compose.local.yaml` file optimized for local development with features like hot-reloading (via Octane/FrankenPHP) and volume mapping.

```bash
# Clone the repository
git clone https://github.com/Fahmousss/laravel-api-kit.git
cd laravel-api-kit

# Copy environment file
cp .env.example .env

# Build and start containers
docker compose -f compose.local.yaml up -d --build

# Install dependencies
docker compose -f compose.local.yaml exec app composer install

# Generate application key
docker compose -f compose.local.yaml exec app php artisan key:generate

# Generate JWT secret
docker compose -f compose.local.yaml exec app php artisan jwt:secret

# Run migrations
docker compose -f compose.local.yaml exec app php artisan migrate

# Run tests to verify installation
docker compose -f compose.local.yaml exec app ./vendor/bin/pest
```

### Local Development Features

The Docker setup is specifically designed for local development:
- **Hot Reloading**: Uses Laravel Octane with FrankenPHP for high-performance and instant feedback during development.
- **Live Sync**: Local source code is mounted to `/var/www` in the container, so any changes you make locally are reflected immediately.
- **Database Management**: Includes `pgadmin` on [http://localhost:5050](http://localhost:5050) to manage your PostgreSQL database.
- **Tools Included**: Comes with `redis`, `horizon`, `scheduler`, and `reverb` (WebSockets) pre-configured for a full-stack development experience.

### Without Docker

```bash
# Clone and install
git clone https://github.com/Fahmousss/laravel-api-kit.git
cd laravel-api-kit
composer install

# Configure
cp .env.example .env
php artisan key:generate
php artisan jwt:secret

# Database (SQLite by default)
touch database/database.sqlite
php artisan migrate

# Verify
./vendor/bin/pest
```

## API Documentation

Once running, access the auto-generated documentation:

- **Swagger UI**: [http://localhost/docs/v1](http://localhost/docs/v1)
- **OpenAPI JSON**: [http://localhost/docs/v1/api.json](http://localhost/docs/v1/api.json)

## Authentication

This kit uses **JWT (JSON Web Token)** with token-based authentication (ideal for mobile apps and third-party API consumers).

### Login

```bash
curl -X POST http://localhost/api/v1/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password"
  }'
```

**Response:**

```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": "9d903f90-845b-4b13-9b63-149f13e54b63",
            "name": "John Doe",
            "email": "john@example.com",
            "created_at": "2025-01-15T10:30:00+00:00",
            "updated_at": "2025-01-15T10:30:00+00:00"
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
    }
}
```

### Using the Token

Include the token in the `Authorization` header for protected routes:

```bash
curl -X GET http://localhost/api/v1/me \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1Qi..." \
```

### Logout

```bash
curl -X POST http://localhost/api/v1/logout \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1Qi..." \
  -H "Accept: application/json"
```

## API Endpoints

### Version 1 (`/api/v1`)

| Method | Endpoint                  | Auth | Description                 | Rate Limit |
| ------ | ------------------------- | ---- | --------------------------- | ---------- |
| POST   | /login                    | No   | Get authentication token    | 5/min      |
| POST   | /logout                   | Yes  | Revoke current token        | 120/min    |
| GET    | /me                       | Yes  | Get current user profile    | 120/min    |

## Response Format

All API responses follow a consistent format:

### Success Response

```json
{
    "success": true,
    "message": "Operation successful",
    "data": {
        // Response data here
    }
}
```

### Error Response

```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        "field": ["Validation error message"]
    }
}
```

### HTTP Status Codes

| Code | Description       |
| ---- | ----------------- |
| 200  | Success           |
| 201  | Resource created  |
| 204  | No content        |
| 400  | Bad request       |
| 401  | Unauthorized      |
| 403  | Forbidden         |
| 404  | Not found         |
| 422  | Validation error  |
| 429  | Too many requests |
| 500  | Server error      |

## Project Structure

```
laravel-api-kit/
├── app/
│   ├── Application/                # Application logic (Use Cases/CQRS, Bus, DTOs)
│   ├── Domain/                     # Core business logic (Entities, Value Objects, Repository Interfaces)
│   ├── Infrastructure/             # External boundaries (Persistence, Third-party APIs, Service Providers)
│   ├── Presentation/               # HTTP Entry points (Controllers, Requests, Resources)
│   └── Providers/                  # Global Service Providers
├── console/                        # Artisan commands (including custom Make commands)
├── config/
│   ├── api/
│   │   └── v1.php                         # Version 1 routes
├── tests/
│   └── Feature/Api/V1/
│       └── AuthTest.php                   # Authentication tests
├── docker-compose.yml
├── Dockerfile
└── CLAUDE.md                              # AI assistant instructions
```

## API Versioning

This kit follows a manual versioning approach using directory-based route files:

- **URI Path**: `/api/v1/users`, `/api/v2/users`

### Adding a New API Version

1. Create controllers in `app/Presentation/Controllers/Api/V2/`
2. Create route file `routes/api/v2.php`:

```php
<?php

use App\Presentation\Controllers\Api\V2\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
// ... more routes
```

3. Register the new version in `routes/api.php`:

```php
// Version 2
Route::prefix('v2')->group(base_path('routes/api/v2.php'));

// Documentation
Scramble::registerUiRoute('docs/v2', api: 'v2');
Scramble::registerJsonSpecificationRoute('docs/v2/api.json', api: 'v2');
```

## Rate Limiting

Configured in `app/Providers/AppServiceProvider.php`:

| Limiter         | Limit   | Use Case                                |
| --------------- | ------- | --------------------------------------- |
| `api`           | 60/min  | Default for all API routes              |
| `auth`          | 5/min   | Login/register (brute force protection) |
| `authenticated` | 120/min | Logged-in users                         |

### Applying Rate Limiters

```php
// In routes/api.php
Route::middleware('throttle:auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware(['auth:api', 'throttle:authenticated'])->group(function () {
    Route::get('me', [AuthController::class, 'me']);
});
```

### Rate Limit Headers

Responses include rate limit information:

```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
Retry-After: 60  # When limit exceeded
```

> **Attention:**
>
> - This package **coexists** with Laravel's built-in `throttle:` middleware. You do not need to remove the existing rate limiters.
> - If you want to **replace** the native throttle on specific routes, swap `throttle:authenticated` with `throttle.smart` on those routes only.
> - Do **not** apply both `throttle:authenticated` and `throttle.smart` on the same route group — choose one per group to avoid double rate limiting.
> - The default driver is `cache`. For production, `redis` is recommended for performance and distributed consistency.
> - Configure your subscription plans in `config/throttle-smart.php` to match your business model (Free, Pro, Enterprise, etc.).

---

## Middleware

The kit includes three production-ready middleware patterns that you can apply to your routes as needed.

### Available Middleware

| Alias        | Class                 | Description                               |
| ------------ | --------------------- | ----------------------------------------- |
| `force.json` | `ForceJsonResponse`   | Ensures all responses are JSON formatted  |
| `log.api`    | `LogApiRequests`      | Logs API requests with timing information |

### ForceJsonResponse

Automatically sets `Accept: application/json` header and converts non-JSON responses to JSON format.

```php
Route::middleware('force.json')->group(function () {
    // All responses will be JSON
});
```

### LogApiRequests

Logs API requests with detailed information and adds `X-Response-Time` header to responses.

**Logged data:** timestamp, method, URL, IP, user ID, status code, duration (ms), user agent.

**Enable logging via environment:**

```env
APP_LOG_API_REQUESTS=true
```

```php
Route::middleware('log.api')->group(function () {
    // Requests will be logged
});
```


## Testing

This kit uses [Pest PHP](https://pestphp.com/) for testing:

```bash
# Run all tests
docker compose -f compose.local.yaml run --rm app ./vendor/bin/pest

# Run specific test file
docker compose -f compose.local.yaml run --rm app ./vendor/bin/pest tests/Feature/Api/V1/AuthTest.php

# Run with coverage
docker compose -f compose.local.yaml run --rm app ./vendor/bin/pest --coverage

# Run in parallel
docker compose -f compose.local.yaml run --rm app ./vendor/bin/pest --parallel
```

### Writing Tests

```php
// tests/Feature/Api/V1/UserTest.php
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lists users for authenticated user', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    User::factory()->count(5)->create();

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->getJson('/api/v1/users');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                '*' => ['id', 'name', 'email']
            ]
        ]);
});

it('requires authentication', function () {
    $this->getJson('/api/v1/users')
        ->assertStatus(401);
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
# Apply all fixes (Rector + Pint)
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

# Export OpenAPI spec to file
docker compose run --rm app php artisan scramble:export
```

### DDD Scaffolding Commands

Use the custom commands developed for this architecture to scaffold classes in their appropriate layers. These commands are interactive and will prompt you for missing arguments:

```bash
# Scaffold a new domain (Entity, RepositoryInterface, Exception)
php artisan make:domain

# Scaffold an Infrastructure layer (Model, Migration, Factory, Repository, Provider Binding)
php artisan make:infrastructure

# Scaffold an Eloquent repository
php artisan make:repository

# Scaffold a CQRS use-case (DTO + Handler)
php artisan make:use-case [--command|--query]

# Scaffold a typed DTO in the Application layer
php artisan make:data

# Scaffold a model in the Infrastructure layer
php artisan make:model
```

## Environment Configuration

Key `.env` variables:

```env
# Application
APP_NAME="Laravel API Kit"
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

# JWT
JWT_SECRET=
JWT_TTL=60

# Rate Limiting
API_RATE_LIMIT=60

# Documentation
API_DOCS_URL=http://localhost:8080/docs/api
```

## Deployment

### Production Checklist

- [ ] Set `APP_ENV=production` and `APP_DEBUG=false`
- [ ] Configure proper database (MySQL/PostgreSQL)
- [ ] See `DEPLOYMENT.md` for full production setup instructions.
- [ ] Set `APP_URL` to your production URL
- [ ] Configure `JWT_SECRET` for your application
- [ ] Review and tighten CORS settings in `config/cors.php`
- [ ] Set up proper rate limiting for production load
- [ ] Configure caching (Redis recommended)
- [ ] Set up queue worker for background jobs
- [ ] Enable HTTPS and update URLs

### Docker Production

```dockerfile
# Example production Dockerfile additions
FROM dunglas/frankenphp:1.12-php8.5-alpine

# Install opcache for performance
RUN docker-php-ext-install opcache

# Production PHP settings
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/
COPY docker/php/php.ini /usr/local/etc/php/conf.d/
```

## Extending the Kit

### Adding a New Domain Component (CRUD Example)

Using the custom DDD commands, building out a feature like `Blog` with a `Post` entity ensures your code stays organized out-of-the-box.

1. **Scaffold the Domain Layer:**

```bash
docker compose run --rm app php artisan make:domain Blog Post
# Generates App\Domain\Blog\Entities\PostEntity
# Generates App\Domain\Blog\Repositories\PostRepositoryInterface
# Generates App\Domain\Blog\Exceptions\PostNotFoundException
```

2. **Scaffold the Infrastructure Layer:**

```bash
docker compose run --rm app php artisan make:infrastructure Blog Post
# Generates App\Infrastructure\Blog\Models\Post
# Generates App\Infrastructure\Blog\Persistence\EloquentPostRepository
# Generates Database\Factories\PostFactory
# Generates Database\Migrations\..._create_posts_table.php
# Auto-registers BlogServiceProvider and binds PostRepositoryInterface
```

3. **Scaffold Use Cases (Application Layer):**

```bash
docker compose run --rm app php artisan make:use-case Blog CreatePost --command
# Generates App\Application\Features\Blog\Commands\CreatePost\CreatePostCommand
# Generates App\Application\Features\Blog\Commands\CreatePost\CreatePostCommandHandler
```

_(Optionally use `make:data Blog CreatePostData` to create DTOs separately, if prefered)._

4. **Create Controller & Form Request (Presentation Layer):**

```bash
docker compose run --rm app php artisan make:controller Presentation/Controllers/Api/V1/Blog/PostController
docker compose run --rm app php artisan make:request Presentation/Requests/Api/V1/Blog/CreatePostRequest
```

5. **Wire the Presentation to the Application Layer:**

```php
// app/Presentation/Controllers/Api/V1/Blog/PostController.php
namespace App\Presentation\Controllers\Api\V1\Blog;

use App\Application\Features\Blog\Commands\CreatePost\CreatePostCommand;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Requests\Api\V1\Blog\CreatePostRequest;
use Illuminate\Http\JsonResponse;

class PostController extends ApiController
{
    public function store(CreatePostRequest $request): JsonResponse
    {
        $command = new CreatePostCommand(
            title: $request->validated('title'),
            content: $request->validated('content'),
        );

        $post = $this->commandBus->dispatch($command);

        return $this->created(['id' => $post->id, 'title' => $post->title]);
    }
}
```

6. **Add Routes & Tests:**
   Define API routes in `routes/api/v1.php` mapping to your `Presentation` controllers.
   Use Pest to build out feature tests testing through the HTTP layer to the Infrastructure.

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request
