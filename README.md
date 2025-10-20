# 🧭 Rorex — HR Management System

<div align="center">

A production-ready Human Resource Management System (HRMS) designed for enterprise-scale operations, featuring real-time attendance synchronization, advanced reporting, and seamless API integration.

</div>

---

## 📋 Table of Contents

- [Overview](#overview)
- [Key Features](#key-features)
- [Quick Start](#quick-start)
  - [Docker Setup](#docker-setup)
  - [Manual Installation](#manual-installation)
- [Tech Stack](#tech-stack)
- [API Integration](#api-integration)
- [Performance](#performance)
- [Deployment](#deployment)
- [Contributing](#contributing)
- [Author](#author)

---

## 📖 Overview

Rorex is a comprehensive Human Resource Management System built with Laravel and Bootstrap, designed to streamline HR operations for organizations of any size. By automating critical HR workflows such as employee data management, attendance tracking, and performance reporting, Rorex reduces manual administrative work by **90%** while improving operational efficiency by **30%**.

**Developed and maintained by Rorex SRL**, this project emphasizes scalability, performance optimization, and real-time data integration through secure APIs. Whether managing 100 or 100,000+ employees, Rorex provides a robust foundation for modern HR operations.

---

## ✨ Key Features

| Feature | Description |
|---------|-------------|
| 👥 **Employee Management** | Handle 10,000+ employee records with advanced filtering, search, and bulk operations |
| ⏱ **Real-Time Attendance** | Automatic synchronization with attendance device APIs for accurate tracking |
| 📦 **Bulk Operations** | CSV import/export functionality for seamless data migration and reporting |
| 📊 **Advanced Reporting** | Visual dashboards with HR metrics, attendance analytics, and performance insights |
| ⚡ **Performance Optimized** | Query caching and optimization reduce page load times by 30% (3s → <1s) |
| 🔐 **Secure & Scalable** | Built with security best practices and designed to scale horizontally |
| 🔁 **Automated Deployments** | CI/CD pipeline with GitHub Actions for zero-downtime updates |
| 🐳 **Containerized** | Docker support for consistent development and production environments |
| 🌐 **API-First Design** | RESTful APIs for third-party integrations and mobile app support |

---

## 🚀 Quick Start

### Docker Setup (Recommended)

The fastest way to get started with Rorex is using Docker. Ensure you have Docker and Docker Compose installed.

```bash
# 1️⃣ Clone the repository
git clone https://github.com/mmd-af/rorex.git
cd rorex

# 2️⃣ Start all services (Nginx, PHP-FPM, MySQL)
docker-compose up -d

# 3️⃣ Copy and configure environment variables
cp .env.example .env

# 4️⃣ Install PHP and Node dependencies inside the container
docker exec -it rorex-app composer install
docker exec -it rorex-app npm install && npm run build

# 5️⃣ Generate Laravel application key
docker exec -it rorex-app php artisan key:generate

# 6️⃣ Run database migrations and seed initial data
docker exec -it rorex-app php artisan migrate --seed
```

The application will be available at `http://localhost`

### Manual Installation

For local development without Docker:

```bash
# 1️⃣ Clone the repository
git clone https://github.com/mmd-af/rorex.git
cd rorex

# 2️⃣ Copy environment configuration
cp .env.example .env

# 3️⃣ Install dependencies
composer install
npm install && npm run build

# 4️⃣ Generate application key
php artisan key:generate

# 5️⃣ Configure your database in .env, then run migrations
php artisan migrate --seed

# 6️⃣ Start the development server
php artisan serve
```

Access the application at `http://localhost:8000`

---

## 🔧 Tech Stack

| Layer | Technologies |
|-------|--------------|
| **Backend** | PHP 8.x, Laravel 10+, Eloquent ORM |
| **Frontend** | Bootstrap 5, Blade Templating, Alpine.js |
| **Database** | MySQL 8.0+, Redis (Caching) |
| **DevOps** | Docker, Docker Compose, Nginx |
| **CI/CD** | GitHub Actions (Automated Testing & Deployment) |
| **Cloud** | AWS (EC2, S3, RDS) |
| **Testing** | PHPUnit, Feature & Unit Tests |

---

## 📡 API Integration

Rorex integrates seamlessly with third-party attendance device systems through scheduled jobs and webhooks. This ensures real-time synchronization of employee attendance data without manual intervention.

**Key Integration Features:**
- Automated webhook receivers for attendance device updates
- Scheduled jobs for periodic data synchronization
- Retry logic and error handling for reliable data flow
- API rate limiting and security controls
- Support for multiple device manufacturers

---

## ⚡ Performance & Results

Rorex delivers measurable improvements in HR operations:

| Metric | Improvement |
|--------|------------|
| 🤖 **Automation** | 90% reduction in manual HR workload |
| ⏱ **Page Load Time** | 30% faster (3s → <1s) |
| 💾 **Data Handling** | Support for 10,000+ employee records |
| ⏰ **Time Saved** | 2+ hours per day of administrative work |
| 📈 **Operational Efficiency** | 30% overall improvement |

---

## 🚀 Deployment

### GitHub Actions CI/CD Pipeline

Rorex includes a pre-configured CI/CD pipeline that automates:
- Code quality checks and testing
- Automated deployments to AWS EC2
- Zero-downtime deployments
- Database migrations
- Cache invalidation

### Production Deployment

```bash
# Push to main branch to trigger automated deployment
git push origin main
```

---

## 📦 Environment Variables

Create a `.env` file based on `.env.example`:

```env
APP_NAME=Rorex
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=rorex
DB_USERNAME=root
DB_PASSWORD=secret

CACHE_DRIVER=redis
REDIS_HOST=cache
REDIS_PORT=6379

ATTENDANCE_API_KEY=your_api_key
ATTENDANCE_API_URL=https://api.attendance.device
```

---



## 👨‍💻 Author

**Mohammad Afsharardekani**  
Software Engineer — Bucharest, Romania

Backend engineer specializing in Laravel, PHP 8, and AWS with focus on performance optimization, scalability, and automation.

📧 **Email:** [mohammad.afshar.dev@gmail.com](mailto:mohammad.afshar.dev@gmail.com)  
🔗 **GitHub:** [github.com/mmd-af](https://github.com/mmd-af)

---

<div align="center">

Made with ❤️ by Mohammad Afsharardekani

</div>
