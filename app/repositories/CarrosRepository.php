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

    public function getVehicles(): array
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->query('SELECT * FROM vehicles');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    //criar função com o nome listarCarrosPorId aqui
    public function listarVeiculosId($args): array
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->query('SELECT * FROM vehicles WHERE id = '.$args["id"]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteVehicles($args): array {
        $pdo = $this->database->getConnection();

        // $array_id = $pdo->query('SELECT id FROM vehicles')->fetchAll(PDO::FETCH_ASSOC);
        // if (in_array($string, $array)) {}
        // Código a ser estudado para fazer a verficação se o ID já foi excluído ou é inexistente
        
        $stmt = $pdo->query('DELETE FROM vehicles WHERE id = '.$args["id"]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}