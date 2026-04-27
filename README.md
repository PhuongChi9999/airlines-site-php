# To start

## Install dependencies
```
composer i && npm i
```

## Configure DB
```
php artisan migrate --seed
```
### Always `YES` if asks.

## Configure .env file
Just copy `.env.example` in the root of the project and rename a new copy to `.env`.

## Configure Encryption Keys
```
php artisan key:generate
```

## Run server and vite
```
php artisan serve
npm run dev
```
