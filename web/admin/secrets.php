<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$conn = new mysqli(
    getenv('DB_HOST') ?: 'db',
    getenv('DB_USER') ?: 'root',
    getenv('DB_PASS') ?: 'mystery123',
    getenv('DB_NAME') ?: 'mystery_corp'
);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Company Secrets - Mystery Corporation</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f4f4; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
        .nav { background: #dc3545; color: white; padding: 10px; margin: -20px -20px 20px -20px; }
        .nav a { color: white; text-decoration: none; margin-right: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f2f2f2; }
        .flag { background: #d4edda; padding: 15px; margin: 15px 0; border: 1px solid #c3e6cb; border-radius: 5px; }
        .secret-content { background: #fff3cd; padding: 10px; border-left: 4px solid #ffc107; }
        .error { background: #f8d7da; padding: 15px; margin: 15px 0; border: 1px solid #f5c6cb; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <strong>🔐 TOP SECRET - Company Secrets</strong>
            <span style="float: right;">
                <a href="../dashboard.php">← Back to Dashboard</a> | 
                <a href="../logout.php">Logout</a>
            </span>
        </div>

        <h2>🕵️ Classified Information</h2>
        
        <div class="flag">
            <strong>🎉 CONGRATULATIONS!</strong> You've accessed the admin secrets panel!<br>
            <code><?php echo getenv('FLAG_5') ?: 'FLAG{admin_access_s3cr3t_pr0j3ct}'; ?></code><br>
            <em>You're getting closer to the truth...</em>
        </div>

        <?php
        $query = "SELECT * FROM secrets ORDER BY access_level DESC";
        $result = $conn->query($query);
        
        if ($conn->error) {
            echo "<div class='error'>";
            echo "<strong>⚠️ Database Error:</strong> " . htmlspecialchars($conn->error);
            echo "<br><small>The secrets table may not exist yet. Please restart the database containers.</small>";
            echo "</div>";
        } elseif ($result && $result->num_rows > 0) {
            echo "<h3>Company Secrets Database</h3>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Title</th><th>Access Level</th><th>Content</th><th>Created</th></tr>";
            
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                echo "<td>Level " . $row['access_level'] . "</td>";
                echo "<td><div class='secret-content'>" . htmlspecialchars($row['content']) . "</div></td>";
                echo "<td>" . $row['created_at'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='error'>";
            echo "<strong>📋 No Secrets Found</strong><br>";
            echo "The secrets table exists but contains no data. Please check the database initialization.";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>