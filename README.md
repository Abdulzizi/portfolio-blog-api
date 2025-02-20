# Laravel

Description project

## Installation

Sebelum menjalankan projek ini pastikan php yang digunakan minimal versi 8.2.

Berikut tahap untuk setup projek :

-   Clone this repository

```
  git clone https://gitlab.com/venturo-web/portfolio-blog-api.git
```

-   Masuk ke direktori projek

```
cd portfolio-blog-api
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

## Struktur Folder

```
.
├── App
│   ├── Exports : Menyimpan class helper untuk proses Export excel
│   ├── Imports : Menyimpan class helper untuk proses Import excel
│   ├── Mail : Menyimpan class helper untuk mengirim email
│   ├── Models : Menyimpan class model yang terhubung ke suatu tabel di database, Tempatkan tugas komunikasi dengan database (query database) melalui model
│   └── Http
│       ├── Controllers
│       │   ├── Api : Menyimpan class controller khusus untuk rest api
│       │   └── Web : Menyimpan class controller web frontend/selain rest api
│       ├── Helpers : Menyimpan class Helper/Sub function pembantu agar function utama pada controller tidak terlalu komplek, biasanya algoritma/manipulasi array ditempatkan di sini
│       ├── Middleware : Menyimpan class Middleware (Hanya lead programmer yang boleh menambahkan middleware)
│       ├── Request : Menyimpan class Form Request
│       ├── Resource : Menyimpan class Resource
│       └── Services : Menyimpan class Service
├── config : Directory untuk menyimpan semua konfigurasi
├── database : Directory untuk menyimpan Database Migration Script
├── public : Directory public yang digunakan sebagai root directory
├── views
│   ├── layout : save page/komponen untuk layouting, misalnya header/footer/sidebar/etc
│   ├── component : Menyimpan halaman/komponen yang bisa di recycle
│   ├── content
│   ├── email : Menyimpan file format html untuk pengiriman email
│   ├── excel : Menyimpan file format html ketika generate/download file excel
│   └── pdf : Menyimpan file format html ketika generate/download pdf
├── routes
│   ├── api.php : File untuk mendaftarkan routing API
│   └── web.php : FIle untuk mendaftarkan routing non API/Web biasa
├── storage
│   ├── app : Menyimpan file-file yang diupload & digunakan oleh pengguna
│   └── logs: Menyimpan log error yang di generate otomatis oleh laravel
├── tests : Menyimpan file / class untuk PHP unit test
├── .env : File environment untuk menyimpan konfigurasi pada masing-masing device development (Jangan dipush ke repository)
├── .env.example : File environment yang digunakan sebagai template .env (Wajib di push ke repository dan nama variabel harus di update menyesuaikan perubahan pada file .env) agar semua tim bisa mengetahui konfigurasi apa saja yang dibutuhkan.
├── .gitignore : File untuk mendaftarkan folder / file apa saja yang tidak push ke repository
└── composer.json : File untuk menyimpan daftar library apa saja yang digunakan
```
