# DPM Portal System

Institutional landing portal and student feedback pipeline for the Student Representative Council (DPM). Provides a public-facing informational interface, civic aspiration intake, and an administrative review dashboard.

## Architecture

- Frontend: Vite, Vanilla JavaScript (ES6+), Tailwind CSS, Lucide Icons
- Backend: PHP native REST routing with session-based administrative authentication
- Database: MySQL with automated self-healing schema migration
- Containerization: Multi-container Docker Compose environment (Apache, MySQL, phpMyAdmin)

## Workflows

### Public Interface

- Responsive institutional portal detailing council commissions, active legislation, and organizational pillars.
- Interactive aspiration submission intake validating student identification (NIM), department, and feedback categorization.
- Real-time client-side submission feedback and status verification queries.

### Administrative Management

- Session-authenticated administrative portal for reviewing inbound student aspirations.
- Status management workflows: categorize, update review status (`pending`, `diproses`, `selesai`), and purge invalid records.
- Automated database self-initialization upon container startup.

## Directory Layout

```text
portal/
├── docker-compose.yml # Container definitions (Apache, MySQL, phpMyAdmin)
├── Dockerfile         # PHP Apache runtime
├── backend/           # API and administrative dashboard
│   ├── .env.example   # Backend environment template
│   ├── config.php     # Database initialization and PDO connection
│   ├── index.php      # Request router and API endpoints
│   ├── database.sql   # Schema definitions
│   └── views/         # Administrative views (login, dashboard)
└── frontend/          # Public landing portal
    ├── .env.example   # Frontend environment template
    ├── index.html     # Portal markup
    ├── package.json   # Build dependencies
    └── src/           # Styles and interactive scripts
```

## Local Development

### 1. Backend & Database (Docker)

1. Create a local backend environment file:

   ```bash
   cp backend/.env.example backend/.env
   ```

2. Start backend containers:

   ```bash
   docker compose up -d --build
   ```

3. Endpoints:
   - Backend API & Admin: http://localhost:8000
   - Administrative Login: http://localhost:8000/admin/login
   - Default Admin Credentials: `admin` / `admin123`
   - phpMyAdmin: http://localhost:8087

### 2. Frontend (Vite)

1. Navigate to the frontend directory and prepare environment:

   ```bash
   cd frontend
   cp .env.example .env
   pnpm install
   ```

2. Start the development server:

   ```bash
   pnpm run dev
   ```

3. Frontend URL: http://localhost:3000

## Credits & Attribution

- Original foundation and initial landing layout by [Nibras Abyan](https://github.com/nibrasabyan) ([DPM-UB-2026](https://github.com/nibrasabyan/DPM-UB-2026)).
- Re-architected, overhauled, and performance-optimized for the DPM Governance Suite by schrundeng.
