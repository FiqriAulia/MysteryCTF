-- Mystery Corporation Database Backup
-- Generated: 2024-01-15 10:30:00
-- WARNING: This backup contains sensitive information

-- Admin credentials (for emergency access)
-- Username: admin
-- Password: admin123
-- 
-- Database connection details:
-- Host: db
-- Username: root  
-- Password: mystery123
-- Database: mystery_corp

-- FLAG for finding backup files
-- FLAG{backup_files_exposed_g0bust3r}

-- Emergency SQL commands:
-- SELECT * FROM users WHERE role = 'admin';
-- SELECT * FROM secrets WHERE access_level = 3;
-- 
-- Vulnerable endpoints discovered:
-- /dashboard.php?search=' UNION SELECT 1,username,password,role,5 FROM users--
-- 
-- XSS payload locations:
-- Employee notes field in database
-- Search functionality without proper sanitization

CREATE TABLE IF NOT EXISTS emergency_access (
    id INT PRIMARY KEY,
    access_code VARCHAR(100),
    description TEXT
);

INSERT INTO emergency_access VALUES 
(1, 'EMERGENCY_ADMIN_2024', 'Emergency admin access code'),
(2, 'BACKUP_RESTORE_KEY', 'Database restoration key'),
(3, 'FLAG{emergency_backup_access}', 'Emergency flag for backup discovery');

-- End of backup file