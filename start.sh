#!/bin/bash

echo "🕵️  Mystery Corporation CTF Setup"
echo "=================================="

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Please install Docker first."
    exit 1
fi

# Check if Docker daemon is running
echo "🔍 Checking Docker status..."
if ! docker info > /dev/null 2>&1; then
    echo "⚠️  Docker daemon not running. Attempting to start..."
    
    # Try different methods to start Docker
    if command -v systemctl &> /dev/null; then
        echo "   Trying: sudo systemctl start docker"
        sudo systemctl start docker 2>/dev/null || true
    elif command -v service &> /dev/null; then
        echo "   Trying: sudo service docker start"
        sudo service docker start 2>/dev/null || true
    fi
    
    sleep 3
    
    # Check again
    if ! docker info > /dev/null 2>&1; then
        echo "❌ Docker daemon is not running. Please start Docker manually:"
        echo "   Linux: sudo systemctl start docker"
        echo "   macOS/Windows: Open Docker Desktop application"
        echo "   Then run this script again"
        exit 1
    fi
fi

echo "✅ Docker is running"

echo "📦 Using: sudo docker compose"

echo "🔧 Starting Mystery Corporation CTF..."

# Stop any existing containers
sudo docker compose down > /dev/null 2>&1

# Build and start containers
echo "📦 Building containers..."
sudo docker compose build --no-cache

echo "🚀 Starting services..."
sudo docker compose up -d

# Wait for services to be ready
echo "⏳ Waiting for services to start..."
sleep 10

# Check if services are running
if sudo docker compose ps | grep -q "Up"; then
    echo "✅ Services are running!"
    echo ""
    echo "🌐 Access the CTF at:"
    echo "   Main Application: http://localhost:8080"
    echo "   Hidden Service:   http://localhost:8081"
    echo ""
    echo "🎯 Your mission:"
    echo "   1. Perform reconnaissance (nmap, gobuster)"
    echo "   2. Find SQL injection vulnerabilities"
    echo "   3. Discover XSS vulnerabilities"
    echo "   4. Escalate privileges to admin"
    echo "   5. Collect all flags!"
    echo ""
    echo "📚 Check README.md for detailed instructions"
    echo "🔍 Check SOLUTION.md if you need help"
    echo ""
    echo "🏁 Flags to find:"
    echo "   • FLAG{hidden_service_discovered_8081}"
    echo "   • FLAG{backup_files_exposed_g0bust3r}"
    echo "   • FLAG{sql_injection_master_b4d1c0d3}"
    echo "   • FLAG{xss_found_h3r3}"
    echo "   • FLAG{admin_access_s3cr3t_pr0j3ct}"
    echo "   • FLAG{vault_master_mystery_solved_c0mpl3t3}"
    echo ""
    echo "Good luck, Detective! 🕵️‍♂️"
else
    echo "❌ Failed to start services. Check logs with:"
    echo "   sudo docker compose logs"
fi