# VisionBridgeSolutions

A client-facing website built with Laravel 11, Dockerized with PHP 8.3, MySQL, Nginx, and phpMyAdmin.

## Stack

| Service    | Technology     | Port |
|------------|----------------|------|
| App        | PHP 8.3-FPM    | 9000 |
| Web Server | Nginx          | 8000 |
| Database   | MySQL (latest) | 3306 |
| DB Manager | phpMyAdmin     | 8080 |

## Setup

```bash
# 1. Copy environment file
cp .env.example .env

# 2. Build and start containers
docker-compose up -d --build

# 3. Generate app key
docker-compose exec app php artisan key:generate

# 4. Run migrations
docker-compose exec app php artisan migrate
```

## Access

| Service    | URL                    |
|------------|------------------------|
| Website    | http://localhost:8000  |
| phpMyAdmin | http://localhost:8080  |

**phpMyAdmin:** user `visionbridge_user` / password `visionbridge_pass`

## Common commands

```bash
docker-compose up -d          # start
docker-compose down           # stop
docker-compose down -v        # stop + wipe database
docker-compose exec app bash  # shell into app container
docker-compose exec app php artisan <command>
```
