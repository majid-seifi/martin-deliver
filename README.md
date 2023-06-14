<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Requirements
- **[Docker - version  23.0.3](https://www.docker.com//)**
- **[Docker Compose - version v2.3.3](https://docs.docker.com/compose/)**

## Installation

### Run these steps for installation:
- Get a clone from repository:
```shell
git clone https://github.com/majid-seifi/martin-deliver/
```
- Go inside the directory:
```shell
cd martin-deliver
```
- If you have composer installed in your OS and all requirements are enabled, run:
```shell
composer install
```
- Otherwise, use this command:
```shell
docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php82-composer:latest composer install --ignore-platform-reqs
```
- Now, Sail is installed, up it with this:
```shell
./vendor/bin/sail up -d
```
- Migrate the database with seeds:
```shell
./vendor/bin/sail artisan migrate --seed
```
- Then go to this address http://localhost/api/documentation in browser.
- Default port is 80, but if you want to use another port change APP_PORT in .env file and restart Sail.
- By using SwaggerUI you can login and retrieve token. Then use other apis by roles.
- For login and get token use these credentials:
#### Intermediary Users:
- Email: intermediary1@example.test
- Password: password
- There are 3 users, and you can login with them.(intermediary1, intermediary2, intermediary3)
#### Delivery Users:
- Email: delivery1@example.test
- Password: password
- There are 3 users, and you can login with them.(delivery1, delivery2, delivery3)
#### Note
- If you want to use webhook, change it directly for each user in "intermediaries" table in database.
