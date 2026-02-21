<?php
$host = 'sql313.infinityfree.com';
$dbname = 'if0_41188703_ecommerse';
$user = 'if0_41188703';
$password = 'Joshua2370';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
