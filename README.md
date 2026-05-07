# 🚨 RescueNet — Disaster Coordination Platform

> A full-stack Laravel web application for real-time disaster management, enabling rescue agencies to coordinate resources, report calamities, broadcast alerts, communicate, and track field positions on a live map.

---

## 📑 Table of Contents

- [Overview](#-overview)
- [Tech Stack](#-tech-stack)
- [Features at a Glance](#-features-at-a-glance)
- [Architecture](#-architecture)
- [Database Schema](#-database-schema)
- [Module Breakdown](#-module-breakdown)
  - [Authentication](#1-authentication)
  - [Dashboard](#2-dashboard)
  - [Agencies](#3-agencies)
  - [Resources](#4-resources)
  - [Calamities](#5-calamities)
  - [Alerts](#6-alerts)
  - [Messages](#7-messages)
  - [Live Map](#8-live-map)
  - [Profile](#9-profile)
- [Route Reference](#-route-reference)
- [Project Structure](#-project-structure)
- [Getting Started](#-getting-started)
- [Seed Data](#-seed-data)
- [Design System](#-design-system)
- [License](#-license)

---

## 🌐 Overview

**RescueNet** is a disaster coordination platform built for agencies like NDRF, Red Cross, Fire Services, Medical Teams, and Coast Guard to work together during natural and man-made calamities. The platform provides:

- A centralized **dashboard** with real-time statistics on active calamities, available resources, and pending alerts.
- **Calamity reporting** with geo-location, severity levels, and affected radius tracking.
- **Resource inventory** management (personnel, vehicles, equipment, medical, supplies) per agency.
- A **broadcast alert** system for urgent inter-agency communication.
- **Direct messaging** between agencies for coordination.
- A **live interactive map** (Leaflet.js) displaying agency positions and calamity zones in real time.
- Full **authentication** (Laravel Breeze) with agency-specific registration fields.

---

## 🛠️ Tech Stack

| Layer        | Technology                                       |
| ------------ | ------------------------------------------------ |
| **Backend**  | PHP 8.2+, Laravel 12                             |
| **Auth**     | Laravel Breeze (session-based)                    |
| **Frontend** | Blade templates, Tailwind CSS 4, Alpine.js       |
| **Mapping**  | Leaflet.js 1.9 + OpenStreetMap tiles             |
| **Build**    | Vite 7 + `@tailwindcss/vite` + `laravel-vite-plugin` |
| **Database** | SQLite (default, easily switchable to MySQL/Postgres) |
| **Queue**    | Database driver                                  |
| **Testing**  | PHPUnit 11                                       |

---

## ✨ Features at a Glance

| Feature                | Description                                                        |
| ---------------------- | ------------------------------------------------------------------ |
| 🔐 Agency Registration | Sign up with org name, agency type, phone                          |
| 📊 Dashboard           | Stat cards: active agencies, calamities, resources, critical events |
| 🏢 Agency Directory    | Browse and view details of all active agencies                     |
| 📦 Resource Inventory  | CRUD for personnel, vehicles, equipment, medical, supplies         |
| ⚠️ Calamity Reporting  | Report disasters with type, severity, geo-coords, affected radius  |
| 🔔 Alert Broadcasts    | Targeted or broadcast alerts linked to calamities                  |
| 💬 Direct Messaging    | 1-on-1 messaging with read receipts and unread counts              |
| 🗺️ Live Map            | Real-time map with agency markers and calamity zones               |
| 📍 Location Sharing    | Share GPS position; tracked in location history log                |
| 👤 Profile Management  | Edit profile, update password, delete account                      |

---

## 🏗️ Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                        BROWSER                              │
│  Blade + Tailwind CSS + Alpine.js + Leaflet.js              │
├─────────────────────────────────────────────────────────────┤
│                     LARAVEL 12 (PHP 8.2+)                   │
│                                                             │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────────┐   │
│  │   Routes     │→ │ Controllers  │→ │   Blade Views    │   │
│  │  (web.php,   │  │  (8 main +   │  │  (10 dirs,       │   │
│  │   auth.php)  │  │   9 auth)    │  │   25+ templates) │   │
│  └──────────────┘  └──────┬───────┘  └──────────────────┘   │
│                           │                                  │
│  ┌──────────────┐  ┌──────▼───────┐  ┌──────────────────┐   │
│  │   Policies   │  │   Models     │  │  Form Requests   │   │
│  │  (Resource)  │  │  (6 models)  │  │ (ProfileUpdate)  │   │
│  └──────────────┘  └──────┬───────┘  └──────────────────┘   │
│                           │                                  │
├───────────────────────────▼─────────────────────────────────┤
│                   SQLite / MySQL / Postgres                  │
│  users, resources, calamities, alerts, messages,             │
│  location_logs, cache, sessions, jobs                        │
└─────────────────────────────────────────────────────────────┘
```

---

## 🗄️ Database Schema

### Entity Relationship Diagram

```
┌──────────────┐       ┌──────────────────┐       ┌──────────────┐
│    users     │1────M │   resources      │       │ location_logs│
│──────────────│       │──────────────────│       │──────────────│
│ id           │       │ id               │       │ id           │
│ name         │       │ user_id (FK)     │       │ user_id (FK) │
│ email        │       │ name             │       │ latitude     │
│ password     │       │ type             │       │ longitude    │
│ phone        │       │ quantity         │       │ timestamps   │
│ organization │       │ available_qty    │       └──────────────┘
│ agency_type  │       │ status           │
│ description  │       │ notes            │       ┌──────────────┐
│ latitude     │       │ timestamps       │       │   alerts     │
│ longitude    │       └──────────────────┘       │──────────────│
│ status       │                                  │ id           │
│ timestamps   │1────M ┌──────────────────┐       │ calamity_id  │
│              │       │  calamities      │1────M │ created_by   │
│              │       │──────────────────│       │ target_user  │
│              │       │ id               │       │ title        │
│              │       │ reported_by (FK) │       │ message      │
│              │       │ title            │       │ priority     │
│              │       │ type             │       │ is_broadcast │
│              │       │ custom_type      │       │ acknowledged │
│              │       │ description      │       │ timestamps   │
│              │       │ severity         │       └──────────────┘
│              │       │ lat / lng        │
│              │       │ radius_km        │       ┌──────────────┐
│              │       │ status           │       │  messages    │
│              │       │ timestamps       │       │──────────────│
│              │       └──────────────────┘       │ id           │
│              │1────M                            │ sender_id    │
│              │──────────────────────────────────│ receiver_id  │
│              │                                  │ calamity_id  │
└──────────────┘                                  │ content      │
                                                  │ read_at      │
                                                  │ timestamps   │
                                                  └──────────────┘
```

### Tables Summary

| Table            | Purpose                                    | Key Columns                                           |
| ---------------- | ------------------------------------------ | ----------------------------------------------------- |
| `users`          | Agency accounts & profiles                 | name, email, organization, agency_type, lat/lng, status |
| `resources`      | Inventory items per agency                 | name, type, quantity, available_quantity, status       |
| `calamities`     | Disaster reports                           | title, type, severity, lat/lng, radius_km, status     |
| `alerts`         | Targeted/broadcast notifications           | title, message, priority, is_broadcast, acknowledged  |
| `messages`       | Direct agency-to-agency messages           | sender_id, receiver_id, content, read_at              |
| `location_logs`  | GPS position history per user              | user_id, lat/lng                                      |
| `cache`          | Application cache (database driver)        | key, value, expiration                                |
| `sessions`       | User sessions (database driver)            | id, user_id, payload                                  |
| `jobs`           | Queue jobs (database driver)               | id, queue, payload, attempts                          |

### Enumerations

| Field                  | Allowed Values                                                     |
| ---------------------- | ------------------------------------------------------------------ |
| `users.agency_type`    | `government`, `ngo`, `fire_rescue`, `medical`, `coast_guard`, `volunteer`, `military`, `other` |
| `resources.type`       | `personnel`, `vehicle`, `equipment`, `medical`, `supplies`         |
| `resources.status`     | `available`, `deployed`, `maintenance`                             |
| `calamities.type`      | `earthquake`, `flood`, `fire`, `cyclone`, `tsunami`, `landslide`, `industrial`, `other` |
| `calamities.severity`  | `low`, `medium`, `high`, `critical`                                |
| `calamities.status`    | `active`, `contained`, `resolved`                                  |
| `alerts.priority`      | `low`, `medium`, `high`, `critical`                                |

---

## 📦 Module Breakdown

### 1. Authentication

**Scaffolded with Laravel Breeze** — session-based auth with the following flows:

| Flow                | Route                   | Controller                          |
| ------------------- | ----------------------- | ----------------------------------- |
| Register            | `GET/POST /register`    | `RegisteredUserController`          |
| Login               | `GET/POST /login`       | `AuthenticatedSessionController`    |
| Logout              | `POST /logout`          | `AuthenticatedSessionController`    |
| Forgot Password     | `GET/POST /forgot-password` | `PasswordResetLinkController`   |
| Reset Password      | `GET/POST /reset-password`  | `NewPasswordController`         |
| Email Verification  | `GET /verify-email`     | `EmailVerificationPromptController` |
| Confirm Password    | `GET/POST /confirm-password` | `ConfirmablePasswordController` |

**Custom Registration Fields:** Registration extends the default Breeze form to include:
- `organization` — Agency organization name
- `agency_type` — Dropdown: Government, NGO, Fire & Rescue, Medical, Coast Guard, Volunteer, Military, Other
- `phone` — Contact phone number

### 2. Dashboard

**Controller:** `DashboardController@index`  
**Route:** `GET /dashboard`  
**View:** `resources/views/dashboard.blade.php`

Displays 4 stat cards and two panels:

| Stat Card          | Data Source                                        |
| ------------------ | -------------------------------------------------- |
| Active Agencies    | Count of users with `status = active`              |
| Active Calamities  | Count of calamities with `status = active`         |
| Available Resources| Sum of `available_quantity` / sum of `quantity`     |
| Critical Events    | Calamities with `severity = critical` and active   |

**Panels:**
- **Recent Alerts** — Latest 5 alerts (targeted + broadcast) with inline acknowledge button
- **Active Calamities** — Latest 5 active calamities linked to detail view

**Quick Actions:** Report Calamity, Send Alert, Add Resource, Open Map

### 3. Agencies

**Controller:** `AgencyController`  
**Views:** `agencies/index.blade.php`, `agencies/show.blade.php`

| Action         | Route              | Description                        |
| -------------- | ------------------ | ---------------------------------- |
| List Agencies  | `GET /agencies`    | Paginated (12/page), shows resource count |
| View Agency    | `GET /agencies/{user}` | Agency profile + resource list   |

All registered users are treated as agencies. Displays organization, type, phone, description, and resource inventory.

### 4. Resources

**Controller:** `ResourceController`  
**Policy:** `ResourcePolicy` — Only the owner can update/delete their resources  
**Views:** `resources/index.blade.php`, `resources/create.blade.php`, `resources/edit.blade.php`

| Action         | Route                      | Description                       |
| -------------- | -------------------------- | --------------------------------- |
| List (mine)    | `GET /resources`           | Current user's resources only     |
| Create Form    | `GET /resources/create`    | Form for new resource             |
| Store          | `POST /resources`          | Validate & save resource          |
| Edit Form      | `GET /resources/{id}/edit` | Pre-filled edit form              |
| Update         | `PUT /resources/{id}`      | Validate & update                 |
| Delete         | `DELETE /resources/{id}`   | Remove resource (authorized only) |

**Resource Types:** Personnel, Vehicle, Equipment, Medical, Supplies  
**Status Options:** Available, Deployed, Maintenance  
**Tracks:** Total quantity vs. available quantity

### 5. Calamities

**Controller:** `CalamityController`  
**Views:** `calamities/index.blade.php`, `calamities/create.blade.php`, `calamities/show.blade.php`

| Action         | Route                      | Description                          |
| -------------- | -------------------------- | ------------------------------------ |
| List All       | `GET /calamities`          | Paginated (12/page), with reporter   |
| Create Form    | `GET /calamities/create`   | Report a new disaster                |
| Store          | `POST /calamities`         | Validate with geo-coords & severity  |
| View Detail    | `GET /calamities/{id}`     | Full detail + alerts + messages      |
| Update Status  | `PUT /calamities/{id}`     | Change status/severity               |

**Calamity Types:** Earthquake, Flood, Fire, Cyclone, Tsunami, Landslide, Industrial, Other (with custom_type free text)  
**Severity Levels:** Low, Medium, High, Critical  
**Status Lifecycle:** `Active` → `Contained` → `Resolved`  
**Geo Data:** Latitude, Longitude, Radius (km) — displayed on the live map

### 6. Alerts

**Controller:** `AlertController`  
**Views:** `alerts/index.blade.php`, `alerts/create.blade.php`

| Action         | Route                             | Description                          |
| -------------- | --------------------------------- | ------------------------------------ |
| List Alerts    | `GET /alerts`                     | All alerts relevant to user          |
| Create Form    | `GET /alerts/create`              | New alert (broadcast or targeted)    |
| Store          | `POST /alerts`                    | Create alert linked to calamity      |
| Acknowledge    | `POST /alerts/{id}/acknowledge`   | Mark alert as acknowledged           |

**Alert Types:**
- **Broadcast** (`is_broadcast = true`) — Sent to all agencies
- **Targeted** (`target_user_id` set) — Sent to a specific agency

**Priority Levels:** Low, Medium, High, Critical  
**Acknowledgement:** Timestamp recorded when an agency acknowledges an alert  
**Linked to:** Calamity (optional), Creator (required)

### 7. Messages

**Controller:** `MessageController`  
**Views:** `messages/index.blade.php`, `messages/show.blade.php`

| Action         | Route                  | Description                            |
| -------------- | ---------------------- | -------------------------------------- |
| Conversations  | `GET /messages`        | List all conversations + unread counts |
| Thread View    | `GET /messages/{user}` | Full message thread with a user        |
| Send Message   | `POST /messages`       | Send a new message                     |

**Features:**
- **Read receipts** — `read_at` timestamp set when recipient views the thread
- **Unread counts** — Badge shown on conversation list
- **Calamity context** — Messages can optionally be linked to a calamity
- **UI:** Chat-style message bubbles (sent = amber, received = beige)

### 8. Live Map

**Controller:** `MapController`  
**View:** `map/index.blade.php`

| Action           | Route                   | Description                          |
| ---------------- | ----------------------- | ------------------------------------ |
| Map View         | `GET /map`              | Full-page interactive Leaflet map    |
| Map Data API     | `GET /api/map-data`     | JSON: agencies + calamities geo data |
| Update Location  | `POST /api/location`    | Update current user's GPS position   |

**Map Features:**
- **Agency Markers** — Amber circle markers showing name, org, type, phone, resource count
- **Calamity Zones** — Color-coded circles based on severity (green → red) with radius visualization
- **Toggle Layers** — Show/hide agencies and calamities independently
- **Share Location** — Uses browser Geolocation API; updates user record + creates LocationLog entry
- **Auto-Refresh** — Map data refreshes every 15 seconds
- **Default View:** Centered on India (20.5937°N, 78.9629°E, zoom 5)

**Severity Color Coding:**
| Severity | Color     |
| -------- | --------- |
| Low      | `#10b981` (Green)  |
| Medium   | `#f59e0b` (Amber)  |
| High     | `#f97316` (Orange) |
| Critical | `#ef4444` (Red)    |

### 9. Profile

**Controller:** `ProfileController`  
**Request:** `ProfileUpdateRequest`  
**View:** `profile/edit.blade.php`

| Action         | Route             | Description                       |
| -------------- | ----------------- | --------------------------------- |
| Edit Profile   | `GET /profile`    | Edit name, email                  |
| Update Profile | `PATCH /profile`  | Validate & save changes           |
| Delete Account | `DELETE /profile` | Permanent account deletion        |

---

## 🛣️ Route Reference

### Web Routes (auth-protected)

```
GET     /                          → welcome page (public)
GET     /dashboard                 → Dashboard
GET     /profile                   → Edit profile
PATCH   /profile                   → Update profile
DELETE  /profile                   → Delete account
GET     /agencies                  → Agency list
GET     /agencies/{user}           → Agency detail
GET     /resources                 → My resources
GET     /resources/create          → New resource form
POST    /resources                 → Store resource
GET     /resources/{resource}/edit → Edit resource form
PUT     /resources/{resource}      → Update resource
DELETE  /resources/{resource}      → Delete resource
GET     /calamities                → Calamity list
GET     /calamities/create         → Report calamity form
POST    /calamities                → Store calamity
GET     /calamities/{calamity}     → Calamity detail
PUT     /calamities/{calamity}     → Update calamity status
GET     /alerts                    → Alert list
GET     /alerts/create             → New alert form
POST    /alerts                    → Store alert
POST    /alerts/{alert}/acknowledge → Acknowledge alert
GET     /messages                  → Conversations list
GET     /messages/{user}           → Message thread
POST    /messages                  → Send message
GET     /map                       → Live map
GET     /api/map-data              → Map data (JSON)
POST    /api/location              → Update my location
```

### Auth Routes (Laravel Breeze)

```
GET     /register                  → Registration form
POST    /register                  → Create account
GET     /login                     → Login form
POST    /login                     → Authenticate
POST    /logout                    → Logout
GET     /forgot-password           → Password reset request
POST    /forgot-password           → Send reset link
GET     /reset-password/{token}    → Password reset form
POST    /reset-password            → Reset password
GET     /verify-email              → Email verification notice
GET     /verify-email/{id}/{hash}  → Verify email
POST    /email/verification-notification → Resend verification
GET     /confirm-password          → Confirm password form
POST    /confirm-password          → Confirm password
PUT     /password                  → Update password
```

---

## 📁 Project Structure

```
laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/                    # 9 Breeze auth controllers
│   │   │   ├── AgencyController.php     # Agency listing & detail
│   │   │   ├── AlertController.php      # Alert CRUD & acknowledge
│   │   │   ├── CalamityController.php   # Calamity CRUD
│   │   │   ├── DashboardController.php  # Dashboard stats & panels
│   │   │   ├── MapController.php        # Map view, data API, location
│   │   │   ├── MessageController.php    # Messaging system
│   │   │   ├── ProfileController.php    # Profile management
│   │   │   └── ResourceController.php   # Resource inventory CRUD
│   │   └── Requests/
│   │       ├── Auth/                    # Breeze auth requests
│   │       └── ProfileUpdateRequest.php # Profile validation
│   ├── Models/
│   │   ├── Alert.php                    # Alert model (belongsTo Calamity, User)
│   │   ├── Calamity.php                 # Calamity model (hasMany Alert, Message)
│   │   ├── LocationLog.php              # GPS log model (belongsTo User)
│   │   ├── Message.php                  # Message model (belongsTo User, Calamity)
│   │   ├── Resource.php                 # Resource model (belongsTo User)
│   │   └── User.php                     # User/Agency model (hasMany of everything)
│   ├── Policies/
│   │   └── ResourcePolicy.php           # Owner-only update/delete
│   ├── Providers/
│   └── View/
│       └── Components/
│           ├── AppLayout.php            # Authenticated layout
│           └── GuestLayout.php          # Guest/auth layout
├── database/
│   ├── database.sqlite                  # SQLite database file
│   ├── factories/
│   │   └── UserFactory.php              # User factory for testing
│   ├── migrations/
│   │   ├── create_users_table           # Base users table
│   │   ├── add_fields_to_users_table    # Agency fields (phone, org, type, geo, status)
│   │   ├── create_resources_table       # Resource inventory
│   │   ├── create_calamities_table      # Disaster reports
│   │   ├── create_alerts_table          # Alert/notification system
│   │   ├── create_messages_table        # Messaging
│   │   ├── create_location_logs_table   # GPS tracking
│   │   ├── create_cache_table           # Cache driver
│   │   └── create_jobs_table            # Queue driver
│   └── seeders/
│       └── DatabaseSeeder.php           # Demo data: 5 agencies, 15 resources,
│                                        # 3 calamities, 3 alerts, 5 messages
├── resources/
│   ├── css/
│   │   └── app.css                      # Full design system (475 lines)
│   ├── js/
│   │   └── app.js                       # Alpine.js & Axios bootstrap
│   └── views/
│       ├── agencies/                    # index, show
│       ├── alerts/                      # index, create
│       ├── auth/                        # login, register, forgot/reset password
│       ├── calamities/                  # index, create, show
│       ├── components/                  # 13 reusable Blade components
│       ├── layouts/                     # app (sidebar), guest, navigation
│       ├── map/                         # index (Leaflet integration)
│       ├── messages/                    # index, show
│       ├── profile/                     # edit
│       ├── resources/                   # index, create, edit
│       ├── dashboard.blade.php          # Main dashboard
│       └── welcome.blade.php            # Landing page
├── routes/
│   ├── web.php                          # All application routes
│   ├── auth.php                         # Breeze authentication routes
│   └── console.php                      # Console commands
├── composer.json                        # PHP dependencies
├── package.json                         # Node dependencies
├── vite.config.js                       # Vite + Tailwind + Laravel plugin
└── tailwind.config.js                   # Tailwind configuration
```

---

## 🚀 Getting Started

### Prerequisites

- **PHP** ≥ 8.2 with extensions: `sqlite3`, `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`
- **Composer** (latest)
- **Node.js** ≥ 18 & **npm**

### Installation

```bash
# 1. Clone the repository
git clone <repository-url>
cd laravel

# 2. Install PHP dependencies
composer install

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# 4. Create SQLite database
touch database/database.sqlite

# 5. Run migrations
php artisan migrate

# 6. (Optional) Seed demo data
php artisan db:seed

# 7. Install Node dependencies
npm install

# 8. Start development servers
npm run dev          # Vite dev server (terminal 1)
php artisan serve    # Laravel server (terminal 2)
```

### Quick Start (single command)

```bash
composer run setup   # Installs deps, generates key, migrates, builds assets
composer run dev     # Starts all services concurrently (server, queue, logs, vite)
```

### Access the App

- **URL:** `http://localhost:8000`
- **Demo Credentials** (after seeding):

| Agency                            | Email                        | Password   |
| --------------------------------- | ---------------------------- | ---------- |
| National Disaster Response Force  | `ndrf@rescuenet.test`        | `password` |
| Red Cross Emergency Unit          | `redcross@rescuenet.test`    | `password` |
| State Fire Services               | `fire@rescuenet.test`        | `password` |
| Medical Emergency Team            | `medical@rescuenet.test`     | `password` |
| Coast Guard Rescue                | `coastguard@rescuenet.test`  | `password` |

---

## 🌱 Seed Data

Running `php artisan db:seed` creates a realistic demo environment:

| Entity     | Count | Examples                                                    |
| ---------- | ----- | ----------------------------------------------------------- |
| Agencies   | 5     | NDRF, Red Cross, Fire Services, Medical Team, Coast Guard   |
| Resources  | 15    | Search teams, trucks, first aid kits, ambulances, boats     |
| Calamities | 3     | Gujarat Flood (critical), Pune Fire (high), Uttarakhand Earthquake (medium) |
| Alerts     | 3     | Deploy rescue teams (broadcast), Medical support (targeted), Earthquake advisory (broadcast) |
| Messages   | 5     | Inter-agency coordination threads                           |

Agencies are geolocated across India (Delhi, Mumbai, Pune, Bangalore, Chennai).

---

## 🎨 Design System

The app uses a **warm, sandy, premium aesthetic** with a custom CSS design system defined in `resources/css/app.css`:

### Color Palette

| Token                    | Value        | Usage                    |
| ------------------------ | ------------ | ------------------------ |
| `--color-primary`        | `#d97706`    | Amber 600 — Primary CTA  |
| `--color-primary-dark`   | `#b45309`    | Amber 700 — Hover states  |
| `--color-accent`         | `#f59e0b`    | Amber 500 — Bright accent |
| `--color-danger`         | `#ef4444`    | Red 500 — Alerts/errors   |
| `--color-success`        | `#10b981`    | Emerald 500 — Success     |
| `--color-bg-primary`     | `#fdfbf7`    | Sandy off-white           |
| `--color-bg-secondary`   | `#f3ede4`    | Warm beige                |
| `--color-text-primary`   | `#3b2f2f`    | Deep warm brown           |

### UI Components

- **Glass Cards** — Frosted glass with `backdrop-filter: blur(16px)` and warm shadows
- **Stat Cards** — Gradient background with hover lift animation
- **Badges** — Color-coded pills for severity, status, priority
- **Message Bubbles** — Chat-style with sent (amber) and received (beige)
- **Sidebar Navigation** — Fixed glass sidebar with active indicator
- **Toast Notifications** — Animated success messages
- **Data Tables** — Clean tables with hover highlighting
- **Form Inputs** — Styled with amber focus ring
- **Responsive** — Sidebar collapses on mobile (< 1024px)
- **Animations** — Fade-in-up on load, pulse glow on critical items

---

## 📜 License

This project is open-sourced software licensed under the [MIT License](https://opensource.org/licenses/MIT).
