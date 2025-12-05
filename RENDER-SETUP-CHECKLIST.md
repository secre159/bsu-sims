# 🚀 Render Deployment Quick Checklist

## ✅ Database Created
Your PostgreSQL database is ready:
- **Name**: bsu-sims-db
- **Region**: Singapore
- **Status**: ✅ Active

## 📋 Next Steps

### 1. Create Web Service on Render

1. Go to [Render Dashboard](https://dashboard.render.com)
2. Click **"New +"** → **"Web Service"**
3. Connect your GitHub repository: `secre159/bsu-sims`

### 2. Configure Web Service

#### Basic Settings:
```
Name: bsu-sims
Region: Singapore (same as database)
Branch: main
Root Directory: (leave blank)
Runtime: (Auto-detect)
```

#### Build & Deploy:
```
Build Command: bash render-build.sh
Start Command: bash render-start.sh
```

#### Instance Type:
```
Plan: Starter ($7/month minimum recommended)
```

### 3. Add Environment Variables

Click on **"Environment"** tab and add these variables one by one:

**Copy from `render-env-variables.txt` file** or use these:

#### Required Variables (Copy-Paste Ready):

| Key | Value |
|-----|-------|
| `APP_NAME` | `BSU-SIMS` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | `base64:ZSY/2S6yUReY6WY0w5E+WLNWHFAZtpJP30gwS4KEVrA=` |
| `APP_URL` | `https://bsu-sims.onrender.com` |
| `LOG_CHANNEL` | `stderr` |
| `LOG_LEVEL` | `error` |
| `DB_CONNECTION` | `pgsql` |
| `DB_HOST` | `dpg-d4pcra49c44c73bc8nug-a` |
| `DB_PORT` | `5432` |
| `DB_DATABASE` | `bsu_sims` |
| `DB_USERNAME` | `bsu_sims_user` |
| `DB_PASSWORD` | `oZac4YUPkgpT2dMAJGCR8mtw5exd3YnT` |
| `SESSION_DRIVER` | `database` |
| `SESSION_LIFETIME` | `120` |
| `CACHE_DRIVER` | `database` |
| `QUEUE_CONNECTION` | `database` |
| `FILESYSTEM_DISK` | `public` |

### 4. Deploy

1. Click **"Create Web Service"**
2. Wait 5-10 minutes for the build to complete
3. Monitor progress in the **"Logs"** tab

### 5. Post-Deployment Setup

Once deployed successfully, open the **Shell** tab and run:

```bash
# Seed the database with initial data
php artisan db:seed --class=DepartmentSeeder
php artisan db:seed --class=ProgramSeeder
php artisan db:seed --class=SubjectSeeder
php artisan db:seed --class=AcademicYearSeeder

# Create admin user
php artisan tinker
```

Then in tinker:
```php
\App\Models\User::create([
    'name' => 'BSU Admin',
    'email' => 'admin@bsu.edu.ph',
    'password' => bcrypt('ChangeThisPassword123!'),
    'role' => 'admin'
]);
exit
```

### 6. Access Your Application

Your app will be available at:
```
https://bsu-sims.onrender.com
```

**Default Login:**
- Email: `admin@bsu.edu.ph`
- Password: `ChangeThisPassword123!` (change this immediately!)

---

## 🔍 Troubleshooting

### Build Fails?
- Check the **Logs** tab for specific errors
- Common issue: Missing PHP extensions
  - Solution: The build script handles most dependencies

### Can't Connect to Database?
- Verify all environment variables are correct
- Check database and web service are in same region (Singapore)
- Ensure `DB_CONNECTION=pgsql` (not mysql)

### Application Shows Error 500?
- Check logs: Click on your service → **Logs** tab
- Common causes:
  - Missing `APP_KEY`
  - Incorrect database credentials
  - Migrations not run

### Need to Run Commands?
Use the **Shell** tab in your web service dashboard:
```bash
php artisan cache:clear
php artisan config:clear
php artisan migrate:status
```

---

## 📊 Cost Summary

**Current Setup:**
- PostgreSQL Database: $7/month (Starter)
- Web Service: $7/month (Starter)
- **Total: $14/month**

**Free Tier Option:**
- Both services can run on free tier for testing
- ⚠️ Free tier limitations:
  - Spins down after 15 min inactivity
  - Slower cold starts
  - Limited resources

---

## 🔐 Security Reminders

After first login:
1. ✅ Change admin password immediately
2. ✅ Set `APP_DEBUG=false` (already configured)
3. ✅ Review user permissions
4. ✅ Enable 2FA if available
5. ✅ Set up automatic database backups in Render

---

## 🆘 Need Help?

- **Render Docs**: https://render.com/docs
- **Laravel Deployment**: https://laravel.com/docs/deployment
- **Full Guide**: See `DEPLOYMENT.md` in repository

---

**Ready to deploy?** Follow the steps above! 🚀
