<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

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

## Launching the Application

This application can be launched using Docker and Docker Compose. Follow these steps to get started:

### Prerequisites

- Docker and Docker Compose installed on your system
- Make sure ports 8000 (web), 3306 (MySQL), and 6379 (Redis) are available

### Quick Start

The application is already configured and ready to run. Simply execute:

```bash
make up
```

This will start all Docker services and make the application available at **http://localhost:8000**.

### First-Time Setup (if needed)

If this is your first time running the application or you want a completely fresh start:

1. **Clone the repository and navigate to the project directory:**
   ```bash
   git clone https://github.com/yourusername/favorite-products.git
   cd favorite-products
   ```

2. **Copy the environment file:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Launch the application with fresh setup:**
   ```bash
   make fresh
   ```

   This command will:
   - Clean up any existing containers
   - Build the Docker images
   - Start all services (app, nginx, MySQL, Redis)
   - Install PHP and Node.js dependencies
   - Run database migrations

### Access Points

- **Web Application:** http://localhost:8000
- **API Documentation (Swagger/OpenAPI):** http://localhost:8000/api/documentation
- **Database:** localhost:3306 (user: `favorite_user`, password: `favorite_password`)
- **Redis:** localhost:6379

### Available Make Commands

- `make help` - Show all available commands
- `make up` - Start all services
- `make down` - Stop all services
- `make build` - Build Docker images
- `make install` - Install dependencies
- `make migrate` - Run database migrations
- `make seed` - Seed the database
- `make test` - Run tests
- `make logs` - Show logs from all services
- `make shell` - Access the app container shell
- `make clean` - Remove all containers and volumes
- `make fresh` - Complete fresh start

### Manual Setup (Alternative)

If you prefer not to use Docker:

1. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

2. **Set up the environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Set up the database:**
   Configure your `.env` file with database credentials and run:
   ```bash
   php artisan migrate
   ```

4. **Start the development servers:**
   ```bash
   php artisan serve
   npm run dev
   ```

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

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
