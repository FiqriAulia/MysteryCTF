<!DOCTYPE html>
<html>
<head>
    <title>Leaderboard - Mystery Corporation CTF</title>
    <meta http-equiv="refresh" content="30">
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f4f4; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
        .nav { background: #ffc107; color: #212529; padding: 10px; margin: -20px -20px 20px -20px; }
        .nav a { color: #212529; text-decoration: none; margin-right: 20px; font-weight: bold; }
        .stats { display: flex; justify-content: space-around; margin: 20px 0; }
        .stat-box { background: #e9ecef; padding: 15px; border-radius: 8px; text-align: center; min-width: 120px; }
        .stat-number { font-size: 24px; font-weight: bold; color: #007cba; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #343a40; color: white; }
        .rank-1 { background: linear-gradient(135deg, #ffd700, #ffed4e); font-weight: bold; }
        .rank-2 { background: linear-gradient(135deg, #c0c0c0, #e8e8e8); font-weight: bold; }
        .rank-3 { background: linear-gradient(135deg, #cd7f32, #daa520); font-weight: bold; }
        .progress-bar { background: #e9ecef; border-radius: 10px; height: 20px; overflow: hidden; }
        .progress-fill { background: linear-gradient(90deg, #28a745, #20c997); height: 100%; transition: width 0.3s; }
        .team-details { font-size: 12px; color: #6c757d; }
        .refresh-info { text-align: center; color: #6c757d; margin: 10px 0; }
        .trophy { font-size: 20px; }
        .flag-breakdown { margin-top: 30px; }
        .flag-breakdown h3 { color: #495057; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <strong>🏆 Mystery Corporation CTF - Leaderboard</strong>
            <span style="float: right;">
                <a href="index.php">🏠 Home</a> | 
                <a href="submit.php">🚀 Submit Flag</a> |
                <a href="admin/admin_dashboard.php">👑 Admin</a>
            </span>
        </div>

        <?php
        $conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get statistics
        $total_teams_query = "SELECT COUNT(DISTINCT team_name) as total_teams FROM flag_submissions";
        $total_submissions_query = "SELECT COUNT(*) as total_submissions FROM flag_submissions";
        $max_points_query = "SELECT MAX(total_points) as max_points FROM (SELECT SUM(points) as total_points FROM flag_submissions GROUP BY team_name) as team_totals";
        
        $total_teams = $conn->query($total_teams_query)->fetch_assoc()['total_teams'] ?? 0;
        $total_submissions = $conn->query($total_submissions_query)->fetch_assoc()['total_submissions'] ?? 0;
        $max_points = $conn->query($max_points_query)->fetch_assoc()['max_points'] ?? 0;
        ?>

        <h1>🏆 Live Leaderboard</h1>
        <div class="refresh-info">Auto-refresh every 30 seconds | Last updated: <?php echo date('Y-m-d H:i:s'); ?></div>

        <!-- Statistics -->
        <div class="stats">
            <div class="stat-box">
                <div class="stat-number"><?php echo $total_teams; ?></div>
                <div>Active Teams</div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><?php echo $total_submissions; ?></div>
                <div>Total Submissions</div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><?php echo $max_points; ?></div>
                <div>Highest Score</div>
            </div>
            <div class="stat-box">
                <div class="stat-number">1200</div>
                <div>Max Possible</div>
            </div>
        </div>

        <!-- Main Leaderboard -->
        <h2>🥇 Team Rankings</h2>
        <?php
        $leaderboard_query = "SELECT 
            team_name, 
            SUM(points) as total_points, 
            COUNT(*) as flags_found,
            MAX(submitted_at) as last_submission,
            GROUP_CONCAT(flag_name ORDER BY submitted_at) as flags_list
            FROM flag_submissions 
            GROUP BY team_name 
            ORDER BY total_points DESC, flags_found DESC, last_submission ASC";
        
        $leaderboard_result = $conn->query($leaderboard_query);
        
        if ($leaderboard_result && $leaderboard_result->num_rows > 0) {
            echo "<table>";
            echo "<tr>
                <th>Rank</th>
                <th>Team</th>
                <th>Points</th>
                <th>Progress</th>
                <th>Flags</th>
                <th>Last Submission</th>
                <th>Trophy</th>
            </tr>";
            
            $rank = 1;
            while ($row = $leaderboard_result->fetch_assoc()) {
                $rank_class = '';
                $trophy = '';
                if ($rank == 1) {
                    $rank_class = 'rank-1';
                    $trophy = '🥇';
                } elseif ($rank == 2) {
                    $rank_class = 'rank-2';
                    $trophy = '🥈';
                } elseif ($rank == 3) {
                    $rank_class = 'rank-3';
                    $trophy = '🥉';
                }
                
                $progress_percentage = ($row['total_points'] / 1200) * 100;
                
                echo "<tr class='$rank_class'>";
                echo "<td><strong>#$rank</strong></td>";
                echo "<td><strong>" . htmlspecialchars($row['team_name']) . "</strong></td>";
                echo "<td><strong>" . $row['total_points'] . "</strong></td>";
                echo "<td>
                    <div class='progress-bar'>
                        <div class='progress-fill' style='width: {$progress_percentage}%'></div>
                    </div>
                    <div class='team-details'>" . round($progress_percentage, 1) . "%</div>
                </td>";
                echo "<td><strong>" . $row['flags_found'] . "/6</strong></td>";
                echo "<td><small>" . date('H:i:s', strtotime($row['last_submission'])) . "</small></td>";
                echo "<td class='trophy'>$trophy</td>";
                echo "</tr>";
                $rank++;
            }
            echo "</table>";
        } else {
            echo "<div style='text-align: center; padding: 40px; color: #6c757d;'>";
            echo "<h3>🎯 No submissions yet!</h3>";
            echo "<p>Be the first team to submit a flag and claim the top spot!</p>";
            echo "</div>";
        }
        ?>

        <!-- Flag Breakdown -->
        <div class="flag-breakdown">
            <h3>📊 Flag Submission Statistics</h3>
            <?php
            $flag_stats_query = "SELECT 
                flag_name, 
                COUNT(*) as submission_count,
                AVG(points) as avg_points
                FROM flag_submissions 
                GROUP BY flag_name, points
                ORDER BY avg_points DESC";
            
            $flag_stats_result = $conn->query($flag_stats_query);
            
            if ($flag_stats_result && $flag_stats_result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Flag Challenge</th><th>Teams Solved</th><th>Points</th><th>Difficulty</th></tr>";
                
                while ($row = $flag_stats_result->fetch_assoc()) {
                    $difficulty = '';
                    $points = $row['avg_points'];
                    if ($points <= 150) $difficulty = '🟢 Easy';
                    elseif ($points <= 200) $difficulty = '🟡 Medium';
                    else $difficulty = '🔴 Hard';
                    
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['flag_name']) . "</td>";
                    echo "<td>" . $row['submission_count'] . " teams</td>";
                    echo "<td>" . $points . " pts</td>";
                    echo "<td>$difficulty</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            ?>
        </div>

        <!-- Recent Activity -->
        <div style="margin-top: 30px;">
            <h3>⚡ Recent Submissions</h3>
            <?php
            $recent_query = "SELECT team_name, flag_name, points, submitted_at 
                           FROM flag_submissions 
                           ORDER BY submitted_at DESC 
                           LIMIT 10";
            
            $recent_result = $conn->query($recent_query);
            
            if ($recent_result && $recent_result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Time</th><th>Team</th><th>Flag</th><th>Points</th></tr>";
                
                while ($row = $recent_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><small>" . date('H:i:s', strtotime($row['submitted_at'])) . "</small></td>";
                    echo "<td>" . htmlspecialchars($row['team_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['flag_name']) . "</td>";
                    echo "<td><strong>+" . $row['points'] . "</strong></td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No recent activity.</p>";
            }
            ?>
        </div>

        <div style="text-align: center; margin-top: 30px; padding: 20px; background: #e9ecef; border-radius: 8px;">
            <h3>🎯 Competition Status</h3>
            <p>Keep pushing! The mystery isn't solved until all flags are found!</p>
            <p><strong>Good luck to all teams! 🕵️♂️</strong></p>
        </div>
    </div>
</body>
</html>