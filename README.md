<p align="center"><img src="public/img/factorydesk-logo.png" width="400"></p>


## About
Simple ticketing system designed for manufacturing environments where production workers need to contact specific department but they don't have AD/email accounts.
Ticket request form consists of three dependant select fields where production workers can pick select options in following order: <br/>
Manufacturing zone (from which worker raises an issue), position (place where worker performs his duties) and problem.
<br/><br/>
Workers can also: <br/>
-> add short message (250 characters) to describe their problem <br/>
-> attach files <br/>
-> select priority <br/>

Check documentation folder for more info.

## Prequisites
- PHP 8.1+
- Composer

## Installation
1. Download/clone repository
2. Run composer update
3. Setup your .env file with your database configuration, eg.:
<pre>
  DB_CONNECTION=mysql        // your DB driver (pgsql for Postgres, sqlsrv for SQL Server)
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=factorydesk
  DB_USERNAME=root
  DB_PASSWORD=root
</pre>
4. Run following commands:
<pre>
  php artisan migrate
  php artisan serve
</pre>

## Demo
Note: Heroku weirdly loads font sometimes so if dashboard seems off to you just reload page. <br/>
https://factorydesk.herokuapp.com/ <br/>
Test account credentials
<pre>
Login: test
Pass: FactoryDesk
</pre>
