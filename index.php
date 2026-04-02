<?php
// ============================================
// MySQL Database Connection (nfldb)
// ============================================

// Database credentials
$host     = '127.0.0.1';      // localhost or your DB host
$port     = 3306;              // MySQL default port
$dbname   = 'nfldatabase';     // your database
$username = 'root';            // change to your DB username
$password = '';                // change to your DB password
$charset  = 'utf8mb4';

// Create DSN (Data Source Name)
$dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";

// PDO options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Create PDO connection
    $pdo = new PDO($dsn, $username, $password, $options);
    echo "✓ Connected to MySQL nfldb successfully!\n\n";

    // Example: Get list of tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll();
    
    echo "Tables in nfldb:\n";
    foreach ($tables as $table) {
        echo "  - " . reset($table) . "\n";
    }

} catch (PDOException $e) {
    echo "✗ Connection Error: " . $e->getMessage() . "\n";
    exit(1);
}

// ============================================
// Example: Query a table
// ============================================

echo "\n--- Sample Query ---\n";
try {
    // Change 'team' to your actual table name
    $sql = "SELECT * FROM team LIMIT 5";
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();

    if (empty($results)) {
        echo "No records found.\n";
    } else {
        echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    }
} catch (PDOException $e) {
    echo "Query Error: " . $e->getMessage() . "\n";
}
?>
