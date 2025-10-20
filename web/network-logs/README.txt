Network Traffic Analysis - Mystery Corporation
==============================================

This directory contains captured network traffic from our internal systems.

Files:
- admin_traffic.pcap: Captured HTTP requests from admin users
- network_analysis.log: System logs (check for suspicious activities)

Use Wireshark or similar tools to analyze the pcap file:
wireshark admin_traffic.pcap

Look for:
- HTTP requests to admin endpoints
- Suspicious URLs or paths
- Authentication tokens or session data

Note: Some traffic may contain sensitive admin panel locations.