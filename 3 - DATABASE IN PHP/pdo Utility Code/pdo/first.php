<?php
echo "<pre>\n";
$pdo = new PDO('mysql:host=localhost;port=8889;dbname=misc',
    'fred', 'zap');

$stmt = $pdo->query("SELECT * FROM users");

// Retrieve a row and check for false in one statement
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    print_r($row);
}
echo "</pre>\n";
