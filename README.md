# Laravel

Description project

## Installation

Sebelum menjalankan projek ini pastikan php yang digunakan minimal versi 8.2.

Berikut tahap untuk setup projek :

-   Clone this repository

```
  git clone https://gitlab.com/venturo-web/venturo-laravel-skeleton.git
```

-   Masuk ke direktori projek

```
cd venturo-laravel-skeleton
```

-   Instal dependency laravel menggunakan perintah

```
composer install
```

-   Copy `.env.example` menjadi `.env` dengan perintah

```
cp .env.example .env
```

-   Generate key laravel

```
php artisan key:generate
```

-   Konfigurasi Database
    Sesuaikan konfigurasi database pada file `.env`

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=core_laravel_11_venturo
DB_USERNAME=root
DB_PASSWORD=
```

-   Generate Database & Seeder

```
php artisan migrate --seed
```

-   Generate token jwt

```
php artisan jwt:secret
```

-   Menjalankan projek laravel

```
php artisan serve
```

## Perintah Sebelum Commit

Pastikan untuk menjalankan perintah berikut sebelum melakukan commit agar kode tetap konsisten dengan standar yang ditentukan:

```
vendor/bin/pint
```
