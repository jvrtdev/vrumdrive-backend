<?php 

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;

class CarrosRepository
{
    protected $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnection();
    }

    public function getColumns(): array
    {
        $stmt = $this->pdo->query('SHOW COLUMNS FROM vehicles');

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getVehicles(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM vehicles');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function listarVeiculosId($args): array
    {
        $stmt = $this->pdo->query('SELECT * FROM vehicles WHERE id = '.$args["id"]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createVehicle(array $data_columns): bool
    {
        $columns = $this->getColumns();

        array_shift($columns);

        $placeholders = array_map(function($column) 
        {
            return ":$column";
        }, $columns);

        $sql = 'INSERT INTO vehicles (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $placeholders) . ')';

        $stmt = $this->pdo->prepare($sql);

        for($i = 0; $i < count($columns); $i++)
        {
            $stmt->bindValue($placeholders[$i], $data_columns[$columns[$i]]);
        }
        
        return $stmt->execute();
    }
    public function deleteVehicles($id): bool
    {
        $sql = 'DELETE FROM vehicles WHERE id = ' . $id;

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute();
    }

    public function updateVehicles()
    {

    }
}