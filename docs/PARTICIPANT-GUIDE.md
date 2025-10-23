# 🕵️ Mystery Corporation CTF - Participant Guide

## 🎯 Mission Briefing
Selamat datang, Detective! Anda telah ditugaskan untuk menyelidiki **Mystery Corporation** yang diduga terlibat dalam aktivitas mencurigakan. Tugas Anda adalah mengumpulkan bukti digital dengan menemukan **6 flag tersembunyi**.

## 🌐 Target Information
**Repository:** https://github.com/FiqriAulia/MysteryCTF.git  
**Target IP:** `[AKAN_DIBERIKAN_OLEH_HOST]`  
**Ports:** 8080, 8081, 3306

## 🛠️ Tools yang Dibutuhkan

### Essential (Wajib):
- **Nmap** - Port scanning
- **Gobuster** - Directory enumeration  
- **Browser** dengan Developer Tools (Firefox/Chrome)
- **Curl** - HTTP requests

### Optional (Opsional):
- **Burp Suite/OWASP ZAP** - GUI web testing
- **SQLmap** - Automated SQL injection
- **Text editor** - Payload crafting

## 🎮 Getting Started

### Step 1: Reconnaissance 🔍
```bash
# Ganti TARGET_IP dengan IP yang diberikan host
export TARGET_IP=[TARGET_IP_FROM_HOST]

# Port scanning
nmap -p- $TARGET_IP
nmap -sV -p 8080,8081,3306 $TARGET_IP

# Directory enumeration
gobuster dir -u http://$TARGET_IP:8080 -w /usr/share/wordlists/dirb/common.txt

# Check for information disclosure
curl http://$TARGET_IP:8080/robots.txt
```

### Step 2: Web Application Analysis 🌐
```bash
# Access main application
firefox http://$TARGET_IP:8080

# Check source code for comments
curl http://$TARGET_IP:8080 | grep -i "<!--"

# Look for backup files
curl http://$TARGET_IP:8080/backup/
```

### Step 3: Vulnerability Testing 💉
- Test login form untuk SQL injection
- Analyze search functionality
- Look for XSS vulnerabilities
- Check for privilege escalation opportunities

## 🏁 Flags to Collect (6 Total)

1. **Reconnaissance Flag** - Temukan hidden service
2. **Directory Enumeration Flag** - Temukan backup files  
3. **SQL Injection Flag** - Bypass authentication
4. **XSS Flag** - Temukan stored XSS
5. **Admin Access Flag** - Akses admin panel
6. **Final Flag** - Akses security vault

## 💡 Hints & Tips

### SQL Injection Hints
```sql
-- Authentication bypass
admin' OR '1'='1'-- 

-- Union-based injection
' UNION SELECT 1,2,3,4,5-- 

-- Database enumeration
' UNION SELECT 1,table_name,3,4,5 FROM information_schema.tables-- 
```

### Common Credentials to Try
- admin/admin123
- guest/guest  
- detective/sherlock

### Directory Wordlists
- `/usr/share/wordlists/dirb/common.txt`
- `/usr/share/wordlists/dirbuster/directory-list-2.3-medium.txt`

### XSS Testing Payloads
```javascript
<script>alert('XSS')</script>
<img src=x onerror=alert('XSS')>
```

## 🎓 Scoring System

| Challenge | Points | Difficulty |
|-----------|--------|------------|
| Hidden Service Discovery | 100 | Easy |
| Backup File Discovery | 150 | Easy |
| SQL Injection | 200 | Medium |
| XSS Discovery | 200 | Medium |
| Admin Access | 250 | Hard |
| Final Vault | 300 | Hard |

**Total: 1200 Points**

## 📝 Submission Format

Ketika menemukan flag, catat dalam format:
```
Flag: FLAG{flag_content_here}
Method: [Teknik yang digunakan]
Location: [Dimana flag ditemukan]
Timestamp: [Waktu penemuan]
```

## ⚠️ Rules & Guidelines

### ✅ Allowed
- Port scanning dan enumeration
- Web application testing
- SQL injection testing
- XSS testing
- Privilege escalation
- Database queries

### ❌ Not Allowed
- DoS attacks atau flooding
- Brute force attacks yang excessive
- Modifying atau deleting data
- Attacking infrastructure host
- Sharing flags dengan peserta lain

## 🔧 Troubleshooting

### Connection Issues
```bash
# Test connectivity
ping $TARGET_IP
telnet $TARGET_IP 8080

# Check if services are running
nmap -p 8080,8081,3306 $TARGET_IP
```

### Tool Installation (Ubuntu/Debian)
```bash
# Install required tools
sudo apt update
sudo apt install nmap gobuster curl firefox

# Install Burp Suite Community
wget https://portswigger.net/burp/releases/download?product=community&type=Linux
```

### Tool Installation (macOS)
```bash
# Using Homebrew
brew install nmap gobuster

# Download Burp Suite from official website
```

## 🏆 Success Criteria

Untuk menyelesaikan CTF ini, Anda harus:
1. ✅ Menemukan semua 6 flags
2. ✅ Mendokumentasikan metodologi yang digunakan
3. ✅ Menjelaskan setiap vulnerability yang ditemukan
4. ✅ Memberikan rekomendasi perbaikan

## 📞 Support

Jika mengalami kesulitan teknis:
1. Pastikan koneksi network ke target IP
2. Verify tools sudah terinstall dengan benar
3. Check firewall settings
4. Hubungi host CTF untuk bantuan

## 🎉 Final Notes

- **Be methodical** - Ikuti systematic approach
- **Document everything** - Catat semua findings
- **Think like a detective** - Cari clues dan connections
- **Have fun!** - Enjoy the learning process

---

**Good luck, Detective! The mystery awaits... 🕵️♂️🔍**

*Remember: Real hackers are ethical hackers. Use these skills responsibly!*