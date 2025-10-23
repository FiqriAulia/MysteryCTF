# 🕵️ Mystery Corporation CTF

**A comprehensive Capture The Flag challenge focused on web application security, digital forensics, and penetration testing.**

[![Docker](https://img.shields.io/badge/Docker-Ready-blue)](https://docker.com)
[![Difficulty](https://img.shields.io/badge/Difficulty-Advanced-red)](#)
[![Flags](https://img.shields.io/badge/Flags-6-green)](#)
[![Points](https://img.shields.io/badge/Points-1200-yellow)](#)

## 🎯 Challenge Overview

**Scenario:** You are a Digital Forensics Investigator tasked with investigating a security incident at Mystery Corporation. Recent suspicious activities include unauthorized admin access, database anomalies, and network traffic to unknown services.

**Mission:** Uncover the full scope of the security breach, collect digital evidence, and document all findings using proper forensic methodology.

## 🚀 Quick Start

```bash
# Clone repository
git clone https://github.com/FiqriAulia/MysteryCTF.git
cd MysteryCTF

# Setup environment
./setup.sh

# Start CTF (Advanced mode - no hints)
./start.sh

# Access CTF at http://localhost:8080
```

## 🏁 Flags & Challenges

| Flag | Challenge | Type | Difficulty | Points |
|------|-----------|------|------------|--------|
| `FLAG{hidden_service_discovered_8081}` | Service Discovery | Reconnaissance | Easy | 100 |
| `FLAG{backup_files_exposed_g0bust3r}` | Directory Enumeration | OSINT | Easy | 150 |
| `FLAG{sql_injection_master_b4d1c0d3}` | Authentication Bypass | Web App | Medium | 200 |
| `FLAG{xss_found_h3r3}` | XSS Discovery | Web App | Medium | 200 |
| `FLAG{admin_access_s3cr3t_pr0j3ct}` | Privilege Escalation | Forensics | Hard | 250 |
| `FLAG{vault_master_mystery_solved_c0mpl3t3}` | Final Challenge | Advanced | Hard | 300 |

**Total: 1200 Points**

## 🛠️ Investigation Toolkit

### Reconnaissance
- **nmap** - Network and port scanning
- **gobuster** - Directory/file enumeration  
- **robots.txt** - Information disclosure

### Web Application Security
- **SQL Injection** - Systematic database enumeration
- **XSS Discovery** - Cross-site scripting identification
- **Authentication Bypass** - Privilege escalation techniques

### Digital Forensics
- **Wireshark** - Network packet analysis
- **Log Analysis** - System and application logs
- **Timeline Reconstruction** - Evidence correlation

## 🎮 Difficulty Modes

- **🔴 Advanced** (Default): No hints - realistic penetration testing experience
- **🟡 Intermediate**: Limited guidance for key challenges
- **🟢 Beginner**: Full hints and step-by-step instructions

*Change difficulty via admin panel: `/admin/admin_dashboard.php`*

## 📊 Features

✅ **Real-time Leaderboard** - Live team rankings and scoring  
✅ **Multi-user Support** - Team-based competition ready  
✅ **Admin Dashboard** - Comprehensive monitoring and control  
✅ **Docker Containerized** - Easy deployment and isolation  
✅ **Forensic Realism** - Authentic investigation scenarios  
✅ **Progressive Difficulty** - Escalating challenge complexity  

## 🔧 Management Commands

```bash
# Container Management
./start.sh              # Start CTF (local)
./start-network.sh      # Start CTF (network)
docker compose down     # Stop CTF

# Database Operations
./scripts/reset-db.sh   # Reset database and progress
./scripts/restart.sh    # Restart all containers

# Monitoring & Testing
./scripts/monitor.sh    # Resource monitoring
php scripts/test-db.php # Database connectivity test
```

## 📚 Documentation

| Document | Description |
|----------|-------------|
| **[📖 Story Background](docs/STORY.md)** | Investigation scenario and mission briefing |
| **[🔍 Solution Guide](docs/SOLUTION.md)** | Complete step-by-step walkthrough |
| **[🚀 Deployment Guide](docs/DEPLOYMENT.md)** | Production setup and configuration |
| **[⚙️ Setup Guide](docs/CTF-SETUP-GUIDE.md)** | Detailed technical configuration |

## 🎓 Learning Objectives

### Technical Skills Development
- **Web Application Penetration Testing**
- **SQL Injection Techniques & Prevention**
- **Cross-Site Scripting (XSS) Identification**
- **Network Traffic Analysis & Forensics**
- **Digital Evidence Collection & Analysis**
- **Privilege Escalation Methodologies**

### Professional Methodology
- **Systematic Reconnaissance Approach**
- **Evidence Documentation & Chain of Custody**
- **Timeline Reconstruction Techniques**
- **Technical Report Writing**
- **Ethical Hacking Principles**

## 🏗️ Architecture

```
MysteryCTF/
├── 🌐 Web Application (PHP/MySQL)
├── 🗄️ Database (MySQL 8.0)
├── 🔍 Hidden Services (Port 8081)
├── 📦 Network Logs & PCAP Files
├── 🛡️ Admin Panel & Monitoring
└── 📊 Real-time Scoring System
```

## ⚠️ Important Disclaimers

- **🎓 Educational Purpose**: Designed for cybersecurity learning and training
- **🔒 Authorized Testing Only**: Never use these techniques without explicit permission
- **🏠 Isolated Environment**: Deploy in dedicated lab/testing networks only
- **💻 System Requirements**: Minimum 4GB RAM, 10GB disk space
- **🔐 Security**: Change default passwords before production deployment

## 🤝 Contributing

We welcome contributions! Areas for improvement:
- 🆕 Additional challenges and flags
- 📝 Documentation enhancements
- 🐛 Bug fixes and performance improvements
- 🎯 New difficulty levels and scenarios

Please submit pull requests with detailed descriptions.

## 📄 License

MIT License - See [LICENSE](LICENSE) file for details.

---

## 🚀 Ready to Investigate?

**"Every packet tells a story. Every log entry holds a clue. Welcome to Mystery Corporation - where digital forensics meets hands-on learning."**

**Start your investigation now and uncover the truth! 🕵️♂️**