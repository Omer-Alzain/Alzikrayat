<?php
//class for making connection with the database
//Params:$db to store the pdo connection
class Model
{
    protected $db;

    //initializing the connection.
    public function __construct()
    {
        $this->db = require __DIR__ . '/../config/database.php';
    }
}