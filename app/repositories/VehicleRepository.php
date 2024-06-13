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

    public function getColumnsVehicles(): array
    {
        $stmt = $this->pdo->query('SHOW COLUMNS FROM vehicles');

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getColumnsDetails(): array
    {
        $stmt = $this->pdo->query('SHOW COLUMNS FROM vehicles_details');

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getAllVehicles(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM vehicles LEFT JOIN vehicles_details ON (vehicles.id_details = vehicles_details.id_details)');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getVehicleById($id): array
    {
        $stmt = $this->pdo->query('SELECT * FROM vehicles LEFT JOIN vehicles_details ON (vehicles.id_details = vehicles_details.id_details) WHERE id_vehicle = ' . $id);

        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
    }

    public function createDetals(array $data_columns): int
    {
        $columns_details = $this->getColumnsDetails();

        array_shift($columns_details);

        $placeholders_details = array_map(function($column) 
        {
            return ":$column";
        }, $columns_details);

        $sql = 'INSERT INTO vehicles_details (' . implode(', ', $columns_details) . ') VALUES (' . implode(', ', $placeholders_details) . ')';

        $stmt = $this->pdo->prepare($sql);

        for($i = 0; $i < count($columns_details); $i++)
        {
            $stmt->bindValue($placeholders_details[$i], $data_columns[$columns_details[$i]]);
        }
        
        $stmt->execute();

        $stmt = $this->pdo->query('SELECT LAST_INSERT_ID()');

        $id_details = $stmt->fetch();

        return $id_details[0];
    }

    public function createVehicle(array $data_columns): bool
    {
        $data_columns["id_details"] = $this->createDetals($data_columns);

        $columns_vehicles = $this->getColumnsVehicles();

        array_shift($columns_vehicles);

        $placeholders_vehicles = array_map(function($column) 
        {
            return ":$column";
        }, $columns_vehicles);

        $sql = 'INSERT INTO vehicles (' . implode(', ', $columns_vehicles) . ') VALUES (' . implode(', ', $placeholders_vehicles) . ')';

        $stmt = $this->pdo->prepare($sql);

        for($i = 0; $i < count($columns_vehicles); $i++)
        {
            $stmt->bindValue($placeholders_vehicles[$i], $data_columns[$columns_vehicles[$i]]);
        }
        
        return $stmt->execute();
    }

    //arrumar o Update e o Delete

    public function updateVehicles($data, $id)
    {
        $fields = [];
        $values = [];
    
        foreach ($data as $key => $value)
        {
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
        } 
        catch (PDOException $e) 
        {
            // Log do erro ou outra manipulação de erro
            return false;
        }
    }

    public function deleteVehicleById($id): bool
    {
        $sql = 'DELETE FROM vehicles WHERE id_vehicle = :id';

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }
}