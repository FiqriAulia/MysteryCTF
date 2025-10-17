<!DOCTYPE html>
<html>
<head>
    <title>Realtime Leaderboard - Mystery Corporation CTF</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #1a1a1a; color: #ffffff; }
        .container { max-width: 1400px; margin: 0 auto; }
        .header { background: #ffc107; color: #212529; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin: 20px 0; }
        .stat-card { background: #2c3e50; padding: 20px; border-radius: 8px; text-align: center; }
        .stat-number { font-size: 28px; font-weight: bold; color: #e74c3c; }
        .stat-label { color: #bdc3c7; font-size: 14px; }
        .main-content { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
        .leaderboard, .activity { background: #2c3e50; padding: 20px; border-radius: 10px; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { border: 1px solid #34495e; padding: 10px; text-align: left; }
        th { background: #34495e; color: #3498db; }
        .rank-1 { background: #f39c12; color: #000; }
        .rank-2 { background: #95a5a6; color: #000; }
        .rank-3 { background: #d35400; color: #fff; }
        .progress-bar { background: #34495e; border-radius: 10px; height: 20px; overflow: hidden; }
        .progress-fill { background: linear-gradient(90deg, #28a745, #20c997); height: 100%; transition: width 0.3s; }
        .status { padding: 10px; border-radius: 5px; margin: 10px 0; }
        .online { background: #27ae60; }
        .offline { background: #e74c3c; }
        .activity-item { padding: 8px; border-bottom: 1px solid #34495e; font-size: 14px; }
        .new-submission { animation: highlight 2s ease-in-out; }
        @keyframes highlight { 0% { background: #f39c12; } 100% { background: transparent; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏆 Live Leaderboard - Mystery Corporation CTF</h1>
            <div id="status" class="status online">🟢 Connected - Auto-updating every 3 seconds</div>
        </div>

        <!-- Statistics -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number" id="total-teams">0</div>
                <div class="stat-label">Active Teams</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="total-submissions">0</div>
                <div class="stat-label">Total Submissions</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="leader-score">0</div>
                <div class="stat-label">Leading Score</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">1200</div>
                <div class="stat-label">Max Possible</div>
            </div>
        </div>

        <div class="main-content">
            <!-- Leaderboard -->
            <div class="leaderboard">
                <h2>🥇 Live Rankings</h2>
                <div id="leaderboard-table">Loading...</div>
            </div>

            <!-- Recent Activity -->
            <div class="activity">
                <h3>⚡ Live Activity</h3>
                <div id="recent-activity">Loading...</div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 20px; color: #bdc3c7;">
            Last updated: <span id="last-update">Never</span>
        </div>
    </div>

    <script>
        let lastSubmissionCount = 0;
        
        async function updateLeaderboard() {
            try {
                const response = await fetch('api/leaderboard.php');
                const data = await response.json();
                
                // Update stats
                document.getElementById('total-teams').textContent = data.stats.total_teams;
                document.getElementById('total-submissions').textContent = data.stats.total_submissions;
                document.getElementById('leader-score').textContent = data.leaderboard[0]?.total_points || 0;
                document.getElementById('last-update').textContent = new Date().toLocaleTimeString();
                
                // Update leaderboard
                updateLeaderboardTable(data.leaderboard);
                
                // Update recent activity
                updateRecentActivity(data.recent_activity);
                
                // Check for new submissions
                if (data.stats.total_submissions > lastSubmissionCount && lastSubmissionCount > 0) {
                    // Play notification sound or show alert
                    showNewSubmissionAlert();
                }
                lastSubmissionCount = data.stats.total_submissions;
                
                // Update status
                document.getElementById('status').className = 'status online';
                document.getElementById('status').innerHTML = '🟢 Connected - Auto-updating every 3 seconds';
                
            } catch (error) {
                console.error('Error updating leaderboard:', error);
                document.getElementById('status').className = 'status offline';
                document.getElementById('status').innerHTML = '🔴 Connection Error - Retrying...';
            }
        }
        
        function updateLeaderboardTable(leaderboard) {
            let html = '<table><tr><th>Rank</th><th>Team</th><th>Points</th><th>Progress</th><th>Flags</th><th>Duration</th><th>Trophy</th></tr>';
            
            leaderboard.forEach(team => {
                let rankClass = '';
                let trophy = '';
                if (team.rank === 1) { rankClass = 'rank-1'; trophy = '🥇'; }
                else if (team.rank === 2) { rankClass = 'rank-2'; trophy = '🥈'; }
                else if (team.rank === 3) { rankClass = 'rank-3'; trophy = '🥉'; }
                
                html += `<tr class="${rankClass}">
                    <td><strong>#${team.rank}</strong></td>
                    <td><strong>${team.team_name}</strong></td>
                    <td><strong>${team.total_points}</strong></td>
                    <td>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: ${team.progress}%"></div>
                        </div>
                        <small>${team.progress}%</small>
                    </td>
                    <td><strong>${team.flags_found}/6</strong></td>
                    <td><small>${team.duration}m</small></td>
                    <td>${trophy}</td>
                </tr>`;
            });
            
            html += '</table>';
            document.getElementById('leaderboard-table').innerHTML = html;
        }
        
        function updateRecentActivity(activity) {
            let html = '';
            activity.forEach(item => {
                const time = new Date(item.submitted_at).toLocaleTimeString();
                html += `<div class="activity-item">
                    <strong>${item.team_name}</strong> solved <em>${item.flag_name}</em> 
                    (+${item.points} pts) at ${time}
                </div>`;
            });
            document.getElementById('recent-activity').innerHTML = html || '<div class="activity-item">No recent activity</div>';
        }
        
        function showNewSubmissionAlert() {
            // Add highlight animation to recent activity
            const activityDiv = document.getElementById('recent-activity');
            activityDiv.classList.add('new-submission');
            setTimeout(() => activityDiv.classList.remove('new-submission'), 2000);
        }
        
        // Start real-time updates
        updateLeaderboard();
        setInterval(updateLeaderboard, 3000); // Update every 3 seconds
        
        // Handle page visibility change
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                updateLeaderboard(); // Update immediately when page becomes visible
            }
        });
    </script>
</body>
</html>