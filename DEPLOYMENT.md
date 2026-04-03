# Deployment Guide for shakshak.net

## Server Information
- **Server IP**: 138.68.110.230
- **SSH Port**: 22
- **Deployment Path**: /var/www/shakshak.net/

## Initial Server Setup

### 1. Connect to your server
```bash
ssh root@138.68.110.230
```

### 2. Upload and run the setup script
From your local machine:
```bash
scp server-setup.sh root@138.68.110.230:/root/
ssh root@138.68.110.230
chmod +x /root/server-setup.sh
./server-setup.sh
```

### 3. Configure Environment
After the script completes, edit your `.env` file:
```bash
nano /var/www/shakshak.net/.env
```

Update these values:
```env
APP_NAME="Shakshak"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shakshak
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# Add other configuration as needed
```

### 4. Set up MySQL Database
```bash
mysql -u root -p
```

Then in MySQL:
```sql
CREATE DATABASE shakshak CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'shakshak_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON shakshak.* TO 'shakshak_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 5. Run Migrations
```bash
cd /var/www/shakshak.net
php artisan migrate --seed
php artisan storage:link
```

## GitHub Actions Setup

### 1. Add GitHub Secrets
Go to your GitHub repository → Settings → Secrets and variables → Actions

Add these secrets:
- **SSH_HOST**: `138.68.110.230`
- **SSH_USERNAME**: `root`
- **SSH_PASSWORD**: `your_password`

### 2. Protected Files and Directories
The deployment pipeline is configured to preserve:
- **`.env` file** - Server environment configuration (automatically backed up during deployment)
- **`public/uploads/`** - User uploaded images and files
- **`storage/app/public/`** - Laravel storage linked files

These directories are excluded from git via `.gitignore` and will not be affected during deployment.

### 3. Push to Deploy
The pipeline is configured to deploy automatically when you push to the `main` branch:

```bash
git add .
git commit -m "Deploy to production"
git push origin main
```

### 4. Monitor Deployment
Go to your GitHub repository → Actions tab to see the deployment progress.

## Manual Deployment

If you need to deploy manually:

```bash
ssh root@138.68.110.230
cd /var/www/shakshak.net
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
sudo systemctl reload php8.2-fpm
sudo systemctl reload nginx
```

## Security Recommendations

### 1. Change Root Password
```bash
passwd
```

### 2. Set Up SSH Key Authentication
From your local machine:
```bash
ssh-keygen -t ed25519 -C "your_email@example.com"
ssh-copy-id root@138.68.110.230
```

### 3. Disable Password Authentication
```bash
nano /etc/ssh/sshd_config
```
Change:
```
PasswordAuthentication no
```
Then restart SSH:
```bash
systemctl restart sshd
```

### 4. Set Up Firewall
```bash
ufw allow 22/tcp
ufw allow 80/tcp
ufw allow 443/tcp
ufw enable
```

### 5. Install SSL Certificate (Let's Encrypt)
```bash
apt install certbot python3-certbot-nginx
certbot --nginx -d shakshak.net -d www.shakshak.net
```

## Troubleshooting

### Check Application Logs
```bash
tail -f /var/www/shakshak.net/storage/logs/laravel.log
```

### Check Nginx Logs
```bash
tail -f /var/log/nginx/error.log
```

### Check PHP-FPM Status
```bash
systemctl status php8.2-fpm
```

### Permission Issues
```bash
cd /var/www/shakshak.net
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage bootstrap/cache
```

### Clear All Caches
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear
```

## Domain Configuration

Point your domain to the server IP:
- **A Record**: `@` → `138.68.110.230`
- **A Record**: `www` → `138.68.110.230`

DNS propagation may take up to 48 hours.
