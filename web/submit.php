<!DOCTYPE html>
<html>
<head>
    <title>Flag Submission - Mystery Corporation CTF</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
        .nav { background: #28a745; color: white; padding: 10px; margin: -20px -20px 20px -20px; }
        .nav a { color: white; text-decoration: none; margin-right: 20px; }
        .form-group { margin: 15px 0; }
        input[type="text"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        button { background: #007cba; color: white; padding: 12px 25px; border: none; cursor: pointer; border-radius: 5px; }
        button:hover { background: #005a8b; }
        .success { background: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px; margin: 15px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px; margin: 15px 0; }
        .flag-info { background: #e7f3ff; padding: 15px; border-left: 4px solid #007cba; margin: 15px 0; }
        .leaderboard { margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f2f2f2; }
        .rank-1 { background: #ffd700; }
        .rank-2 { background: #c0c0c0; }
        .rank-3 { background: #cd7f32; }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <strong>🏁 Flag Submission Portal</strong>
            <span style="float: right;">
                <a href="index.php">← Back to Main</a> | 
                <a href="leaderboard.php">🏆 Leaderboard</a>
            </span>
        </div>

        <h2>🕵️ Submit Your Flag</h2>
        
        <?php
        $conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
        
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

        // Flag definitions with points
        $flags = [
            $_ENV['FLAG_1'] => ['name' => 'Hidden Service Discovery', 'points' => 100],
            $_ENV['FLAG_2'] => ['name' => 'Backup File Discovery', 'points' => 150],
            $_ENV['FLAG_3'] => ['name' => 'SQL Injection Master', 'points' => 200],
            $_ENV['FLAG_4'] => ['name' => 'XSS Discovery', 'points' => 200],
            $_ENV['FLAG_5'] => ['name' => 'Admin Access', 'points' => 250],
            $_ENV['FLAG_6'] => ['name' => 'Vault Master', 'points' => 300]
        ];

        if (isset($_POST['submit_flag'])) {
            $team_name = trim($_POST['team_name']);
            $flag_content = trim($_POST['flag_content']);
            
            if (empty($team_name) || empty($flag_content)) {
                echo "<div class='error'>❌ Team name and flag are required!</div>";
            } elseif (isset($flags[$flag_content])) {
                $flag_info = $flags[$flag_content];
                
                // Check if team already submitted this flag
                $check_query = "SELECT * FROM flag_submissions WHERE team_name = ? AND flag_content = ?";
                $check_stmt = $conn->prepare($check_query);
                $check_stmt->bind_param("ss", $team_name, $flag_content);
                $check_stmt->execute();
                $result = $check_stmt->get_result();
                
                if ($result->num_rows > 0) {
                    echo "<div class='error'>⚠️ Flag already submitted by team '$team_name'!</div>";
                } else {
                    // Insert new flag submission
                    $insert_query = "INSERT INTO flag_submissions (team_name, flag_content, points, flag_name) VALUES (?, ?, ?, ?)";
                    $insert_stmt = $conn->prepare($insert_query);
                    $insert_stmt->bind_param("ssis", $team_name, $flag_content, $flag_info['points'], $flag_info['name']);
                    
                    if ($insert_stmt->execute()) {
                        echo "<div class='success'>
                            🎉 <strong>FLAG ACCEPTED!</strong><br>
                            Team: <strong>$team_name</strong><br>
                            Flag: <strong>{$flag_info['name']}</strong><br>
                            Points: <strong>{$flag_info['points']}</strong>
                        </div>";
                    } else {
                        echo "<div class='error'>❌ Error submitting flag. Please try again.</div>";
                    }
                }
            } else {
                echo "<div class='error'>❌ Invalid flag! Please check your flag format.</div>";
            }
        }
        ?>

        <form method="POST">
            <div class="form-group">
                <label><strong>Team Name:</strong></label>
                <input type="text" name="team_name" placeholder="Enter your team name" required 
                       value="<?php echo isset($_POST['team_name']) ? htmlspecialchars($_POST['team_name']) : ''; ?>">
            </div>
            <div class="form-group">
                <label><strong>Flag:</strong></label>
                <input type="text" name="flag_content" placeholder="FLAG{...}" required>
            </div>
            <button type="submit" name="submit_flag">🚀 Submit Flag</button>
        </form>

        <div class="flag-info">
            <h3>🎯 Available Flags & Points</h3>
            <table>
                <tr><th>Flag Name</th><th>Points</th><th>Difficulty</th></tr>
                <tr><td>Hidden Service Discovery</td><td>100</td><td>Easy</td></tr>
                <tr><td>Backup File Discovery</td><td>150</td><td>Easy</td></tr>
                <tr><td>SQL Injection Master</td><td>200</td><td>Medium</td></tr>
                <tr><td>XSS Discovery</td><td>200</td><td>Medium</td></tr>
                <tr><td>Admin Access</td><td>250</td><td>Hard</td></tr>
                <tr><td>Vault Master</td><td>300</td><td>Hard</td></tr>
            </table>
            <p><strong>Total Possible Points: 1200</strong></p>
        </div>

        <!-- Mini Leaderboard -->
        <div class="leaderboard">
            <h3>🏆 Top 5 Teams</h3>
            <?php
            $leaderboard_query = "SELECT team_name, SUM(points) as total_points, COUNT(*) as flags_found 
                                 FROM flag_submissions 
                                 GROUP BY team_name 
                                 ORDER BY total_points DESC, flags_found DESC 
                                 LIMIT 5";
            $leaderboard_result = $conn->query($leaderboard_query);
            
            if ($leaderboard_result && $leaderboard_result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Rank</th><th>Team</th><th>Points</th><th>Flags</th></tr>";
                
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
                    echo "</tr>";
                    $rank++;
                }
                echo "</table>";
            } else {
                echo "<p>No submissions yet. Be the first to submit a flag!</p>";
            }
            ?>
            <p><a href="leaderboard.php">View Full Leaderboard →</a></p>
        </div>
    </div>
</body>
</html>