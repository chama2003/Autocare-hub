# AutoCare Hub — Vehicle Service & Maintenance Tracking System

A Laravel + Blade + Tailwind CSS application for car/bike service centers or corporate
fleet management: customer & vehicle profiles, appointment booking, mechanic job cards,
automated invoicing (PDF), inventory/stock control, service reminders, and a revenue &
expense financial dashboard.

## What's in this package

This package contains the **application-layer code** for AutoCare Hub — models,
controllers, migrations, seeders, routes, and Blade views. It is designed to be laid on
top of a **fresh Laravel installation** (rather than shipping a full vendor/ tree), so it
stays lightweight and always installs against the current stable Laravel/PHP release.

```
app/
  Console/Commands/CheckServiceReminders.php
  Http/Controllers/*.php
  Http/Middleware/EnsureUserHasRole.php
  Models/*.php
  Notifications/ServiceReminderNotification.php
database/
  migrations/*.php
  seeders/*.php
resources/views/**/*.blade.php
routes/web.php
routes/console.php
composer-additions.json   (extra composer package required)
```

## Tech stack

- **Backend:** PHP 8.2+, Laravel 11.x, MySQL, Eloquent ORM
- **Frontend:** Blade templates, Tailwind CSS (via CDN, no build step required),
  Alpine.js for interactive components (calendar widget, mobile sidebar), FontAwesome
  icons, Chart.js for the financial dashboard
- **PDF invoices:** `barryvdh/laravel-dompdf`
- **Auth scaffold:** Laravel Breeze (recommended) for register/login/password reset

## Features implemented

1. **Customer & Vehicle Profile Management** — registration, vehicle registry (plate
   number, make/model, year, mileage), full historical service record per vehicle
   (`Vehicle::serviceHistory()`).
2. **Service Appointment Booking** — booking form + a lightweight Alpine.js month
   calendar (fed by `/appointments/calendar-feed`), with staff approve / reschedule /
   cancel actions.
3. **Job Card Management** — mechanic panel to open a job card per vehicle arrival, log
   services performed, assign spare parts, track labor hours, and move status through
   Pending → In Progress → Quality Check → Completed.
4. **Automated Cost Estimation & Invoicing** — `JobCard::laborTotal()` /
   `partsTotal()` / `grandTotal()` compile costs automatically; `InvoiceController`
   generates a PDF via DomPDF, downloadable by the customer.
5. **Service Reminder System** — `Vehicle::isServiceDue()` checks elapsed
   mileage/time; the `autocare:check-service-reminders` Artisan command (scheduled
   daily in `routes/console.php`) emails owners via `ServiceReminderNotification`.
6. **Inventory & Spare Parts Stock Management** — stock levels, low-stock threshold,
   automatic deduction when a part is assigned to a job card
   (`SparePart::deductStock()` inside a DB transaction with row locking), manual
   restock endpoint.
7. **Revenue & Expense Financial Dashboard** — `FinanceController::dashboard()`
   aggregates paid invoices vs recorded expenses by month/category, rendered with
   Chart.js bar + doughnut charts.

Role-based access is enforced with the `role:` route middleware
(`app/Http/Middleware/EnsureUserHasRole.php`) across four roles: `admin`, `staff`,
`mechanic`, `customer`.

## Local setup

### 1. Prerequisites
- PHP 8.2+ with the usual Laravel extensions (mbstring, openssl, pdo_mysql, tokenizer, xml, ctype, json, bcmath)
- Composer 2.x
- MySQL 8 (or MariaDB) and a database created, e.g. `autocare_hub`
- Node.js is **not required** — Tailwind/Alpine/FontAwesome/Chart.js are all loaded via CDN in `layouts/app.blade.php`

### 2. Create the base Laravel project
```bash
composer create-project laravel/laravel autocare-hub
cd autocare-hub
```

### 3. Install Laravel Breeze for authentication (login/register/password reset)
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
```
This scaffolds `routes/auth.php`, the auth views, and the `auth`/`verified` middleware
used by this package's routes. If you'd rather write your own auth, just make sure a
`login` named route and `auth` middleware exist.

### 4. Install the PDF invoicing package
```bash
composer require barryvdh/laravel-dompdf
```

### 5. Copy this package's files into the project
Copy the contents of this package into your fresh Laravel project, **overwriting**
`routes/web.php` (Breeze's own routes stay in `routes/auth.php` and are unaffected) and
merging into `app/`, `database/`, and `resources/views/`:

```bash
cp -r app/*        /path/to/autocare-hub/app/
cp -r database/*   /path/to/autocare-hub/database/
cp -r resources/*  /path/to/autocare-hub/resources/
cp routes/web.php     /path/to/autocare-hub/routes/web.php
cp routes/console.php /path/to/autocare-hub/routes/console.php
```

### 6. Register the role middleware
In Laravel 11's `bootstrap/app.php`, add the alias inside `withMiddleware`:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\EnsureUserHasRole::class,
    ]);
})
```

### 7. Environment configuration
Copy `.env.example` to `.env` and set your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=autocare_hub
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=log   # switch to smtp/ses/etc. to actually deliver service reminder emails
```
Then generate the app key and link storage (used for generated invoice PDFs):
```bash
php artisan key:generate
php artisan storage:link
```

### 8. Migrate and seed demo data
```bash
php artisan migrate --seed
```
This creates all tables and seeds:
- **4 demo accounts** (password for all: `password`)
  - `admin@autocarehub.test` — Admin (full access incl. Finance dashboard)
  - `staff@autocarehub.test` — Staff (appointments, job cards, invoices, inventory)
  - `mechanic@autocarehub.test` — Mechanic (assigned job cards)
  - `customer@autocarehub.test` / `amara@autocarehub.test` — Customers
- **8 service types** (Oil Change, General Tuning, Engine Diagnostics, Brake Service, etc.)
- **10 spare parts** with realistic stock levels (some intentionally near/at their
  low-stock threshold so you can see the dashboard warning immediately)
- **3 demo vehicles** across the two seeded customers, with service history dates set
  so at least one vehicle shows as "due for service" on first login

> ⚠️ These are demo credentials for local development only. Change or remove them
> before deploying to a real environment.

### 9. Run it
```bash
php artisan serve
```
Visit `http://localhost:8000`, log in with one of the seeded accounts above, and
explore the role-appropriate dashboard.

### 10. (Optional) Enable the daily service-reminder job
Laravel's scheduler needs one cron entry on your server:
```
* * * * * cd /path/to/autocare-hub && php artisan schedule:run >> /dev/null 2>&1
```
Locally you can trigger it manually any time with:
```bash
php artisan autocare:check-service-reminders
```

## Notes on production readiness

- Switch `MAIL_MAILER` to a real transactional provider (SES, Postmark, SMTP) to
  actually deliver service reminder emails; for SMS, add a channel (e.g. Vonage/Twilio)
  in `ServiceReminderNotification::via()`.
- Tailwind/Alpine/FontAwesome/Chart.js are loaded from CDN for zero-build-step
  simplicity; for a production asset pipeline, swap to `laravel/vite` + local packages.
- All monetary figures are stored as `decimal(10,2)`; adjust currency formatting
  (`Rs.` is used throughout the views) to your locale.
- Stock deduction runs inside a DB transaction with `lockForUpdate()` to avoid race
  conditions when two staff members log parts on concurrent job cards simultaneously.
- Role checks are centralized on the `User` model (`isAdmin()`, `isStaff()`,
  `isMechanic()`, `isCustomer()`) and enforced via the `role:` middleware — extend
  `EnsureUserHasRole` if you need finer-grained permissions (e.g. Laravel Policies).
