<?php
session_start();

// Handle login processing first (before any output)
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Vulnerable SQL query - intentional for CTF
    $conn = new mysqli(
        getenv('DB_HOST') ?: 'db',
        getenv('DB_USER') ?: 'root',
        getenv('DB_PASS') ?: 'mystery123', 
        getenv('DB_NAME') ?: 'mystery_corp'
    );
    
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
        $login_error = "Invalid credentials! Try common passwords...";
    }
    
    $conn->close();
}

// Show hints based on global difficulty level set by admin
$difficulty = file_exists('difficulty_setting.txt') ? file_get_contents('difficulty_setting.txt') : 'beginner';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mystery Corporation - Employee Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-body">
                        <h1 class="text-center mb-4"><i class="fas fa-user-secret"></i> Mystery Corporation</h1>
                        <p class="text-muted text-center"><em>Welcome to the employee portal. Something strange has been happening at our company...</em></p>
        
        <?php
        if ($difficulty !== 'advanced') {
        ?>
                        <div class="alert alert-info border-start border-4 border-primary">
                            <strong><i class="fas fa-search"></i> Investigation Notes:</strong><br>
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
                            • <a href="submit.php" class="btn btn-sm btn-primary me-2"><i class="fas fa-rocket"></i> Submit Flags</a>
                            <a href="leaderboard.php" class="btn btn-sm btn-warning me-2"><i class="fas fa-trophy"></i> Leaderboard</a>
                            <a href="realtime-leaderboard.php" class="btn btn-sm btn-success"><i class="fas fa-tv"></i> Live Board</a>
                        </div>
        <?php } else { ?>
                        <div class="alert alert-danger border-start border-4 border-danger">
                            <strong><i class="fas fa-circle text-danger"></i> Advanced Mode:</strong> No hints provided. Good luck!<br>
                            <a href="submit.php" class="btn btn-sm btn-primary me-2"><i class="fas fa-rocket"></i> Submit Flags</a>
                            <a href="leaderboard.php" class="btn btn-sm btn-warning me-2"><i class="fas fa-trophy"></i> Leaderboard</a>
                            <a href="realtime-leaderboard.php" class="btn btn-sm btn-success"><i class="fas fa-tv"></i> Live Board</a>
                        </div>
        <?php } ?>

        <?php
        if (isset($_SESSION['user'])) {
            echo "<div class='alert alert-success'><i class='fas fa-user'></i> Welcome back, " . htmlspecialchars($_SESSION['user']) . "!</div>";
            echo "<div class='d-grid gap-2 d-md-flex justify-content-md-center'>";
            echo "<a href='dashboard.php' class='btn btn-primary'><i class='fas fa-tachometer-alt'></i> Dashboard</a>";
            echo "<a href='submit.php' class='btn btn-success'><i class='fas fa-rocket'></i> Submit Flag</a>";
            echo "<a href='leaderboard.php' class='btn btn-warning'><i class='fas fa-trophy'></i> Leaderboard</a>";
            echo "<a href='realtime-leaderboard.php' class='btn btn-info'><i class='fas fa-tv'></i> Live Board</a>";
            echo "<a href='logout.php' class='btn btn-outline-danger'><i class='fas fa-sign-out-alt'></i> Logout</a>";
            echo "</div>";
        } else {
        ?>
        
                        <div class="row justify-content-center mt-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-primary text-white text-center">
                                        <h5><i class="fas fa-lock"></i> Employee Login</h5>
                                    </div>
                                    <div class="card-body">
                                        <?php if (isset($login_error)): ?>
                                        <div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> <?php echo $login_error; ?></div>
                                        <?php endif; ?>
            
                                        <form method="POST">
                                            <div class="mb-3">
                                                <label class="form-label"><i class="fas fa-user"></i> Username:</label>
                                                <input type="text" name="username" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label"><i class="fas fa-key"></i> Password:</label>
                                                <input type="password" name="password" class="form-control" required>
                                            </div>
                                            <div class="d-grid">
                                                <button type="submit" name="login" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Login</button>
                                            </div>
                                        </form>
            
            <?php if ($difficulty == 'beginner'): ?>
                                        <div class="alert alert-info mt-3"><small><i class="fas fa-lightbulb"></i> Hint: Try admin/admin123 or SQL injection: <code>admin' OR '1'='1'-- </code></small></div>
            <?php elseif ($difficulty == 'intermediate'): ?>
                                        <div class="alert alert-info mt-3"><small><i class="fas fa-lightbulb"></i> Hint: Try default credentials or SQL injection techniques</small></div>
            <?php endif; ?>
                                        <div class="text-center mt-3">
                                            <small class="text-muted"><i class="fas fa-cog"></i> Difficulty: <strong><?php echo ucfirst($difficulty); ?></strong> Mode (Set by Admin)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
        
        <?php } ?>
        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Hidden comment for reconnaissance -->
    <!-- TODO: Remove debug info - Database: mystery_corp, Tables: users, employees, secrets -->
    <!-- Hidden service running on port 8081 for internal use only -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>