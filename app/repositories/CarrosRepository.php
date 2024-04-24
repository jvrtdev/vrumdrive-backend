<?php 

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;

class CarrosRepository
{   
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getColumns(): array
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->query('SHOW COLUMNS FROM vehicles');

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getVehicles(): array
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->query('SELECT * FROM vehicles');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function listarVeiculosId($args): array
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->query('SELECT * FROM vehicles WHERE id = '.$args["id"]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}