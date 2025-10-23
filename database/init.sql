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
('sys_administrator', 'MyS73ry_C0rp_2024!@#', 'admin'),
('backup_admin', 'B4ckup_S3cur3_P@ssw0rd', 'admin'),
('security_chief', 'Ch13f_S3c_M@st3r_K3y', 'admin');

-- Flag 2: Hidden in employee data  
INSERT INTO employees (name, department, salary, secret_note) VALUES 
('John Smith', 'IT', 75000.00, 'Working on server maintenance this week'),
('Jane Doe', 'HR', 65000.00, 'Remember to check robots.txt for web crawler policies'),
('Bob Wilson', 'Finance', 80000.00, 'Quarterly reports due next Friday'),
('Alice Brown', 'Security', 90000.00, 'Security audit scheduled for next week'),
('Mike Johnson', 'IT', 82000.00, 'Network monitoring logs stored in /network-logs/ directory'),
('Sarah Davis', 'Marketing', 68000.00, 'New campaign launch scheduled for next month'),
('Tom Wilson', 'DevOps', 95000.00, 'Docker containers running on non-standard ports'),
('Lisa Chen', 'Security', 88000.00, 'Backup files should be secured properly'),
('David Brown', 'IT', 79000.00, 'Database maintenance completed successfully'),
('Emma Taylor', 'HR', 71000.00, 'Employee onboarding process updated'),
('James Miller', 'Finance', 73000.00, 'Budget planning for Q2 in progress'),
('Maria Garcia', 'Marketing', 69000.00, 'Social media strategy review completed'),
('Robert Johnson', 'IT', 81000.00, 'Server migration project on track'),
('Jennifer Lee', 'HR', 67000.00, 'Performance reviews scheduled this month'),
('Michael Davis', 'DevOps', 92000.00, 'CI/CD pipeline optimization in progress'),
('Ashley Wilson', 'Marketing', 70000.00, 'Brand guidelines update completed'),
('Christopher Brown', 'Finance', 76000.00, 'Financial forecasting models updated'),
('Amanda Jones', 'Security', 89000.00, 'Penetration testing results under review'),
('Daniel Garcia', 'IT', 78000.00, 'Network infrastructure upgrade planned'),
('Jessica Miller', 'HR', 72000.00, 'Training program development ongoing'),
('Matthew Taylor', 'DevOps', 94000.00, 'Kubernetes cluster deployment successful'),
('Lauren Davis', 'Marketing', 71000.00, 'Customer engagement metrics analysis'),
('Ryan Anderson', 'Security', 91000.00, 'XSS payload: <script>alert("FLAG{xss_found_h3r3}")</script>'),
('Stephanie Wilson', 'Finance', 74000.00, 'Expense reporting system upgrade'),
('Kevin Brown', 'IT', 80000.00, 'Backup system verification completed'),
('Nicole Johnson', 'HR', 68000.00, 'Employee satisfaction survey results'),
('Brandon Lee', 'DevOps', 93000.00, 'Monitoring dashboard improvements'),
('Samantha Garcia', 'Marketing', 69500.00, 'Content strategy roadmap finalized'),
('Justin Miller', 'Security', 87000.00, 'Security policy documentation update'),
('Rachel Taylor', 'Finance', 75500.00, 'Audit preparation documentation ready');

-- Flag 3: In secrets table (requires admin access)
INSERT INTO secrets (title, content, access_level) VALUES 
('Company Policy', 'Standard company policies and procedures', 1),
('Financial Reports', 'Q3 financial data and projections', 2),
('Top Secret Project', 'Classified project details - The final piece is hidden in /admin/vault.php', 3),
('Emergency Contacts', 'Contact information for emergencies', 1),
('Network Configuration', 'Server configs stored in /config/network.conf', 2),
('Database Credentials', 'Production DB access: db_user:FLAG{backup_files_exposed_g0bust3r}', 3),
('API Keys', 'Third-party service integration keys', 2),
('Security Logs', 'Incident response logs from last quarter', 2),
('Backup Procedures', 'Daily backup scripts and recovery procedures', 1),
('Admin Passwords', 'Administrative account credentials - FLAG{admin_access_s3cr3t_pr0j3ct}', 3),
('Vendor Contracts', 'External service provider agreements', 1),
('Compliance Reports', 'Regulatory compliance documentation', 2),
('Disaster Recovery', 'Business continuity and disaster recovery plans', 2),
('Source Code', 'Internal application source code repository access', 3),
('Encryption Keys', 'Master encryption keys for data protection', 3);

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