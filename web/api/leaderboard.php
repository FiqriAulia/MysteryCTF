<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$conn = new mysqli(
    getenv('DB_HOST') ?: 'db',
    getenv('DB_USER') ?: 'root',
    getenv('DB_PASS') ?: 'mystery123',
    getenv('DB_NAME') ?: 'mystery_corp'
);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

// Get leaderboard data
$leaderboard_query = "SELECT 
    team_name, 
    SUM(points) as total_points, 
    COUNT(*) as flags_found,
    MIN(submitted_at) as first_submission,
    MAX(submitted_at) as last_submission
    FROM flag_submissions 
    GROUP BY team_name 
    ORDER BY total_points DESC, flags_found DESC, first_submission ASC";

$result = $conn->query($leaderboard_query);
$leaderboard = [];

if ($result && $result->num_rows > 0) {
    $rank = 1;
    while ($row = $result->fetch_assoc()) {
        $duration = $row['first_submission'] && $row['last_submission'] ? 
            round((strtotime($row['last_submission']) - strtotime($row['first_submission'])) / 60, 1) : 0;
        
        $leaderboard[] = [
            'rank' => $rank,
            'team_name' => $row['team_name'],
            'total_points' => (int)$row['total_points'],
            'flags_found' => (int)$row['flags_found'],
            'first_submission' => $row['first_submission'],
            'last_submission' => $row['last_submission'],
            'duration' => $duration,
            'progress' => round(($row['total_points'] / 1200) * 100, 1)
        ];
        $rank++;
    }
}

// Get recent activity
$recent_query = "SELECT team_name, flag_name, points, submitted_at 
                FROM flag_submissions 
                ORDER BY submitted_at DESC 
                LIMIT 5";

$recent_result = $conn->query($recent_query);
$recent_activity = [];

if ($recent_result && $recent_result->num_rows > 0) {
    while ($row = $recent_result->fetch_assoc()) {
        $recent_activity[] = [
            'team_name' => $row['team_name'],
            'flag_name' => $row['flag_name'],
            'points' => (int)$row['points'],
            'submitted_at' => $row['submitted_at']
        ];
    }
}

// Get stats
$stats = [
    'total_teams' => $conn->query("SELECT COUNT(DISTINCT team_name) as count FROM flag_submissions")->fetch_assoc()['count'] ?? 0,
    'total_submissions' => $conn->query("SELECT COUNT(*) as count FROM flag_submissions")->fetch_assoc()['count'] ?? 0,
    'last_update' => date('Y-m-d H:i:s')
];

echo json_encode([
    'leaderboard' => $leaderboard,
    'recent_activity' => $recent_activity,
    'stats' => $stats
]);

$conn->close();
?>