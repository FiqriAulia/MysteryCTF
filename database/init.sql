CREATE DATABASE IF NOT EXISTS mystery_corp;
USE mystery_corp;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    department VARCHAR(50),
    salary DECIMAL(10,2),
    secret_note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE secrets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100),
    content TEXT,
    access_level INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Flag 1: SQL Injection vulnerability (weak password)
INSERT INTO users (username, password, role) VALUES 
('guest', 'guest', 'user'),
('detective', 'sherlock', 'investigator'),
('admin', 'admin123', 'admin');

-- Flag 2: Hidden in employee data  
INSERT INTO employees (name, department, salary, secret_note) VALUES 
('John Smith', 'IT', 75000.00, 'Nothing suspicious here'),
('Jane Doe', 'HR', 65000.00, 'Check network traffic logs for admin activities'),
('Bob Wilson', 'Finance', 80000.00, 'Check the hidden service on port 8081'),
('Alice Brown', 'Security', 90000.00, 'XSS payload: <script>alert("FLAG{xss_found_h3r3}")</script>');

-- Flag 3: In secrets table (requires admin access)
INSERT INTO secrets (title, content, access_level) VALUES 
('Company Policy', 'Standard company policies and procedures', 1),
('Financial Reports', 'Q3 financial data and projections', 2),
('Top Secret Project', 'Classified project details - The final piece is hidden in /admin/vault.php', 3),
('Emergency Contacts', 'Contact information for emergencies', 1);

-- Create a view that might be useful for enumeration
CREATE VIEW employee_summary AS 
SELECT name, department, 
       CASE WHEN salary > 80000 THEN 'High' ELSE 'Standard' END as salary_grade
FROM employees;

-- Create flag submissions table for CTF scoring
CREATE TABLE flag_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    team_name VARCHAR(100) NOT NULL,
    flag_content VARCHAR(255) NOT NULL,
    points INT DEFAULT 0,
    flag_name VARCHAR(100),
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_team_flag (team_name, flag_content)
);