# Laravel SaaS Admin Kit

A Laravel 13-based SaaS admin starter kit featuring authentication, RBAC, permissions, audit logging, profile management, and scalable backend architecture.

## Features

* Authentication system
* Role-based access control (RBAC)
* Permission middleware
* User & role management
* Audit logging
* Policy-based authorization
* Service-layer architecture
* Form Request validation
* Pagination & search
* Database seeders
* Profile & password management
* Avatar uploads
* Bootstrap 5 UI

## Tech Stack

* Laravel 13
* PHP
* MySQL
* Bootstrap 5

## Backend Architecture

* MVC architecture
* Service classes
* Middleware
* Policies
* Eloquent relationships
* Route model binding
* Eager loading
* Validation separation

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

Email: admin@example.com
Password: password

## Future Improvements

* REST API support
* Automated testing
* Docker setup
* Two-factor authentication (2FA)
* Email verification
* Activity analytics
* Advanced permission management
* Tailwind UI redesign

## License

MIT License
