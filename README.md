# BodGam BackEnd API

Based on Laravel PHP Framework.

## Todo

-   Implement EventHandler Listeners to establish Notifications and Events relationship (via EventServiceProvider)
-   Move models to Models folder

## Operation

API Router > Controller > Repository > Model > MySQL DB

The **API Router** will call the related **Controller** method, that will get/set data from/to the **MySQL DB**'s **Model** passing through the **Repository** interface.

Repository design pattern [from Connor Leech](https://medium.com/employbl/use-the-repository-design-pattern-in-a-laravel-application-13f0b46a3dce)

## Commands

### Install

-   Install dependencies : `composer install`

See also **Laravel > Laravel customization** below.

### Initialize

-   Drop All Tables & Migrate : `php artisan migrate:fresh --seed`
-   Passport : `php artisan passport:client --personal` or `php artisan passport:install`

### Run

-   Run Laravel server : `php artisan serve`

### Deploy

1. Switch **.env** to the proper environment settings
2. [Autoloader Optimization](https://laravel.com/docs/7.x/deployment#autoloader-optimization) : `composer install --optimize-autoloader --no-dev`
3. `php artisan optimize`
4. `composer dumpautoload -o`
5. `php artisan route:clear` + `php artisan config:clear` + `php artisan cache:clear`

### Helpers

-   Restart Apache server : `sudo /etc/init.d/apache2 restart`
-   Run MySQL : `sudo /usr/bin/mysql -u root -p`

### Artisan

#### Artisan Make:Model

[Create a model](https://laravel.com/docs/5.8/eloquent#defining-models) : `php artisan make:model User -mc`

[Options](https://quickadminpanel.com/blog/list-of-21-artisan-make-commands-with-parameters/) :

-   `--migration` or `-m` : Create a new migration file for the model.
-   `--controller` or `-c` : Create a new controller file for the model.

#### Artisan Make:Controller

[Create a controller](https://laravel.com/docs/5.7/controllers) : `php artisan make:controller UserController --api --model=User`

[Options](https://quickadminpanel.com/blog/list-of-21-artisan-make-commands-with-parameters/) :

-   `--api` : Generates 5 methods: index(), store(), show(), update(), destroy(). Because create/edit forms are not needed for API.
-   `--model=Photo` : If you are using route model binding and would like the resource controller’s methods to type-hint a model instance.
-   `--invokable` : Generates controller with one \_\_invoke() method. [Invokable Controllers with One Specific Action](https://laraveldaily.com/invokable-controllers-with-one-specific-action/)

#### Artisan Make:Request

[Create a request](https://medium.com/@kamerk22/the-smart-way-to-handle-request-validation-in-laravel-5e8886279271) : `php artisan make:request UserRequest`

### Database Commands

#### Artisan Make:Migration

-   [Create a migration](https://laravel.com/docs/7.x/migrations#generating-migrations) : `php artisan make:migration create_users_table`
-   [Run all of the outstanding migrations](https://laravel.com/docs/7.x/migrations#running-migrations) : `php artisan migrate`, to force the commands to run without a prompt, use the `--force` flag
-   [Roll back the latest migration operation](https://laravel.com/docs/7.x/migrations#rolling-back-migrations) : `php artisan migrate:rollback`
-   Roll back all migrations: `php artisan migrate:reset`
-   Drop All Tables & Migrate : `php artisan migrate:fresh --seed`
-   Convert migration to SQL query : `php artisan migrate --pretend --no-ansi > database/migrate.sql`

#### Artisan Make:Seeders

-   [Create a seeder](https://laravel.com/docs/7.x/seeding#writing-seeders) : `php artisan make:seeder UsersTableSeeder`
-   [Running Seeders](https://laravel.com/docs/7.x/seeding#running-seeders) : `php artisan db:seed`

[6 Tips About Data Seeding in Laravel](https://laraveldaily.com/10-tips-about-data-seeding-in-laravel/)

#### MySQL Export

-   Copy SQL database : `mysqldump -uroot -p database_name > dump_database.sql`
-   Copy SQL table : `mysqldump -uroot -p database_name table_name > dump_table.sql`

## Laravel

Laravel version 6.7.1

<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

### Laravel Doc

-   [Available Validation Rules](https://laravel.com/docs/5.8/validation#available-validation-rules)
-   [Laravel Daily](https://laraveldaily.com/)

### Laravel Best Practices

Top 10 Laravel Best Practices You Should Follow [from Sree](https://www.innofied.com/top-10-laravel-best-practices/)

### Laravel Tutorials

-   [Laravel API Tutorial: How to Build and Test a RESTful API](https://www.toptal.com/laravel/restful-laravel-api-tutorial)

#### API Rest with Laravel 5.6 Passport Authentication

[GitHub](https://github.com/modulr/api-laravel-passport)

4.  [Reset Password](https://medium.com/modulr/api-rest-with-laravel-5-6-passport-authentication-reset-password-part-4-50d27455dcca)

### Laravel customization

After a Laravel installation or update you should add the following :

#### # implement [Composite keys](https://stackoverflow.com/questions/36332005/laravel-model-with-two-primary-keys-update) to use two primary keys

-   in _\App\your_model.php_ :

```
protected $primaryKey = ['user_id', 'stock_id'];
public $incrementing = false;
```

-   in \vendor\laravel\framework\src\Illuminate\Database\Eloquent\Model.php

```
/**
 * Set the keys for a save update query.
 *
 * @param  \Illuminate\Database\Eloquent\Builder  $query
 * @return \Illuminate\Database\Eloquent\Builder
 */
protected function setKeysForSaveQuery(Builder $query)
{
    $keys = $this->getKeyName();

    if (!is_array($keys)) {
        $query->where($this->getKeyName(), '=', $this->getKeyForSaveQuery());
        return $query;
    }

    foreach ($keys as $keyName) {
        $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
    }

    return $query;
}

/**
 * Get the primary key value for a save query.
 *
 * @param  string $keyName
 * @return mixed
 */
protected function getKeyForSaveQuery($keyName = null)
{
    if (is_null($keyName)) {
        $keyName = $this->getKeyName();
    }

    if (isset($this->original[$keyName])) {
        return $this->original[$keyName];
    }

    return $this->getAttribute($keyName);
}
```

## Issues

### # `php artisan migrate` returns error `Illuminate\Database\QueryException could not find driver`

-   fix : `sudo apt install php7.2-pdo php7.2-mysql`

### # `500 : Failed to connect to Pusher.`

-   fix : [PusherBroadcaster exception "Failed to connect to Pusher" when running in queue](https://github.com/laravel/framework/issues/16478#issuecomment-261796182)

1. Download curl certificate at [curl.haxx.se/ca/cacert.pem](https://curl.haxx.se/ca/cacert.pem)
2. Update `curl.cainfo` in your _php.ini_ (apache server and php.x.x.x/bin) configuration, e.g. `curl.cainfo = "/home/dev/certificates/cacert.pem"`
