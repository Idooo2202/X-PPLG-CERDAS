# CLAUDE.md

> **Project Context**: This file guides Claude Code when working with the X PPLG C Classroom Management System. Read this FIRST before making any changes to understand the complete architecture, conventions, and critical constraints.

---

## 🎯 Project Mission

**X PPLG Cerdas** - A comprehensive classroom management system for X PPLG C (Software Engineering class) at SMKN 1 Padaherang. The goal is to digitize and streamline daily classroom operations: attendance tracking, class finances (kas), student violations, messaging, and gamified engagement through a leaderboard system.

**Target Users**: 35 students + 1 homeroom teacher (wali kelas) + designated treasurer (bendahara) + secretary (sekretaris)

---

## 📚 Technology Stack

### Backend
- **Framework**: Laravel 13.6.0 (PHP 8.5.5)
- **Database**: MySQL (production) / SQLite (dev)
- **Authentication**: Custom session-based (NOT Sanctum/Passport)
- **Session Storage**: Database driver
- **Queue**: Database driver (for background jobs)

### Frontend
- **Template Engine**: Blade
- **Styling**: Native css + Custom CSS (coastal theme)
- **JavaScript**: Native JS (NO framework - React/Vue/Alpine not used)
- **Icons**: Emoji-based icons (🌊, ⚓, 🐚, etc.)
- **Design Theme**: Coastal/Beach/Ocean aesthetic with gradients

### Development Tools
- **Package Manager**: Composer (PHP) + npm (Node)
- **Build Tool**: Vite (for CSS/JS bundling)
- **Testing**: PHPUnit + Laravel testing utilities
- **Code Style**: PSR-12 (enforced via Laravel Pint)

---

## 🏗️ Architecture Overview

### Directory Structure

```
app/
├── Http/
│   ├── Controllers/          # All business logic goes here
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── KasController.php
│   │   ├── KehadiranController.php
│   │   ├── LeaderboardController.php
│   │   ├── PesanController.php
│   │   ├── ProfileController.php
│   │   └── UserController.php
│   └── Middleware/
│       ├── CheckAuth.php     # Custom auth middleware
│       └── CheckRole.php     # Role-based access control
├── Models/                   # Eloquent ORM models
│   ├── User.php
│   ├── Kas.php
│   ├── KasPayment.php
│   ├── Kehadiran.php
│   ├── Pelanggaran.php
│   ├── Pesan.php
│   ├── ActivityLog.php
│   └── Leaderboard.php
└── Services/                 # Business logic extracted from controllers
    ├── LeaderboardService.php
    ├── StreakCalculator.php
    └── NotificationService.php

database/
├── migrations/               # Database schema definitions
└── seeders/                  # Initial data (default users, jadwal)

resources/
├── views/
│   ├── welcome.blade.php    # Public landing page (coastal theme)
│   ├── auth/
│   │   └── login.blade.php  # Login modal/page
│   ├── layouts/
│   │   └── dashboard.blade.php  # Main dashboard layout
│   └── dashboard/
│       ├── index.blade.php  # Dashboard home
│       ├── kas/             # Finance module views
│       ├── kehadiran/       # Attendance module views
│       ├── leaderboard/     # Gamification views
│       ├── pesan/           # Messaging views
│       └── profile/         # User profile views
└── css/
    └── app.css              # Tailwind + custom coastal CSS

routes/
└── web.php                  # All HTTP routes defined here
```

---

## 🔐 Authentication & Authorization

### Authentication Flow

**CRITICAL**: This project uses **custom session-based authentication**, NOT Laravel's default Auth system or Sanctum/Passport.

#### Login Process (AuthController)
1. User submits `username` + `password`
2. Validate against `users` table (bcrypt password hash)
3. Check `is_active = true` flag
4. Rate limit: max 5 attempts per minute per IP
5. On success:
   - Store session: `user_id`, `user_role`, `user_name`
   - Log activity in `activity_log` table
   - Redirect to `/dashboard`

#### Session Management
- Driver: `database` (stored in `sessions` table)
- Lifetime: 120 minutes (configurable in `.env`)
- Regenerate session ID on login to prevent fixation attacks

#### Logout Process
- Clear session data
- Log activity
- Redirect to landing page (`/`)

### Authorization (Role-Based Access Control)

**4 Roles** (defined as ENUM in `users.role`):

| Role | Slug | Full Access? | Special Permissions |
|------|------|--------------|---------------------|
| Wali Kelas | `wali_kelas` | ✅ YES | User management, send broadcasts, view all reports |
| Bendahara | `bendahara` | ❌ NO | Manage kas (income/expenses), verify payments |
| Sekretaris | `sekretaris` | ❌ NO | Manage kehadiran, report violations |
| Siswa | `siswa` | ❌ NO | View-only + edit own profile + reply to messages |

#### Middleware Usage

```php
// In routes/web.php

// Require authentication
Route::middleware(['auth.custom'])->group(function () {
    // All logged-in users can access
});

// Role-specific access
Route::middleware(['auth.custom', 'role:wali_kelas'])->group(function () {
    // Only wali_kelas can access
});

Route::middleware(['auth.custom', 'role:wali_kelas,bendahara'])->group(function () {
    // Both wali_kelas AND bendahara can access
});
```

#### Permission Helpers (User Model)

```php
// Usage in controllers/views
Auth::user()->isWaliKelas();        // bool
Auth::user()->canManageKas();       // bool (wali_kelas OR bendahara)
Auth::user()->canManageKehadiran(); // bool (wali_kelas OR sekretaris)
Auth::user()->canManageUsers();     // bool (wali_kelas only)
```

---

## 📊 Database Schema & Relationships

### Core Tables

#### `users` - User Accounts
```sql
id, username (unique), password, nama_lengkap, role, nis (optional), 
foto (optional), is_active, remember_token, timestamps
```
- **Role ENUM**: `wali_kelas`, `bendahara`, `sekretaris`, `siswa`
- **Relationships**: hasMany(Kehadiran, KasPayment, Pelanggaran, ActivityLog), hasOne(Leaderboard)

#### `kas` - Financial Transactions
```sql
id, tanggal, jenis (pemasukan/pengeluaran), keterangan, jumlah, 
bukti (file path), created_by, timestamps
```
- **Jenis ENUM**: `pemasukan`, `pengeluaran`
- **Relationships**: belongsTo(User, 'created_by')

#### `kas_payments` - Daily Payment Checklist
```sql
id, user_id, tanggal, jumlah, status (lunas/belum), verified_by, 
verified_at, timestamps
UNIQUE(user_id, tanggal)
```
- **Default Amount**: Rp 2,000 per student per day
- **Status ENUM**: `lunas`, `belum`
- **Purpose**: Track who paid class funds each day

#### `kehadiran` - Attendance Records
```sql
id, user_id, tanggal, status (hadir/sakit/izin/alpha), waktu_checkin, 
keterangan, recorded_by, timestamps
UNIQUE(user_id, tanggal)
```
- **Status ENUM**: `hadir`, `sakit`, `izin`, `alpha`
- **Relationships**: belongsTo(User), belongsTo(User, 'recorded_by')

#### `pelanggaran` - Violation Reports
```sql
id, user_id, tanggal, jenis_pelanggaran, deskripsi, poin, reported_by, 
status (pending/ditindaklanjuti/selesai), tindak_lanjut, handled_by, timestamps
```
- **Reported By**: Sekretaris or Wali Kelas
- **Handled By**: Usually Wali Kelas (follow-up actions)

#### `leaderboards` - Gamification Points
```sql
id, user_id (unique), current_streak, longest_streak, total_points, 
tier (sultan/kaya/normal/kelas_bawah), last_attendance, last_payment, timestamps
```
- **Tier Logic**:
  - `sultan`: Top 10%, high points, consistent payments
  - `kaya`: Top 30%, good performance
  - `normal`: Average performance
  - `kelas_bawah`: Below average, needs improvement

#### `pesan` - Messaging System
```sql
id, from_user_id, to_user_id (nullable for broadcasts), is_broadcast, 
judul, isi, is_read, read_at, timestamps
```
- **Broadcast**: Wali Kelas can send to all students (to_user_id = NULL)
- **hasMany**: BalasanPesan (replies)

#### `activity_log` - Audit Trail
```sql
id, user_id, jenis (login/logout/payment/attendance/etc), deskripsi, 
metadata (JSON), timestamps
```
- **Purpose**: Track all user actions for history page

#### `jadwal_pelajaran` - Class Schedule
```sql
id, hari (1-5 for Mon-Fri), jam_mulai, jam_selesai, mata_pelajaran, 
guru, is_istirahat, urutan, timestamps
```
- **Hari**: 1=Senin, 2=Selasa, ..., 5=Jumat

#### `jadwal_piket` - Cleaning Duty Schedule
```sql
id, hari (1-5), user_id, timestamps
```

---

## 🎨 UI/UX Guidelines

### Design System: Coastal Theme

**Brand Colors** (defined in CSS `:root`):
```css
--ocean: #0077be;        /* Primary blue */
--ocean-dk: #005a8e;     /* Darker ocean */
--ocean-deep: #001f3f;   /* Deep sea navy */
--turq: #40E0D0;         /* Turquoise accent */
--seafoam: #93E9BE;      /* Light green accent */
--sandy: #F5DEB3;        /* Sand/beige */
--coral: #FF7B6B;        /* Coral red */
--cream: #FFF9F0;        /* Off-white background */
```

**Typography**:
- **Headings**: `Baloo 2` (playful, rounded) → var(--fd)
- **Body**: `Nunito` (clean, readable) → var(--fb)

**Icon Strategy**:
- Use emoji icons: 🌊 (waves), ⚓ (anchor), 🐚 (shell), 🐠 (fish), etc.
- NO icon libraries (FontAwesome, Heroicons, etc.)
- Keeps design lightweight and playful

### Responsive Breakpoints

```css
/* Desktop-first approach */
@media (max-width: 1024px) { /* Tablet */ }
@media (max-width: 768px)  { /* Mobile */ }
```

**Navbar Behavior** (CRITICAL):
- **Desktop (>1024px)**: Vertical sidebar (left side, ~240px wide)
- **Tablet (768-1024px)**: Bottom navigation bar (fixed, 70px height)
- **Mobile (<768px)**: Bottom navigation bar (fixed, 70px height)

### Animation Principles

- **Entrance**: Slide up + fade in (GSAP preferred)
- **Hover**: Slight translate + scale (transform)
- **Loading**: Wave/ocean-themed spinners
- **Transitions**: 0.3s ease-out (fast but smooth)

---

## 🔄 Common Workflows

### 1. Adding a New User (Wali Kelas Only)

**Route**: `POST /users` → `UserController@store`

**Steps**:
1. Validate input (username unique, role valid)
2. Hash password with `bcrypt()`
3. Create User record
4. Initialize Leaderboard entry (points=0, streak=0)
5. Log activity: "Menambahkan user baru: [nama]"
6. Return JSON success response

**IMPORTANT**: Auto-generate NIS if not provided (format: `XPPLGC-{sequential_number}`)

### 2. Recording Attendance (Sekretaris/Wali Kelas)

**Route**: `POST /kehadiran/store` → `KehadiranController@store`

**Steps**:
1. Check if attendance already exists for user+date (UNIQUE constraint)
2. Create Kehadiran record
3. Update Leaderboard:
   - If `status = 'hadir'`: increment `current_streak`, add points (+10)
   - If missed (alpha): reset `current_streak` to 0, deduct points (-5)
4. Log activity for the student
5. Return success

**Bulk Input**: Use `POST /kehadiran/bulk` to mark all students at once (default status: hadir)

### 3. Managing Kas Payments (Bendahara/Wali Kelas)

**Daily Payment Flow**:
1. System auto-generates `kas_payments` records for all students each day (CRON or manual trigger)
2. Bendahara checks off payments: `PUT /kas/pembayaran/{id}` with `status = 'lunas'`
3. On verification:
   - Update KasPayment: `status = 'lunas'`, `verified_by = Auth::id()`, `verified_at = now()`
   - Update Leaderboard: add points (+5)
   - Update SaldoKas: increment `total_pemasukan`

**Adding Income/Expense**:
- `POST /kas/pemasukan`: Record extra income (e.g., donations)
- `POST /kas/pengeluaran`: Record expenses (attach `bukti` file if available)
- Both update `SaldoKas` table for daily balance tracking

### 4. Leaderboard Calculation (Automated)

**Trigger**: Runs daily at 00:01 (scheduled job in `app/Console/Kernel.php`)

**Algorithm** (simplified):
```php
// LeaderboardService::calculateTiers()

foreach (User::where('role', 'siswa')->get() as $user) {
    $leaderboard = $user->leaderboard;
    
    // Calculate points
    $attendance_points = $leaderboard->current_streak * 10;
    $payment_points = KasPayment::where('user_id', $user->id)
        ->where('status', 'lunas')->count() * 5;
    $violation_deduction = Pelanggaran::where('user_id', $user->id)->sum('poin');
    
    $total = $attendance_points + $payment_points - $violation_deduction;
    $leaderboard->update(['total_points' => $total]);
}

// Rank by points, assign tiers
$ranked = Leaderboard::orderBy('total_points', 'desc')->get();
// Top 10%: sultan, 11-30%: kaya, 31-70%: normal, 71-100%: kelas_bawah
```

---

## 🚀 Development Commands

```bash
# Start development environment (runs all services concurrently)
composer run dev
# → php artisan serve + queue:work + vite + logs tail

# Run all tests
composer run test

# Run specific test
php artisan test --filter=UserControllerTest

# Database operations
php artisan migrate:fresh --seed  # Reset DB with seed data
php artisan db:seed --class=JadwalSeeder  # Seed specific data

# Code formatting
./vendor/bin/pint  # Auto-fix PSR-12 violations

# Clear caches
php artisan optimize:clear
```

---

## 🧪 Testing Guidelines

### Test Structure

Tests located in `tests/Feature/`:
- `AuthTest.php` - Login/logout flows
- `KasTest.php` - Finance module
- `KehadiranTest.php` - Attendance tracking
- `LeaderboardTest.php` - Point calculation logic
- `PermissionTest.php` - Role-based access

### Writing Tests

```php
// Example: Testing kas payment verification
public function test_bendahara_can_verify_payment()
{
    $bendahara = User::factory()->create(['role' => 'bendahara']);
    $student = User::factory()->create(['role' => 'siswa']);
    $payment = KasPayment::create([
        'user_id' => $student->id,
        'tanggal' => today(),
        'jumlah' => 2000,
        'status' => 'belum',
    ]);

    $response = $this->actingAs($bendahara)
        ->putJson("/kas/pembayaran/{$payment->id}", [
            'status' => 'lunas'
        ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('kas_payments', [
        'id' => $payment->id,
        'status' => 'lunas',
        'verified_by' => $bendahara->id,
    ]);
}
```

**IMPORTANT**: Always use `->actingAs()` to simulate authenticated users with specific roles.

---

## 🛡️ Security Considerations

### Input Validation

**ALWAYS validate** using Laravel's Form Request or inline validation:

```php
$request->validate([
    'jumlah' => 'required|numeric|min:0|max:9999999',
    'keterangan' => 'required|string|max:255',
    'tanggal' => 'required|date|before_or_equal:today',
]);
```

### SQL Injection Prevention

- ✅ Use Eloquent ORM or Query Builder (parameterized queries)
- ❌ NEVER use raw SQL with user input concatenation

```php
// ✅ GOOD
User::where('username', $request->username)->first();

// ❌ BAD (vulnerable to SQL injection)
DB::select("SELECT * FROM users WHERE username = '{$request->username}'");
```

### XSS Prevention

- Blade automatically escapes output: `{{ $variable }}`
- Use `{!! $html !!}` ONLY for trusted HTML (e.g., editor content)
- Sanitize file uploads: validate MIME types, store outside public folder

### CSRF Protection

- All POST/PUT/DELETE routes require CSRF token
- Blade: `@csrf` directive in forms
- AJAX: Include `X-CSRF-TOKEN` header (meta tag provided)

### Rate Limiting

Login route has throttle: max 5 attempts per minute per IP (see `AuthController`)

---

## 🐛 Troubleshooting

### Common Issues

**"Session not persisting after login"**
- Check `SESSION_DRIVER=database` in `.env`
- Run `php artisan session:table` + `php artisan migrate`
- Verify session cookie settings (same-site, secure flags)

**"Role middleware not working"**
- Ensure middleware is registered in `bootstrap/app.php`:
  ```php
  $middleware->alias(['role' => CheckRole::class]);
  ```
- Check session has `user_role` key (set in AuthController)

**"Leaderboard points not updating"**
- Check if scheduled job is running: `php artisan schedule:work` (dev) or cron (prod)
- Manually trigger: `php artisan leaderboard:calculate`

**"File upload fails (bukti pengeluaran)"**
- Verify `storage/app/public` is linked: `php artisan storage:link`
- Check file permissions: `chmod -R 775 storage bootstrap/cache`

### Debug Logging

Enable query logging in `AppServiceProvider`:
```php
DB::listen(function ($query) {
    Log::info($query->sql, $query->bindings);
});
```

Check logs: `storage/logs/laravel.log` or `debug-*.log`

---

## 📦 Deployment Checklist

```bash
# 1. Environment
cp .env.example .env
php artisan key:generate

# 2. Database
php artisan migrate --force
php artisan db:seed --force

# 3. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Storage
php artisan storage:link
chmod -R 775 storage bootstrap/cache

# 5. Queue worker (systemd service)
php artisan queue:work --daemon

# 6. Scheduler (crontab)
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 💡 Predictive AI Features (Roadmap)

### 1. **Smart Attendance Predictions**
- **Goal**: Predict which students are likely to be absent tomorrow
- **Algorithm**: Analyze historical patterns (weekday trends, streak breaks, previous violations)
- **UI**: Dashboard widget showing "⚠️ High Risk: 3 students likely absent tomorrow"
- **Action**: Wali Kelas can send preventive reminder messages

### 2. **Financial Forecasting**
- **Goal**: Predict monthly kas balance based on spending patterns
- **Algorithm**: Linear regression on past 3 months of expenses
- **UI**: Graph showing "Projected Balance: Rp 150,000 by end of month"
- **Alert**: "🚨 Warning: Budget will run out by week 3 if spending continues"

### 3. **Student Behavior Analysis**
- **Goal**: Early warning system for at-risk students
- **Inputs**: Attendance drops, payment delays, increasing violations
- **Output**: Risk score (0-100) per student
- **UI**: "🔴 Critical: 2 students need intervention" on dashboard
- **Action**: Auto-generate report for Wali Kelas to review

### 4. **Optimal Piket Scheduling**
- **Goal**: AI suggests fair piket rotation based on past absences
- **Algorithm**: Balance load across students, avoid consecutive weeks for same person
- **UI**: "✨ AI Suggestion: Swap Ridho & Alfino this week (Ridho has 3-week streak)"

### 5. **Leaderboard Tier Forecasting**
- **Goal**: Show students "If you attend next 5 days, you'll reach 'Kaya' tier"
- **Algorithm**: Simulate point additions based on consistent behavior
- **UI**: Progress bar + motivational message on profile page

### 6. **Message Priority Classifier**
- **Goal**: Auto-tag messages from Wali Kelas as Urgent/Normal/FYI
- **Algorithm**: NLP keyword detection (deadline, segera, penting, etc.)
- **UI**: 🔴/🟡/🟢 indicator on message list

### 7. **Payment Reminder Optimizer**
- **Goal**: Send reminders at optimal times (when students most likely to pay)
- **Algorithm**: Analyze payment time patterns (e.g., most pay after 10 AM)
- **Action**: Auto-schedule WhatsApp/SMS reminders via queue

---

## 📝 Code Conventions

### Naming Conventions

- **Controllers**: Singular noun + `Controller` (e.g., `KasController`, not `KassController`)
- **Models**: Singular noun (e.g., `User`, `Kehadiran`)
- **Routes**: Plural resource names (`/users`, `/kas`, `/kehadiran`)
- **Views**: Snake_case (`dashboard/kas/index.blade.php`)
- **CSS Classes**: Kebab-case (`btn-login`, `card-header`)
- **JavaScript**: camelCase (`currentStreak`, `calculatePoints`)

### Comment Standards

```php
/**
 * Calculate leaderboard points for a student
 * 
 * @param User $user The student to calculate points for
 * @param Carbon $startDate Start of calculation period
 * @param Carbon $endDate End of calculation period
 * @return int Total points earned
 */
public function calculatePoints(User $user, Carbon $startDate, Carbon $endDate): int
```

### Blade Best Practices

```blade
{{-- Use components for reusable UI --}}
<x-alert type="success" message="Data berhasil disimpan!" />

{{-- Escape by default --}}
<p>{{ $user->nama_lengkap }}</p>

{{-- Only use raw output for trusted content --}}
<div>{!! $sanitizedHtml !!}</div>

{{-- Prefer @auth/@guest over @if(Auth::check()) --}}
@auth
    <p>Welcome, {{ Auth::user()->nama_lengkap }}</p>
@endauth
```

---

## 🔗 External Dependencies

**Minimal third-party packages** to reduce complexity:

| Package | Purpose | Docs |
|---------|---------|------|
| `laravel/framework` | Core framework | [laravel.com/docs](https://laravel.com/docs) |
| `laravel/tinker` | REPL for debugging | Built-in |
| `phpunit/phpunit` | Testing | Built-in |
| None | Frontend (vanilla JS) | N/A |

**NO external APIs** (all features self-contained).

---

## 🎓 Learning Resources

For new contributors:
1. Read Laravel docs: [laravel.com/docs/11.x](https://laravel.com/docs/11.x)
2. Understand Eloquent ORM: [laravel.com/docs/11.x/eloquent](https://laravel.com/docs/11.x/eloquent)
3. Review Blade templating: [laravel.com/docs/11.x/blade](https://laravel.com/docs/11.x/blade)
4. Study this codebase's `UserController` + `KasController` as reference implementations

---

## 📞 Support

Questions? Check:
1. This CLAUDE.md file (you're reading it!)
2. Inline code comments in controllers
3. Database migration files for schema reference
4. Test files for usage examples

**Project Lead**: Ridho (GitHub: @Idooo2202)

---

*Last updated: 2026-05-05*