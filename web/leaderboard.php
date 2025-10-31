<!DOCTYPE html>
<html>
<head>
    <title>Leaderboard - Mystery Corporation CTF</title>
    <meta http-equiv="refresh" content="30">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container-fluid mt-3">
        <nav class="navbar navbar-expand-lg navbar-dark bg-warning mb-4">
            <div class="container-fluid">
                <span class="navbar-brand"><i class="fas fa-trophy"></i> Mystery Corporation CTF - Leaderboard</span>
                <div class="navbar-nav ms-auto">
                    <a href="index.php" class="nav-link"><i class="fas fa-home"></i> Home</a>
                    <a href="submit.php" class="nav-link"><i class="fas fa-rocket"></i> Submit Flag</a>
                    <a href="realtime-leaderboard.php" class="nav-link"><i class="fas fa-tv"></i> Live Board</a>
                    <a href="admin/admin_dashboard.php" class="nav-link"><i class="fas fa-crown"></i> Admin</a>
                </div>
            </div>
        </nav>

        <?php
        $conn = new mysqli(
            getenv('DB_HOST') ?: 'db',
            getenv('DB_USER') ?: 'root',
            getenv('DB_PASS') ?: 'mystery123',
            getenv('DB_NAME') ?: 'mystery_corp'
        );
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get statistics with error handling
        $total_teams_query = "SELECT COUNT(DISTINCT team_name) as total_teams FROM flag_submissions";
        $total_submissions_query = "SELECT COUNT(*) as total_submissions FROM flag_submissions";
        $max_points_query = "SELECT MAX(total_points) as max_points FROM (SELECT SUM(points) as total_points FROM flag_submissions GROUP BY team_name) as team_totals";
        
        $total_teams_result = $conn->query($total_teams_query);
        $total_submissions_result = $conn->query($total_submissions_query);
        $max_points_result = $conn->query($max_points_query);
        
        $total_teams = ($total_teams_result && $total_teams_result->num_rows > 0) ? $total_teams_result->fetch_assoc()['total_teams'] : 0;
        $total_submissions = ($total_submissions_result && $total_submissions_result->num_rows > 0) ? $total_submissions_result->fetch_assoc()['total_submissions'] : 0;
        $max_points = ($max_points_result && $max_points_result->num_rows > 0) ? $max_points_result->fetch_assoc()['max_points'] : 0;
        ?>

        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0"><i class="fas fa-trophy"></i> Live Leaderboard</h3>
                        <small>Auto-refresh every 30 seconds | Last updated: <?php echo date('Y-m-d H:i:s'); ?></small>
                    </div>
                    <div class="card-body">

                        <!-- Statistics -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white text-center">
                                    <div class="card-body">
                                        <h2><?php echo $total_teams; ?></h2>
                                        <p>Active Teams</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white text-center">
                                    <div class="card-body">
                                        <h2><?php echo $total_submissions; ?></h2>
                                        <p>Total Submissions</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white text-center">
                                    <div class="card-body">
                                        <h2><?php echo $max_points ?: 0; ?></h2>
                                        <p>Highest Score</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white text-center">
                                    <div class="card-body">
                                        <h2>1200</h2>
                                        <p>Max Possible</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Main Leaderboard -->
                        <h4><i class="fas fa-medal"></i> Team Rankings</h4>
                        <?php
                        $leaderboard_query = "SELECT 
                            team_name, 
                            SUM(points) as total_points, 
                            COUNT(*) as flags_found,
                            MIN(submitted_at) as first_submission,
                            MAX(submitted_at) as last_submission,
                            GROUP_CONCAT(flag_name ORDER BY submitted_at) as flags_list
                            FROM flag_submissions 
                            GROUP BY team_name 
                            ORDER BY total_points DESC, flags_found DESC, first_submission ASC";
                        
                        $leaderboard_result = $conn->query($leaderboard_query);
                        
                        if ($leaderboard_result && $leaderboard_result->num_rows > 0) {
                            echo "<div class='table-responsive'>";
                            echo "<table class='table table-striped table-hover'>";
                            echo "<thead class='table-dark'><tr>
                                <th><i class='fas fa-medal'></i> Rank</th>
                                <th><i class='fas fa-users'></i> Team</th>
                                <th><i class='fas fa-star'></i> Points</th>
                                <th><i class='fas fa-chart-line'></i> Progress</th>
                                <th><i class='fas fa-flag'></i> Flags</th>
                                <th><i class='fas fa-play'></i> First Submit</th>
                                <th><i class='fas fa-stop'></i> Last Submit</th>
                                <th><i class='fas fa-clock'></i> Duration</th>
                                <th><i class='fas fa-trophy'></i> Trophy</th>
                            </tr></thead><tbody>";
                            
                            $rank = 1;
                            while ($row = $leaderboard_result->fetch_assoc()) {
                                $rank_class = '';
                                $trophy = '';
                                if ($rank == 1) {
                                    $rank_class = 'table-warning';
                                    $trophy = '🥇';
                                } elseif ($rank == 2) {
                                    $rank_class = 'table-secondary';
                                    $trophy = '🥈';
                                } elseif ($rank == 3) {
                                    $rank_class = 'table-info';
                                    $trophy = '🥉';
                                }
                                
                                $progress_percentage = ($row['total_points'] / 1200) * 100;
                                $duration = ($row['first_submission'] && $row['last_submission']) ? 
                                    round((strtotime($row['last_submission']) - strtotime($row['first_submission'])) / 60, 1) : 0;
                                
                                echo "<tr class='$rank_class'>";
                                echo "<td><strong>#$rank</strong></td>";
                                echo "<td><strong>" . htmlspecialchars($row['team_name']) . "</strong></td>";
                                echo "<td><strong>" . $row['total_points'] . "</strong></td>";
                                echo "<td>
                                    <div class='progress'>
                                        <div class='progress-bar bg-success' style='width: {$progress_percentage}%'></div>
                                    </div>
                                    <small>" . round($progress_percentage, 1) . "%</small>
                                </td>";
                                echo "<td><strong>" . $row['flags_found'] . "/6</strong></td>";
                                echo "<td><small>" . ($row['first_submission'] ? date('H:i:s', strtotime($row['first_submission'])) : '-') . "</small></td>";
                                echo "<td><small>" . ($row['last_submission'] ? date('H:i:s', strtotime($row['last_submission'])) : '-') . "</small></td>";
                                echo "<td><small>" . $duration . "m</small></td>";
                                echo "<td>$trophy</td>";
                                echo "</tr>";
                                $rank++;
                            }
                            echo "</tbody></table></div>";
                        } else {
                            echo "<div class='text-center py-5 text-muted'>";
                            echo "<i class='fas fa-target fa-3x mb-3'></i>";
                            echo "<h3>No submissions yet!</h3>";
                            echo "<p>Be the first team to submit a flag and claim the top spot!</p>";
                            echo "</div>";
                        }
                        ?>

                        <!-- Flag Breakdown -->
                        <div class="mt-4">
                            <h4><i class="fas fa-chart-bar"></i> Flag Submission Statistics</h4>
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
                                echo "<div class='table-responsive'>";
                                echo "<table class='table table-sm'>";
                                echo "<thead class='table-secondary'><tr><th>Flag Challenge</th><th>Teams Solved</th><th>Points</th><th>Difficulty</th></tr></thead><tbody>";
                                
                                while ($row = $flag_stats_result->fetch_assoc()) {
                                    $difficulty = '';
                                    $points = $row['avg_points'];
                                    if ($points <= 150) $difficulty = '<span class="badge bg-success">Easy</span>';
                                    elseif ($points <= 200) $difficulty = '<span class="badge bg-warning">Medium</span>';
                                    else $difficulty = '<span class="badge bg-danger">Hard</span>';
                                    
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['flag_name']) . "</td>";
                                    echo "<td>" . $row['submission_count'] . " teams</td>";
                                    echo "<td>" . $points . " pts</td>";
                                    echo "<td>$difficulty</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody></table></div>";
                            } else {
                                echo "<p class='text-muted'>No flag statistics available yet.</p>";
                            }
                            ?>
                        </div>

                        <!-- Recent Activity -->
                        <div class="mt-4">
                            <h4><i class="fas fa-bolt"></i> Recent Submissions</h4>
                            <?php
                            $recent_query = "SELECT team_name, flag_name, points, submitted_at 
                                           FROM flag_submissions 
                                           ORDER BY submitted_at DESC 
                                           LIMIT 10";
                            
                            $recent_result = $conn->query($recent_query);
                            
                            if ($recent_result && $recent_result->num_rows > 0) {
                                echo "<div class='table-responsive'>";
                                echo "<table class='table table-sm'>";
                                echo "<thead class='table-secondary'><tr><th>Date & Time</th><th>Team</th><th>Flag</th><th>Points</th></tr></thead><tbody>";
                                
                                while ($row = $recent_result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td><small>" . date('M j, H:i:s', strtotime($row['submitted_at'])) . "</small></td>";
                                    echo "<td>" . htmlspecialchars($row['team_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['flag_name']) . "</td>";
                                    echo "<td><span class='badge bg-success'>+" . $row['points'] . "</span></td>";
                                    echo "</tr>";
                                }
                                echo "</tbody></table></div>";
                            } else {
                                echo "<p class='text-muted'>No recent activity.</p>";
                            }
                            ?>
                        </div>

                        <div class="text-center mt-4 p-4 bg-light rounded">
                            <h4><i class="fas fa-target"></i> Competition Status</h4>
                            <p>Keep pushing! The mystery isn't solved until all flags are found!</p>
                            <p><strong>Good luck to all teams! 🕵️♂️</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>