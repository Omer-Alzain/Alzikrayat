<?php
define('DB_HOST', 'localhost');
    define('DB_NAME', 'Alzikrayat');
    define('DB_USER', 'username of the database');
    define('DB_PASS', 'the pass word of your database');
    define('DB_CHARSET', 'utf8mb4');
    define('DBCONNECTION', 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET);
try{
    $pdo = new PDO(DBCONNECTION, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}catch(Exception $e){
    die('Database connection failed: ');
}