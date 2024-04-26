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

    public function getColumnsUser(): array
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->query('SHOW COLUMNS FROM users');

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getColumnsAddress(): array
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->query('SHOW COLUMNS FROM address');

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function createAddress(array $data_columns): string
    {
        $columns_address = $this->getColumnsAddress();

        array_shift($columns_address);

        $placeholders_address = array_map(function($column) 
        {
            return ":$column";
        }, $columns_address);

        $sql = 'INSERT INTO address (' . implode(', ', $columns_address) . ') VALUES (' . implode(', ', $placeholders_address) . ')';

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        for($i = 0; $i < count($columns_address); $i++)
        {
            $stmt->bindValue($placeholders_address[$i], $data_columns[$columns_address[$i]]);
        }
        
        $stmt->execute();

        $stmt = $pdo->query('SELECT LAST_INSERT_ID()');

        $id_address = $stmt->fetch();

        return $id_address[0];
    }

    public function createUser(array $data_columns): bool
    {
        $data_columns["id_address"] = $this->createAddress($data_columns);

        $columns_user = $this->getColumnsUser();

        array_shift($columns_user);

        $placeholders_user = array_map(function($column) 
        {
            return ":$column";
        }, $columns_user);

        $sql = 'INSERT INTO users (' . implode(', ', $columns_user) . ') VALUES (' . implode(', ', $placeholders_user) . ')';

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);

        for($i = 0; $i < count($columns_user); $i++)
        {
            $stmt->bindValue($placeholders_user[$i], $data_columns[$columns_user[$i]]);
        }
        
        return $stmt->execute();
    }
}