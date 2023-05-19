# php-consultants-app

1. Create database from dump: mysql -u username -p database < dump.sql. Mysql database must exist prior to this command.
Modify .env file with the mysql credentials:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=password

2. How to run: php artisan serve (from inside veziv folder)

3. Go to http://localhost:8000/consultants where you will be redirected to login page. Login with admin6@gmail.com and password 123456 or create new user from registration.

Calendar page: http://localhost:8000/consultants

<img width="1540" alt="Screenshot 2023-05-19 at 2 16 49 PM" src="https://github.com/cornelradu/php-consultants-app/assets/3815479/ec6365d3-9a6c-412e-9b93-eedb0350be72">

