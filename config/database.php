<?php
// db_connect.php

$mysql_host = 'localhost';
$mysql_db   = 'mini_bank_db';
$mysql_user = 'root'; 
$mysql_pass = '';     
$mysql_charset = 'utf8mb4';

$dsn = "mysql:host=$mysql_host;dbname=$mysql_db;charset=$mysql_charset";
$pdo_options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Strict error mode
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false, // True prepared statements
];

try {
    $mysql_pdo = new PDO($dsn, $mysql_user, $mysql_pass, $pdo_options);
} catch (\PDOException $e) {
    die("Database Connection failed. Please check credentials. Error: " . $e->getMessage());
}
