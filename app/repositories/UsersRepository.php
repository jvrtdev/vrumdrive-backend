<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;

class UsersRepository
{   
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getColumns(): array
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->query('SHOW COLUMNS FROM users');

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function createUser(array $data_columns)
    {
        $columns = $this->getColumns();

        array_shift($columns);

        $placeholders = array_map(function($column) {
            return ":$column";
        }, $columns);

        $sql = 'INSERT INTO users (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $placeholders) . ')';

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        for($i = 0; $i < count($columns); $i++){
            $stmt->bindValue($placeholders[$i], $data_columns[$columns[$i]]);
        }
        
        return $stmt->execute();
    }

    public function getUser($login)
    {
        $sql = 'SELECT * FROM users WHERE (login = :login)';

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':login', $login);

        return $stmt->execute();
    }
}