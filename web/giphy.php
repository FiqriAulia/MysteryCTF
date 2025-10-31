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
        <h1 class="blink">🚨 FLAG DETECTED! 🚨</h1>
        
        <div class="gif-container">
            <img src="https://media.giphy.com/media/3o7abKhOpu0NwenH3O/giphy.gif" 
                 alt="Hacker GIF" class="img-fluid" style="max-width: 500px;">
        </div>
        
        <h2>NICE TRY, HACKER!</h2>
        <p>You found a flag, but this one doesn't count for points! 😄</p>
        <p>Keep exploring for the real challenges...</p>
        
        <div class="mt-4">
            <a href="index.php" class="btn btn-success">← Back to Portal</a>
            <a href="submit.php" class="btn btn-primary">Submit Real Flags</a>
        </div>
        
        <div class="mt-5">
            <small>Flag detected: <code><?php echo isset($_GET['flag']) ? htmlspecialchars($_GET['flag']) : 'Unknown'; ?></code></small>
        </div>
    </div>
</body>
</html>