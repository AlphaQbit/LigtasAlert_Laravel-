# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**LigtasAlert** is a two-part emergency alert system:
- **Rescuee App** (`resources/views/rescuee.blade.php`) — Allows users to broadcast emergency alerts (Fire, Medical, Lockdown, Evacuation, Custom) from specific rooms/locations
- **Admin Console** (`resources/views/admin.blade.php`) — Real-time dashboard for responders to monitor, acknowledge, and resolve active alerts across facilities

The system is a Laravel 13 app. Alerts persist in SQLite via Eloquent.

> The pre-Laravel version (standalone `api/index.php` + JSON file storage + static HTML) is kept at `C:\Users\kyle\Desktop\original_backup\`. Its API contract is unchanged, so the frontend JS needed no edits. A snapshot of its `alerts.json` is vendored at `database/seeders/alerts.json` so seeding doesn't depend on that folder.

## Architecture

### Backend: Laravel (`routes/api.php`)

**Endpoints** (unchanged from the legacy API):
- `GET /api/alerts` — List all alerts, supports `?status=active` and `?facility=<id>` filters
- `POST /api/alerts` — Create new alert (from rescuee app)
- `PUT /api/alerts/{id}` — Update alert status (acknowledge/resolve)
- `POST /api/alerts/{id}/respond` — Add responder to alert
- `GET /api/stats` — Dashboard stats (active count, resolved today, etc.)
- `GET /api/facilities` — List available facilities

**Data Storage**: `alerts` table (SQLite) via the `Alert` model. Facility list is config-driven (`config/facilities.php`).

**Key classes**:
- `App\Models\Alert` — ULID keys (`ALT-…`), `responders` cast to array
- `App\Http\Controllers\AlertController` — index/store/update/respond
- `App\Http\Controllers\StatsController`, `FacilityController`
- `Database\Seeders\LegacyAlertsSeeder` — one-time import from `database/seeders/alerts.json`

### Frontend: Rescuee App (`test-api.html`)

**Flow**:
1. User selects alert type (4 preset buttons) + facility + room/location
2. Click "SEND ALERT" → POST to `/api/alerts`
3. Show toast confirmation
4. Reset form

**Key Functions**:
- `fetchAPI(endpoint, options)` — Generic fetch wrapper
- Event listeners on alert-type buttons to track `selectedType`

### Frontend: Admin Console (`admin.html`)

**Architecture**: Tab-based dashboard with three views.

**Tabs**:
1. **Dashboard** (active on load) — Stats cards, active incidents grid, activity log
2. **Contacts** — Emergency responders with online/offline status, facility cards
3. **History** — All alerts (past + active), response timeline

**Live Updates**: Polls `/api/alerts` and `/api/stats` every 500ms. Grid updates in place if data changes.

**Key Functions**:
- `loadStats()` — Fetch and display top-level numbers (active count, responders, resolved today)
- `loadAlerts()` — Fetch active alerts, render cards with Acknowledge/Resolve buttons
- `loadContacts()` — Render mock responder list (currently hardcoded data)
- `loadHistory()` — Fetch all alerts (active + resolved), display in reverse chronological order
- `loadTimeline()` — Mock response events (currently hardcoded)
- `switchTab(tabName)` — Hide/show tab content, trigger data load

**Acknowledge/Resolve**: Click button → PUT `/api/alerts/{id}` with new status → refresh grid.

## Development Workflow

### Running the System

**Start the server** (from project root):
```bash
php artisan serve
```

**First-time setup**:
```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
```

**URLs**:
- Rescuee app: `http://localhost:8000/rescuee` (also `/test-api.html`)
- Admin console: `http://localhost:8000/admin` (also `/admin.html`)
- API direct: `http://localhost:8000/api/stats`

**Run tests**:
```bash
php artisan test
```

### Testing a New Alert

1. Open rescuee app in one tab
2. Select alert type → facility → room
3. Click "SEND ALERT"
4. Refresh admin tab (or wait 500ms for auto-poll)
5. Alert appears in Dashboard

### Adding a New Alert Type

1. Add button to rescuee app (`resources/views/rescuee.blade.php`) with `data-type="NewType"`
2. Add styling class `.history-type.newtype` to admin (`resources/views/admin.blade.php`) if needed for History tab
3. Backend already handles any `type` string — no changes needed

### Adding Responder Management

**Current state**: Contacts tab shows mock responders. To wire to real data:
1. Create a `responders` table + model, and a `GET /api/responders` endpoint in `routes/api.php`
2. Replace hardcoded array in `loadContacts()` with `fetchAPI('responders')`
3. Update `StatsController::responders_on_duty` to count real on-duty responders (currently hardcoded to 12)

### Adding Database Persistence Beyond SQLite

To move to MySQL: set the `DB_*` env vars in `.env`. The Eloquent queries need no changes.

## Key Design Decisions

- **500ms polling** on admin: Fast enough for real-time feel without overloading. Can reduce if needed, but API calls add latency.
- **No WebSocket**: Polling is simpler and sufficient for a small facility. Upgrade when user count or latency becomes critical.
- **SQLite storage**: Zero-config for a small facility. Swap `DB_CONNECTION` for MySQL without changing API routes.
- **Frontend JS kept as-is**: The Laravel conversion targeted the backend only. The existing vanilla JS was moved to Blade untouched — the API contract is identical, so no JS changes were needed.
- **Mock data in Contacts/History tabs**: Placeholder until real data sources are added. Tab structure allows incremental wiring.
- **Relative API paths** (`/api`): Works from any page URL, survives redeployment.

## Files

```
.
├── app/
│   ├── Http/Controllers/
│   │   ├── AlertController.php      # alerts CRUD + respond
│   │   ├── FacilityController.php   # facility list
│   │   └── StatsController.php      # dashboard stats
│   └── Models/Alert.php
├── config/facilities.php            # facility list (config, not DB)
├── database/
│   ├── migrations/…_create_alerts_table.php
│   └── seeders/
│       ├── alerts.json       # snapshot of the 17 pre-Laravel alerts
│       └── LegacyAlertsSeeder.php
├── resources/views/
│   ├── admin.blade.php       # Admin dashboard (3 tabs)
│   └── rescuee.blade.php     # Rescuee app (alert sender)
└── routes/{api,web}.php
```

The pre-Laravel version lives outside the project at `C:\Users\kyle\Desktop\original_backup\`.

## Common Tasks

**View all active alerts**:
```bash
curl http://localhost:8000/api/alerts?status=active
```

**Send a test alert** (curl):
```bash
curl -X POST http://localhost:8000/api/alerts \
  -H "Content-Type: application/json" \
  -d '{"type":"Fire","facility_id":"building-a","room":"Room 214","recipients":45}'
```

**Mark alert as resolved**:
```bash
curl -X PUT http://localhost:8000/api/alerts/ALT-xyz123 \
  -H "Content-Type: application/json" \
  -d '{"status":"resolved"}'
```

**Check dashboard stats**:
```bash
curl http://localhost:8000/api/stats
```

## Notes for Future Work

- **Real responder data**: Replace mock contacts with API endpoint
- **Real timeline**: Derive from alert timestamps instead of hardcoding
- **Notification sounds**: Add audio alert when new incident lands (admin only, togglable)
- **Facility management**: UI to create/edit facilities and zones instead of hardcoded list
- **User roles**: Distinguish admin/responder/viewer permissions
- **SMS/email alerts**: Send notifications to responders when new alert fires
- **Alert history export**: CSV/PDF report of incidents by date range
