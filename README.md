# php-consultants-app

1. Create database from dump: mysql -u username -p laravel < dump.sql

2. How to run: php artisan serve (from inside veziv folder)

3. Go to http://localhost:8000/consultants where you will be redirected to login page. Login with admin6@gmail.com and password 123456 or create new user from registration.

Calendar page: http://localhost:8000/consultants

4 tables are used in this app: Consultants, Users, Appointments; Each consultant has multiple appointments. Each user has many appointments. Each appointment belongs to one user and one consultant.
