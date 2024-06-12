<?php 

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;
use PDOException;

class LogRepository
{
    protected $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnection();
    }

    public function getLog(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM logs');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}