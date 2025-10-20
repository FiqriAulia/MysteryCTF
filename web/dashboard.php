<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$conn = new mysqli(
    getenv('DB_HOST') ?: 'db',
    getenv('DB_USER') ?: 'root',
    getenv('DB_PASS') ?: 'mystery123',
    getenv('DB_NAME') ?: 'mystery_corp'
);

// Get difficulty from database
if (!$conn->connect_error) {
    $conn->query("CREATE TABLE IF NOT EXISTS ctf_settings (setting_key VARCHAR(50) PRIMARY KEY, setting_value TEXT)");
    $result = $conn->query("SELECT setting_value FROM ctf_settings WHERE setting_key = 'difficulty'");
    $difficulty = ($result && $result->num_rows > 0) ? $result->fetch_assoc()['setting_value'] : 'beginner';
} else {
    $difficulty = 'beginner';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Mystery Corporation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container-fluid mt-3">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
            <div class="container-fluid">
                <span class="navbar-brand"><i class="fas fa-tachometer-alt"></i> Mystery Corporation Dashboard</span>
                <div class="navbar-nav ms-auto">
                    <span class="navbar-text me-3">
                        <i class="fas fa-user"></i> Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?> (<?php echo $_SESSION['role']; ?>)
                    </span>
                    <a href="submit.php" class="nav-link"><i class="fas fa-rocket"></i> Submit Flag</a>
                    <a href="leaderboard.php" class="nav-link"><i class="fas fa-trophy"></i> Leaderboard</a>
                    <a href="realtime-leaderboard.php" class="nav-link"><i class="fas fa-tv"></i> Live Board</a>
                    <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </nav>

        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0"><i class="fas fa-search"></i> Employee Investigation Portal</h3>
                    </div>
                    <div class="card-body">
                        
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                        <div class="alert alert-success border-start border-4 border-success">
                            <h5><i class="fas fa-check-circle"></i> FLAG FOUND!</h5>
                            <p>You've gained admin access!</p>
                            <code class="fs-5">FLAG{sql_injection_master_b4d1c0d3}</code><br>
                            <small class="text-muted">Next clue: Check the employee records for suspicious entries...</small>
                        </div>
                        <?php endif; ?>

                        <!-- Employee Search -->
                        <div class="card mt-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-users"></i> Employee Search</h5>
                            </div>
                            <div class="card-body">
                                <form method="GET" class="mb-3">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Search employees..." 
                                               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Search
                                        </button>
                                    </div>
                                </form>
                                
                                <?php if ($difficulty == 'beginner'): ?>
                                <div class="alert alert-info">
                                    <small><i class="fas fa-lightbulb"></i> Try searching for: John, IT, or SQL injection: <code>' UNION SELECT 1,2,3,4,5-- </code></small>
                                </div>
                                <?php elseif ($difficulty == 'intermediate'): ?>
                                <div class="alert alert-info">
                                    <small><i class="fas fa-lightbulb"></i> Try searching for: John, IT, or use SQL injection techniques</small>
                                </div>
                                <?php endif; ?>
                                <small class="text-muted">Mode: <strong><?php echo ucfirst($difficulty); ?></strong></small>

                                <?php
                                if (isset($_GET['search'])) {
                                    $search = $_GET['search'];
                                    
                                    // Another SQL injection vulnerability
                                    $query = "SELECT * FROM employees WHERE name LIKE '%$search%' OR department LIKE '%$search%'";
                                    $result = $conn->query($query);
                                    
                                    if ($result && $result->num_rows > 0) {
                                        echo "<h6 class='mt-4'><i class='fas fa-list'></i> Search Results:</h6>";
                                        echo "<div class='table-responsive'>";
                                        echo "<table class='table table-striped'>";
                                        echo "<thead class='table-dark'><tr><th>Name</th><th>Department</th><th>Salary</th><th>Notes</th></tr></thead><tbody>";
                                        
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['department']) . "</td>";
                                            echo "<td>$" . number_format($row['salary'], 2) . "</td>";
                                            
                                            // XSS vulnerability - not escaping secret_note
                                            echo "<td>" . $row['secret_note'] . "</td>";
                                            echo "</tr>";
                                        }
                                        echo "</tbody></table></div>";
                                    } else {
                                        echo "<div class='alert alert-warning mt-3'><i class='fas fa-exclamation-triangle'></i> No employees found.</div>";
                                    }
                                }
                                ?>
                            </div>
                        </div>

                        <!-- Admin Panel Link -->
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                        <div class="card mt-4 border-danger">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0"><i class="fas fa-lock"></i> Admin Functions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2 d-md-flex">
                                    <a href="admin/secrets.php" class="btn btn-warning">
                                        <i class="fas fa-eye"></i> View Company Secrets
                                    </a>
                                    <a href="admin/vault.php" class="btn btn-danger">
                                        <i class="fas fa-vault"></i> Access Security Vault
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($difficulty !== 'advanced'): ?>
                        <div class="card mt-4 border-primary">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-user-secret"></i> Investigation Tips</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <?php if ($difficulty == 'beginner'): ?>
                                    <li><i class="fas fa-arrow-right text-primary"></i> Try SQL injection in the search field: <code>' UNION SELECT 1,2,3,4,5-- </code></li>
                                    <li><i class="fas fa-arrow-right text-primary"></i> Extract user data: <code>' UNION SELECT 1,username,password,role,5 FROM users-- </code></li>
                                    <li><i class="fas fa-arrow-right text-primary"></i> Look for XSS vulnerabilities in employee notes</li>
                                    <li><i class="fas fa-arrow-right text-primary"></i> Use nmap to scan for other services: <code>nmap -p- localhost</code></li>
                                    <li><i class="fas fa-arrow-right text-primary"></i> Try directory enumeration with gobuster</li>
                                    <li><i class="fas fa-arrow-right text-primary"></i> Check for hidden services mentioned in employee notes</li>
                                    <?php else: ?>
                                    <li><i class="fas fa-arrow-right text-primary"></i> Try SQL injection in the search field</li>
                                    <li><i class="fas fa-arrow-right text-primary"></i> Look for XSS vulnerabilities in employee notes</li>
                                    <li><i class="fas fa-arrow-right text-primary"></i> Use reconnaissance tools for discovery</li>
                                    <li><i class="fas fa-arrow-right text-primary"></i> Check for hidden services and directories</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>