<?php
// define all the info that is needed for database connection
define('DB_HOST', 'localhost');//define Host
    define('DB_NAME', 'Alzikrayat');// database name 
    define('DB_USER', 'your user name!!');//database user
    define('DB_PASS', 'your password!!!');//data base password
    define('DB_CHARSET', 'utf8mb4');//charset for the database 
    // this the connection defenition that will be used
    define('DBCONNECTION', 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET); 
//try catsh for making the connection and return the connection if succeed and error in case of falier
try{
    $pdo = new PDO(DBCONNECTION, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}catch(Exception $e){
    die('Database connection failed: ');
}