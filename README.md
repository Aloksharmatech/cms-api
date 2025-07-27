<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>
# 📰 Laravel CMS API with AI-Powered Slug & Summary

This project is a **Content Management System (CMS)** API built with **Laravel 12**, **MySQL**, and **Sanctum Authentication**. It includes **role-based access** (Admin, Author) and **AI-powered** slug and summary generation using **OpenRouter** and models like `mistral-7b-instruct`.

---

## 🚀 Features

- ✅ User Authentication (Login, Logout, Me)
- ✅ Role-based Authorization (Admin / Author)
- ✅ CRUD operations for:
  - Articles (with filters, pagination)
  - Categories (Admin-only)
- ✅ AI Integration for:
  - Auto-generating Slugs
  - Auto-summarizing Article Content
- ✅ Laravel Policies for secure update/delete
- ✅ Article Filters by:
  - Category
  - Status
  - Published Date Range

---

## 🔧 Technologies Used

- **Laravel 12**
- **MySQL**
- **Sanctum** for API authentication
- **Spatie Laravel-Permission** for roles
- **OpenRouter API** for AI integration

---

## 📁 Installation

```bash
git clone https://github.com/yourusername/laravel-cms-api.git
cd laravel-cms-api

composer install
cp .env.example .env

php artisan key:generate
php artisan migrate --seed



DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_pass

OPENROUTER_API_KEY=your_openrouter_key

## 📬 Postman Collection

You can test all the CMS API endpoints using this Postman collection:

👉 [Laravel CMS API Postman Collection](https://web.postman.co/workspace/My-Workspace~4b7f783b-3f58-46a7-8b3c-00ab00df9140/collection/43050549-30e8de57-7d1b-4c41-a16f-1735dc4583e2?action=share&source=copy-link&creator=43050549)

To get started:
- Click the link above
- Fork or import into your Postman workspace
- Set `{{base_url}}` to `http://localhost:8000/api`


## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
