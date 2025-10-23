# 🕵️ Mystery Corporation - The Investigation

## 📖 Background Story

**Mystery Corporation** adalah perusahaan teknologi yang baru-baru ini mengalami insiden keamanan yang mencurigakan. Sebagai **Digital Forensics Investigator**, Anda ditugaskan untuk menyelidiki kemungkinan adanya **insider threat** dan **data breach**.

## 🎯 Mission Briefing

**Tanggal:** 15 Januari 2024  
**Waktu:** 10:00 WIB  
**Lokasi:** Mystery Corporation HQ  
**Investigator:** [Your Name]  

### 📋 Initial Report

Pada pagi hari tanggal 14 Januari 2024, **Security Operations Center (SOC)** Mystery Corporation mendeteksi aktivitas yang tidak biasa:

1. **Akses tidak sah** ke sistem admin pada jam 02:30 WIB
2. **Database queries** yang mencurigakan terdeteksi
3. **Network traffic** anomali ke port yang tidak standar
4. **File backup** yang diakses tanpa otorisasi

### 🔍 Investigation Leads

**Lead 1: Web Application Security**
- Employee portal menunjukkan tanda-tanda **SQL injection attempts**
- Beberapa **XSS payloads** ditemukan di employee notes
- **Admin panel** diakses dari IP yang tidak dikenal

**Lead 2: Network Forensics**
- **Packet captures** tersimpan di server monitoring
- **Traffic logs** menunjukkan komunikasi ke **hidden services**
- **Port scanning** activity terdeteksi dari internal network

**Lead 3: Data Exfiltration**
- **Database backup files** mungkin telah diakses
- **Sensitive documents** di admin vault mungkin telah dibaca
- **Company secrets** berpotensi telah bocor

## 🎪 Your Investigation Tasks

### Phase 1: Reconnaissance & Discovery
**Objective:** Temukan entry points dan gather intelligence

**Hints:**
- 🤖 "The company's web crawlers follow specific rules - check what they're told to avoid"
- 🔍 "Network administrators often leave breadcrumbs in their monitoring systems"
- 🚪 "Not all services run on standard ports - some prefer to hide"

### Phase 2: Vulnerability Assessment
**Objective:** Identify and exploit security weaknesses

**Hints:**
- 💉 "Employee database searches might be more flexible than intended"
- 🔐 "Authentication systems sometimes trust too easily"
- 📊 "Database schemas can reveal more than just structure"

### Phase 3: Digital Forensics
**Objective:** Analyze captured evidence and network traffic

**Hints:**
- 📦 "Network packets tell stories - learn to read between the bytes"
- 📝 "System logs often contain the breadcrumbs attackers leave behind"
- 🕸️ "Web traffic patterns can reveal hidden administrative interfaces"

### Phase 4: Privilege Escalation
**Objective:** Gain administrative access and uncover the full scope

**Hints:**
- 👑 "Administrative privileges unlock doors to company secrets"
- 🏛️ "The most sensitive data is kept in the deepest vaults"
- 🔓 "Sometimes the path to the treasure is hidden in plain sight"

## 🏁 Success Criteria

Your investigation will be considered successful when you can:

1. **Identify the attack vectors** used by the threat actor
2. **Reconstruct the timeline** of the security incident  
3. **Assess the scope** of potential data compromise
4. **Collect digital evidence** of the security breach
5. **Document all findings** with proper forensic methodology

## 📚 Investigation Resources

**Available Tools:**
- Network scanning tools (nmap, gobuster)
- Web application testing tools (Burp Suite, curl)
- Network forensics tools (Wireshark, tcpdump)
- Database analysis tools (MySQL client, SQLmap)

**Evidence Sources:**
- Web application logs
- Network packet captures  
- Database backup files
- System configuration files
- Employee access records

## ⚠️ Important Notes

- **Document everything** - Proper forensic methodology requires detailed logs
- **Preserve evidence** - Don't modify original files during analysis
- **Follow the trail** - Each clue leads to the next piece of the puzzle
- **Think like an attacker** - Understanding their methodology helps predict their moves

---

**Good luck, Detective! The security of Mystery Corporation depends on your investigation skills.**

*Remember: This is a controlled environment for educational purposes. Always obtain proper authorization before conducting security testing on real systems.*