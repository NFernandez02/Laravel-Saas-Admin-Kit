# Laravel SaaS Admin Kit

A Laravel 13-based SaaS admin starter kit featuring authentication, role-based access control (RBAC), permissions, audit logging, profile management, and a scalable service-layer architecture with both Web (Blade) and API (Sanctum) support.

---

![Tests](https://github.com/NFernandez02/Laravel-Saas-Admin-Kit/actions/workflows/tests.yml/badge.svg)

---

## Project Status

Active development.

Current focus:
- Backend architecture
- RBAC system
- API development
- Redis caching
- Queues & scheduling
- Docker deployment

Frontend modernization remains a future enhancement.

## Features

### Authentication & Authorization

- Laravel Sanctum authentication
- Role-Based Access Control
- Permission-based middleware
- Policy-based authorization
- API authentication support

### User Management

- User CRUD operations
- Role assignment per user
- Profile management (name, email, bio, avatar)
- Password update functionality
- Search and pagination

### Roles & Permissions

- Role management
- Permission management
- Many-to-many role-permission relationships
- Route-level authorization
- Admin-only route grouping

### Audit Logging

- Automatic audit log generation
- Create, update, and delete activity tracking
- User activity history
- Audit log management interface

### API Features

- RESTful API architecture
- API Resources for response formatting
- API versioning
- Rate limiting
- Form Request validation
- Consistent error handling

### Performance & Infrastructure

- Redis caching
- Event-driven cache invalidation
- Laravel Events & Listeners
- Queue-based background jobs
- Scheduled task execution

### Architecture

- Service Layer architecture
- Form Request validation
- Route Model Binding
- Eager loading optimization
- Hybrid Web (Blade) and API architecture

### DevOps & Deployment

- Docker development environment
- Production Docker Compose setup
- Nginx reverse proxy
- Redis queue workers
- Dedicated scheduler container

### Quality Assurance

- Feature tests
- PHPStan static analysis
- Laravel Pint code formatting
- GitHub Actions CI

### UI

- Bootstrap 5 admin dashboard
- Responsive admin interface

---

## Tech Stack
- Laravel 13
- PHP 8.4
- MySQL 8
- Redis 8
- Bootstrap 5
- Docker
- Nginx
- Laravel Sanctum
- GitHub Actions

---

## Architecture Overview

### Application Architecture

- MVC structure
- Service Layer pattern for business logic
- Form Requests for validation
- API Resources for response transformation
- Route Model Binding
- Eloquent ORM relationships
- Eager Loading optimization

### Authorization & Security

- Role-Based Access Control (RBAC)
- Permission-based middleware
- Policy-based authorization
- Laravel Sanctum authentication
- API rate limiting

### Infrastructure & Performance

- Redis caching
- Event-driven cache invalidation
- Laravel Events & Listeners
- Queue-based background processing
- Scheduled task execution
- Dockerized application stack

### Development & Quality

- Feature testing
- Laravel Pint code formatting
- PHPStan static analysis
- GitHub Actions CI workflow

### Deployment

- Docker development environment
- Production Docker Compose setup
- Nginx reverse proxy
- Redis queue workers
- Dedicated scheduler container

---

## Installation

### Requirements
- PHP 8.4+
- Composer
- Node.js & npm
- MySQL 8+
- Redis 8+ (included automatically in Docker setup)

### Local Development Setup
```bash
git clone https://github.com/NFernandez02/Laravel-Saas-Admin-Kit

cd laravel-saas-admin-kit

composer install

cp .env.example .env

php artisan key:generate

npm install

npm run dev

php artisan storage:link

php artisan migrate --seed

php artisan serve
```
Application will be available at:

http://localhost:8000

### Docker Development Setup

Copy environment file:

```bash
cp .env.example .env
```

Start containers:

```bash
docker compose up --build
```

Install dependencies:

```bash
docker compose exec app composer install
```

Generate application key:

```bash
docker compose exec app php artisan key:generate
```

Run migrations and seeders:

```bash
docker compose exec app php artisan migrate --seed
```

Available services:
- Laravel Application
- MySQL
- Redis
- Nginx
- Queue Worker
- Scheduler

Application will be available at:

http://localhost:8000

### Docker Production Setup

Copy environment production file

```bash
cp .env.production.example .env.production
```

Edit `.env.production` and configure:

```text
APP_KEY
MYSQL_ROOT_PASSWORD
DB_PASSWORD
APP_URL
```

Build and start containers:

```bash
docker compose --env-file .env.production -f docker-compose.prod.yml up --build -d
```

Run database migrations:

```bash
docker compose --env-file .env.production -f docker-compose.prod.yml exec app php artisan migrate --force
```

Create storage symlink:

```bash
docker compose --env-file .env.production -f docker-compose.prod.yml exec app php artisan storage:link
```

Verify services:

```bash
docker compose --env-file .env.production -f docker-compose.prod.yml ps
```

Production stack includes:
- Nginx
- PHP-FPM
- MySQL
- Redis
- Queue Worker
- Scheduler

View logs with:
```bash
docker compose --env-file .env.production -f docker-compose.prod.yml logs -f
```

Optional Demo Data
```bash
docker compose --env-file .env.production -f docker-compose.prod.yml exec app php artisan db:seed
```
Only run seeders if you want demo accounts,
roles, permissions, and sample data.

Stop the application:

```bash
docker compose --env-file .env.production -f docker-compose.prod.yml down
```

Stop and remove all data volumes:

```bash
docker compose --env-file .env.production -f docker-compose.prod.yml down -v
```

## Demo Credentials

### Admin Account

Email: admin@example.com
Password: password

## API Overview

Full API documentation is available in the `api.md` in the docs folder.

- Authentication endpoints
- User, role, and permission management
- Profile and password endpoints
- Audit logging
- Pagination and search behavior

## Future Improvements
- Frontend SPA (Vue or React)
- Email verification
- Two-factor authentication (2FA)
- OAuth authentication
- Multi-tenancy support
- Activity analytics dashboard
- Advanced permission inheritance system

## License

MIT License
