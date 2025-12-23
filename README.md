# HomySync PHP API

A clean architecture implementation of the HomySync API using pure PHP and SQLite. This project serves as a reference for implementing hexagonal architecture principles without the overhead of heavy frameworks.

## 🚀 Features

- **Hexagonal Architecture**: Clear separation between Domain, Core (Ports), Application (Services), and Infrastructure (Adapters).
- **Zero Framework Overheads**: Built with pure PHP to understand the flow of data and dependency injection.
- **SQLite Integration**: Portable database for easy development and testing.
- **Security**: Industry-standard password hashing using `password_hash` (Bcrypt).

## 📁 Project Structure

```text
src/
├── Domain/          # Business entities (User, Household, etc.)
├── Core/            # Interfaces and Ports (Contracts)
├── Services/        # Application Logic (Use Cases)
├── Adapters/
│   ├── Persistence/ # Database implementations (PDO / SQLite)
│   └── Http/        # Request handlers (Controllers)
public/              # Entry point (index.php) and Routing
```

## 🛠️ Requirements
* PHP 8.1 or higher
* SQLite3 extension enabled
* Composer (for autoloading)

## 📥 Installation

```bash
git clone [https://github.com/your-username/homysync-php-api.git](https://github.com/your-username/homysync-php-api.git)
cd homysync-php-api
```

```bash
composer install
```

```bash
composer dump-autoload
```

## 🏃 Running the API

```bash
php -S localhost:8080 -t public
```