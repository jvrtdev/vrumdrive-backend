<?php

declare(strict_types=1);

namespace App;

use PDO;

class Database 
{
    public function getConnection(): PDO
    {
        $dsn = "mysql:host=vrumdrive-db.cvuuqw68ubdh.us-east-1.rds.amazonaws.com;dbname=vrumvrum";
        
        $pdo = new PDO($dsn, "admin", "wNbg(%[Jb)CB#v)96<3G?JN>tL?x", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        return $pdo;
    }
}