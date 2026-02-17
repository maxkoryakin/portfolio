# Symfony IT Portfolio (Bilingual, Dynamic, Dockerized)

A fully dynamic, bilingual (EN/FR) IT portfolio built with Symfony, Doctrine, EasyAdmin, Tailwind CSS, and MySQL — running entirely in Docker.

## Features

- 100% dynamic content stored in the database
- Bilingual UI and content (English / French)
- Secure admin dashboard (EasyAdmin) for all content
- Public pages for projects, skills, experience, education, resume, hobbies, testimonials, and contact
- Testimonials are moderated (pending → approved/rejected)
- Contact form stores messages in database
- Tailwind CSS UI with responsive layout
- Docker + Apache + MySQL

## Tech Stack

- PHP 8.2+
- Symfony 7 LTS
- Doctrine ORM
- EasyAdmin Bundle
- Tailwind CSS (SymfonyCasts Tailwind Bundle)
- MySQL
- Docker / Docker Compose

## Requirements

- Docker
- Docker Compose

> No local PHP, Composer, or MySQL installation is required.

## Setup (Development)

1) Start containers

```bash
docker compose up -d --build
```

2) Install PHP dependencies

```bash
docker compose exec app composer install
```

3) Create database and run migrations

```bash
docker compose exec app php bin/console doctrine:database:create
docker compose exec app php bin/console doctrine:migrations:migrate
```

4) Build Tailwind CSS

```bash
docker compose exec app php bin/console tailwind:build
```

For watch mode during development:

```bash
docker compose exec app php bin/console tailwind:build --watch
```

5) Create the first admin user

```bash
docker compose exec app php bin/console app:create-admin
```

6) Open the app

- Public site: `http://localhost:8080/en`
- Admin dashboard: `http://localhost:8080/admin`

## Environment Variables

The project uses `.env` for configuration. Update as needed:

- `DB_NAME`, `DB_USER`, `DB_PASSWORD`, `DB_ROOT_PASSWORD`
- `DATABASE_URL`
- `APP_SECRET`
- `RECAPTCHA_SITE_KEY`, `RECAPTCHA_SECRET_KEY` (for Google reCAPTCHA on contact form)

To enable captcha protection, create a site in Google reCAPTCHA (v2 Checkbox), then set both keys in `.env.local`.

## Admin Panel (EasyAdmin)

Login at `/admin` and manage all content:

- Projects
- Skills
- Work Experience
- Education
- Resume (PDF upload)
- Hobbies
- Contact Info
  - Includes author photo upload (stored in `public/uploads/profile`)
- Testimonials (approve/reject)
- Contact Messages (read-only)
- Admin Users

Uploads are stored in `public/uploads/`.

## Public Pages

- Projects grid
- Skills
- Work Experience
- Education
- Resume download
- Hobbies
- Testimonials (approved only)
- Contact form (messages stored in DB)

## Production Notes

Before deploying:

```bash
docker compose exec app php bin/console tailwind:build --minify
```

Then build assets:

```bash
docker compose exec app php bin/console asset-map:compile
```

Set `APP_ENV=prod` and configure `APP_SECRET` & `DATABASE_URL` securely.

## Production Deploy (Your Current Ubuntu Server Setup)

This section matches your current server layout:

- Project path: `/opt/portfolio/portfolio`
- Docker files: `compose.yaml` + `compose.prod.yaml`
- App container published to: `127.0.0.1:8081:80`
- DB container published to: `127.0.0.1:3307:3306`
- Production env file: `.env.local` (with `APP_ENV=prod`, `APP_DEBUG=0`, DB and reCAPTCHA keys)
- Apache reverse proxy forwards public traffic to `http://127.0.0.1:8081`

### 1) Connect and go to the project

```bash
ssh ubuntu@<SERVER_IP>
cd /opt/portfolio/portfolio
```

### 2) (Recommended) quick safety checks before update

```bash
git status
docker compose -f compose.yaml -f compose.prod.yaml ps
```

Optional backup (DB + uploads):

```bash
mkdir -p ~/backups/portfolio
STAMP=$(date +%F-%H%M%S)
docker compose -f compose.yaml -f compose.prod.yaml exec -T database sh -lc 'mysqldump -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE"' > ~/backups/portfolio/${STAMP}-db.sql
tar -czf ~/backups/portfolio/${STAMP}-uploads.tgz public/uploads
```

### 3) Pull latest code

```bash
git fetch origin
git checkout main
git pull --ff-only origin main
```

### 4) Rebuild/restart containers with production override

```bash
docker compose -f compose.yaml -f compose.prod.yaml up -d --build
```

### 5) Run production update commands inside the app container

```bash
docker compose -f compose.yaml -f compose.prod.yaml exec -T app composer install --no-dev --optimize-autoloader --no-interaction
docker compose -f compose.yaml -f compose.prod.yaml exec -T app php bin/console doctrine:migrations:migrate --no-interaction --env=prod
docker compose -f compose.yaml -f compose.prod.yaml exec -T app php bin/console tailwind:build --minify --env=prod
docker compose -f compose.yaml -f compose.prod.yaml exec -T app php bin/console asset-map:compile --env=prod
docker compose -f compose.yaml -f compose.prod.yaml exec -T app php bin/console cache:clear --env=prod
```

Important:

- Do **not** run `doctrine:database:create` on this server after first setup (DB already exists).
- Keep secrets only in `.env.local` (never commit them).

### 6) Verify deployment

```bash
docker compose -f compose.yaml -f compose.prod.yaml ps
docker compose -f compose.yaml -f compose.prod.yaml logs --tail=100 app
curl -I http://127.0.0.1:8081/en
```

Then verify public URLs in browser:

- `https://max.coreachin.com/en`
- `https://max.coreachin.com/admin`

### 7) Apache (only if proxy config was changed)

```bash
sudo apachectl configtest
sudo systemctl reload apache2
```

### 8) One-command update helper (optional)

```bash
cd /opt/portfolio/portfolio
git fetch origin && git checkout main && git pull --ff-only origin main && \
docker compose -f compose.yaml -f compose.prod.yaml up -d --build && \
docker compose -f compose.yaml -f compose.prod.yaml exec -T app composer install --no-dev --optimize-autoloader --no-interaction && \
docker compose -f compose.yaml -f compose.prod.yaml exec -T app php bin/console doctrine:migrations:migrate --no-interaction --env=prod && \
docker compose -f compose.yaml -f compose.prod.yaml exec -T app php bin/console tailwind:build --minify --env=prod && \
docker compose -f compose.yaml -f compose.prod.yaml exec -T app php bin/console asset-map:compile --env=prod && \
docker compose -f compose.yaml -f compose.prod.yaml exec -T app php bin/console cache:clear --env=prod
```

## Helpful Commands

```bash
# Symfony cache clear
 docker compose exec app php bin/console cache:clear

# Run migrations
 docker compose exec app php bin/console doctrine:migrations:migrate
```
