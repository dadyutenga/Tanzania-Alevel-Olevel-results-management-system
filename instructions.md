Deployment Fixes for CodeIgniter 4 Application on test.ditronics.co.tz
This document outlines the issues encountered and fixes applied while deploying a CodeIgniter 4 application on an Ubuntu server with Nginx, PHP 8.2, and MySQL. The application is located at /var/www/elliot and serves the domain https://test.ditronics.co.tz. Follow these steps to resolve common issues during deployment.
Prerequisites

Server: Ubuntu (e.g., 22.04 LTS)
Web Server: Nginx
PHP: 8.2 (with PHP-FPM)
Database: MySQL 8.0.41
Domain: test.ditronics.co.tz (with SSL via Let’s Encrypt)
Project Directory: /var/www/elliot (web root: /var/www/elliot/public)
Composer: Installed globally
Permissions: Application files owned by www-data user

Issues and Fixes
1. MySQL Root Access Denied
Issue: Unable to log in to MySQL with sudo mysql -u root due to an authentication error (ERROR 1045 (28000): Access denied for user 'root'@'localhost' (using password: NO)).
Fix:

Stop MySQL:
sudo systemctl stop mysql


Start MySQL in safe mode to bypass authentication:
sudo mysqld_safe --skip-grant-tables --skip-networking &


Log in to MySQL:
mysql -u root


Reset the root password:
FLUSH PRIVILEGES;
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'your_secure_password';
EXIT;


Stop safe mode and restart MySQL:
sudo killall mysqld
sudo systemctl start mysql


Test login:
mysql -u root -p

Enter the new password.


Note: Replace your_secure_password with a strong password (avoid weak passwords like 123456789 used during testing).
2. Composer Installation Failed Due to Missing PHP Extensions
Issue: Running composer install failed with:
codeigniter4/framework v4.6.0 requires ext-intl * -> it is missing from your system.

Fix:

Install required PHP extensions:
sudo apt update
sudo apt install php8.2-intl php8.2-mbstring php8.2-curl php8.2-xml


Verify extensions are enabled:
php -m | grep -E 'intl|mbstring|curl|xml|mysqli|pdo_mysql|json'

Expected output includes: intl, mbstring, curl, xml, mysqli, pdo_mysql, json.

Restart PHP-FPM:
sudo systemctl restart php8.2-fpm


Run Composer as the www-data user:
sudo chown -R www-data:www-data /var/www/elliot
sudo -u www-data composer install


If lock file issues persist, update dependencies:
sudo -u www-data composer update


Verify the vendor directory:
ls -l /var/www/elliot/vendor/codeigniter4/framework/system/Boot.php



Note: Avoid running Composer as root. Ensure permissions:
sudo chown -R www-data:www-data /var/www/elliot/vendor
sudo chmod -R 775 /var/www/elliot/vendor

3. PHP Fatal Error: Missing Boot.php
Issue: Nginx error log showed:
PHP Fatal error: Uncaught Error: Failed opening required '/var/www/elliot/app/Config/../../vendor/codeigniter4/framework/system/Boot.php' in /var/www/elliot/public/index.php:54

Fix: This was resolved by installing Composer dependencies (see Issue 2). Verify the file exists:
ls -l /var/www/elliot/vendor/codeigniter4/framework/system/Boot.php

4. Buttons Redirecting to localhost/link
Issue: Clicking buttons redirected to URLs like http://localhost:8080/link due to an incorrect baseURL.
Fix:

Update the baseURL in /var/www/elliot/.env:
sudo nano /var/www/elliot/.env

Add or update:
app.baseURL = https://test.ditronics.co.tz


If .env doesn’t exist, create it:
sudo cp /var/www/elliot/.env.example /var/www/elliot/.env
sudo nano /var/www/elliot/.env


Set permissions:
sudo chown www-data:www-data /var/www/elliot/.env
sudo chmod 664 /var/www/elliot/.env


Optionally, update /var/www/elliot/app/Config/App.php:
sudo nano /var/www/elliot/app/Config/App.php

Change:
public string $baseURL = 'http://localhost:8080/';

To:
public string $baseURL = 'https://test.ditronics.co.tz';


Clear CodeIgniter cache:
sudo -u www-data php spark cache:clear


Search for hardcoded localhost URLs in views/controllers:
sudo grep -r "localhost:8080" /var/www/elliot/app

Replace with:
<?php echo base_url('route'); ?>

Example:
<a href="<?php echo base_url('results'); ?>">Results</a>



5. Nginx Configuration Errors: Duplicate SSL Directives
Issue: Running sudo nginx -t failed with:
nginx: [warn] duplicate value "TLSv1.2" in /etc/nginx/sites-enabled/test.ditronics.co.tz:16
nginx: [warn] duplicate value "TLSv1.3" in /etc/nginx/sites-enabled/test.ditronics.co.tz:16
nginx: [emerg] "ssl_ciphers" directive is duplicate in /etc/nginx/sites-enabled/test.ditronics.co.tz:17

Fix:

Open the Nginx configuration:
sudo nano /etc/nginx/sites-enabled/test.ditronics.co.tz


Remove duplicate ssl_protocols and ssl_ciphers directives, as they’re included in /etc/letsencrypt/options-ssl-nginx.conf. Use:
server {
    listen 80;
    server_name test.ditronics.co.tz www.test.ditronics.co.tz;
    return 301 https://test.ditronics.co.tz$request_uri;
}

server {
    listen 443 ssl;
    server_name test.ditronics.co.tz www.test.ditronics.co.tz;

    ssl_certificate /etc/letsencrypt/live/test.ditronics.co.tz/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/test.ditronics.co.tz/privkey.pem;
    include /etc/letsencrypt/options-ssl-nginx.conf;
    ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem;

    root /var/www/elliot/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~* /(.git|vendor|writable|env) {
        deny all;
        return 403;
    }

    location ~ /\.ht {
        deny all;
    }

    access_log /var/log/nginx/test.ditronics.co.tz.access.log;
    error_log /var/log/nginx/test.ditronics.co.tz.error.log;
}


Test the configuration:
sudo nginx -t


Reload Nginx:
sudo systemctl reload nginx



6. 404 Errors for /public/results and /login
Issue: Nginx error log showed:
open() "/var/www/elliot/public/public/results" failed (2: No such file or directory)
open() "/var/www/elliot/public/login" failed (2: No such file or directory)

Fix:

Ensure the try_files directive in Nginx routes requests to index.php (see Issue 5 configuration).

Search for incorrect links:
sudo grep -r "public/results" /var/www/elliot/app
sudo grep -r "login" /var/www/elliot/app

Update hardcoded links in views:
<a href="<?php echo base_url('results'); ?>">Results</a>
<a href="<?php echo base_url('login'); ?>">Login</a>


Define routes in /var/www/elliot/app/Config/Routes.php:
sudo nano /var/www/elliot/app/Config/Routes.php

Add:
$routes->get('results', 'ResultsController::index');
$routes->get('login', 'AuthController::login');
$routes->group('alevel', function ($routes) {
    $routes->get('marks/view', 'MarksController::view');
    $routes->get('results/publish', 'ResultsController::publish');
});


Create controllers if missing:
sudo nano /var/www/elliot/app/Controllers/ResultsController.php

Add:
<?php
namespace App\Controllers;
use CodeIgniter\Controller;
class ResultsController extends Controller
{
    public function index()
    {
        return view('results');
    }
}

Create view:
sudo nano /var/www/elliot/app/Views/results.php

Add:
<!DOCTYPE html>
<html>
<head>
    <title>Results</title>
</head>
<body>
    <h1>Results Page</h1>
</body>
</html>

Repeat for AuthController and MarksController as needed.

Set permissions:
sudo chown -R www-data:www-data /var/www/elliot/app
sudo chmod -R 775 /var/www/elliot/app


Clear cache:
sudo -u www-data php spark cache:clear



7. SSL Handshake Errors
Issue: Nginx error log showed:
SSL_do_handshake() failed (SSL: error:0A00006C:SSL routines::bad key share)

Fix: The Nginx configuration (Issue 5) includes modern SSL settings via /etc/letsencrypt/options-ssl-nginx.conf. Verify the certificate:
sudo certbot certificates

Renew if expired:
sudo certbot renew
sudo systemctl reload nginx

8. Database Configuration
Issue: Potential database connection issues (e.g., for tz_web_setting table).
Fix:

Configure database in /var/www/elliot/.env:
sudo nano /var/www/elliot/.env

Add:
database.default.hostname = localhost
database.default.database = your_db_name
database.default.username = your_db_user
database.default.password = your_db_password


Test connection:
mysql -u your_db_user -p -h localhost your_db_name


Create database and user if needed:
mysql -u root -p

In MySQL:
CREATE DATABASE your_db_name;
CREATE USER 'your_db_user'@'localhost' IDENTIFIED WITH mysql_native_password BY 'your_db_password';
GRANT ALL PRIVILEGES ON your_db_name.* TO 'your_db_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;


Verify tz_web_setting table:
USE your_db_name;
SHOW TABLES LIKE 'tz_web_setting';


Run migrations:
sudo -u www-data php spark migrate



9. Security Concerns
Issue: Log showed attempts to access /webui/ and /.git/config.
Fix: Nginx configuration (Issue 5) blocks sensitive paths:
location ~* /(.git|vendor|writable|env) {
    deny all;
    return 403;
}

Run MySQL security script:
sudo mysql_secure_installation

Post-Deployment Steps

Test Routes:

https://test.ditronics.co.tz
https://test.ditronics.co.tz/results
https://test.ditronics.co.tz/login
https://test.ditronics.co.tz/alevel/marks/view
https://test.ditronics.co.tz/alevel/results/publish


Monitor Logs:
sudo tail -f /var/log/nginx/test.ditronics.co.tz.error.log
sudo tail -f /var/log/php8.2-fpm.log
sudo tail -f /var/www/elliot/writable/logs/log-$(date +%Y-%m-%d).php


Backup:
tar -czf /backup/elliot-$(date +%Y%m%d).tar.gz /var/www/elliot
mysqldump -u root -p your_db_name > /backup/db-$(date +%Y%m%d).sql


Optimize Performance: Adjust PHP-FPM settings in /etc/php/8.2/fpm/pool.d/www.conf (e.g., pm.max_children) based on server resources.


Notes

Replace your_db_name, your_db_user, and your_db_password with actual values.
Ensure SSL certificates are valid and auto-renewed via Certbot.
If routes like /alevel/marks/view fail, verify controllers and views in /var/www/elliot/app/Controllers and /var/www/elliot/app/Views.

