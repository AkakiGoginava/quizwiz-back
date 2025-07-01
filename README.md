# QuizWiz Backend

QuizWiz is a Laravel-based backend for a Quizwiz platform, supporting quiz filtering, sorting, user progress tracking, and email notifications.

## Features

-   Filtering and sorting for quizzes (by category, difficulty, title, completion status, etc.)
-   User quiz completion tracking
-   RESTful API endpoints for quizzes and user actions
-   Email notifications with custom HTML/CSS templates (Gmail-compatible)
-   Unit tests

## Admin Panel (Filament)

QuizWiz includes an integrated admin panel built with [Filament](https://filamentphp.com/), a modern Laravel admin toolkit.

-   **Purpose:** Manage quizzes, categories, users, and other resources via a user-friendly web interface.
-   **Location:** Admin panel code is in `app/Filament/` (resources, pages, widgets, etc.).
-   **Access:**
    -   By default, access the admin panel at: `/admin`
    -   You must be logged in as an admin user
    -   To create an admin user, run:
        ```bash
        php artisan make:filament-user
        ```
        When prompted, use an email ending with `@quizwiz.com` (e.g., `admin@quizwiz.com`). Only users with such emails can access the admin panel.
-   **Customization:**
    -   Add or modify resources in `app/Filament/Resources/`
    -   See [Filament documentation](https://filamentphp.com/docs/3.x/admin/resources) for advanced customization

## Tech Stack

-   PHP 8.x
-   Laravel 10.x
-   MySQL
-   Composer
-   PHPUnit

## Project Structure

```
quizwiz-back-end/
├── app/
│   ├── Models/
│   ├── Http/
│   ├── Notifications/
│   └── ..
├── bootstrap/
├── config/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── public/
├── resources/
│   ├── views/
│   └── ..
├── routes/
├── storage/
├── tests/
├── .env.example
├── composer.json
├── package.json
└── README.md
```

Project structure is standard for Laravel project

## Database Schema

The MySQL database schema for QuizWiz is visualized and maintained using [DrawSQL](https://drawsql.app/).

You can view the schema diagram here:

[Database Design Diagram](readme/assets/quizwiz-database-diagram.png)

## Getting Started

### Prerequisites

-   PHP >= 8.1
-   Composer
-   MySQL

### Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/RedberryInternship/quizwiz-back-akaki-goginava.git
    cd quizwiz-back-akaki-goginava
    ```
2. Install PHP dependencies:
    ```bash
    composer install
    ```
3. Install JS dependencies:
    ```bash
    npm install
    ```
4. Build frontend assets:
    ```bash
    npm run build
    ```
5. Copy the example environment file and configure it:
    ```bash
    cp .env.example .env
    ```
6. Generate application key:
    ```bash
    php artisan key:generate
    ```
7. Run migrations and seeders:
    ```bash
    php artisan migrate --seed
    ```
8. Seed socials table
    ```bash
    php artisan db:seed --class=SocialSeeder
    ```
9. Link storage:
    ```bash
    php artisan storage:link
    ```
10. Optimize the application:
    ```bash
    php artisan optimize
    ```
11. (Optional) Run the development server:
    ```bash
    php artisan serve
    ```

## Environment Configuration

-   `.env` — main environment file for local/dev
-   `.env.testing` — used for automated tests

**Important:**

-   Set correct `DB_CONNECTION`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` in your `.env`.
-   Configure `MAIL_*` variables for email notifications.
-   Set `APP_URL` and configure `config/cors.php` for allowed origins.

## Running Tests

Run all tests with:

```bash
php artisan test
```

## API Overview

-   All quiz endpoints are under `/api/quizzes`
-   Supports filtering by:
    -   `categories.id`
    -   `difficulty_id`
    -   `title`
    -   `my_quizzes` (completed by user)
-   Supports sorting by:
    -   `created_at`, `total_users`, `title`
-   Example:
    ```
    GET /api/quizzes?filter[title]=Alpha&filter[categories.id]=1,2&sort=-created_at
    ```

## Email Templates

-   Located in `resources/views/email/`
-   Use inline CSS and web-safe fonts for best compatibility
