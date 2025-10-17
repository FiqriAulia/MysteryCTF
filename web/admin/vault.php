<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Security Vault - Mystery Corporation</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #1a1a1a; color: #00ff00; }
        .container { max-width: 800px; margin: 0 auto; background: #000; padding: 20px; border-radius: 10px; border: 2px solid #00ff00; }
        .nav { background: #ff0000; color: white; padding: 10px; margin: -20px -20px 20px -20px; }
        .nav a { color: white; text-decoration: none; margin-right: 20px; }
        .terminal { background: #000; color: #00ff00; padding: 20px; font-family: 'Courier New', monospace; border: 1px solid #00ff00; }
        .flag { background: #004400; padding: 15px; margin: 15px 0; border: 2px solid #00ff00; border-radius: 5px; }
        .ascii-art { font-family: 'Courier New', monospace; font-size: 12px; line-height: 1.2; }
        .blink { animation: blink 1s infinite; }
        @keyframes blink { 0%, 50% { opacity: 1; } 51%, 100% { opacity: 0; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <strong>🔐 MAXIMUM SECURITY VAULT</strong>
            <span style="float: right;">
                <a href="secrets.php">← Back to Secrets</a> | 
                <a href="../logout.php">Logout</a>
            </span>
        </div>

        <div class="terminal">
            <div class="ascii-art">
╔══════════════════════════════════════════════════════════════╗
║                    MYSTERY CORPORATION                       ║
║                   SECURITY VAULT ACCESS                      ║
║                                                              ║
║  ██╗   ██╗ █████╗ ██╗   ██╗██╗  ████████╗                  ║
║  ██║   ██║██╔══██╗██║   ██║██║  ╚══██╔══╝                  ║
║  ██║   ██║███████║██║   ██║██║     ██║                     ║
║  ╚██╗ ██╔╝██╔══██║██║   ██║██║     ██║                     ║
║   ╚████╔╝ ██║  ██║╚██████╔╝███████╗██║                     ║
║    ╚═══╝  ╚═╝  ╚═╝ ╚═════╝ ╚══════╝╚═╝                     ║
╚══════════════════════════════════════════════════════════════╝
            </div>
            
            <p><span class="blink">ACCESS GRANTED</span></p>
            <p>Initializing secure connection...</p>
            <p>Loading classified data...</p>
            <p>Decrypting vault contents...</p>
        </div>

        <div class="flag">
            <h2>🏆 FINAL FLAG DISCOVERED!</h2>
            <p><strong>Congratulations, Detective!</strong></p>
            <p>You have successfully infiltrated Mystery Corporation and uncovered all secrets.</p>
            <br>
            <p><code style="font-size: 18px; color: #ffff00;">FLAG{vault_master_mystery_solved_c0mpl3t3}</code></p>
            <br>
            <p><em>The mystery has been solved. The corporation's secrets are now exposed!</em></p>
        </div>

        <div style="margin-top: 30px; padding: 15px; background: #003300; border: 1px solid #00ff00;">
            <h3>🕵️ Investigation Complete!</h3>
            <p><strong>Flags Collected:</strong></p>
            <ol>
                <li><code>FLAG{sql_injection_master_b4d1c0d3}</code> - SQL Injection via login</li>
                <li><code>FLAG{xss_found_h3r3}</code> - XSS in employee notes</li>
                <li><code>FLAG{admin_access_s3cr3t_pr0j3ct}</code> - Admin panel access</li>
                <li><code>FLAG{vault_master_mystery_solved_c0mpl3t3}</code> - Final vault access</li>
            </ol>
            
            <p><strong>Techniques Used:</strong></p>
            <ul>
                <li>🔍 Reconnaissance (nmap, gobuster)</li>
                <li>💉 SQL Injection</li>
                <li>🚨 Cross-Site Scripting (XSS)</li>
                <li>🔐 Privilege Escalation</li>
                <li>📊 Database Manipulation</li>
            </ul>
        </div>
    </div>
</body>
</html>