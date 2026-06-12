# Laravel SaaS Admin Kit

A Laravel 13-based SaaS admin starter kit featuring authentication, role-based access control (RBAC), permissions, audit logging, profile management, and a scalable service-layer architecture with both Web (Blade) and API (Sanctum) support.

---
![Tests](https://github.com/NFernandez02/Laravel-Saas-Admin-Kit/actions/workflows/tests.yml/badge.svg)
---
## Features

### Authentication & Authorization
- Laravel Sanctum authentication
- Role-based access control (RBAC)
- Permission-based middleware
- Policy-based authorization

### User Management
- User CRUD (admin-only)
- Role assignment per user
- Profile management (name, email, bio, avatar)
- Password update endpoint

### Access Control System
- Roles & permissions (many-to-many)
- Route-level permission middleware
- Admin-only route grouping

### System Features
- Audit logging (create, update, delete tracking)
- Pagination and search
- Service-layer architecture
- Form Request validation
- API + Blade hybrid structure

### UI
- Bootstrap 5 admin dashboard

---

## Tech Stack
- Laravel 13
- PHP 8+
- MySQL
- Bootstrap 5

---

## Architecture Overview
- MVC structure
- Service layer for business logic
- Form Requests for validation
- Policies for authorization
- Eloquent relationships
- Route model binding
- Eager loading optimization

---

## Installation

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

## Demo Credentials

### Admin Account

Email: admin@admin.com
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
- Docker setup
- Email verification
- Two-factor authentication (2FA)
- API rate limiting enhancements
- Activity analytics dashboard
- Advanced permission inheritance system
- Testing (PHPUnit/Pest)

## License

MIT License
