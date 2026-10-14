# DPM Governance Suite

Consolidated institutional monorepo for the Student Representative Council (Dewan Perwakilan Mahasiswa). Unifies civic communication, statutory document exploration, and internal fiscal approval pipelines into a containerized ecosystem.

## Architecture & Ecosystem Overview

```text
dpm-governance-suite/
└── apps/
    ├── portal/        # Public institutional landing & student feedback intake
    ├── legislative/   # Legislative statute repository & split-pane PDF explorer
    └── budget/        # Budget proposal intake, evaluation pipeline & archival
```

### Subsystems

1. **Portal (`apps/portal`)**
   - Public-facing institutional landing portal detailing council commissions, active legislation, and organizational pillars.
   - Student feedback and civic aspiration intake with validation.
   - Administrative session dashboard for triaging and processing inbound feedback.
   - Stack: Vite, Vanilla JavaScript (ES6+), Tailwind CSS, PHP REST API, MySQL.

2. **Legislative Archive (`apps/legislative`)**
   - Statutory repository organizing student regulations, decrees, and institutional charters.
   - Interactive split-pane interface with draggable divider and integrated PDF viewing.
   - Administrative statute management: document categorization, metadata tagging, pinning, and file revisions.
   - Stack: PHP 8.2 MVC, Vanilla CSS, MySQL, PDF.js.

3. **Budget Pipeline (`apps/budget`)**
   - Fiscal governance workflow managing student organization proposal submissions (RAB) and accountability reports (LPJ).
   - Real-time status progression (`dikirim` -> `in-review` -> `diterima` / `ditolak`).
   - Administrative evaluation system featuring downloadable reviewer feedback attachments and mandatory revision logs.
   - Automated 14-day archiving pipeline with administrative restoration capabilities.
   - Stack: PHP 8.2 MVC, Vanilla CSS, MySQL, PHPMailer.

## Local Port Allocation

To support simultaneous local execution, services are mapped across distinct host ports:

| Service | Subsystem | Host Port | Database Port | phpMyAdmin Port |
| :--- | :--- | :--- | :--- | :--- |
| Portal (Backend) | `apps/portal` | `8000` | `3309` | `8087` |
| Portal (Frontend) | `apps/portal/frontend` | `3000` | — | — |
| Legislative | `apps/legislative` | `8081` | `3310` | `8082` |
| Budget | `apps/budget` | `8085` | `3308` | `8086` |

## Default Authentication Credentials

Pre-seeded accounts provisioned on initial database initialization:

| Subsystem | Role | Login URL | Identifier (Username / Email) | Password |
| :--- | :--- | :--- | :--- | :--- |
| Portal | Administrator | `http://localhost:8000/admin/login` | `admin` | `admin123` |
| Legislative | Administrator | `http://localhost:8081/login` | `admin@dpm.org` | `admin` |
| Budget | Administrator | `http://localhost:8085/login` | `admin@example.com` | `admin123` |
| Budget | Delegasi (User) | `http://localhost:8085/login` | `user@example.com` | `admin123` |

## Quickstart

Each application is containerized with Docker Compose. Refer to individual directory documentation for service-specific commands and seeding instructions:

- [Portal Documentation](file:///apps/portal/README.md)
- [Legislative Documentation](file:///apps/legislative/README.md)
- [Budget Documentation](file:///apps/budget/README.md)
