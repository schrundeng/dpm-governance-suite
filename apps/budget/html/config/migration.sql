-- Database Migration: Magic Link Activation
ALTER TABLE users ADD COLUMN is_active TINYINT(1) DEFAULT 0;
UPDATE users SET is_active = 1; -- activate existing users

CREATE TABLE IF NOT EXISTS auth_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    used TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Database Migration: Cleanup unused kategori
ALTER TABLE dokumen DROP FOREIGN KEY dokumen_ibfk_2;
ALTER TABLE dokumen DROP COLUMN kategori_id;
DROP TABLE IF EXISTS kategori;

-- Database Migration: Document archiving
ALTER TABLE dokumen ADD COLUMN is_archived TINYINT(1) DEFAULT 0;
