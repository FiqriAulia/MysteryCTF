<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Mystery Corporation</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f4f4; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
        .nav { background: #007cba; color: white; padding: 10px; margin: -20px -20px 20px -20px; }
        .nav a { color: white; text-decoration: none; margin-right: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f2f2f2; }
        .search-form { margin: 20px 0; padding: 15px; background: #f9f9f9; }
        .flag { background: #ffffcc; padding: 10px; margin: 10px 0; border: 1px solid #ffcc00; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <strong>Mystery Corporation Dashboard</strong>
            <span style="float: right;">
                Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?> (<?php echo $_SESSION['role']; ?>) | 
                <a href="submit.php">🚀 Submit Flag</a> |
                <a href="leaderboard.php">🏆 Leaderboard</a> |
                <a href="logout.php">Logout</a>
            </span>
        </div>

        <h2>🔍 Employee Investigation Portal</h2>
        
        <?php if ($_SESSION['role'] == 'admin'): ?>
        <div class="flag">
            <strong>🎉 FLAG FOUND!</strong> You've gained admin access!<br>
            <code>FLAG{sql_injection_master_b4d1c0d3}</code><br>
            <em>Next clue: Check the employee records for suspicious entries...</em>
        </div>
        <?php endif; ?>

        <!-- Employee Search (Vulnerable to SQL Injection) -->
        <div class="search-form">
            <h3>Employee Search</h3>
            <form method="GET">
                <input type="text" name="search" placeholder="Search employees..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit">Search</button>
            </form>
            <small>Try searching for: John, IT, or use SQL injection techniques</small>
        </div>

        <?php
        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            
            // Another SQL injection vulnerability
            $query = "SELECT * FROM employees WHERE name LIKE '%$search%' OR department LIKE '%$search%'";
            $result = $conn->query($query);
            
            if ($result && $result->num_rows > 0) {
                echo "<h3>Search Results:</h3>";
                echo "<table>";
                echo "<tr><th>Name</th><th>Department</th><th>Salary</th><th>Notes</th></tr>";
                
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['department']) . "</td>";
                    echo "<td>$" . number_format($row['salary'], 2) . "</td>";
                    
                    // XSS vulnerability - not escaping secret_note
                    echo "<td>" . $row['secret_note'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No employees found.</p>";
            }
        }
        ?>

        <!-- Admin Panel Link -->
        <?php if ($_SESSION['role'] == 'admin'): ?>
        <div style="margin: 20px 0; padding: 15px; background: #ffe6e6; border: 1px solid #ff9999;">
            <h3>🔐 Admin Functions</h3>
            <p><a href="admin/secrets.php">View Company Secrets</a></p>
            <p><a href="admin/vault.php">Access Security Vault</a></p>
        </div>
        <?php endif; ?>

        <div style="margin-top: 30px; padding: 15px; background: #e6f3ff; border: 1px solid #99ccff;">
            <h3>🕵️ Investigation Tips</h3>
            <ul>
                <li>Try SQL injection in the search field: <code>' UNION SELECT 1,2,3,4,5-- </code></li>
                <li>Look for XSS vulnerabilities in employee notes</li>
                <li>Use nmap to scan for other services: <code>nmap -p- localhost</code></li>
                <li>Try directory enumeration with gobuster</li>
                <li>Check for hidden services mentioned in employee notes</li>
            </ul>
        </div>
    </div>
</body>
</html>