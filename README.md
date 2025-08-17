## Requirements

PHP >= 8.1

Composer

MySQL or any supported database

Laravel 10+

---

Installation

Clone the repository

```bash
git clone https://github.com/saifur-shamim/gad-cheap.git

````

```bash
cd your-repo
````

Install dependencies

```bash
composer install
````

Copy .env file

```bash
cp .env.example .env
````

Set up environment variables
Edit .env to set your database and app settings:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=root
DB_PASSWORD=secret


Generate application key

```bash
php artisan key:generate
````



Run migrations


```bash
php artisan migrate
````

Run the development server

```bash
php artisan serve
````






Server will run at: http://127.0.0.1:8000

API Endpoints
Register
POST /api/register


Request Body:

{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone": "01712345678",
    "password": "password123"
}

Login
POST /api/login


Request Body:

{
    "email": "john@example.com",
    "password": "password123"
}


Response:

{
  "status": 200,
  "message": "Logged in successfully",
  "user": { ... },
  "token": "your_api_token",
  "token_type": "Bearer"
}

Logout
POST /api/logout


Header:

Authorization: Bearer {token}

