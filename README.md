# BSU-Bokod Student Information Management System (SIMS)

A comprehensive Laravel-based Student Information Management System for Benguet State University - Bokod Campus.

## Quick Start

```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Create database 'bsu_sims' in phpMyAdmin or MySQL, then:
php artisan migrate --seed
php artisan storage:link

# Run application (use 2 terminals)
php artisan serve    # Terminal 1
npm run dev          # Terminal 2
```

**Access:** http://localhost:8000 (admin@bsu-bokod.edu.ph / password)

---

## Features

- **Student Management** - Complete student records with demographic and academic information
- **Grade Management** - Chairperson grade entry with Excel import and admin approval workflow
- **Academic Year Management** - Semester and year level tracking
- **Program & Subject Management** - Curriculum and course management
- **Enrollment Tracking** - Student enrollment history and status monitoring
- **GPA/GWA Calculation** - Automated grade point average computation
- **Semester Transition** - Automated student promotion and retention system
- **Activity Logging** - Comprehensive audit trail for all system changes
- **Role-Based Access** - Admin, Chairperson, and Student portals
- **Archive System** - Historical data preservation
- **Reports & Analytics** - Various academic reports and statistics

## Technology Stack

- **Framework:** Laravel 12.x
- **PHP:** 8.3+
- **Database:** MySQL 5.7+ / MariaDB 10.3+
- **Frontend:** Blade Templates, Tailwind CSS, Alpine.js
- **Asset Bundling:** Vite
- **Authentication:** Laravel Breeze (Admin/Staff), Custom Student Auth

## System Requirements

- PHP >= 8.3
- Composer
- Node.js >= 18.x and npm
- MySQL >= 5.7 or MariaDB >= 10.3
- Apache/Nginx web server with mod_rewrite enabled

## Prerequisites - What You Need First

Before installing, make sure you have these tools installed on your computer:

### For Windows Users:
1. **XAMPP** (includes PHP, MySQL, Apache)
   - Download from: https://www.apachefriends.org/
   - Install and start Apache and MySQL from XAMPP Control Panel

2. **Composer** (PHP package manager)
   - Download from: https://getcomposer.org/download/
   - Run the installer and follow the steps
   - Open Command Prompt and type `composer --version` to verify

3. **Node.js** (JavaScript runtime)
   - Download from: https://nodejs.org/ (get the LTS version)
   - Run the installer
   - Open Command Prompt and type `node --version` to verify

4. **Git** (version control)
   - Download from: https://git-scm.com/download/win
   - Run the installer with default options

### For Linux Users:
1. **PHP and MySQL**
   ```bash
   sudo apt update
   sudo apt install php8.3 php8.3-cli php8.3-mysql php8.3-xml php8.3-mbstring php8.3-curl
   sudo apt install mysql-server
   ```

2. **Composer**
   ```bash
   php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
   php composer-setup.php
   sudo mv composer.phar /usr/local/bin/composer
   ```

3. **Node.js**
   ```bash
   curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
   sudo apt install -y nodejs
   ```

4. **Git**
   ```bash
   sudo apt install git
   ```

---

## Installation Guide

### Step 1: Get the Project Files

**Option A: If you have the project folder already**
- Copy the `bsu-sims` folder to:
  - **Windows:** `C:\xampp\htdocs\bsu-sims`
  - **Linux:** `/opt/lampp/htdocs/bsu-sims` or `/var/www/html/bsu-sims`

**Option B: If using Git (for developers)**
```bash
# Navigate to your web server directory first
# Windows (XAMPP): cd C:\xampp\htdocs
# Linux: cd /var/www/html

git clone <repository-url> bsu-sims
cd bsu-sims
```

### Step 2: Install PHP Dependencies

Composer installs PHP libraries that Laravel needs to run.

**Open your terminal/command prompt in the project folder** and run:

```bash
composer install
```

💡 **What this does:** Downloads all PHP packages needed (like Laravel framework itself)

⏱️ **Takes:** 2-5 minutes depending on your internet speed

### Step 3: Install JavaScript Dependencies

NPM installs JavaScript libraries for the frontend (design and styling).

```bash
npm install
```

💡 **What this does:** Downloads Tailwind CSS, Vite, and other frontend tools

⏱️ **Takes:** 3-10 minutes

### Step 4: Configure Environment Settings

The `.env` file contains important settings like database connection.

**Create the configuration file:**

**Windows (Command Prompt):**
```cmd
copy .env.example .env
```

**Mac/Linux:**
```bash
cp .env.example .env
```

💡 **What this does:** Creates your personal configuration file from the example template

**Edit the `.env` file:**
- Open `.env` with any text editor (Notepad, VS Code, etc.)
- Find and update these lines:

```env
APP_NAME="BSU-Bokod SIMS"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bsu_sims
DB_USERNAME=root
DB_PASSWORD=          # Leave empty if using XAMPP, or put your MySQL password
```

💡 **Important:** 
- For XAMPP users, `DB_PASSWORD` is usually empty (no password)
- For Linux users with MySQL installed separately, use your MySQL root password

### Step 5: Generate Security Key

This creates a unique encryption key for your application.

```bash
php artisan key:generate
```

💡 **What this does:** Creates a secure random key and saves it to your `.env` file

### Step 6: Create the Database

**Method 1: Using phpMyAdmin (Easiest for beginners)**
1. Open your browser and go to `http://localhost/phpmyadmin`
2. Click "New" in the left sidebar
3. Database name: `bsu_sims`
4. Collation: `utf8mb4_unicode_ci`
5. Click "Create"

**Method 2: Using Command Line**
```bash
# Windows (XAMPP): Open "MySQL Console" from XAMPP Control Panel
# Linux: Open terminal

mysql -u root -p
# Enter your password (or press Enter if no password)

CREATE DATABASE bsu_sims CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;
```

💡 **What this does:** Creates an empty database where all your data will be stored

### Step 7: Set Up Database Tables and Sample Data

```bash
php artisan migrate:fresh --seed
```

💡 **What this does:** 
- Creates all necessary tables (students, subjects, grades, etc.)
- Adds sample data so you can test the system immediately
- Creates default admin and chairperson accounts

⏱️ **Takes:** 30 seconds to 2 minutes

✅ **You'll get sample:**
- 1 Admin account
- 1 Chairperson account  
- Several test students with grades
- Sample programs and subjects

### Step 8: Link Storage for File Uploads

```bash
php artisan storage:link
```

💡 **What this does:** Allows the application to serve uploaded files (like student photos)

### Step 9: Build the Frontend Design

**For Development (with auto-refresh):**
```bash
npm run dev
```

**For Production (optimized files):**
```bash
npm run build
```

💡 **What this does:** Compiles CSS and JavaScript files into optimized versions

⏱️ **Takes:** 1-3 minutes for first build

**Important:** Keep this terminal window open if you're developing! Press `Ctrl+C` to stop.

### Step 10: Start the Application

Open a **new terminal window** in the project folder and run:

```bash
php artisan serve
```

💡 **What this does:** Starts a local web server

✅ **Success!** Open your browser and go to:
- **Main site:** `http://localhost:8000`
- **Student portal:** `http://localhost:8000/student`

**Important:** Keep this terminal window open while using the application!

## Default Credentials

### Admin Portal
- **URL:** `http://localhost:8000`
- **Email:** `admin@bsu-bokod.edu.ph`
- **Password:** `password`

### Chairperson Portal
- **URL:** `http://localhost:8000`
- **Email:** `bugtong@gmail.com`
- **Password:** `password`

### Student Portal
- **URL:** `http://localhost:8000/student`
- Use any seeded student's `student_id` as username
- **Password:** Student's birthdate in `YYYY-MM-DD` format (check database)

## 🔄 Setting Up on Another Computer

### Option 1: Fresh Installation (Recommended for Beginners)

Simply follow the [Installation Guide](#installation-guide) on the new computer. This gives you a clean installation with sample data.

### Option 2: Transfer Your Existing Data

Use this if you want to move your project **with all your actual data** (students, grades, etc.) to another computer.

#### Step 1: Backup on Original Computer

**A. Export the Database**

**Method 1: Using phpMyAdmin (Easiest)**
1. Go to `http://localhost/phpmyadmin`
2. Click on `bsu_sims` database in the left sidebar
3. Click the "Export" tab at the top
4. Click "Go" (keep default settings)
5. Save the `.sql` file (e.g., `bsu_sims_backup.sql`)

**Method 2: Using Command Line**
```bash
# Windows (XAMPP): Open Command Prompt in xampp/mysql/bin folder
# Linux: Open terminal

mysqldump -u root -p bsu_sims > bsu_sims_backup.sql
# Enter your MySQL password when prompted
```

💡 **What this does:** Creates a backup file containing all your data

**B. Copy Project Files**

Copy these items to a USB drive or cloud storage:
1. Your entire `bsu-sims` folder
2. The `bsu_sims_backup.sql` file you just created

⚠️ **Note:** You can **skip** copying these folders (they'll be regenerated):
- `node_modules` (very large, ~300MB)
- `vendor` (large, ~100MB)
- `storage/logs/*`

#### Step 2: Restore on New Computer

**A. Install Prerequisites**
Make sure the new computer has PHP, Composer, Node.js, and MySQL installed (see [Prerequisites](#prerequisites---what-you-need-first))

**B. Copy Files**
- Copy the `bsu-sims` folder to:
  - **Windows:** `C:\xampp\htdocs\bsu-sims`
  - **Linux:** `/var/www/html/bsu-sims`

**C. Install Dependencies**
Open terminal in the `bsu-sims` folder:
```bash
# This will take 5-10 minutes
composer install
npm install
```

**D. Import the Database**

**Method 1: Using phpMyAdmin**
1. Go to `http://localhost/phpmyadmin`
2. Click "New" and create database named `bsu_sims`
3. Click on the `bsu_sims` database
4. Click "Import" tab
5. Click "Choose File" and select your `bsu_sims_backup.sql`
6. Click "Go" at the bottom
7. Wait for "Import has been successfully finished"

**Method 2: Using Command Line**
```bash
# Create the database first
mysql -u root -p -e "CREATE DATABASE bsu_sims CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import the backup
mysql -u root -p bsu_sims < bsu_sims_backup.sql
```

**E. Update Configuration**
1. Open the `.env` file in the `bsu-sims` folder
2. Update database password if needed:
   ```env
   DB_PASSWORD=     # Your MySQL password on the new computer
   ```
3. Save the file

**F. Final Setup Commands**
```bash
# Link storage for file uploads
php artisan storage:link

# Clear any old cached data
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Build the frontend
npm run build
```

**G. Start the Application**
```bash
php artisan serve
```

✅ **Done!** Go to `http://localhost:8000` and log in with your account!

## Development Workflow

### Running the Full Dev Stack

Run all services at once (PHP server, queue listener, log viewer, Vite):

```bash
composer dev
```

### Running Services Individually

```bash
# Backend server
php artisan serve

# Frontend dev server (hot reload)
npm run dev

# Queue worker (for background jobs)
php artisan queue:work

# Log viewer
tail -f storage/logs/laravel.log
```

### Testing

Run the test suite:

```bash
php artisan test
```

Or via Composer:

```bash
composer test
```

### Code Style

Format code with Laravel Pint:

```bash
./vendor/bin/pint
```

## Common Commands

### Database

```bash
# Reset database with fresh data
php artisan migrate:fresh --seed

# Run only specific seeder
php artisan db:seed --class=SampleActivitiesSeeder

# Create new migration
php artisan make:migration create_table_name
```

### Cache Management

```bash
# Clear all caches
php artisan optimize:clear

# Clear specific caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### Maintenance

```bash
# Enter maintenance mode
php artisan down

# Exit maintenance mode
php artisan up
```

## Project Structure

```
bsu-sims/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin-only controllers
│   │   │   ├── Chairperson/    # Chairperson controllers
│   │   │   └── Student/        # Student portal controllers
│   │   └── Middleware/
│   ├── Models/                  # Eloquent models
│   └── Services/                # Business logic services
├── database/
│   ├── migrations/              # Database schema
│   └── seeders/                 # Test data seeders
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── admin/               # Admin views
│       ├── chairperson/         # Chairperson views
│       └── student/             # Student views
├── routes/
│   └── web.php                  # Route definitions
└── public/                      # Public assets
```

## 🔧 Troubleshooting Common Issues

### Issue 1: "Access Denied" or Database Connection Error

**Problem:** Can't connect to the database

**Solutions:**
1. **Check if MySQL is running**
   - **Windows (XAMPP):** Open XAMPP Control Panel, make sure MySQL is started (green)
   - **Linux:** Run `sudo systemctl status mysql`

2. **Verify your `.env` file**
   - Open `.env` file
   - Make sure `DB_PASSWORD` matches your MySQL password
   - For XAMPP, it's usually empty (no password)

3. **Check if database exists**
   - Go to `http://localhost/phpmyadmin`
   - Look for `bsu_sims` in the left sidebar
   - If it doesn't exist, create it (see Step 6 in Installation Guide)

### Issue 2: "Command not found" or "php/composer/npm is not recognized"

**Problem:** System can't find PHP, Composer, or Node.js

**Solutions:**
1. **Restart your computer** (this refreshes environment variables)
2. **Verify installation:**
   ```bash
   php --version
   composer --version
   node --version
   npm --version
   ```
3. **If still not working:**
   - **Windows:** Reinstall the tool and make sure "Add to PATH" is checked
   - **Linux:** Try installing again using the commands in Prerequisites section

### Issue 3: Blank Page or "500 Server Error"

**Problem:** Website shows a white page or error

**Solutions:**
1. **Check if `.env` file exists**
   - Should be in the root of `bsu-sims` folder
   - If missing, copy from `.env.example`

2. **Clear all caches:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   php artisan route:clear
   ```

3. **Check file permissions (Linux only):**
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

### Issue 4: CSS Not Loading or Looks Broken

**Problem:** Website has no styling or looks plain

**Solutions:**
1. **Make sure you built the assets:**
   ```bash
   npm run build
   ```

2. **If in development mode:**
   - Make sure `npm run dev` is running in a separate terminal
   - You should see "VITE ready" message

3. **Clear browser cache:**
   - Press `Ctrl + Shift + Delete` (Windows/Linux) or `Cmd + Shift + Delete` (Mac)
   - Select "Cached images and files"
   - Click "Clear data"

### Issue 5: "npm install" Fails or Takes Forever

**Problem:** npm command stops or is extremely slow

**Solutions:**
1. **Delete old files and try again:**
   ```bash
   # Windows
   rmdir /s node_modules
   del package-lock.json
   
   # Mac/Linux
   rm -rf node_modules package-lock.json
   
   # Then reinstall
   npm install
   ```

2. **Check your internet connection**

3. **Try using npm cache clean:**
   ```bash
   npm cache clean --force
   npm install
   ```

### Issue 6: "Port 8000 is already in use"

**Problem:** Another application is using port 8000

**Solution:** Use a different port
```bash
php artisan serve --port=8080
```
Then access the site at `http://localhost:8080`

### Issue 7: "SQLSTATE[HY000] [1049] Unknown database"

**Problem:** Database `bsu_sims` doesn't exist

**Solution:** Create the database
- Go to `http://localhost/phpmyadmin`
- Click "New" in the left sidebar  
- Type `bsu_sims` as the database name
- Click "Create"
- Then run `php artisan migrate:fresh --seed` again

---

### 💡 Still Having Issues?

1. **Check the Laravel log file:** `storage/logs/laravel.log` (last few lines show recent errors)
2. **Google the exact error message** - Laravel has great community support!
3. **Contact your development team** with:
   - Exact error message
   - Screenshot if possible
   - What you were trying to do

## Production Deployment

### 1. Server Requirements

- Ubuntu 20.04+ / CentOS 8+ / Debian 11+
- PHP 8.3 with extensions: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- Composer 2.x
- Node.js 18.x and npm
- MySQL 8.0+ or MariaDB 10.6+
- Nginx or Apache with SSL certificate

### 2. Deployment Steps

```bash
# Clone and setup
git clone <repository-url> /var/www/bsu-sims
cd /var/www/bsu-sims

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Configure environment
cp .env.example .env
nano .env  # Set APP_ENV=production, APP_DEBUG=false

# Setup database
php artisan key:generate
php artisan migrate --force
php artisan storage:link

# Set permissions
sudo chown -R www-data:www-data /var/www/bsu-sims
sudo chmod -R 755 /var/www/bsu-sims
sudo chmod -R 775 /var/www/bsu-sims/storage
sudo chmod -R 775 /var/www/bsu-sims/bootstrap/cache

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Web Server Configuration

**Nginx Example:**

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/bsu-sims/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Support & Documentation

- **Quick Reference:** See `QUICK_REFERENCE.md` for common queries and test scenarios
- **Testing Guide:** See `TESTING_CHECKLIST.md` for manual testing procedures
- **WARP Guide:** See `WARP.md` for development guidelines

## License

This project is proprietary software developed for Benguet State University - Bokod Campus.

## Contributors

- Development Team
- BSU-Bokod IT Department

---

**Version:** 1.0.0  
**Last Updated:** December 2025
