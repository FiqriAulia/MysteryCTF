<?php
session_start();

// Simple admin authentication
if (!isset($_SESSION['admin_logged_in'])) {
    if (isset($_POST['admin_login'])) {
        $admin_pass = $_POST['admin_password'];
        if ($admin_pass === $_ENV['ADMIN_PASSWORD']) {
            $_SESSION['admin_logged_in'] = true;
        } else {
            $error = "Invalid admin password!";
        }
    }
    
    if (!isset($_SESSION['admin_logged_in'])) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Admin Login - Mystery Corporation CTF</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; background: #2c3e50; color: white; }
                .container { max-width: 400px; margin: 100px auto; background: #34495e; padding: 30px; border-radius: 10px; }
                input[type="password"] { width: 100%; padding: 10px; margin: 10px 0; border: none; border-radius: 5px; }
                button { background: #e74c3c; color: white; padding: 12px 25px; border: none; cursor: pointer; border-radius: 5px; width: 100%; }
                .error { background: #c0392b; padding: 10px; border-radius: 5px; margin: 10px 0; }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>🔐 Admin Access</h2>
                <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
                <form method="POST">
                    <input type="password" name="admin_password" placeholder="Admin Password" required>
                    <button type="submit" name="admin_login">Login</button>
                </form>
                <p><small>Check .env file for password</small></p>
            </div>
        </body>
        </html>
        <?php
        exit();
    }
}

$conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Mystery Corporation CTF</title>
    <meta http-equiv="refresh" content="30">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #1a1a1a; color: #ffffff; }
        .container { max-width: 1400px; margin: 0 auto; }
        .nav { background: #dc3545; color: white; padding: 15px; margin: -20px -20px 20px -20px; border-radius: 8px; }
        .nav a { color: white; text-decoration: none; margin-right: 20px; }
        .dashboard-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0; }
        .widget { background: #2c3e50; padding: 20px; border-radius: 10px; border: 1px solid #34495e; }
        .widget h3 { color: #3498db; margin-top: 0; }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin: 20px 0; }
        .stat-card { background: #34495e; padding: 20px; border-radius: 8px; text-align: center; }
        .stat-number { font-size: 28px; font-weight: bold; color: #e74c3c; }
        .stat-label { color: #bdc3c7; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { border: 1px solid #34495e; padding: 8px; text-align: left; }
        th { background: #2c3e50; color: #3498db; }
        .rank-1 { background: #f39c12; color: #000; }
        .rank-2 { background: #95a5a6; color: #000; }
        .rank-3 { background: #d35400; color: #fff; }
        .activity-log { max-height: 300px; overflow-y: auto; font-family: monospace; font-size: 12px; }
        .log-entry { padding: 5px; border-bottom: 1px solid #34495e; }
        .controls { background: #27ae60; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .controls button { background: #e74c3c; color: white; padding: 8px 15px; border: none; border-radius: 5px; margin: 5px; cursor: pointer; }
        .alert { background: #e74c3c; padding: 15px; border-radius: 8px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <strong>👑 CTF Admin Dashboard</strong>
            <span style="float: right;">
                <a href="../leaderboard.php">🏆 Public Leaderboard</a> | 
                <a href="../submit.php">🚀 Submit Portal</a> |
                <a href="?logout=1">🚪 Logout</a>
            </span>
        </div>

        <?php
        // Handle logout
        if (isset($_GET['logout'])) {
            session_destroy();
            header("Location: admin_dashboard.php");
            exit();
        }

        // Handle admin actions
        if (isset($_POST['reset_team'])) {
            $team_name = $_POST['team_name'];
            $conn->query("DELETE FROM flag_submissions WHERE team_name = '$team_name'");
            echo "<div class='alert'>✅ Team '$team_name' has been reset!</div>";
        }

        if (isset($_POST['reset_all'])) {
            $conn->query("DELETE FROM flag_submissions");
            echo "<div class='alert'>⚠️ All submissions have been reset!</div>";
        }

        // Get comprehensive statistics
        $stats = [];
        $stats['total_teams'] = $conn->query("SELECT COUNT(DISTINCT team_name) as count FROM flag_submissions")->fetch_assoc()['count'] ?? 0;
        $stats['total_submissions'] = $conn->query("SELECT COUNT(*) as count FROM flag_submissions")->fetch_assoc()['count'] ?? 0;
        $stats['completed_teams'] = $conn->query("SELECT COUNT(*) as count FROM (SELECT team_name FROM flag_submissions GROUP BY team_name HAVING COUNT(*) = 6) as completed")->fetch_assoc()['count'] ?? 0;
        $stats['avg_score'] = $conn->query("SELECT AVG(total_points) as avg FROM (SELECT SUM(points) as total_points FROM flag_submissions GROUP BY team_name) as team_totals")->fetch_assoc()['avg'] ?? 0;
        ?>

        <h1>🎮 Mystery Corporation CTF - Admin Control Center</h1>
        <p style="color: #bdc3c7;">Real-time monitoring and management | Auto-refresh: 30s | <?php echo date('Y-m-d H:i:s'); ?></p>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total_teams']; ?></div>
                <div class="stat-label">Active Teams</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total_submissions']; ?></div>
                <div class="stat-label">Total Submissions</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['completed_teams']; ?></div>
                <div class="stat-label">Completed Teams</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo round($stats['avg_score']); ?></div>
                <div class="stat-label">Average Score</div>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- Live Leaderboard -->
            <div class="widget">
                <h3>🏆 Live Leaderboard</h3>
                <?php
                $leaderboard_query = "SELECT 
                    team_name, 
                    SUM(points) as total_points, 
                    COUNT(*) as flags_found,
                    MAX(submitted_at) as last_submission
                    FROM flag_submissions 
                    GROUP BY team_name 
                    ORDER BY total_points DESC, flags_found DESC, last_submission ASC
                    LIMIT 10";
                
                $leaderboard_result = $conn->query($leaderboard_query);
                
                if ($leaderboard_result && $leaderboard_result->num_rows > 0) {
                    echo "<table>";
                    echo "<tr><th>Rank</th><th>Team</th><th>Points</th><th>Flags</th><th>Last Activity</th></tr>";
                    
                    $rank = 1;
                    while ($row = $leaderboard_result->fetch_assoc()) {
                        $rank_class = '';
                        if ($rank == 1) $rank_class = 'rank-1';
                        elseif ($rank == 2) $rank_class = 'rank-2';
                        elseif ($rank == 3) $rank_class = 'rank-3';
                        
                        echo "<tr class='$rank_class'>";
                        echo "<td>#$rank</td>";
                        echo "<td>" . htmlspecialchars($row['team_name']) . "</td>";
                        echo "<td>" . $row['total_points'] . "</td>";
                        echo "<td>" . $row['flags_found'] . "/6</td>";
                        echo "<td>" . date('H:i:s', strtotime($row['last_submission'])) . "</td>";
                        echo "</tr>";
                        $rank++;
                    }
                    echo "</table>";
                } else {
                    echo "<p>No submissions yet.</p>";
                }
                ?>
            </div>

            <!-- Flag Statistics -->
            <div class="widget">
                <h3>📊 Flag Completion Stats</h3>
                <?php
                $flag_stats_query = "SELECT 
                    flag_name, 
                    COUNT(*) as solved_count,
                    points
                    FROM flag_submissions 
                    GROUP BY flag_name, points
                    ORDER BY points ASC";
                
                $flag_stats_result = $conn->query($flag_stats_query);
                
                if ($flag_stats_result && $flag_stats_result->num_rows > 0) {
                    echo "<table>";
                    echo "<tr><th>Flag Challenge</th><th>Solved By</th><th>Points</th><th>%</th></tr>";
                    
                    while ($row = $flag_stats_result->fetch_assoc()) {
                        $percentage = $stats['total_teams'] > 0 ? round(($row['solved_count'] / $stats['total_teams']) * 100, 1) : 0;
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['flag_name']) . "</td>";
                        echo "<td>" . $row['solved_count'] . " teams</td>";
                        echo "<td>" . $row['points'] . " pts</td>";
                        echo "<td>" . $percentage . "%</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
                ?>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="widget">
            <h3>⚡ Live Activity Feed</h3>
            <div class="activity-log">
                <?php
                $activity_query = "SELECT team_name, flag_name, points, submitted_at 
                                 FROM flag_submissions 
                                 ORDER BY submitted_at DESC 
                                 LIMIT 20";
                
                $activity_result = $conn->query($activity_query);
                
                if ($activity_result && $activity_result->num_rows > 0) {
                    while ($row = $activity_result->fetch_assoc()) {
                        $time = date('H:i:s', strtotime($row['submitted_at']));
                        echo "<div class='log-entry'>";
                        echo "[$time] <strong>" . htmlspecialchars($row['team_name']) . "</strong> ";
                        echo "solved <em>" . htmlspecialchars($row['flag_name']) . "</em> ";
                        echo "(+" . $row['points'] . " points)";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='log-entry'>No activity yet...</div>";
                }
                ?>
            </div>
        </div>

        <!-- Team Management -->
        <div class="widget">
            <h3>👥 Team Management</h3>
            <?php
            $teams_query = "SELECT 
                team_name, 
                SUM(points) as total_points, 
                COUNT(*) as flags_found,
                MIN(submitted_at) as first_submission,
                MAX(submitted_at) as last_submission
                FROM flag_submissions 
                GROUP BY team_name 
                ORDER BY team_name";
            
            $teams_result = $conn->query($teams_query);
            
            if ($teams_result && $teams_result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Team Name</th><th>Points</th><th>Flags</th><th>Duration</th><th>Actions</th></tr>";
                
                while ($row = $teams_result->fetch_assoc()) {
                    $duration = round((strtotime($row['last_submission']) - strtotime($row['first_submission'])) / 60, 1);
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['team_name']) . "</td>";
                    echo "<td>" . $row['total_points'] . "</td>";
                    echo "<td>" . $row['flags_found'] . "/6</td>";
                    echo "<td>" . $duration . " min</td>";
                    echo "<td>
                        <form method='POST' style='display: inline;'>
                            <input type='hidden' name='team_name' value='" . htmlspecialchars($row['team_name']) . "'>
                            <button type='submit' name='reset_team' onclick='return confirm(\"Reset team " . htmlspecialchars($row['team_name']) . "?\")'>Reset</button>
                        </form>
                    </td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            ?>
        </div>

        <!-- Admin Controls -->
        <div class="controls">
            <h3>🔧 Admin Controls</h3>
            <form method="POST" style="display: inline;">
                <button type="submit" name="reset_all" onclick="return confirm('⚠️ Reset ALL submissions? This cannot be undone!')">🗑️ Reset All Data</button>
            </form>
            <button onclick="window.open('../leaderboard.php', '_blank')">📊 View Public Leaderboard</button>
            <button onclick="window.location.reload()">🔄 Refresh Dashboard</button>
        </div>

        <div style="text-align: center; margin-top: 30px; padding: 15px; background: #2c3e50; border-radius: 8px;">
            <p style="color: #bdc3c7;">
                <strong>CTF Status:</strong> 
                <?php 
                if ($stats['completed_teams'] > 0) {
                    echo "🎉 " . $stats['completed_teams'] . " team(s) completed all challenges!";
                } else {
                    echo "🎯 Competition in progress...";
                }
                ?>
            </p>
        </div>
    </div>
</body>
</html>