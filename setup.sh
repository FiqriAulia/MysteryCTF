#!/bin/bash

echo "🕵️ Mystery Corporation CTF - Quick Setup"
echo "========================================"

# Check if .env exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file from template..."
    cp .env.example .env
    echo "✅ .env file created! You can customize flags and passwords in .env"
    echo "⚠️  Please restart containers after modifying .env: sudo docker compose restart"
else
    echo "✅ .env file already exists"
fi

# Make scripts executable
chmod +x *.sh

# Check Docker
if ! command -v docker &> /dev/null; then
    echo "❌ Docker not found. Please install Docker first."
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose not found. Please install Docker Compose first."
    exit 1
fi

echo ""
echo "🚀 Ready to start CTF!"
echo "Run: ./start.sh (local) or ./start-network.sh (multi-user)"