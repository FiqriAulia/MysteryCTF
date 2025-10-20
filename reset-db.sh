#!/bin/bash
echo "🗑️ Resetting database with new password..."
sudo docker compose down
sudo docker volume rm mysteryctf_mysql_data 2>/dev/null || true
echo "✅ Database volume removed"
sudo docker compose up -d
echo "✅ Database recreated with new password from .env"