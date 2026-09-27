# 🚗 AutoHub — Vehicle Service & Maintenance Tracking System

**AutoHub** is a production-shaped, full-stack vehicle service and fleet management web application built with Laravel 11. It streamlines workshop operations by digitizing customer and vehicle profiles, appointment scheduling, mechanic job cards, automated cost estimations, inventory tracking, and financial reporting.

---

## ✨ Features

### 👤 Customer & Vehicle Profile Management
* Secure user registration and profile management for vehicle owners and fleet managers.
* Comprehensive vehicle database tracking registration/plate numbers, models, manufacturing years, current mileage (km), and complete historical service records per vehicle.

### 📅 Service Appointment Booking System
* Interactive time-slot booking engine allowing customers to schedule services such as oil changes, general tuning, engine diagnostics, and mechanical repairs.
* Admin and staff management capabilities to approve, reschedule, or cancel incoming appointments.

### 🛠️ Job Card Management (Mechanic / Workshop Panel)
* Dedicated operational panel for mechanics to open, manage, and update digital "Job Cards" upon a vehicle's arrival.
* Ability to log performed services, record replaced spare parts, track labor hours, and update repair statuses (*Pending*, *In Progress*, *Quality Check*, *Completed*).

### 💳 Automated Cost Estimation & Invoicing
* Dynamic billing engine that automatically compiles costs based on selected spare parts, services rendered, and labor charges.
* Automated PDF invoice generation upon job completion, viewable and downloadable by customers.

### 🔔 Service Reminder System (Automated Logic)
* Background notification logic triggered when a vehicle approaches its next recommended service interval based on elapsed time or mileage thresholds.

### 📦 Inventory & Spare Parts Stock Management
* Stock tracking system for workshop inventory (e.g., oil filters, brake pads, spark plugs, lubricants).
* Automated stock deduction when a spare part is assigned to a Job Card, complete with low-stock warning indicators for administrators.

### 📊 Revenue & Expense Financial Dashboard
* Comprehensive financial panel for workshop owners to track daily/monthly income from completed services and operational expenses (spare parts purchasing costs).
* Visual charts and report generation tools for business profitability analysis.

---

## 🛠️ Tech Stack

| Layer | Technology |
| :--- | :--- |
| **Backend** | PHP 8.2+, Laravel 11, Eloquent ORM |
| **Database** | MySQL 8 / MariaDB 10.6+ |
| **Frontend** | Blade Templates, Tailwind CSS, Alpine.js, Chart.js, FontAwesome |
| **PDF & Export** | `barryvdh/laravel-dompdf`, `maatwebsite/excel` |
| **Authentication** | Laravel Session Auth + Custom Middleware |

---

## 📁 Project Directory Structure

```text
app/
  Models/                User, Vehicle, Appointment, JobCard, JobCardItem, Service, SparePart, Invoice, Expense
  Http/
    Controllers/
      VehicleController.php       # Customer vehicle & service history management
      AppointmentController.php   # Booking engine & time slots
      Staff/JobCardController.php # Mechanic workshop operations & job cards
      Admin/InventoryController.php# Spare parts stock management
      Admin/FinanceController.php # Revenue, expenses, and financial dashboards
    Middleware/RoleMiddleware.php # Role-gated access control (Customer, Mechanic, Staff, Admin)
database/
  migrations/             Database schema migrations
  seeders/                UserSeeder, ServiceSeeder, SparePartSeeder, VehicleSeeder
resources/views/          Blade templates organized by area (vehicles, appointments, workshop, inventory, admin)
routes/web.php            Role-gated route definitions
config/autohub.php        Service intervals, tax rates, and system settings
