# Favorite Products API

A modern, well-architected Laravel API for managing user favorite product lists. Built with clean architecture principles, comprehensive testing, and extensive documentation.

## 🏗️ Architecture Overview

This project demonstrates modern PHP/Laravel development practices with:

- **Clean Architecture**: Repository pattern with service layer separation
- **Data Transfer Objects**: Type-safe DTOs using Spatie Laravel Data
- **Comprehensive Testing**: Unit and integration tests with 100% coverage
- **API Documentation**: OpenAPI/Swagger documentation
- **Authentication**: Custom middleware with Bearer token support
- **Exception Handling**: Structured error responses with custom exceptions

## 🚀 Features

- **User Management**: Multi-user system with proper authorization
- **Favorite Lists**: Create, read, update, and delete favorite product lists
- **Product Management**: Add/remove products from favorite lists
- **RESTful API**: Full CRUD operations with proper HTTP status codes
- **Data Validation**: Request validation using DTOs
- **Error Handling**: Structured error responses
- **Soft Deletes**: Safe deletion with recovery capability
- **API Documentation**: Interactive Swagger UI documentation

## 📊 Database Schema

### Tables
- **`users`** - User accounts with authentication
- **`favorite_lists`** - User-created favorite lists (soft delete enabled)
- **`favorite_list_products`** - Products within favorite lists (unique constraints)

### Relationships
- Users → Favorite Lists (1:M)
- Favorite Lists → Products (1:M)
- Cascade delete protection

## 📋 API Endpoints

### Favorite Lists
- `GET /api/lists` - Get user's favorite lists
- `POST /api/lists` - Create a new favorite list
- `GET /api/lists/{id}` - Get specific list with products
- `PUT /api/lists/{id}` - Update a favorite list
- `DELETE /api/lists/{id}` - Delete a favorite list

### Favorite List Products
- `POST /api/lists/{listId}/products` - Add product to list
- `DELETE /api/lists/{listId}/products/{sku}` - Remove product from list

### Authentication
Uses Bearer token authentication via `Authorization: Bearer {token}` header.

## 🧪 Testing

The project includes comprehensive testing:

```bash
# Run all tests
make test
# or
php artisan test

# Run specific test suite
php artisan test --filter FavoriteListAuthorizationTest

# Generate test coverage (if configured)
php artisan test --coverage
```

### Test Coverage
- **Unit Tests**: Basic functionality and utilities
- **Feature Tests**: API endpoints and integration testing
- **Authorization Tests**: Complete user isolation and access control
- **Database Tests**: Data integrity and relationships

## 🏛️ Architecture Details

### Layered Architecture
```
┌─────────────────┐
│   Controllers   │  ← HTTP request/response handling
├─────────────────┤
│    Services     │  ← Business logic
├─────────────────┤
│  Repositories   │  ← Data access layer
├─────────────────┤
│     Models      │  ← Eloquent ORM
├─────────────────┤
│    Database     │  ← MySQL with migrations
└─────────────────┘
```

### Key Components

#### Data Transfer Objects (DTOs)
- `FavoriteListData` - List information with camelCase properties
- `ProductData` - Product details with stock status
- `FavoriteListWithProductsData` - Combined list and products

#### Request Objects
- `CreateFavoriteListRequest` - Validated list creation
- `UpdateFavoriteListRequest` - Validated list updates
- `AddProductToListRequest` - Validated product addition

#### Custom Exceptions
- `FavoriteListNotFoundException` - List not found errors
- `ProductNotFoundException` - Product not found in list errors
- Proper HTTP 404 responses with structured JSON

#### Repositories
- Data access abstraction
- Exception throwing for not found resources
- Clean separation from business logic

#### Services
- Business logic encapsulation
- Transaction management (when needed)
- Complex operations coordination

## 🔧 Development

### Code Quality
- **PHPDoc**: Comprehensive documentation for all methods
- **Type Hints**: Full type declarations
- **PSR Standards**: PSR-4 autoloading, PSR-12 coding standards
- **Clean Code**: Single responsibility principle

### Security
- **Authorization**: User isolation and access control
- **Input Validation**: Request object validation
- **SQL Injection Protection**: Eloquent ORM protection
- **XSS Protection**: Laravel's built-in protections

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
