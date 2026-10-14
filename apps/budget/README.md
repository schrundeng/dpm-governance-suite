# DPM Budget System

Internal document review and proposal lifecycle pipeline designed for the Student Representative Council (DPM - Badan Anggaran). Handles submission intake, token-verified accounts, administrative evaluation loops, and document archival.

## Architecture

- Backend: PHP 8.2 custom MVC engine (front controller routing, PDO abstraction)
- Database: MySQL
- Web Server: Apache with `mod_rewrite` enabled
- Mailer: PHPMailer for token-based authentication and status notifications
- Containerization: Multi-container Docker Compose environment

## Workflows

### Client Intake
- User registration requires token-based email activation prior to first login.
- Submissions accept PDF and image attachments up to 10MB with structured tracking identifiers (`DPM-YYYY-XXXX`).
- Users track processing stages in real time: `dikirim` -> `in-review` -> `diterima` / `ditolak`.
- Approved documents provide downloadable reviewer feedback; rejected submissions display required revisions.

### Administrative Review
- Filter and inspect incoming proposals by commission and submission state.
- Locking mechanism sets active files to `in-review` during examination.
- Resolution allows feedback file attachments on approval or mandatory revision logs on rejection.
- Automated archival flags processed records older than 14 days, with administrative restore and batch deletion.

## Directory Layout

```text
budget/
├── .env.example       # Template environment variables
├── docker-compose.yml # Container definitions (Apache, MySQL, phpMyAdmin)
├── Dockerfile         # PHP 8.2 Apache runtime
├── uploads/           # Local attachment directory
└── html/              # Web root
    ├── config/        # Database connection and migrations
    ├── public/        # Front controller (index.php) and static assets
    └── app/
        ├── controllers/ # Routing handlers
        ├── models/      # Persistence layer
        └── views/       # PHP view templates
```

## Local Development

1. Create a local environment file from the template:
   ```bash
   cp .env.example .env
   ```

2. Start the local containers:
   ```bash
   docker compose up -d --build
   ```

3. Endpoints:
   - Application: http://localhost:8085
   - Login Portal: http://localhost:8085/login
   - Default Admin Credentials: `admin@example.com` / `admin123`
   - Default User Credentials: `user@example.com` / `admin123`
   - phpMyAdmin: http://localhost:8086
