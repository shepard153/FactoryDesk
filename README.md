<p align="center"><img src="public/img/factorydesk-logo.png" width="400"></p>


## About
Simple ticketing system designed for manufacturing environments where production workers need to contact specific department but they don't have email access.

## Prequisites
- PHP 8.1+
- Composer

## Installation
1. Download/clone repository
2. Run composer update
3. Setup your .env file with your database configuration
4. Run following commands:
<pre>
  php artisan migrate
  php artisan storage:link
  php artisan serve
</pre>
