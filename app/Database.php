<?php

declare(strict_types=1);

namespace App;

use PDO;

class Database 
{
    public function getConnection(): PDO
    {
        $dsn = "mysql:host=".$_ENV['DB_HOST'].";dbname=". $_ENV['DB_NAME'];
        
        $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        return $pdo;
    }
}