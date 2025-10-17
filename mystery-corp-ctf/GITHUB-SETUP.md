# 🐙 GitHub Setup Guide

## 📤 Upload ke GitHub

### 1. Buat Repository Baru
```bash
# Di GitHub, buat repository baru bernama "mystery-corp-ctf"
# Jangan initialize dengan README (karena sudah ada)
```

### 2. Push ke GitHub
```bash
cd mystery-corp-ctf

# Initialize git (jika belum)
git init

# Add semua files (kecuali .env karena ada di .gitignore)
git add .

# Commit
git commit -m "Initial CTF setup"

# Add remote origin
git remote add origin https://github.com/YOUR_USERNAME/mystery-corp-ctf.git

# Push to GitHub
git push -u origin main
```

## 📥 Clone dan Setup

### Untuk Pengguna Baru
```bash
# Clone repository
git clone https://github.com/YOUR_USERNAME/mystery-corp-ctf.git
cd mystery-corp-ctf

# Quick setup (creates .env from template)
./setup.sh

# Start CTF
./start.sh
```

### Untuk Host CTF
```bash
# Clone dan setup
git clone https://github.com/YOUR_USERNAME/mystery-corp-ctf.git
cd mystery-corp-ctf
./setup.sh

# Customize .env jika perlu
nano .env

# Start untuk network access
./start-network.sh
```

## 🔒 Security Notes

### ✅ Aman di GitHub:
- `.env` tidak akan ter-upload (ada di `.gitignore`)
- Flags dan passwords tidak hardcoded
- `.env.example` sebagai template

### ⚠️ Yang Perlu Diperhatikan:
- Jangan commit file `.env` 
- Customize flags di `.env` sebelum competition
- Backup `.env` secara terpisah jika perlu

## 🎯 Workflow untuk CTF Host

### Persiapan Competition:
```bash
# 1. Clone repository
git clone https://github.com/YOUR_USERNAME/mystery-corp-ctf.git
cd mystery-corp-ctf

# 2. Setup environment
./setup.sh

# 3. Customize flags (optional)
nano .env

# 4. Test locally
./start.sh
curl http://localhost:8080

# 5. Start for participants
./start-network.sh
```

### Update CTF:
```bash
# Pull latest changes
git pull origin main

# Restart with new changes
docker-compose down
docker-compose up -d
```

## 📋 Repository Structure
```
mystery-corp-ctf/
├── .env.example          # Template environment
├── .env                  # Your config (not in git)
├── .gitignore           # Protects .env
├── docker-compose.yml   # Uses .env variables
├── setup.sh            # Auto-setup script
├── start.sh            # Local startup
├── start-network.sh    # Network startup
├── web/                # Application files
├── database/           # Database init
└── README.md           # Main documentation
```

## 🚀 One-Command Deploy
```bash
curl -sSL https://raw.githubusercontent.com/YOUR_USERNAME/mystery-corp-ctf/main/setup.sh | bash
```

## 🔄 Updates dan Maintenance

### Update Flags:
```bash
# Edit .env file
nano .env

# Restart containers
docker-compose restart
```

### Update Code:
```bash
# Pull changes
git pull

# Rebuild if needed
docker-compose build --no-cache
docker-compose up -d
```

---

**Ready for GitHub! 🎉**

*Repository siap untuk di-clone dan dijalankan oleh siapa saja dengan satu command!*