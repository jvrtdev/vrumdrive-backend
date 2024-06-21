<?php 

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;
use PDOException;
use App\Repositories\UserRepository;

class LogRepository
{
    protected $pdo;

    protected $userRepository;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnection();

        $this->userRepository = new userRepository($database);
    }

    public function getColumnsLog(): array
    {
        $stmt = $this->pdo->query('SHOW COLUMNS FROM logs');

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getAllLogs(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM logs LEFT JOIN users ON (logs.id_user = users.id_user)');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLogsById($id): array
    {
        $stmt = $this->pdo->query('SELECT * FROM logs LEFT JOIN users ON (logs.id_user = users.id_user) WHERE log_id = ' . $id);

        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
    }

    public function getFilterLogs($value)
    {   
        $columns_log = $this->getColumnsLog();
        unset($columns_log[1]);

        $columns_user = $this->userRepository->getColumnsUser();
        array_shift($columns_user);

        $columns = array_merge($columns_log, $columns_user);

        $fields = [];

        foreach ($columns as $values)
        {
            $fields[] = "$values LIKE '%$value%'";
        }
        
        $fields = implode(" or ", $fields);

        $sql = 'SELECT * FROM logs LEFT JOIN users ON (logs.id_user = users.id_user) where ' . $fields;

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createLog($data): bool
    {
        $columns_log = $this->getColumnsLog();

        array_shift($columns_log);

        $placeholders_log = array_map(function($column) 
        {
            return ":$column";
        }, $columns_log);

        $sql = 'INSERT INTO logs (' . implode(', ', $columns_log) . ') VALUES (' . implode(', ', $placeholders_log) . ')';

        $stmt = $this->pdo->prepare($sql);

        for($i = 0; $i < count($columns_log); $i++)
        {
            $stmt->bindValue($placeholders_log[$i], $data[$columns_log[$i]]);
        }

        return $stmt->execute();
    }
}