# Deployment Guide

This guide explains how to deploy the Laravel API Kit using Docker only — no local PHP or Composer required.

## Prerequisites

- Docker and Docker Compose installed on the production server.
- `make` installed (`apt install make` / `yum install make`).
- A domain name pointing to your server's IP (if applicable).

---

## Production Setup

### 1. Clone the Repository

```bash
git clone <your-repo-url> && cd <project-folder>
```

### 2. Prepare the Environment File

The `.env.production` file is your production config. Fill in the values step by step below.

### 3. Build the Image & Generate Secrets

Run the one-shot init command. It builds the image and prints all generated secrets:

```bash
make init
```

This will output something like:
```
APP_KEY=base64:abc123...
JWT_SECRET=xyz789...
RANDOM_PASSWORD=s0m3Str0ngP4ssw0rd
```

### 4. Fill `.env.production`

Edit `.env.production` and set the following values:

| Variable | Description |
| --- | --- |
| `APP_KEY` | Output of `make init` above |
| `APP_URL` | Your actual production URL (e.g. `https://api.example.com`) |
| `JWT_SECRET` | Output of `make init` above |
| `DB_PASSWORD` | Strong random password from `make init` |
| `REDIS_PASSWORD` | Same strong random password (must match) |
| `WITH_SCHEDULER` | `true` to enable cron jobs |
| `WITH_HORIZON` | `true` to enable Horizon queue manager |
| `WITH_REVERB` | `true` to enable WebSocket server |
| `REVERB_APP_ID` | Any unique integer (required if `WITH_REVERB=true`) |
| `REVERB_APP_KEY` | Any random string (required if `WITH_REVERB=true`) |
| `REVERB_APP_SECRET` | Any random string (required if `WITH_REVERB=true`) |

### 5. Custom Container and Image Naming (optional)

You can customize Docker image and container names via `.env.production`:

- `DOCKER_IMAGE_NAME`
- `DOCKER_IMAGE_TAG`
- `APP_CONTAINER_NAME`
- `SCHEDULER_CONTAINER_NAME`
- `DB_CONTAINER_NAME`
- `REDIS_CONTAINER_NAME`

### 6. Start the Stack

```bash
make up
```

This starts:
- **app** — Laravel application running on FrankenPHP (Octane), port `APP_PORT` (default: 80)
- **scheduler** — Cron jobs, and optionally Horizon and Reverb (controlled by `WITH_*` toggles)
- **db** — PostgreSQL database
- **redis** — Redis for cache, sessions, and queues

---

## Makefile Reference

Run `make help` to see all available commands.

| Command | Description |
| --- | --- |
| `make init` | Build image + generate all secrets |
| `make build` | Build the production Docker image |
| `make generate-keys` | Generate APP_KEY, JWT_SECRET, and a random password |
| `make up` | Start the full stack |
| `make down` | Stop and remove all containers |
| `make restart` | Stop then start the stack |
| `make rebuild` | Rebuild image and restart (after code changes) |
| `make update` | Pull latest code, rebuild, restart |
| `make migrate` | Run database migrations |
| `make optimize` | Clear and rebuild all caches |
| `make logs` | Stream logs from all containers |
| `make logs-app` | Stream logs from the app container |
| `make logs-scheduler` | Stream logs from the scheduler container |
| `make logs-db` | Stream logs from the database container |
| `make logs-redis` | Stream logs from the Redis container |
| `make ps` | Show running container status |
| `make tinker` | Open artisan tinker inside app container |
| `make artisan CMD="..."` | Run any artisan command |

---

## Monitoring and Maintenance

### Checking Logs

```bash
make logs          # all containers
make logs-app      # app only
make logs-scheduler  # horizon / reverb / cron
```

### Updating the Application

```bash
make update
```

---

## Security Considerations

- **Never commit `.env.production`** to version control — it is listed in `.gitignore`.
- The `db` and `redis` ports are **not exposed** to the host by default for security.
- Use a reverse proxy (Nginx, Traefik, or Cloudflare Tunnel) in front of the `app` container to handle SSL/TLS. The `app` container listens on port 80 by default (configurable via `APP_PORT`).
