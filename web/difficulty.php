<?php
session_start();

// Set default difficulty if not set
if (!isset($_SESSION['difficulty'])) {
    $_SESSION['difficulty'] = 'beginner';
}

// Handle difficulty change
if (isset($_POST['set_difficulty'])) {
    $_SESSION['difficulty'] = $_POST['difficulty'];
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Select Difficulty - Mystery Corporation CTF</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
        .difficulty-card { border: 2px solid #ddd; padding: 20px; margin: 15px 0; border-radius: 8px; cursor: pointer; }
        .difficulty-card:hover { border-color: #007cba; }
        .difficulty-card.selected { border-color: #007cba; background: #e7f3ff; }
        .beginner { border-color: #28a745; }
        .intermediate { border-color: #ffc107; }
        .advanced { border-color: #dc3545; }
        button { background: #007cba; color: white; padding: 12px 25px; border: none; cursor: pointer; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎯 Select Your Challenge Level</h1>
        <p>Choose your difficulty level. This affects the amount of hints and guidance provided.</p>
        
        <form method="POST">
            <div class="difficulty-card beginner <?php echo $_SESSION['difficulty'] == 'beginner' ? 'selected' : ''; ?>">
                <label>
                    <input type="radio" name="difficulty" value="beginner" <?php echo $_SESSION['difficulty'] == 'beginner' ? 'checked' : ''; ?>>
                    <h3>🟢 Beginner Mode</h3>
                    <ul>
                        <li>Full hints and guidance provided</li>
                        <li>Step-by-step instructions</li>
                        <li>Example payloads shown</li>
                        <li>Perfect for learning</li>
                    </ul>
                </label>
            </div>
            
            <div class="difficulty-card intermediate <?php echo $_SESSION['difficulty'] == 'intermediate' ? 'selected' : ''; ?>">
                <label>
                    <input type="radio" name="difficulty" value="intermediate" <?php echo $_SESSION['difficulty'] == 'intermediate' ? 'checked' : ''; ?>>
                    <h3>🟡 Intermediate Mode</h3>
                    <ul>
                        <li>Limited hints provided</li>
                        <li>General guidance only</li>
                        <li>Focus on methodology</li>
                        <li>Good for practice</li>
                    </ul>
                </label>
            </div>
            
            <div class="difficulty-card advanced <?php echo $_SESSION['difficulty'] == 'advanced' ? 'selected' : ''; ?>">
                <label>
                    <input type="radio" name="difficulty" value="advanced" <?php echo $_SESSION['difficulty'] == 'advanced' ? 'checked' : ''; ?>>
                    <h3>🔴 Advanced Mode</h3>
                    <ul>
                        <li>No hints provided</li>
                        <li>Pure black-box testing</li>
                        <li>Real-world simulation</li>
                        <li>Challenge for experts</li>
                    </ul>
                </label>
            </div>
            
            <button type="submit" name="set_difficulty">Set Difficulty & Start CTF</button>
        </form>
        
        <p><small>Current difficulty: <strong><?php echo ucfirst($_SESSION['difficulty']); ?></strong> | <a href="index.php">Continue to CTF</a></small></p>
    </div>
</body>
</html>