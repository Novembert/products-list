# OnlinePOS Recruitment Task

## Live demo

https://frontend-production-5cd0.up.railway.app/#/products

## Assumptions

In this project I assumed that active user is already logged in as a user of type Restaurant Owner. 

## UI Design decisions

The table design is a bit off, comparing to the UI design shared by OnlinePOS. This is because there is a little bug in PrimeVue's DataTable component, which makes it impossible to drag and drop table rows using touch screens. Therefore, I decided to change the mobile design a bit, making it possible to re-order list elements on mobile devides. 

Unfortunately, I spotted this issue a bit too late. Therefore, I didn't manage to cover the re-ordering functionality with unit tests. Anyway, the overall tests coverage of this project is just enough to show that I am capable of writing tests. 

## Local Development

### Prerequsities

- node (minimal version v22.14.0)
- npm (minimal version v10.9.2)
- WSL2 (Only if you're using Windows)
- Docker
- php
- composer

### Steps to complete for Local Development

In order to continue development locally, you'll need to follow the steps below:

1. Backend development

    1.1 Open terminal in the root of this repository

    1.2 Run `cd backend`

    1.3. copy file `/backend/.env.example`, and rename the copy to `/backend/.env`

    1.4. generate laravel app key (you can use this website )

    1.5. run `composer install`

    1.6. run `php artisan key:generate`, copy the generated key and paste it to `APP_KEY` env variable in your backend .env file

    1.7. (If you're on Windows computer) Open WSL2 terminal, and make sure you're in `/backend` location

    1.8. run `./vendor/bin/sail up -d`

    1.9. run `./vendor/bin/sail artisan migrate:fresh --seed`

2. Frontend development

    2.1. Open terminal in the root of this repository 

    2.2. Run `cd frontend`

    2.3. Run `npm ci`

    2.4. copy file `/frontend/.env.example`, and rename the copy to `/frontend/.env`

    2.5. Run `npm run dev`

    2.6. Open the link shown in latest command's results, e.g. `http://localhost:5174/`

Cheers!