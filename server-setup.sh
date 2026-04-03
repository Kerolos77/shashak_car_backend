#!/bin/bash

# Server Setup Script for shakshak.net
# Run this script on your DigitalOcean server as root

echo "=== Shakshak.net Server Setup ==="

# Update system
echo "Updating system packages..."
apt update && apt upgrade -y

# Install required packages
echo "Installing required packages..."
apt install -y nginx php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml php8.2-bcmath \
    php8.2-curl php8.2-zip php8.2-gd php8.2-intl php8.2-redis \
    mysql-server git composer curl unzip

# Create project directory
echo "Creating project directory..."
mkdir -p /var/www/shakshak.net
cd /var/www/shakshak.net

# Clone repository (you'll need to add your git repo URL)
echo "Enter your Git repository URL (e.g., https://github.com/username/shakshak.net.git):"
read GIT_REPO
git clone $GIT_REPO .

# Set permissions
echo "Setting permissions..."
chown -R www-data:www-data /var/www/shakshak.net
chmod -R 755 /var/www/shakshak.net
chmod -R 775 /var/www/shakshak.net/storage
chmod -R 775 /var/www/shakshak.net/bootstrap/cache

# Install Composer dependencies
echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Copy environment file
echo "Setting up environment file..."
cp .env.example .env
php artisan key:generate

# Configure Nginx
echo "Configuring Nginx..."
cat > /etc/nginx/sites-available/shakshak.net << 'EOF'
server {
    listen 80;
    listen [::]:80;
    server_name shakshak.net www.shakshak.net;
    root /var/www/shakshak.net/public;

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
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

# Enable site
ln -s /etc/nginx/sites-available/shakshak.net /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Test Nginx configuration
nginx -t

# Restart services
echo "Restarting services..."
systemctl restart nginx
systemctl restart php8.2-fpm

# Enable services on boot
systemctl enable nginx
systemctl enable php8.2-fpm

echo "=== Setup Complete ==="
echo ""
echo "Next steps:"
echo "1. Edit /var/www/shakshak.net/.env and configure your database and other settings"
echo "2. Run: php artisan migrate --seed"
echo "3. Set up your GitHub secrets (SSH_HOST, SSH_USERNAME, SSH_PASSWORD)"
echo "4. Push to main branch to trigger automatic deployment"
echo ""
echo "⚠️  IMPORTANT SECURITY STEPS:"
echo "1. Change your root password: passwd"
echo "2. Set up SSH key authentication instead of password"
echo "3. Disable password authentication in /etc/ssh/sshd_config"
echo "4. Set up a firewall: ufw allow 22/tcp && ufw allow 80/tcp && ufw allow 443/tcp && ufw enable"
