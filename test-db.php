<?php
echo "Testing database connection...\n";

$conn = new mysqli('localhost', 'root', 'mystery123', 'mystery_corp', 3306);

if ($conn->connect_error) {
    echo "❌ Connection failed: " . $conn->connect_error . "\n";
    exit(1);
}

echo "✅ Database connected successfully!\n";

$result = $conn->query("SELECT username, password, role FROM users");
if ($result) {
    echo "Users in database:\n";
    while ($row = $result->fetch_assoc()) {
        echo "- {$row['username']} / {$row['password']} ({$row['role']})\n";
    }
} else {
    echo "❌ Error querying users: " . $conn->error . "\n";
}

$conn->close();
?>