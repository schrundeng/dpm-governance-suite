# DPM Legislative Archive (BALeg)

Public statutory repository and document explorer for the Student Representative Council (DPM - Badan Legislasi). Features hierarchical statutory browsing, an inline PDF.js reader with dark-mode invert, and self-referential document relationship tracking.

## Architecture

- Backend: PHP 8.2 custom MVC engine (front controller routing, PDO abstraction)
- Frontend: Vanilla JavaScript, responsive CSS, PDF.js inline renderer
- Database: MySQL
- Web Server: Apache with `mod_rewrite` enabled
- Containerization: Multi-container Docker Compose environment

## Key Capabilities

### Document Exploration
- File-system style hierarchical browsing: Category -> Year -> Documents.
- Dynamic split-pane view with draggable divider handle separating the catalog index and inline PDF viewer.
- Integrated PDF.js iframe reader with automatic color-inversion filters for dark mode.
- Slide-out metadata drawer detailing statute numbers, promulgation years, descriptions, and linked legal relationships.

### Legislative Relationship Graph
- Cross-references statutory relationships (repealing / mencabut, amended by / diubah oleh).
- Graph-linked documents allow instant navigation across legislative revisions.

### Administrative Management
- CRUD administration for statutory documents and legal categories.
- Document pinning for featured legislative landing placement.
- Automated database migration and default administrative provisioning on initialization.

## Directory Layout

```text
legislative/
├── .env.example       # Template environment variables
├── docker-compose.yml # Container definitions (Apache, MySQL, phpMyAdmin)
├── Dockerfile         # PHP 8.2 Apache runtime
├── uploads/           # Persistent volume for statutory PDF documents
└── html/              # Web root
    ├── config/        # Database connection and migration scripts
    ├── pdfjs/         # PDF.js library assets
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
   - Public Portal: http://localhost:8081
   - phpMyAdmin: http://localhost:8082
