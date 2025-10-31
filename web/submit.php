<?php
session_start();

$conn = new mysqli(
    getenv('DB_HOST') ?: 'db',
    getenv('DB_USER') ?: 'root',
    getenv('DB_PASS') ?: 'mystery123',
    getenv('DB_NAME') ?: 'mystery_corp'
);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create submissions table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS flag_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    team_name VARCHAR(100) NOT NULL,
    flag_content VARCHAR(255) NOT NULL,
    points INT DEFAULT 0,
    flag_name VARCHAR(100),
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_team_flag (team_name, flag_content)
)");

// Real flags from .env with points
$real_flags = [
    (getenv('FLAG_1') ?: 'FLAG{hidden_service_discovered_8081}') => ['name' => 'Hidden Service Discovery', 'points' => 100],
    (getenv('FLAG_2') ?: 'FLAG{backup_files_exposed_g0bust3r}') => ['name' => 'Backup File Discovery', 'points' => 150],
    (getenv('FLAG_3') ?: 'FLAG{sql_injection_master_b4d1c0d3}') => ['name' => 'SQL Injection Master', 'points' => 200],
    (getenv('FLAG_4') ?: 'FLAG{xss_found_h3r3}') => ['name' => 'XSS Discovery', 'points' => 200],
    (getenv('FLAG_5') ?: 'FLAG{admin_access_s3cr3t_pr0j3ct}') => ['name' => 'Admin Access', 'points' => 250],
    (getenv('FLAG_6') ?: 'FLAG{vault_master_mystery_solved_c0mpl3t3}') => ['name' => 'Vault Master', 'points' => 300]
];

// Hardcoded fake flags (redirect to giphy)
$fake_flags = [
    'FLAG{hidden_service_discovered_8081}',
    'FLAG{backup_files_exposed_g0bust3r}',
    'FLAG{sql_injection_master_b4d1c0d3}',
    'FLAG{xss_found_h3r3}',
    'FLAG{admin_access_s3cr3t_pr0j3ct}',
    'FLAG{vault_master_mystery_solved_c0mpl3t3}'
];

if (isset($_POST['submit_flag'])) {
    $team_name = trim($_POST['team_name']);
    $flag_content = trim($_POST['flag_content']);
    
    if (empty($team_name) || empty($flag_content)) {
        $error = "Team name and flag are required!";
    } 
    // Check if it's a hardcoded fake flag - save with 0 points and mark team
    elseif (in_array($flag_content, $fake_flags)) {
        $cheater_team = $team_name . " ❓";
        
        // Check if team already submitted this flag
        $check_query = "SELECT * FROM flag_submissions WHERE team_name LIKE ? AND flag_content = ?";
        $check_stmt = $conn->prepare($check_query);
        $team_pattern = $team_name . "%";
        $check_stmt->bind_param("ss", $team_pattern, $flag_content);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Flag already submitted by team '$team_name'!";
        } else {
            // Insert fake flag with 0 points
            $insert_query = "INSERT INTO flag_submissions (team_name, flag_content, points, flag_name) VALUES (?, ?, 0, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $fake_flag_name = "SUS";
            $insert_stmt->bind_param("sss", $cheater_team, $flag_content, $fake_flag_name);
            
            if ($insert_stmt->execute()) {
                header("Location: giphy.php?flag=" . urlencode($flag_content) . "&type=fake");
                exit();
            } else {
                $error = "Error submitting flag. Please try again.";
            }
        }
    }
    // Check if it's a real flag from .env
    elseif (isset($real_flags[$flag_content])) {
        $flag_info = $real_flags[$flag_content];
        
        // Check if team already submitted this flag
        $check_query = "SELECT * FROM flag_submissions WHERE team_name = ? AND flag_content = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("ss", $team_name, $flag_content);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Flag already submitted by team '$team_name'!";
        } else {
            // Insert new flag submission
            $insert_query = "INSERT INTO flag_submissions (team_name, flag_content, points, flag_name) VALUES (?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("ssis", $team_name, $flag_content, $flag_info['points'], $flag_info['name']);
            
            if ($insert_stmt->execute()) {
                $success = "FLAG ACCEPTED! Team: $team_name | Flag: {$flag_info['name']} | Points: {$flag_info['points']}";
            } else {
                $error = "Error submitting flag. Please try again.";
            }
        }
    }
    // Check for any other FLAG{} pattern - save as unknown flag
    elseif (preg_match('/FLAG\{[^}]+\}/', $flag_content)) {
        $unknown_team = $team_name . " ❓";
        
        // Check if team already submitted this flag
        $check_query = "SELECT * FROM flag_submissions WHERE team_name LIKE ? AND flag_content = ?";
        $check_stmt = $conn->prepare($check_query);
        $team_pattern = $team_name . "%";
        $check_stmt->bind_param("ss", $team_pattern, $flag_content);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Flag already submitted by team '$team_name'!";
        } else {
            // Insert unknown flag with 0 points
            $insert_query = "INSERT INTO flag_submissions (team_name, flag_content, points, flag_name) VALUES (?, ?, 0, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $unknown_flag_name = "SUS";
            $insert_stmt->bind_param("sss", $unknown_team, $flag_content, $unknown_flag_name);
            
            if ($insert_stmt->execute()) {
                header("Location: giphy.php?flag=" . urlencode($flag_content) . "&type=unknown");
                exit();
            } else {
                $error = "Error submitting flag. Please try again.";
            }
        }
    }
    else {
        $error = "Invalid flag! Please check your flag format.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Flag Submission - Mystery Corporation CTF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <nav class="navbar navbar-expand-lg navbar-dark bg-success mb-4 rounded">
                    <div class="container-fluid">
                        <span class="navbar-brand"><i class="fas fa-rocket"></i> Flag Submission Portal</span>
                        <div class="navbar-nav ms-auto">
                            <a href="index.php" class="nav-link"><i class="fas fa-home"></i> Home</a>
                            <a href="leaderboard.php" class="nav-link"><i class="fas fa-trophy"></i> Leaderboard</a>
                            <a href="realtime-leaderboard.php" class="nav-link"><i class="fas fa-tv"></i> Live Board</a>
                        </div>
                    </div>
                </nav>

                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0"><i class="fas fa-flag"></i> Submit Your Flag</h3>
                    </div>
                    <div class="card-body">
                        
                        <?php if (isset($success)): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> <strong><?php echo $success; ?></strong>
                        </div>
                        <?php endif; ?>

                        <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                        </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-users"></i> <strong>Team Name:</strong></label>
                                <input type="text" name="team_name" class="form-control" placeholder="Enter your team name" required 
                                       value="<?php echo isset($_POST['team_name']) ? htmlspecialchars($_POST['team_name']) : ''; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-flag"></i> <strong>Flag:</strong></label>
                                <input type="text" name="flag_content" class="form-control" placeholder="FLAG{...}" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="submit_flag" class="btn btn-primary btn-lg">
                                    <i class="fas fa-rocket"></i> Submit Flag
                                </button>
                            </div>
                        </form>

                        <div class="mt-4">
                            <h5><i class="fas fa-target"></i> Available Flags & Points</h5>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="table-dark">
                                        <tr><th>Flag Name</th><th>Points</th><th>Difficulty</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>Hidden Service Discovery</td><td>100</td><td><span class="badge bg-success">Easy</span></td></tr>
                                        <tr><td>Backup File Discovery</td><td>150</td><td><span class="badge bg-success">Easy</span></td></tr>
                                        <tr><td>SQL Injection Master</td><td>200</td><td><span class="badge bg-warning">Medium</span></td></tr>
                                        <tr><td>XSS Discovery</td><td>200</td><td><span class="badge bg-warning">Medium</span></td></tr>
                                        <tr><td>Admin Access</td><td>250</td><td><span class="badge bg-danger">Hard</span></td></tr>
                                        <tr><td>Vault Master</td><td>300</td><td><span class="badge bg-danger">Hard</span></td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <p class="text-center"><strong>Total Possible Points: 1200</strong></p>
                        </div>

                        <!-- Mini Leaderboard -->
                        <div class="mt-4">
                            <h5><i class="fas fa-trophy"></i> Top 5 Teams</h5>
                            <?php
                            $leaderboard_query = "SELECT team_name, SUM(points) as total_points, COUNT(*) as flags_found 
                                                 FROM flag_submissions 
                                                 GROUP BY team_name 
                                                 ORDER BY total_points DESC, flags_found DESC 
                                                 LIMIT 5";
                            $leaderboard_result = $conn->query($leaderboard_query);
                            
                            if ($leaderboard_result && $leaderboard_result->num_rows > 0) {
                                echo "<div class='table-responsive'>";
                                echo "<table class='table table-sm'>";
                                echo "<thead class='table-secondary'><tr><th>Rank</th><th>Team</th><th>Points</th><th>Flags</th></tr></thead><tbody>";
                                
                                $rank = 1;
                                while ($row = $leaderboard_result->fetch_assoc()) {
                                    $rank_class = '';
                                    if ($rank == 1) $rank_class = 'table-warning';
                                    elseif ($rank == 2) $rank_class = 'table-secondary';
                                    elseif ($rank == 3) $rank_class = 'table-info';
                                    
                                    echo "<tr class='$rank_class'>";
                                    echo "<td><strong>#$rank</strong></td>";
                                    echo "<td>" . htmlspecialchars($row['team_name']) . "</td>";
                                    echo "<td><strong>" . $row['total_points'] . "</strong></td>";
                                    echo "<td>" . $row['flags_found'] . "/6</td>";
                                    echo "</tr>";
                                    $rank++;
                                }
                                echo "</tbody></table></div>";
                            } else {
                                echo "<div class='text-center text-muted py-3'>";
                                echo "<i class='fas fa-info-circle'></i> No submissions yet. Be the first to submit a flag!";
                                echo "</div>";
                            }
                            ?>
                            <div class="text-center">
                                <a href="leaderboard.php" class="btn btn-outline-primary">
                                    <i class="fas fa-chart-line"></i> View Full Leaderboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>