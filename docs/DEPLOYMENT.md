# 🚀 Mystery Corporation CTF - Deployment Guide

## 📋 Quick Start

### Prerequisites
- Docker & Docker Compose
- Git
- 4GB+ RAM
- 10GB+ disk space

### 🎯 Local Deployment (Single User)
```bash
# Clone repository
git clone https://github.com/FiqriAulia/MysteryCTF.git
cd MysteryCTF

# Setup environment
./setup.sh

# Start CTF (Advanced mode - no hints)
./start.sh
```

### 🌐 Network Deployment (Multi-User)
```bash
# Setup for multiple participants
./start-network.sh

# Access admin panel (if needed)
# Default: admin123 (check .env file)
```

## 🎮 CTF Configuration

### Difficulty Levels
- **Advanced** (Default): No hints, realistic challenge
- **Intermediate**: Limited hints for guidance  
- **Beginner**: Full hints and step-by-step guidance

### Admin Controls
Access admin dashboard at `/admin/admin_dashboard.php` with password from `.env` file.

**Available Controls:**
- Set global difficulty level
- Reset team progress
- Monitor real-time activity
- View comprehensive statistics

## 🏁 Flag Overview

| Flag | Challenge Type | Difficulty | Points |
|------|---------------|------------|---------|
| `FLAG{hidden_service_discovered_8081}` | Reconnaissance | Easy | 100 |
| `FLAG{backup_files_exposed_g0bust3r}` | Directory Enum | Easy | 150 |
| `FLAG{sql_injection_master_b4d1c0d3}` | SQL Injection | Medium | 200 |
| `FLAG{xss_found_h3r3}` | XSS Discovery | Medium | 200 |
| `FLAG{admin_access_s3cr3t_pr0j3ct}` | Privilege Escalation | Hard | 250 |
| `FLAG{vault_master_mystery_solved_c0mpl3t3}` | Final Challenge | Hard | 300 |

**Total Points: 1200**

## 🔧 Troubleshooting

### Common Issues

**Database Connection Failed**
```bash
# Reset database
./scripts/reset-db.sh

# Check container status
docker compose ps
```

**Port Already in Use**
```bash
# Stop existing containers
docker compose down

# Check port usage
netstat -tulpn | grep :8080
```

**Permission Denied**
```bash
# Fix script permissions
chmod +x *.sh scripts/*.sh
```

### Monitoring & Logs
```bash
# View container logs
docker compose logs web
docker compose logs db

# Monitor system resources
./scripts/monitor.sh

# Test database connection
php scripts/test-db.php
```

## 🛡️ Security Considerations

### Production Deployment
- Change default passwords in `.env`
- Use HTTPS with proper certificates
- Implement rate limiting
- Monitor for actual attacks
- Isolate from production networks

### Network Security
- Use dedicated VLAN for CTF
- Implement firewall rules
- Monitor network traffic
- Log all activities

## 📊 Monitoring & Analytics

### Real-time Monitoring
- Live leaderboard at `/realtime-leaderboard.php`
- Admin dashboard for comprehensive stats
- Flag submission tracking
- Team progress monitoring

### Performance Metrics
- Response times
- Database query performance
- Container resource usage
- Network bandwidth utilization

## 🎓 Educational Objectives

### Learning Outcomes
1. **Reconnaissance Techniques**
   - Port scanning with nmap
   - Directory enumeration with gobuster
   - Information gathering from public sources

2. **Web Application Security**
   - SQL injection identification and exploitation
   - Cross-site scripting (XSS) discovery
   - Authentication bypass techniques

3. **Digital Forensics**
   - Network traffic analysis with Wireshark
   - Log file examination
   - Evidence correlation and timeline reconstruction

4. **Privilege Escalation**
   - Horizontal and vertical privilege escalation
   - Admin panel discovery and access
   - Sensitive data extraction

### Skills Development
- Systematic penetration testing methodology
- Tool usage and command-line proficiency
- Critical thinking and problem-solving
- Documentation and reporting

## 📚 Additional Resources

### Recommended Tools
- **Reconnaissance**: nmap, gobuster, dirb
- **Web Testing**: Burp Suite, OWASP ZAP, curl
- **Network Analysis**: Wireshark, tcpdump, tshark
- **Database**: MySQL client, SQLmap

### Learning Materials
- OWASP Top 10 Web Application Security Risks
- NIST Cybersecurity Framework
- SANS Penetration Testing Methodology
- CEH (Certified Ethical Hacker) materials

---

**Happy Hacking! 🕵️‍♂️**

*Remember: This is an educational environment. Always obtain proper authorization before testing real systems.*