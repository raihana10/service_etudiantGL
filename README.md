# Service Etudiant GL

A comprehensive Student Service Portal built with Laravel and Vue 3. This application streamlines student requests, complaints, and administrative workflows.

## 🚀 Technical Stack

### Backend
- **Framework**: Laravel 12
- **Language**: PHP 8.2+
- **Database**: MySQL / SQLite
- **Features**: API Discovery, Tinker, PDF Generation (`laravel-dompdf`), Arabic Text Support (`ar-php`).

### Frontend
- **Framework**: Vue 3 (Composition API)
- **Build Tool**: Vite
- **State Management**: Pinia
- **Styling**: Tailwind CSS
- **Routing**: Vue Router
- **HTTP Client**: Axios
- **Charts**: Chart.js

## 📁 Project Structure

The project follows a monorepo-style structure:

- `/backend`: Laravel application handling APIs, business logic, and database management.
- `/frontend`: Vue 3 SPA for the user interface.

## 🛠️ Installation & Setup

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & npm

### Backend Setup
1. Navigate to the backend directory:
   ```bash
   cd backend
   ```
2. Install dependencies:
   ```bash
   composer install
   ```
3. Setup environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Run migrations:
   ```bash
   php artisan migrate
   ```
5. Start the server:
   ```bash
   php artisan serve
   ```

### Frontend Setup
1. Navigate to the frontend directory:
   ```bash
   cd frontend
   ```
2. Install dependencies:
   ```bash
   npm install
   ```
3. Start the development server:
   ```bash
   npm run dev
   ```

## ✨ Key Features

- **Student Dashboard**: Manage personal requests and academic documents.
- **Admin Dashboard**: Comprehensive management of student demands, history, and statistics.
- **Complaint System**: Integrated reclamation management for student support.
- **Document Generation**: Automated PDF generation for administrative documents.
- **Real-time Analytics**: Visualized data using Chart.js on the admin dashboard.

## 📄 License
This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
