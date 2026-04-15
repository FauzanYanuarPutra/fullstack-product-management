# Product Manager Technical Test

A monorepo implementation of the take-home test described in `Technical Test_ Full-Stack Developer.pdf`.

The solution uses:

- Nuxt 3 for the customer-facing product UI
- NestJS for the REST API
- Laravel 11 + Filament 3 for the admin panel
- PostgreSQL as the shared database
- GitLab CI for build/test/deploy pipeline documentation
- DigitalOcean + Terraform as the documented deployment target

Recommended local runtime:

- Node.js 22+
- PHP 8.3+
- Composer
- PostgreSQL 16
- Docker / Docker Compose

## Scope assumptions

- NestJS and Laravel are separate services connected to one PostgreSQL database.
- Product search and pagination are handled server-side in the NestJS `GET /api/products` endpoint.
- A `GET /api/products/:id` endpoint is included because the frontend requires a detail page.
- Filament manages the same `products` table directly.
- Real deployment and real Terraform execution are intentionally out of scope, as required by the brief.

## Repository structure

```text
apps/
  api/        NestJS REST API
  web/        Nuxt 3 frontend
  admin/      Laravel 11 + Filament admin panel
infra/
  terraform/  deployment strategy notes
.gitlab-ci.yml
docker-compose.yml
README.md
```

## Features

### Frontend

- Responsive product list
- Product detail page
- Add product form
- Server-backed search
- Server-backed pagination

### API

- `GET /api/products`
- `GET /api/products/:id`
- `POST /api/products`
- Request validation and structured pagination metadata

### Admin panel

- Product CRUD
- Searchable table columns
- Basic price filter

## Shared database schema

The Laravel migration defines the shared schema:

```sql
CREATE TABLE products (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  price NUMERIC(12,2) NOT NULL CHECK (price >= 0),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Local development

### 1. Start PostgreSQL

```bash
docker-compose up -d
```

### 2. API setup

```bash
cd apps/api
cp .env.example .env
npm install
npm run start:dev
```

API default URL: `http://localhost:3001`

### 3. Frontend setup

```bash
cd apps/web
cp .env.example .env
npm install
npm run dev
```

Frontend default URL: `http://localhost:3000`

### 4. Admin panel setup

```bash
cd apps/admin
cp .env.example .env
composer install
php artisan filament:install --panels
php artisan filament:upgrade
php artisan key:generate
php artisan migrate
php artisan serve
```

Admin default URL: `http://localhost:8000/admin`

## API contract

### List products

`GET /api/products?page=1&limit=10&search=phone`

Response:

```json
{
  "data": [
    {
      "id": "1",
      "name": "Phone XL",
      "description": "Flagship device",
      "price": "999.99",
      "createdAt": "2026-04-15T05:00:00.000Z",
      "updatedAt": "2026-04-15T05:00:00.000Z"
    }
  ],
  "meta": {
    "page": 1,
    "limit": 10,
    "total": 1,
    "totalPages": 1
  }
}
```

### Create product

`POST /api/products`

```json
{
  "name": "Phone XL",
  "description": "Flagship device",
  "price": 999.99
}
```

### Product detail

`GET /api/products/:id`

## Testing

### API

```bash
cd apps/api
npm run test
```

## CI/CD

The GitLab pipeline includes:

- dependency installation for API and frontend
- API linting and unit tests
- build steps for API and frontend
- Laravel dependency installation
- a documented deployment stage for DigitalOcean

## Deployment strategy

The expected production shape is:

- `app.example.com` -> Nuxt app
- `api.example.com` -> NestJS service
- `admin.example.com` -> Laravel Filament
- Nginx as reverse proxy
- PostgreSQL on DigitalOcean Managed Databases or a smaller single-droplet fallback

`infra/terraform/README.md` documents the Terraform-managed resources and release flow.

## Submission checklist

- public Git repository
- clear setup and local run instructions
- Nuxt frontend matching the requested product flows
- NestJS API with validation
- Laravel Filament product CRUD
- PostgreSQL migration
- `.gitlab-ci.yml`
- DigitalOcean/Terraform explanation

## Notes

- The environment used for this implementation does not have Composer, PHP, or installed npm dependencies available, so the repo was scaffolded directly rather than generated from framework CLIs.
- The Laravel app files included here are the project-specific files needed for a standard Laravel 11 + Filament setup.
- Admin authentication is intentionally omitted because the brief only requires product CRUD, search, and filters.
- If Filament assets ever return `404`, rerun `php artisan filament:install --panels` and `php artisan filament:upgrade`, then hard-refresh the browser.
- The frontend visual direction intentionally follows a modern Indonesian product-commerce aesthetic aligned with the target company context, without cloning any proprietary branding or layouts.
