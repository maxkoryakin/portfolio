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

## Helpful Commands

```bash
# Symfony cache clear
 docker compose exec app php bin/console cache:clear

# Run migrations
 docker compose exec app php bin/console doctrine:migrations:migrate
```
