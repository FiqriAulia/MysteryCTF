#!/bin/bash

echo "🕵️  Mystery Corporation CTF Setup"
echo "=================================="

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "⚠️  Docker not detected. Trying to start Docker service..."
    sudo systemctl start docker 2>/dev/null || true
    sleep 2
    if ! docker info > /dev/null 2>&1; then
        echo "❌ Docker is not running. Please start Docker manually:"
        echo "   sudo systemctl start docker"
        echo "   or open Docker Desktop"
        exit 1
    fi
fi

echo "✅ Docker is running"

# Check if Docker Compose is available (prioritize modern version)
if docker compose version &> /dev/null; then
    COMPOSE_CMD="docker compose"
elif command -v docker-compose &> /dev/null; then
    COMPOSE_CMD="docker-compose"
else
    echo "❌ Docker Compose not found. Please install Docker Compose."
    exit 1
fi

echo "📦 Using: $COMPOSE_CMD"

echo "🔧 Starting Mystery Corporation CTF..."

# Stop any existing containers
$COMPOSE_CMD down > /dev/null 2>&1

# Build and start containers
echo "📦 Building containers..."
$COMPOSE_CMD build --no-cache

echo "🚀 Starting services..."
$COMPOSE_CMD up -d

# Wait for services to be ready
echo "⏳ Waiting for services to start..."
sleep 10

# Check if services are running
if $COMPOSE_CMD ps | grep -q "Up"; then
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
    echo "   $COMPOSE_CMD logs"
fi