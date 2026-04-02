<?php
// ============================================
// MySQL Import Script for NFL Database
// ============================================

$host     = '127.0.0.1';
$port     = 3306;
$username = 'root';        // change if different
$password = '';            // change if different
$charset  = 'utf8mb4';

// Read SQL file
$sqlFile = __DIR__ . '/nfldb_mysql.sql';

if (!file_exists($sqlFile)) {
    die("Error: nfldb_mysql.sql not found at: $sqlFile\n");
}

$sql = file_get_contents($sqlFile);

// Split queries by semicolon
$queries = array_filter(array_map('trim', explode(';', $sql)));

// Create initial connection (without database)
$dsn = "mysql:host={$host};port={$port};charset={$charset}";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    echo "Connected to MySQL server.\n";
    
    $count = 0;
    foreach ($queries as $query) {
        if (empty($query)) continue;
        
        try {
            $pdo->exec($query);
            $count++;
            echo "✓ Executed query " . $count . "\n";
        } catch (PDOException $e) {
            echo "⚠ Query error: " . $e->getMessage() . "\n";
            echo "Query: " . substr($query, 0, 100) . "...\n";
        }
    }
    
    echo "\n✓ Import complete! $count queries executed.\n";
    
    // Test the connection to nfldb
    $pdo2 = new PDO("mysql:host={$host};port={$port};dbname=nfldb;charset={$charset}", $username, $password, $options);
    echo "✓ nfldb database is ready!\n\n";
    
    // Show tables
    $stmt = $pdo2->query("SHOW TABLES");
    $tables = $stmt->fetchAll();
    
    echo "Tables created:\n";
    foreach ($tables as $table) {
        echo "  - " . reset($table) . "\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
