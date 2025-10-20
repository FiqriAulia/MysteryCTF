#!/bin/bash

echo "🌐 Mystery Corporation CTF - Network Setup"
echo "=========================================="

# Get host IP address
HOST_IP=$(hostname -I | awk '{print $1}')
if [[ -z "$HOST_IP" ]]; then
    # Fallback for macOS
    HOST_IP=$(ifconfig | grep "inet " | grep -v 127.0.0.1 | awk '{print $2}' | head -1)
fi

echo "🔧 Setting up CTF for network access..."
echo "📍 Host IP Address: $HOST_IP"

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

# Stop any existing containers
$COMPOSE_CMD down > /dev/null 2>&1

# Build and start containers
echo "📦 Building containers..."
$COMPOSE_CMD build --no-cache

echo "🚀 Starting services..."
$COMPOSE_CMD up -d

# Wait for services to be ready
echo "⏳ Waiting for services to start..."
sleep 15

# Check if services are running
if $COMPOSE_CMD ps | grep -q "Up"; then
    echo "✅ CTF is now running and accessible from network!"
    echo ""
    echo "🌐 Access URLs for participants:"
    echo "   Main Application: http://$HOST_IP:8080"
    echo "   Hidden Service:   http://$HOST_IP:8081"
    echo "   Database (MySQL): $HOST_IP:3306"
    echo ""
    echo "📋 Share these details with participants:"
    echo "   Target IP: $HOST_IP"
    echo "   Ports: 8080 (main), 8081 (hidden), 3306 (database)"
    echo ""
    echo "🎯 Participant Instructions:"
    echo "   1. Scan target: nmap -p- $HOST_IP"
    echo "   2. Enumerate directories: gobuster dir -u http://$HOST_IP:8080 -w wordlist.txt"
    echo "   3. Test for SQL injection and XSS"
    echo "   4. Escalate to admin access"
    echo "   5. Collect all 6 flags!"
    echo ""
    echo "🔥 Pro Tips for Participants:"
    echo "   • Start with reconnaissance"
    echo "   • Check robots.txt and source code"
    echo "   • Try default credentials: admin/admin123"
    echo "   • Look for backup files and hidden services"
    echo ""
    echo "🛑 To stop CTF: docker-compose down"
    echo ""
    echo "Happy Hacking! 🕵️♂️"
else
    echo "❌ Failed to start services. Check logs with:"
    echo "   $COMPOSE_CMD logs"
fi

# Show firewall reminder
echo ""
echo "⚠️  FIREWALL REMINDER:"
echo "   Make sure ports 8080, 8081, and 3306 are open in your firewall"
echo "   macOS: System Preferences > Security & Privacy > Firewall"
echo "   Linux: sudo ufw allow 8080,8081,3306"