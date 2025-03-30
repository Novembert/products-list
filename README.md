# OnlinePOS Recruitment Task  

## Live Demo  

[Live Demo](https://frontend-production-5cd0.up.railway.app/#/products)  

## Assumptions  

In this project, I assumed that the active user is already logged in as a user of type **Restaurant Owner**.  

## UI Design Decisions  

The table design slightly differs from the UI design provided by OnlinePOS. This is due to a bug in PrimeVue’s `DataTable` component, which prevents dragging and dropping table rows on touch screens. To address this, I adjusted the mobile design to enable reordering list elements on mobile devices.  

Unfortunately, I spotted this issue a bit too late. Therefore, I didn't manage to cover the re-ordering functionality with unit tests. Anyway, the overall tests coverage of this project is just enough to show that I am capable of writing tests. 

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
