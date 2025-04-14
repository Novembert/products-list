# Products list CRUD app

This is a full-stack web application that allows users to manage a list of products via a user-friendly admin interface. It supports essential CRUD operations (Create, Read, Update, Delete) along with drag-and-drop sorting. The app is built as a modular and responsive interface designed for a restaurant or takeaway business to manage its online product offerings.

## Features

- Create, edit, and delete products
- Drag-and-drop sorting of product list
- Product attributes include:
  - Name
  - Description
  - Price
  - VAT rate
  - Tag name
  - Tag color
- Display of product ID after creation
- Responsive design for desktop and mobile views

The frontend is built using **Vue 3**, **PrimeVue**, **Tailwind CSS**, and **TypeScript**. The backend is powered by **Laravel** and uses **MySQL** as the database. All services are containerized using **Docker** for easy setup and consistency across environments.

## Local Development  

### Prerequisites  

- **Node.js** (minimum version: v22.14.0)  
- **npm** (minimum version: v10.9.2)  
- **WSL2** (Only if using Windows)  
- **Docker**  
- **PHP**  
- **Composer**  

### Steps for Local Development  

#### 1. Backend Setup  

1.1. Open a terminal in the root of this repository.  
1.2. Navigate to the backend directory:  
```bash
cd backend
```
1.3. Copy the `.env.example` file and rename the copy to `.env`:  
```bash
cp .env.example .env
```
1.4. Generate a Laravel application key (you can use an online generator or run the command below).  
1.5. Install dependencies:  
```bash
composer install
```
1.6. Generate the application key and set it in your `.env` file:  
```bash
php artisan key:generate
```
Copy the generated key and paste it as the value of the `APP_KEY` variable in your backend `.env` file.  

1.7. (Windows only) Open your **WSL2 terminal** and ensure you are in the `/backend` directory.  
1.8. Start the application using Docker:  
```bash
./vendor/bin/sail up -d
```
1.9. Run database migrations and seed the database:  
```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

---

#### 2. Frontend Setup  

2.1. Open a terminal in the root of this repository.  
2.2. Navigate to the frontend directory:  
```bash
cd frontend
```
2.3. Install dependencies:  
```bash
npm ci
```
2.4. Copy the `.env.example` file and rename the copy to `.env`:  
```bash
cp .env.example .env
```
2.5. Start the development server:  
```bash
npm run dev
```
2.6. Open the link displayed in the terminal output, e.g., `http://localhost:5174/`.  

---

Cheers!  

---
