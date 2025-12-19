# 🎬 Titipan Movies

A modern movie database web application built with Laravel 8 (Backend API) and React (Frontend). Browse, search, and explore your favorite movies with a beautiful and responsive interface.

![Laravel](https://img.shields.io/badge/Laravel-8.x-red?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-7.4-blue?style=flat-square&logo=php)
![React](https://img.shields.io/badge/React-18.x-blue?style=flat-square&logo=react)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

---

## 📋 Table of Contents

- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Prerequisites](#-prerequisites)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Running the Application](#-running-the-application)
- [Default Users](#-default-users)
- [Project Structure](#-project-structure)
- [API Documentation](#-api-documentation)
- [Troubleshooting](#-troubleshooting)
- [Author](#-author)
- [License](#-license)

---

## ✨ Features

- 🎥 **Dashboard** - Overview of movies statistics
- 🎬 **Movies Management** - Full CRUD operations for movies
- 🔄 **API Sync** - Sync movies from TMDb API
- 🔍 **Search & Filter** - Find movies easily
- 👥 **Multi-role System** - Admin, Manager, Staff, Guest roles
- 🎨 **Beautiful UI** - Modern and responsive design
- 🚀 **RESTful API** - Clean API architecture
- 📱 **Mobile Responsive** - Works on all devices

---

## 🛠 Tech Stack

### Backend
- **Laravel 8.x** - PHP Framework
- **PHP 7.4** - Programming Language
- **MySQL** - Database
- **Laravel Sanctum** - API Authentication

### Frontend
- **React 18.x** - JavaScript Library
- **React Router DOM** - Client-side routing
- **Axios** - HTTP Client
- **Tailwind CSS / Bootstrap** - Styling

---

## 📦 Prerequisites

Before you begin, ensure you have the following installed:

- **PHP >= 7.4**
- **Composer** (latest version)
- **Node.js >= 14.x** and **npm**
- **MySQL >= 5.7** or **MariaDB**
- **Git**

---

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/mragatitipan/Website-API-Laravel-and-React-Titipan-Movie.git
cd Website-API-Laravel-and-React-Titipan-Movie
```

### 2. Install Backend Dependencies

```bash
composer install
```

### 3. Install Frontend Dependencies

```bash
npm install
```

---

## ⚙️ Configuration

### 1. Environment Setup

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

### 2. Generate Application Key

```bash
php artisan key:generate
```

### 3. Database Configuration

Edit the `.env` file and configure your database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=titipanmov
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Create Database

Create a new database in MySQL:

```sql
CREATE DATABASE titipanmov;
```

Or use phpMyAdmin / MySQL Workbench to create the database.

### 5. Run Migrations & Seeders

**IMPORTANT:** Run this command to create tables and insert default users:

```bash
php artisan migrate --seed
```

This will:
- ✅ Create all necessary database tables
- ✅ Insert 4 default users with different roles
- ✅ Populate sample data (if any)

### 6. TMDb API Configuration (Optional)

If you want to fetch real movie data from The Movie Database (TMDb):

1. **Register** at [https://www.themoviedb.org/](https://www.themoviedb.org/)
2. **Login** and go to Settings → API
3. **Request an API Key** (choose "Developer" option)
4. **Copy your API Key** and add to `.env`:

```env
TMDB_API_KEY=your_api_key_here
TMDB_BASE_URL=https://api.themoviedb.org/3
```

---

## 🎯 Running the Application

You need to run **TWO terminals** simultaneously:

### Terminal 1: Backend Server (Laravel)

```bash
php artisan serve
```

✅ Backend API will run at: **http://localhost:8000**

### Terminal 2: Frontend Development Server (React)

```bash
npm run dev
```

✅ Frontend will run at: **http://localhost:3000** (or check terminal for the exact port)

---

## 🌐 Access the Application

Once both servers are running:

- **Frontend**: `http://localhost:3000`
- **Backend API**: `http://localhost:8000/api`

---

## 👥 Default Users

After running `php artisan migrate --seed`, you can login with these accounts:

| Role | Email | Password | Access Level |
|------|-------|----------|--------------|
| **Admin** | `admin@gmail.com` | `masuk123` | Full access to all features |
| **Manager** | `manager@gmail.com` | `masuk123` | Manage movies and users |
| **Staff** | `staff@gmail.com` | `masuk123` | View and edit movies |
| **Guest** | `guest@gmail.com` | `masuk123` | View only access |

### Login Steps:
1. Open `http://localhost:3000`
2. Navigate to Login page
3. Use any email and password from the table above
4. Click Login

---

## 📁 Project Structure

```
project-rest-api/
├── app/                      # Laravel application files
│   ├── Http/Controllers/     # API Controllers
│   ├── Models/               # Eloquent Models
│   └── ...
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
│       └── UserSeeder.php    # Default users seeder
├── resources/
│   └── js/                   # React application
│       ├── components/       # React components
│       │   └── Layout/       # Layout components
│       ├── pages/            # Page components
│       │   ├── Dashboard.jsx # Dashboard page
│       │   ├── Movies.jsx    # Movies CRUD page
│       │   ├── Sync.jsx      # API Sync page
│       │   └── NotFound.jsx  # 404 page
│       └── App.jsx           # Main React component
├── routes/
│   ├── api.php               # API routes
│   └── web.php               # Web routes
├── public/                   # Public assets
├── .env                      # Environment configuration
├── composer.json             # PHP dependencies
├── package.json              # Node dependencies
└── README.md                 # This file
```

---

## 🗺️ Available Routes

### Frontend Routes (React Router)

| Route | Component | Description |
|-------|-----------|-------------|
| `/` | Redirect to `/dashboard` | Home redirect |
| `/dashboard` | Dashboard | Statistics overview |
| `/movies` | Movies | Movies CRUD operations |
| `/sync` | Sync | Sync movies from TMDb API |
| `*` | NotFound | 404 error page |

### Backend API Routes

Base URL: `http://localhost:8000/api`

#### Authentication
- `POST /register` - Register new user
- `POST /login` - User login
- `POST /logout` - User logout (requires auth)

#### Movies
- `GET /movies` - Get all movies
- `GET /movies/{id}` - Get movie details
- `POST /movies` - Create new movie (Admin/Manager)
- `PUT /movies/{id}` - Update movie (Admin/Manager)
- `DELETE /movies/{id}` - Delete movie (Admin only)

#### TMDb Sync
- `POST /sync/movies` - Sync movies from TMDb API
- `GET /sync/status` - Get sync status

---

## 🎨 Features Status

| Feature | Status | Notes |
|---------|--------|-------|
| ✅ Dashboard | Working | Statistics and overview |
| ✅ Movies CRUD | Working | Create, Read, Update, Delete |
| ✅ API Sync | Working | Sync from TMDb |
| ✅ Routing | Working | React Router implemented |
| ✅ Layout | Working | Responsive layout |
| ⚠️ Authentication | Partial | UI ready, needs backend integration |
| ⚠️ Search & Filter | Partial | Basic functionality working |
| 🔄 Reviews System | In Progress | Coming soon |

---

## 🔧 Troubleshooting

### Common Issues

**1. Port already in use**
```bash
# Use different port for Laravel
php artisan serve --port=8001

# For Vite (React)
npm run dev -- --port 3001
```

**2. Permission denied on storage**
```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache

# Windows (run as Administrator)
icacls storage /grant Users:F /t
```

**3. Database connection error**
- Check if MySQL service is running
- Verify database credentials in `.env`
- Make sure database `titipanmov` exists

**4. Composer dependencies error**
```bash
composer clear-cache
composer install --no-scripts
composer dump-autoload
```

**5. Node modules error**
```bash
rm -rf node_modules package-lock.json
npm cache clean --force
npm install
```

**6. Migration error: "Table already exists"**
```bash
# Reset database (WARNING: This will delete all data)
php artisan migrate:fresh --seed
```

**7. React not loading / White screen**
```bash
# Clear cache and rebuild
npm run build
php artisan optimize:clear
```

---

## 📝 Development Commands

### Backend (Laravel)

```bash
# Clear all cache
php artisan optimize:clear

# Clear specific cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh migration with seeders
php artisan migrate:fresh --seed

# Create new migration
php artisan make:migration create_table_name

# Create new controller
php artisan make:controller ControllerName

# Create new model
php artisan make:model ModelName -m

# Run tests
php artisan test
```

### Frontend (React)

```bash
# Development server
npm run dev

# Build for production
npm run build

# Preview production build
npm run preview
```

---

## 🚀 Deployment

### Production Build

```bash
# 1. Build React app
npm run build

# 2. Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Set production environment
# Edit .env: APP_ENV=production, APP_DEBUG=false
```

### Deployment Checklist

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `npm run build`
- [ ] Set proper file permissions
- [ ] Configure web server (Apache/Nginx)
- [ ] Set up SSL certificate
- [ ] Configure database backup

---

## 🤝 Contributing

Contributions, issues, and feature requests are welcome!

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 👨‍💻 Author

**Muhammad Raga Titipan**

- GitHub: [@mragatitipan](https://github.com/mragatitipan)
- Repository: [Titipan Movies](https://github.com/mragatitipan/Website-API-Laravel-and-React-Titipan-Movie)

---

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com/) - The PHP Framework for Web Artisans
- [React](https://reactjs.org/) - A JavaScript library for building user interfaces
- [The Movie Database (TMDb)](https://www.themoviedb.org/) - Movie data API provider
- [React Router](https://reactrouter.com/) - Declarative routing for React
- [Tailwind CSS](https://tailwindcss.com/) - A utility-first CSS framework

---

## 📞 Support

If you have any questions or need help:

- 🐛 Open an issue on [GitHub Issues](https://github.com/mragatitipan/Website-API-Laravel-and-React-Titipan-Movie/issues)
- 💬 Contact via GitHub profile

---

## 🎯 Roadmap

- [x] Basic CRUD operations
- [x] User authentication system
- [x] TMDb API integration
- [x] Responsive UI design
- [ ] Advanced search and filters
- [ ] User reviews and ratings
- [ ] Watchlist feature
- [ ] Social sharing
- [ ] Email notifications
- [ ] Admin dashboard analytics

---

<p align="center">Made with ❤️ by Muhammad Raga Titipan</p>
<p align="center">⭐ Star this repository if you find it helpful!</p>
