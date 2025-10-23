# 🎮 Mystery Corporation CTF - Complete Setup Guide

## 🌐 Multi-User CTF Setup (Network Access)

### Host Setup (Server/Main Computer)

#### 1. Persiapan Environment
```bash
# Clone CTF from GitHub
git clone https://github.com/FiqriAulia/MysteryCTF.git
cd MysteryCTF

# Pastikan Docker dan Docker Compose terinstall
docker --version
docker-compose --version
```

#### 2. Jalankan CTF untuk Network Access
```bash
# Gunakan script network setup
./start-network.sh

# Atau manual dengan docker-compose
docker-compose up -d
```

#### 3. Dapatkan IP Address Host
```bash
# Linux/macOS
hostname -I
# atau
ifconfig | grep "inet " | grep -v 127.0.0.1

# Windows
ipconfig
```

#### 4. Verifikasi Services
```bash
# Check containers running
docker-compose ps

# Test local access
curl http://localhost:8080
curl http://localhost:8081

# Test network access (ganti dengan IP host)
curl http://[HOST_IP]:8080
```

### Participant Setup (Client Computers)

#### 1. Tools Installation

**Ubuntu/Debian:**
```bash
sudo apt update
sudo apt install nmap gobuster curl firefox-esr

# Optional advanced tools:
# Burp Suite Community (GUI web testing)
# OWASP ZAP (free alternative to Burp)
# sqlmap (automated SQL injection)
```

**macOS:**
```bash
# Install Homebrew jika belum ada
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Essential tools only
brew install nmap gobuster curl

# Optional: sqlmap for automated testing
brew install sqlmap
```

**Windows:**
```powershell
# Install chocolatey jika belum ada
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))

# Essential tools
choco install nmap gobuster curl firefox

# Alternative: Use Windows Subsystem for Linux (WSL)
```

#### 2. Target Configuration
```bash
# Set target IP (ganti dengan IP host yang diberikan)
export TARGET_IP=192.168.1.100  # Contoh IP

# Test connectivity
ping $TARGET_IP
nmap -p 8080,8081,3306 $TARGET_IP
```

#### 3. Access URLs
- **Main Application:** `http://[HOST_IP]:8080`
- **Hidden Service:** `http://[HOST_IP]:8081`
- **Flag Submission:** `http://[HOST_IP]:8080/submit.php`
- **Leaderboard:** `http://[HOST_IP]:8080/leaderboard.php`

## 🏆 CTF Management

### Admin Dashboard Access
- **URL:** `http://[HOST_IP]:8080/admin/admin_dashboard.php`
- **Password:** `ctf_admin_2024`

### Admin Features:
- 📊 Real-time leaderboard monitoring
- 👥 Team management and reset
- 📈 Flag completion statistics
- ⚡ Live activity feed
- 🔧 Competition controls

### Monitoring Commands
```bash
# Real-time monitoring
./monitor.sh

# Check logs
docker-compose logs -f web
docker-compose logs -f db

# Database access
docker-compose exec db mysql -u root -pmystery123 mystery_corp
```

## 🎯 Competition Flow

### 1. Pre-Competition Setup
```bash
# Host preparation
./start-network.sh

# Verify all services
curl http://[HOST_IP]:8080/submit.php
curl http://[HOST_IP]:8080/leaderboard.php
curl http://[HOST_IP]:8080/admin/admin_dashboard.php
```

### 2. Participant Briefing
- Bagikan `PARTICIPANT-GUIDE.md`
- Berikan target IP address
- Jelaskan rules dan scoring system
- Demo flag submission process

### 3. Competition Start
- Announce start time
- Monitor via admin dashboard
- Track progress in real-time

### 4. Competition End
- Announce final results
- Export final leaderboard
- Reset for next competition if needed

## 🔧 Troubleshooting

### Network Issues
```bash
# Check firewall (Linux)
sudo ufw status
sudo ufw allow 8080,8081,3306

# Check firewall (macOS)
sudo pfctl -sr | grep 8080

# Check Docker network
docker network ls
docker-compose ps
```

### Database Issues
```bash
# Reset database
docker-compose down -v
docker-compose up -d

# Manual database check
docker-compose exec db mysql -u root -pmystery123 -e "SHOW DATABASES;"
```

### Performance Issues
```bash
# Check system resources
docker stats

# Restart services
docker-compose restart

# Clean up Docker
docker system prune
```

## 📊 Scoring System

| Flag | Challenge | Points | Difficulty |
|------|-----------|--------|------------|
| 1 | Hidden Service Discovery | 100 | Easy |
| 2 | Backup File Discovery | 150 | Easy |
| 3 | SQL Injection Master | 200 | Medium |
| 4 | XSS Discovery | 200 | Medium |
| 5 | Admin Access | 250 | Hard |
| 6 | Vault Master | 300 | Hard |

**Total Maximum Points:** 1200

## 🎮 Competition Variants

### Beginner Mode
- Provide all hints
- Allow team collaboration
- Extended time limits

### Intermediate Mode
- Limited hints
- Individual competition
- Standard time limits

### Advanced Mode
- No hints provided
- Black-box testing only
- Competitive time pressure

## 🔒 Security Considerations

### Host Security
- Run CTF in isolated network/VLAN
- Monitor for excessive traffic
- Backup competition data
- Limit admin access

### Participant Guidelines
- No DoS attacks
- No infrastructure attacks
- Ethical hacking only
- Report any issues to admin

## 📝 Competition Management

### Before Competition
- [ ] Test all services
- [ ] Verify network access
- [ ] Prepare participant materials
- [ ] Set up monitoring
- [ ] Brief admin team

### During Competition
- [ ] Monitor admin dashboard
- [ ] Assist with technical issues
- [ ] Track progress
- [ ] Maintain fair play
- [ ] Document incidents

### After Competition
- [ ] Export final results
- [ ] Gather feedback
- [ ] Clean up environment
- [ ] Prepare debrief materials
- [ ] Archive competition data

## 🎉 Success Metrics

- **Participation Rate:** Number of active teams
- **Engagement:** Average flags per team
- **Completion Rate:** Teams solving all challenges
- **Time Distribution:** Challenge solving timeline
- **Feedback Scores:** Participant satisfaction

---

**Happy CTF Hosting! 🕵️♂️🎯**

*Remember: The goal is learning and fun. Create a supportive environment for all skill levels!*