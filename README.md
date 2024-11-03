# Learning Management System (LMS)

A modern Learning Management System built with Laravel, featuring course management, business subscriptions, and student progress tracking.

## Features

### Course Management
- Create and manage courses with modules and lessons
- Rich media content support
- Category-based organization
- Progress tracking
- Course status management (draft/published)
- Multiple difficulty levels

### Business Features
- Course purchasing for organizations
- Seat management
- Employee enrollment tracking
- Access expiration management

### Student Features
- Course enrollment
- Progress tracking
- Module completion
- Last accessed position memory

## Tech Stack

- Laravel 10.x
- Tailwind CSS
- Livewire
- Alpine.js
- MySQL/PostgreSQL

## Prerequisites

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL/PostgreSQL
- Laravel CLI

## Installation

1. Clone the repository
bash
git clone <repository-url>
cd lms
bash
composer install
npm install
bash
cp .env.example .env
php artisan key:generate
env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lms
DB_USERNAME=root
DB_PASSWORD=
bash
php artisan migrate --seed
bash
php artisan storage:link
bash
npm run build
bash
php artisan serve
php:app/Http/Controllers/Admin/CourseController.php
startLine: 15
endLine: 75
php:resources/views/home.blade.php
startLine: 1
endLine: 20
php:resources/views/courses/show.blade.php
startLine: 1
endLine: 22
bash
npm run dev
bash
php artisan test
├── app/
│ ├── Http/
│ │ ├── Controllers/
│ │ └── Requests/
│ └── Models/
├── database/
│ ├── migrations/
│ └── seeders/
├── resources/
│ └── views/
└── routes/
Ask
Copy
Apply
