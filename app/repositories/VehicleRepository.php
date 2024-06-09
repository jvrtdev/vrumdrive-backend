<?php 

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;
use PDOException;

class VehicleRepository
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
    
    public function getVehicleById($id): array
    {
        $stmt = $this->pdo->query('SELECT * FROM vehicles WHERE id_vehicle = '.$id);

        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
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
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    public function deleteVehicleById($id): bool
    {
        $sql = 'DELETE FROM vehicles WHERE id = ' . $id;

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    }

    public function updateVehicles($data, $id)
    {
        $fields = [];
        $values = [];
    
        foreach ($data as $key => $value){
            $fields[] = "$key = :$key ";
            $values[":$key"] = $value; // Adicione ':' ao nome do parâmetro
        }
    
        $fields = implode(", ", $fields);
        
        $sql = "UPDATE vehicles SET $fields WHERE id = :id";
    
        $stmt = $this->pdo->prepare($sql);
    
        $values[':id'] = $id; // Adicione o ID ao array de valores
    
        try {
            $stmt->execute($values);
    
            if ($stmt->rowCount() > 0) {
                return true; // Atualização bem-sucedida
            } else {
                return false; // Nenhuma linha afetada
            }
        } catch (PDOException $e) {
            // Log do erro ou outra manipulação de erro
            return false;
        }
    }
}