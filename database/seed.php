<?php
// A command-line script to seed the SQLite database.
// To run: php database/seed.php from the project root directory.

// The Database class will load the config for us.
require_once __DIR__ . '/../src/Database.php';

echo "Starting database seeding...\n";

// 1. For a clean seed, we first remove the old database file if it exists.
if (file_exists(DB_PATH)) {
    unlink(DB_PATH);
    echo "Removed old database file.\n";
}

try {
    // 2. Get a PDO connection using our singleton.
    // The first time this is called, it will create the empty .sqlite file.
    $pdo = Database::getInstance()->getConnection();
    echo "New database file created at: " . DB_PATH . "\n";

    // 3. Read the entire schema.sql file into a string.
    $sql = file_get_contents(__DIR__ . '/schema.sql');
    if ($sql === false) {
        throw new Exception("Failed to read the schema.sql file.");
    }
    echo "Successfully read schema.sql.\n";

    // 4. Execute the multi-statement SQL string to build the database.
    $pdo->exec($sql);
    echo "Database seeded successfully! The 'admin' user is now available.\n";

} catch (Exception $e) {
    // Catch any errors and display a helpful message.
    die("ERROR: Could not seed the database. " . $e->getMessage() . "\n");
}
