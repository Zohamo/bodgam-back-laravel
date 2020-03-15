# BodGam BackEnd API

Based on Laravel PHP Framework.

## Commands

-   Run : `php artisan serve`
-   Restart Apache server : `sudo /etc/init.d/apache2 restart`

Initialize :

-   Drop All Tables & Migrate : `php artisan migrate:fresh --seed`
-   Passport : `php artisan passport:client --personal` or `php artisan passport:install`

### Basic Commands

#### Model

[Create a model](https://laravel.com/docs/5.8/eloquent#defining-models) : `php artisan make:model User -mc`

[Options](https://quickadminpanel.com/blog/list-of-21-artisan-make-commands-with-parameters/) :

-   `--migration` or `-m` : Create a new migration file for the model.
-   `--controller` or `-c` : Create a new controller file for the model.

#### Controller

[Create a controller](https://laravel.com/docs/5.7/controllers) : `php artisan make:controller UserController --api --model=User`

[Options](https://quickadminpanel.com/blog/list-of-21-artisan-make-commands-with-parameters/) :

-   `--api` : Generates 5 methods: index(), store(), show(), update(), destroy(). Because create/edit forms are not needed for API.
-   `--model=Photo` : If you are using route model binding and would like the resource controller’s methods to type-hint a model instance.
-   `--invokable` : Generates controller with one \_\_invoke() method. [Invokable Controllers with One Specific Action](https://laraveldaily.com/invokable-controllers-with-one-specific-action/)

#### Request

[Create a request](https://medium.com/@kamerk22/the-smart-way-to-handle-request-validation-in-laravel-5e8886279271) : `php artisan make:request UserStoreRequest`

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

### Laravel Best Practices

Top 10 Laravel Best Practices You Should Follow [from Sree](https://www.innofied.com/top-10-laravel-best-practices/)

## Issues

-   `php artisan migrate` returns error `Illuminate\Database\QueryException could not find driver`
    fix : `sudo apt install php7.2-pdo php7.2-mysql`
