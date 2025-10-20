# 🕵️ Mystery Corporation CTF

Selamat datang di **Mystery Corporation CTF** - sebuah challenge penetration testing yang menggabungkan berbagai teknik hacking dalam satu skenario misteri yang menarik!

## 🎯 Objective

Anda adalah seorang detective yang menyelidiki aktivitas mencurigakan di Mystery Corporation. Temukan semua flag yang tersembunyi dengan menggunakan berbagai teknik penetration testing.

## 🚀 Setup & Installation

### Prerequisites
- Docker & Docker Compose
- Nmap
- Gobuster
- Burp Suite atau tools web security lainnya

### Menjalankan CTF

#### Quick Start (GitHub)
```bash
# Clone repository
git clone https://github.com/FiqriAulia/MysteryCTF.git
cd MysteryCTF

# Setup environment
./setup.sh

# Start CTF
./start.sh
```

#### Single User (Local)
1. Clone repository dan masuk ke direktori
2. Jalankan setup: `./setup.sh`
3. Start CTF: `./start.sh`
4. Akses aplikasi di: `http://localhost:8080`

#### Multi-User (Network Access)
1. Untuk CTF yang dapat diakses dari laptop lain:
   ```bash
   ./start-network.sh
   ```

2. Script akan menampilkan IP address dan port yang dapat diakses peserta
3. Bagikan informasi target kepada peserta CTF
4. Berikan file `PARTICIPANT-GUIDE.md` kepada peserta

### Verifikasi Setup
```bash
# Check running containers
docker-compose ps

# Check logs if needed
docker-compose logs web
docker-compose logs db
```

## 🎮 How to Play

### 1. 🔍 Reconnaissance Phase
Mulai dengan reconnaissance untuk memahami target:

```bash
# Scan ports dengan nmap
nmap -p- localhost

# Directory enumeration dengan gobuster
gobuster dir -u http://localhost:8080 -w /usr/share/wordlists/dirb/common.txt

# Check robots.txt
curl http://localhost:8080/robots.txt
```

### 2. 💉 SQL Injection
- Login form vulnerable terhadap SQL injection
- Search functionality juga vulnerable
- Coba berbagai payload SQL injection

**Hints:**
- Username: `admin` Password: `admin123` (basic login)
- SQL Injection payload: `admin' OR '1'='1'-- `
- Union-based injection di search: `' UNION SELECT 1,2,3,4,5-- `

### 3. 🚨 Cross-Site Scripting (XSS)
- Employee notes field tidak di-sanitize
- Cari XSS payload di database

### 4. 🔐 Privilege Escalation
- Dapatkan akses admin
- Explore admin panel
- Akses company secrets

### 5. 🏆 Final Challenge
- Temukan security vault
- Collect semua flags

## 🏁 Flags to Collect

Terdapat beberapa flag yang harus dikumpulkan:

1. **FLAG{sql_injection_master_b4d1c0d3}** - SQL Injection mastery
2. **FLAG{xss_found_h3r3}** - XSS discovery
3. **FLAG{admin_access_s3cr3t_pr0j3ct}** - Admin panel access
4. **FLAG{hidden_service_discovered_8081}** - Hidden service discovery
5. **FLAG{backup_files_exposed_g0bust3r}** - Backup file discovery
6. **FLAG{vault_master_mystery_solved_c0mpl3t3}** - Final vault access

## 🛠️ Tools Recommended

### Reconnaissance
- **Nmap**: Port scanning
- **Gobuster**: Directory enumeration
- **Nikto**: Web vulnerability scanner

### Manual Testing (Recommended)
- **Browser Developer Tools**: Built-in F12 tools
- **Curl**: HTTP requests dan cookie management
- **Manual payload testing**: Understanding fundamentals

### Advanced Tools (Optional)
- **Burp Suite**: GUI web application testing
- **OWASP ZAP**: Free alternative to Burp Suite
- **SQLmap**: Automated SQL injection (for learning comparison)

## 📚 Learning Objectives

Setelah menyelesaikan CTF ini, Anda akan memahami:

1. **Reconnaissance Techniques**
   - Port scanning dengan nmap
   - Directory enumeration dengan gobuster
   - Information gathering

2. **SQL Injection**
   - Authentication bypass
   - Union-based injection
   - Database enumeration
   - Data extraction

3. **Cross-Site Scripting (XSS)**
   - Stored XSS
   - Payload crafting
   - Impact assessment

4. **Web Application Security**
   - Input validation issues
   - Authentication flaws
   - Authorization bypass
   - Information disclosure

5. **Methodology**
   - Systematic approach to penetration testing
   - Documentation and reporting
   - Chain exploitation techniques

## 🔧 Troubleshooting

### Container Issues
```bash
# Restart containers (use docker compose or docker-compose)
docker compose down  # or: docker-compose down
docker compose up -d # or: docker-compose up -d

# Check logs
docker compose logs -f web  # or: docker-compose logs -f web
docker compose logs -f db   # or: docker-compose logs -f db

# Reset database
docker compose down -v  # or: docker-compose down -v
docker compose up -d    # or: docker-compose up -d
```

### Network Access Issues
```bash
# Check if ports are open
nmap -p 8080,8081,3306 [HOST_IP]

# Test connectivity from participant machine
telnet [HOST_IP] 8080
curl http://[HOST_IP]:8080

# Check firewall (macOS)
sudo pfctl -sr | grep 8080

# Check firewall (Linux)
sudo ufw status
sudo iptables -L
```

### Monitoring Participants
```bash
# Real-time monitoring
./monitor.sh

# Check access logs
docker compose logs web | grep -E "(GET|POST)"  # or: docker-compose logs web

# Monitor database activity
docker compose exec db mysql -u root -pmystery123 -e "SHOW PROCESSLIST;"  # or: docker-compose exec
```

### Database Connection Issues
```bash
# Check if database is ready
docker-compose exec db mysql -u root -pmystery123 -e "SHOW DATABASES;"
```

### Port Conflicts
Jika port 8080 atau 3306 sudah digunakan, edit `docker-compose.yml`:
```yaml
ports:
  - "8081:80"  # Change from 8080:80
```

## 🎓 Difficulty Levels

- **Beginner**: Ikuti hints yang diberikan
- **Intermediate**: Minimal hints, fokus pada methodology
- **Advanced**: No hints, pure black-box testing

## 🤝 Contributing

Jika Anda menemukan bug atau ingin menambah challenge:
1. Fork repository
2. Buat branch baru
3. Submit pull request

## ⚠️ Disclaimer

CTF ini dibuat untuk tujuan edukasi dan pembelajaran cybersecurity. Jangan gunakan teknik yang dipelajari untuk aktivitas ilegal. Selalu dapatkan izin sebelum melakukan penetration testing pada sistem yang bukan milik Anda.

## 📞 Support

Jika mengalami kesulitan:
1. Baca dokumentasi dengan teliti
2. Check troubleshooting section
3. Gunakan hints yang tersedia di aplikasi
4. Practice makes perfect!

---

**Happy Hacking! 🕵️‍♂️🔍**

*Remember: The best hackers are those who think like detectives - methodical, persistent, and always curious!*