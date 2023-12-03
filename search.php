<?php
require 'config.php'; // Include your config file

// Create a new PDO instance
try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Get the search term
$searchTerm = isset($_GET['term']) ? $_GET['term'] : '';

// Prepare and execute query
$stmt = $pdo->prepare("SELECT username FROM users WHERE username LIKE :term");
$stmt->execute(['term' => '%' . $searchTerm . '%']);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Output the results as JSON
header('Content-Type: application/json');
echo json_encode($results);
