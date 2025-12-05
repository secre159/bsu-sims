# Deploying BSU-SIMS to Render

This guide will walk you through deploying the BSU-Bokod Student Information Management System to Render.

## Prerequisites

1. A [Render account](https://render.com) (free tier available)
2. Your GitHub repository pushed and up-to-date
3. Basic understanding of environment variables

## Step-by-Step Deployment Guide

### Step 1: Prepare Your Repository

Ensure all deployment files are committed and pushed to GitHub:

```bash
git add .
git commit -m "Add Render deployment configuration"
git push origin main
```

### Step 2: Create a Render Account

1. Go to [https://render.com](https://render.com)
2. Sign up using your GitHub account
3. Authorize Render to access your repositories

### Step 3: Create a PostgreSQL Database

1. From Render Dashboard, click **"New +"** → **"PostgreSQL"**
2. Configure the database:
   - **Name**: `bsu-sims-db`
   - **Database**: `bsu_sims`
   - **User**: `bsu_sims_user`
   - **Region**: Choose closest to your users
   - **Plan**: Free (for testing) or Starter (for production)
3. Click **"Create Database"**
4. Wait for the database to be provisioned (takes ~2 minutes)
5. **Save the database connection details** (you'll need them)

### Step 4: Create a Web Service

1. From Render Dashboard, click **"New +"** → **"Web Service"**
2. Connect your GitHub repository: `secre159/bsu-sims`
3. Configure the service:

#### Basic Settings:
- **Name**: `bsu-sims`
- **Region**: Same as your database
- **Branch**: `main`
- **Runtime**: `Docker` or `Native Environment`

#### Build Settings:
- **Build Command**: `bash render-build.sh`
- **Start Command**: `bash render-start.sh`

#### Plan:
- Choose **Starter** or higher (Free tier has limitations)

### Step 5: Configure Environment Variables

Click **"Environment"** and add these variables:

#### Required Variables:

```
APP_NAME=BSU-SIMS
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_URL=https://bsu-sims.onrender.com

LOG_CHANNEL=stderr
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=YOUR_DB_HOST_FROM_RENDER
DB_PORT=5432
DB_DATABASE=bsu_sims
DB_USERNAME=bsu_sims_user
DB_PASSWORD=YOUR_DB_PASSWORD_FROM_RENDER

SESSION_DRIVER=database
SESSION_LIFETIME=120

CACHE_DRIVER=database
QUEUE_CONNECTION=database

FILESYSTEM_DISK=public
```

#### How to Get Your APP_KEY:
Run this locally and copy the output:
```bash
php artisan key:generate --show
```

#### How to Get Database Credentials:
1. Go to your PostgreSQL database in Render
2. Click on **"Info"**
3. Copy:
   - **Internal Database URL** (for DB_HOST)
   - **Database** (for DB_DATABASE)
   - **Username** (for DB_USERNAME)
   - **Password** (for DB_PASSWORD)

### Step 6: Deploy

1. Click **"Create Web Service"**
2. Render will automatically:
   - Clone your repository
   - Run the build script
   - Install dependencies
   - Run migrations
   - Start your application

3. Monitor the deployment in the **"Logs"** tab
4. Deployment typically takes 5-10 minutes

### Step 7: Post-Deployment Setup

Once deployed, you need to seed your database with initial data:

1. Go to Render Dashboard → Your Web Service
2. Click **"Shell"** tab
3. Run these commands:

```bash
# Seed the database with initial data
php artisan db:seed --class=DepartmentSeeder
php artisan db:seed --class=ProgramSeeder
php artisan db:seed --class=SubjectSeeder
php artisan db:seed --class=AcademicYearSeeder

# Create an admin user
php artisan tinker
```

Then in tinker:
```php
\App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@bsu.edu.ph',
    'password' => bcrypt('your-secure-password'),
    'role' => 'admin'
]);
exit
```

### Step 8: Configure Custom Domain (Optional)

1. In your web service, click **"Settings"**
2. Scroll to **"Custom Domain"**
3. Add your domain (e.g., `sims.bsu.edu.ph`)
4. Follow Render's instructions to configure DNS

## Troubleshooting

### Build Fails

**Check logs for specific errors:**
- Missing extensions: Add to `composer.json` platform requirements
- Node version: Specify in `package.json` engines
- PHP version: Ensure Render uses PHP 8.2+

### Database Connection Issues

1. Verify environment variables are correct
2. Check database is in same region as web service
3. Ensure `DB_CONNECTION=pgsql` (not mysql)

### Application Crashes After Deploy

1. Check logs: `https://dashboard.render.com/web/YOUR_SERVICE/logs`
2. Common issues:
   - Missing `APP_KEY`
   - Incorrect database credentials
   - Missing migrations

### Storage/File Upload Issues

Render's filesystem is ephemeral. For production, you should:
1. Use a cloud storage service (AWS S3, Cloudinary, etc.)
2. Configure Laravel to use cloud storage:

```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your-key
AWS_SECRET_ACCESS_KEY=your-secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket
```

## Cost Estimates

### Free Tier Limitations:
- Service spins down after 15 minutes of inactivity
- 750 hours/month of runtime
- PostgreSQL: 1GB storage, 97 hours/month

### Recommended for Production:
- **Web Service**: Starter ($7/month) or Standard ($25/month)
- **PostgreSQL**: Starter ($7/month) - 256MB RAM, 1GB storage
- **Total**: ~$14/month minimum

## Maintenance

### Update Your Application:

1. Push changes to GitHub
2. Render will automatically deploy (if auto-deploy is enabled)
3. Or manually deploy from Dashboard → **"Manual Deploy"** → **"Deploy latest commit"**

### Run Commands:

Use the Shell tab in Render Dashboard to run artisan commands:
```bash
php artisan cache:clear
php artisan migrate
php artisan db:seed
```

### Backup Database:

1. Go to your PostgreSQL database
2. Click **"Backups"**
3. Configure automatic daily backups (recommended)

## Security Checklist

- ✅ `APP_DEBUG=false` in production
- ✅ Strong `APP_KEY` generated
- ✅ Secure database password
- ✅ HTTPS enabled (automatic on Render)
- ✅ Environment variables not in code
- ✅ CSRF protection enabled
- ✅ Rate limiting configured

## Support

- **Render Documentation**: [https://render.com/docs](https://render.com/docs)
- **Laravel Deployment**: [https://laravel.com/docs/deployment](https://laravel.com/docs/deployment)
- **BSU-SIMS Issues**: [https://github.com/secre159/bsu-sims/issues](https://github.com/secre159/bsu-sims/issues)

---

**Last Updated**: December 2025
