#!/bin/bash

echo "📊 Mystery Corporation CTF - Monitoring Dashboard"
echo "==============================================="

# Check Docker Compose command
if command -v docker-compose &> /dev/null; then
    COMPOSE_CMD="docker-compose"
elif docker compose version &> /dev/null; then
    COMPOSE_CMD="docker compose"
else
    echo "❌ Docker Compose not found."
    exit 1
fi

# Function to show service status
show_status() {
    echo "🔧 Service Status:"
    $COMPOSE_CMD ps
    echo ""
}

# Function to show access logs
show_logs() {
    echo "📋 Recent Access Logs (Last 20 entries):"
    $COMPOSE_CMD logs --tail=20 web | grep -E "(GET|POST)" | tail -10
    echo ""
}

# Function to show database connections
show_db_connections() {
    echo "🗜️  Database Connections:"
    $COMPOSE_CMD exec -T db mysql -u root -pmystery123 -e "SHOW PROCESSLIST;" 2>/dev/null | grep -v "Sleep" || echo "No active connections"
    echo ""
}

# Function to show network info
show_network_info() {
    HOST_IP=$(hostname -I | awk '{print $1}')
    if [[ -z "$HOST_IP" ]]; then
        HOST_IP=$(ifconfig | grep "inet " | grep -v 127.0.0.1 | awk '{print $2}' | head -1)
    fi
    
    echo "🌐 Network Information:"
    echo "   Host IP: $HOST_IP"
    echo "   Main App: http://$HOST_IP:8080"
    echo "   Hidden Service: http://$HOST_IP:8081"
    echo "   Database: $HOST_IP:3306"
    echo ""
}

# Function to show participant activity
show_activity() {
    echo "👥 Participant Activity (SQL queries in logs):"
    $COMPOSE_CMD logs web | grep -i "select\|union\|insert\|update\|delete" | tail -5 || echo "No SQL activity detected"
    echo ""
}

# Main monitoring loop
while true; do
    clear
    echo "📊 Mystery Corporation CTF - Live Monitoring"
    echo "==========================================="
    echo "$(date)"
    echo ""
    
    show_status
    show_network_info
    show_activity
    show_logs
    
    echo "🔄 Auto-refresh in 30 seconds... (Ctrl+C to exit)"
    sleep 30
done