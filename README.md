# BodGam BackEnd API

Based on Laravel PHP Framework.

## Commands

-   Launch : `php artisan serve`
-   Restart Apache server : `sudo /etc/init.d/apache2 restart`

### Database Commands

#### Migrations

-   [Create a migration](https://laravel.com/docs/7.x/migrations#generating-migrations) : `php artisan make:migration create_users_table`
-   [Run all of the outstanding migrations](https://laravel.com/docs/7.x/migrations#running-migrations) : `php artisan migrate`, to force the commands to run without a prompt, use the `--force` flag
-   [Roll back the latest migration operation](https://laravel.com/docs/7.x/migrations#rolling-back-migrations) : `php artisan migrate:rollback`
-   Drop All Tables & Migrate : `php artisan migrate:fresh --seed`

#### Seeders

-   [Create a seeder](https://laravel.com/docs/7.x/seeding#writing-seeders) : `php artisan make:seeder UsersTableSeeder`
-   [Running Seeders](https://laravel.com/docs/7.x/seeding#running-seeders) : `php artisan db:seed`

## About Laravel

<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

## Issues

-   `php artisan migrate` returns error `Illuminate\Database\QueryException could not find driver`
    fix : `sudo apt install php7.2-pdo php7.2-mysql`
