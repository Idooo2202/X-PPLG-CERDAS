# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Laravel 13 application for classroom management ("Aplikasi-Kelas"). Built with PHP 8.3+, using SQLite database and session-based authentication.

## Commands

```bash
# Development (runs server, queue, logs, and Vite concurrently)
composer run dev

# Run tests
composer run test

# Single test: php artisan test --filter=TestName
```

## Architecture

### Authentication & Authorization

Custom session-based auth (not Laravel Sanctum/Passport):
- `AuthController` handles login/logout with rate limiting (5 attempts/min)
- Credentials stored in `users` table with bcrypt hashing
- Session stores: `user_id`, `user_role`, `user_name`
- Middleware aliases in `bootstrap/app.php`:
  - `auth.custom` → `CheckAuth` (requires valid session)
  - `role` → `CheckRole` (validates against allowed roles)

### User Roles

Four roles defined in `users.role` enum:
- `wali_kelas` (homeroom teacher) — full access
- `bendahara` (treasurer) — manage kas (finances)
- `sekretaris` (secretary) — manage kehadiran (attendance)
- `siswa` (student) — view-only, can edit profile

### Core Modules

| Module | Controller | Key Models | Description |
|--------|-----------|------------|-------------|
| Users | `UserController` | `User` | CRUD for students/staff (wali_kelas only) |
| Kas | `KasController` | `Kas`, `KasPayment` | Class finances: income/expenses + daily payment tracking (Rp2000/student) |
| Kehadiran | `KehadiranController` | `Kehadiran` | Attendance tracking (wali_kelas/sekretaris can input) |
| Leaderboard | `LeaderboardController` | `Leaderboard` | Gamification: points, streaks, tiers (sultan/kaya/normal/kelas_bawah) |
| Pesan | `PesanController` | `Pesan` | Messaging system |
| Pelanggaran | Inline in routes | `Pelanggaran` | Violation reports (sekretaris/wali_kelas) |
| Profile | `ProfileController` | `User` | All users can edit own profile |

### Database Schema

Main tables (see `database/migrations/`):
- `users` — authentication + user data
- `kas` — financial transactions (pemasukan/pengeluaran)
- `kas_payments` — daily student payment checklist
- `kehadiran` — attendance records
- `pelanggaran` — violation reports
- `pesan` — messages
- `activity_log` — audit trail
- `leaderboards` — gamification points/tiers

### Key Relationships

```
User hasMany Kehadiran
User hasMany KasPayment
User hasOne Leaderboard
User hasMany Pelanggaran
User hasMany ActivityLog
```

### Frontend

Blade templates in `resources/views/`:
- `home.blade.php` — landing page with login modal
- `layouts/dashboard.blade.php` — main layout
- Feature views under `dashboard/{module}/`

No JavaScript framework — vanilla JS + Tailwind CSS.

## Configuration Notes

- Sessions stored in database (`SESSION_DRIVER=database` in `.env`)
- SQLite for development (`DB_CONNECTION=sqlite`)
- Debug logging enabled — check `debug-*.log` files for troubleshooting
