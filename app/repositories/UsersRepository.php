<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;

class UsersRepository
{   
    protected $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnection();
    }

    public function getColumnsUser(): array
    {
        $stmt = $this->pdo->query('SHOW COLUMNS FROM users');

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getColumnsAddress(): array
    {
        $stmt = $this->pdo->query('SHOW COLUMNS FROM address');

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function createAddress(array $data_columns): int
    {
        $columns_address = $this->getColumnsAddress();

        array_shift($columns_address);

        $placeholders_address = array_map(function($column) 
        {
            return ":$column";
        }, $columns_address);

        $sql = 'INSERT INTO address (' . implode(', ', $columns_address) . ') VALUES (' . implode(', ', $placeholders_address) . ')';

        $stmt = $this->pdo->prepare($sql);

        for($i = 0; $i < count($columns_address); $i++)
        {
            $stmt->bindValue($placeholders_address[$i], $data_columns[$columns_address[$i]]);
        }
        
        $stmt->execute();

        $stmt = $this->pdo->query('SELECT LAST_INSERT_ID()');

        $id_address = $stmt->fetch();

        return $id_address[0];
    }

    public function createUser(array $data_columns): bool
    {
        $data_columns["id_address"] = $this->createAddress($data_columns);

        $passwordHash = password_hash($data_columns["senha"], PASSWORD_DEFAULT);
        $data_columns["senha"] = $passwordHash;

        $columns_user = $this->getColumnsUser();

        array_shift($columns_user);

        $placeholders_user = array_map(function($column) 
        {
            return ":$column";
        }, $columns_user);

        $sql = 'INSERT INTO users (' . implode(', ', $columns_user) . ') VALUES (' . implode(', ', $placeholders_user) . ')';

        $stmt = $this->pdo->prepare($sql);

        for($i = 0; $i < count($columns_user); $i++)
        {
            $stmt->bindValue($placeholders_user[$i], $data_columns[$columns_user[$i]]);
        }
        
        return $stmt->execute();
    }

    public function updateUser(array $data_columns)
    {
        $columns_user = $this->getColumnsUser();

        foreach($columns_user as $key => $values){
            foreach($data_columns as $key2 => $values2){
                if($key == $key2){
                    $exist = true;
                }
            }
            if(!$exist){
                unset($columns_user[$key]);
            }
        }

        $placeholders_user = array_map(function($column) 
        {
            return ":$column";
        }, $columns_user);

        $sql = 'UPDATE users SET (' . implode(', ', $columns_user) . ') (' . implode(', ', $placeholders_user) . ')';

        $stmt = $this->pdo->prepare($sql);

        for($i = 0; $i < count($columns_user); $i++)
        {
            $stmt->bindValue($placeholders_user[$i], $data_columns[$columns_user[$i]]);
        }
        
        return $stmt->execute();
    }

    public function getUser($login)
    {
        $sql = 'SELECT * FROM users WHERE login = :login';

        // Prepara a consulta SQL
        $stmt = $this->pdo->prepare($sql);
        
        // Substitui o marcador de posição :login pelo valor fornecido
        $stmt->bindValue(':login', $login);

        // Executa a consulta
        $stmt->execute();

        // Retorna os resultados como um array associativo
        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
    }

    public function getAuth($cpf)
    {
        $sql = 'SELECT cpf FROM users WHERE cpf = :cpf';

        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindValue(':cpf', $cpf);
        
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function deleteUser($cpf)
    {
        $sql = 'DELETE FROM users WHERE cpf = ' . "'$cpf'";

        $stmt = $this->pdo->query($sql);

        return $stmt;
    }
}