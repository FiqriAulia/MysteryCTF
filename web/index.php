<!DOCTYPE html>
<html>
<head>
    <title>Mystery Corporation - Employee Portal</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f4f4; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
        .login-form { max-width: 400px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; }
        .form-group { margin: 15px 0; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ddd; }
        button { background: #007cba; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        .error { color: red; margin: 10px 0; }
        .hint { background: #e7f3ff; padding: 10px; margin: 10px 0; border-left: 4px solid #007cba; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🕵️ Mystery Corporation</h1>
        <p><em>Welcome to the employee portal. Something strange has been happening at our company...</em></p>
        
        <?php
        // Show hints based on global difficulty level set by admin
        $difficulty = file_exists('difficulty_setting.txt') ? file_get_contents('difficulty_setting.txt') : 'beginner';
        if ($difficulty !== 'advanced') {
        ?>
        <div class="hint">
            <strong>🔍 Investigation Notes:</strong><br>
            <?php if ($difficulty == 'beginner'): ?>
            • Recent security incidents reported<br>
            • Employee database may contain clues<br>
            • Some services might be running on different ports<br>
            • Start with basic reconnaissance...<br>
            • Try nmap for port scanning: <code>nmap -p- target</code><br>
            • Use gobuster for directories: <code>gobuster dir -u http://target -w wordlist</code><br>
            <?php else: ?>
            • Recent security incidents reported<br>
            • Employee database may contain clues<br>
            • Start with reconnaissance<br>
            <?php endif; ?>
            • <a href="submit.php">🚀 Submit flags here</a> | <a href="leaderboard.php">🏆 View leaderboard</a> | <a href="realtime-leaderboard.php">📺 Live Board</a>
        </div>
        <?php } else { ?>
        <div style="background: #f8d7da; padding: 10px; margin: 10px 0; border-left: 4px solid #dc3545;">
            <strong>🔴 Advanced Mode:</strong> No hints provided. Good luck!<br>
            <a href="submit.php">🚀 Submit flags</a> | <a href="leaderboard.php">🏆 Leaderboard</a> | <a href="difficulty.php">⚙️ Change difficulty</a>
        </div>
        <?php } ?>

        <?php
        session_start();
        
        if (isset($_SESSION['user'])) {
            echo "<p>Welcome back, " . htmlspecialchars($_SESSION['user']) . "!</p>";
            echo "<a href='dashboard.php'>Go to Dashboard</a> | <a href='submit.php'>🚀 Submit Flag</a> | <a href='leaderboard.php'>🏆 Leaderboard</a> | <a href='realtime-leaderboard.php'>📺 Live Board</a> | <a href='logout.php'>Logout</a>";
        } else {
        ?>
        
        <div class="login-form">
            <h3>Employee Login</h3>
            <?php
            if (isset($_POST['login'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                
                // Vulnerable SQL query - intentional for CTF
                $conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
                
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                
                // VULNERABLE: Direct string concatenation - SQL Injection possible
                $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
                $result = $conn->query($query);
                
                if ($result && $result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    $_SESSION['user'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    header("Location: dashboard.php");
                    exit();
                } else {
                    echo "<div class='error'>Invalid credentials! Try common passwords...</div>";
                }
                
                $conn->close();
            }
            ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" name="login">Login</button>
            </form>
            
            <?php if ($difficulty == 'beginner'): ?>
            <p><small>Hint: Try admin/admin123 or SQL injection: <code>admin' OR '1'='1'-- </code></small></p>
            <?php elseif ($difficulty == 'intermediate'): ?>
            <p><small>Hint: Try default credentials or SQL injection techniques</small></p>
            <?php endif; ?>
            <p><small>Difficulty: <strong><?php echo ucfirst($difficulty); ?></strong> Mode (Set by Admin)</small></p>
        </div>
        
        <?php } ?>
        
        <!-- Hidden comment for reconnaissance -->
        <!-- TODO: Remove debug info - Database: mystery_corp, Tables: users, employees, secrets -->
        <!-- Hidden service running on port 8081 for internal use only -->
    </div>
</body>
</html>