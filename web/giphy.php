<!DOCTYPE html>
<html>
<head>
    <title>Flag Detected! - Mystery Corporation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #000; color: #00ff00; font-family: 'Courier New', monospace; }
        .container { text-align: center; padding: 50px 0; }
        .gif-container { margin: 30px 0; }
        .blink { animation: blink 1s infinite; }
        @keyframes blink { 0%, 50% { opacity: 1; } 51%, 100% { opacity: 0; } }
    </style>
</head>
<body>
    <div class="container">
        <?php 
        $flag = isset($_GET['flag']) ? htmlspecialchars($_GET['flag']) : 'Unknown';
        $type = isset($_GET['type']) ? $_GET['type'] : 'unknown';
        ?>
        
        <h1 class="blink">🚨 FLAG DETECTED! 🚨</h1>
        
        <div class="gif-container">
            <?php if ($type == 'fake'): ?>
                <img src="https://media1.giphy.com/media/v1.Y2lkPTc5MGI3NjExNXZyOWhqc29jaWMzdXRocndkdXVtODg3M3lmeTB0c3BxYTFrZng2dCZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/GrMRh6ukoIMhpkeTHM/giphy.gif" 
                     alt="Hacker GIF" class="img-fluid" style="max-width: 500px;">
                <h2>NICE TRY, HACKER!</h2>
                <p>You found a <strong>FAKE FLAG</strong>! 😄</p>
                <p>This is a decoy - keep looking for the real ones...</p>
            <?php else: ?>
                <img src="https://media3.giphy.com/media/v1.Y2lkPTc5MGI3NjExNGR1ZjFvYnlnY2Nsemc2NGYwYThjbWEwY3dzOHo0MW1rd2RrMHRsdSZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/9Rjo1WLyS5LiOg1EdQ/giphy.gif" 
                     alt="Confused GIF" class="img-fluid" style="max-width: 500px;">
                <h2>UNKNOWN FLAG!</h2>
                <p>This flag format looks right, but it's not in our system! 🤔</p>
                <p>Make sure you're submitting the correct flags...</p>
            <?php endif; ?>
        </div>
        
        <div class="mt-4">
            <a href="index.php" class="btn btn-success">← Back to Portal</a>
            <a href="submit.php" class="btn btn-primary">Submit Real Flags</a>
        </div>
        
        <div class="mt-5">
            <small>Flag detected: <code><?php echo $flag; ?></code></small><br>
            <small>Type: <strong><?php echo ucfirst($type); ?></strong></small>
        </div>
        
        <?php if ($type == 'fake'): ?>
        <div class="mt-3">
            <small class="text-warning">💡 Hint: Look deeper into the system for real flags!</small>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>