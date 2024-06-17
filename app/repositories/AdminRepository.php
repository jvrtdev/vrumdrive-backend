<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;
use PDOException;

class AdminRepository
{   
    protected $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnection();
    }

    public function getSubscribes()
    {
        $stmt = $this->pdo->query('SELECT COUNT(*) FROM users');

        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["COUNT(*)"];
    }

    public function getBookings()
    {
        $stmt = $this->pdo->query('SELECT COUNT(*) FROM bookings');

        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["COUNT(*)"];
    }
}