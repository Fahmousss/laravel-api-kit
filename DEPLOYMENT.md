# Deployment Guide

This guide explains how to deploy the Laravel API Kit using the production Docker setup.

## Prerequisites

- Docker and Docker Compose installed on the production server.
- A domain name pointing to your server's IP (if applicable).

## Production Setup

### 1. Environment Configuration

Copy the production environment template:

```bash
cp env.production .env.production
```

Edit `.env.production` and set your production values:
- `APP_KEY`: Generate this using `php artisan key:generate --show`.
- `JWT_SECRET`: Generate this using `php artisan jwt:secret --show`.
- `DB_PASSWORD`: Set a strong database password.
- `REDIS_PASSWORD`: Set a strong Redis password.
- `APP_URL`: Set your actual production URL.
- `WITH_HORIZON`, `WITH_SCHEDULER`, `WITH_REVERB`: Set to `true` or `false` to enable/disable background services.

### 2. Custom Container and Image Naming

You can customize the Docker image name and container names by editing the following variables in `.env.production`:

- `DOCKER_IMAGE_NAME`
- `DOCKER_IMAGE_TAG`
- `APP_CONTAINER_NAME`
- `SCHEDULER_CONTAINER_NAME`
- `WORKER_CONTAINER_NAME`
- `DB_CONTAINER_NAME`
- `REDIS_CONTAINER_NAME`

### 3. Build and Start Containers

Use the production compose file to build and start the services:

```bash
docker compose -f compose.production.yaml up -d --build
```

This will start:
- **app**: The Laravel application running on FrankenPHP (Octane).
- **scheduler**: A dedicated container for running scheduled tasks.
- **worker**: A dedicated container for processing queues.
- **db**: PostgreSQL database.
- **redis**: Redis for caching and queues.

### 3. Initial Commands

Once the containers are running, perform the initial setup:

```bash
# Run migrations
docker compose -f compose.production.yaml exec app php artisan migrate --force

# Optimize the application
docker compose -f compose.production.yaml exec app php artisan optimize
```

## Monitoring and Maintenance

### Checking Logs

```bash
docker compose -f compose.production.yaml logs -f app
```

### Scaling Workers

If you need more queue workers, you can scale the worker service:

```bash
docker compose -f compose.production.yaml up -d --scale worker=3
```

### Updating the Application

To deploy updates:

```bash
git pull origin main
docker compose -f compose.production.yaml up -d --build
docker compose -f compose.production.yaml exec app php artisan migrate --force
```

## Security Considerations

- Ensure `.env.production` is never committed to version control.
- The `db` and `redis` ports are NOT exposed to the host by default in `compose.production.yaml` for security.
- Use a reverse proxy (like Nginx, Traefik, or Cloudflare Tunnel) in front of the `app` container to handle SSL/TLS. The `app` container listens on port 80 by default (mappable via `APP_PORT`).
