#!/bin/bash
echo "🔄 Restarting Mystery Corporation CTF containers..."
sudo docker compose down
sudo docker compose up -d
echo "✅ Containers restarted! Environment variables updated."