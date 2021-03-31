# CODE TEST (YanceyWorks)

This is a simple Laravel-Nuxt project showing administrative CRUD operations for 
companies and users (employees or admin).

![image](https://user-images.githubusercontent.com/63578260/112604673-61d86980-8e51-11eb-951d-00088d25bf7f.png) ![image](https://user-images.githubusercontent.com/63578260/112604780-816f9200-8e51-11eb-948b-34c05d3238d1.png)


## How to start the application

### 1. Copy the .env.example and create a .env file at the root of the both ./api and ./frontend.

### 2. Start the Docker environment.

```bash
docker-compose up
```

### 3. Enter the docker container (For setting up Laravel)
Enter into the api docker container from another terminal
```bash
docker-compose exec api sh
```

### 4. Install dependencies
From within the api docker container
```bash
composer install
```

### 5. Run database migrations and seeders. 
From within the api docker container run this command
```bash
php artisan migrate:fresh --seed
```

### 6. Symlink for storage and public directories
This create a symlink for the storage path. Do this while still
inside the api docker container.
```bash
php artisan storage:link
```

## Users for local development
The database seeders include dummy employees and an admin. You can log-in as 
an admin using the credentials below:

### Admin User
```
email: admin@email.com
password: password
```

## Authorization
Roles and permissions are provided by a package called spatie/laravel-permission.

Usage: [Spatie/Laravel-Permission documentation](https://spatie.be/docs/laravel-permission/v4/basic-usage/basic-usage)

## Authentication
The strategy used for authentication is provided by Laravel Sanctum for both Laravel and Nuxt Auth.

Usage: [Nuxt Auth - Laravel Sanctum](https://auth.nuxtjs.org/providers/laravel-sanctum)
