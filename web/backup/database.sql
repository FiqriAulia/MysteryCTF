-- Mystery Corporation Database Backup
-- Generated: 2024-01-15 10:30:00
-- Database: mystery_corp
-- 
-- WARNING: This backup contains sensitive information!
-- FLAG{backup_files_exposed_g0bust3r}
--

CREATE DATABASE IF NOT EXISTS mystery_corp;
USE mystery_corp;

-- Users table backup
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, password, role) VALUES 
('guest', 'guest', 'user'),
('detective', 'sherlock', 'investigator'),
('admin', 'admin123', 'admin');

-- Employees table backup
CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    department VARCHAR(50),
    salary DECIMAL(10,2),
    secret_note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO employees (name, department, salary, secret_note) VALUES 
('John Smith', 'IT', 75000.00, 'Nothing suspicious here'),
('Jane Doe', 'HR', 65000.00, 'Check network traffic logs for admin activities'),
('Bob Wilson', 'Finance', 80000.00, 'Check the hidden service on port 8081'),
('Alice Brown', 'Security', 90000.00, 'XSS payload found in notes');

-- Company secrets backup
CREATE TABLE secrets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100),
    content TEXT,
    access_level INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO secrets (title, content, access_level) VALUES 
('Company Policy', 'Standard company policies and procedures', 1),
('Financial Reports', 'Q3 financial data and projections', 2),
('Top Secret Project', 'Classified project details - Admin panel required', 3),
('Emergency Contacts', 'Contact information for emergencies', 1);

-- End of backup
-- Remember to secure this file!