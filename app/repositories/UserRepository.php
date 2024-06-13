<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;

class UserRepository
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
    
    public function getAllUsers(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM users LEFT JOIN address ON (users.id_user = address.id_user)');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id): array
    {
        $stmt = $this->pdo->query('SELECT * FROM users LEFT JOIN address ON (users.id_user = address.id_user) WHERE id_address = ' . $id);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserLogin($login): array
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

    public function createAddress(array $data_columns, $user_id): bool
    {
        $data_columns["id_user"] = $user_id;

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
        
        return $stmt->execute();
    }

    public function createUser(array $data_columns): bool
    {
        $passwordHash = password_hash($data_columns["senha"], PASSWORD_DEFAULT);
        $data_columns["senha"] = $passwordHash;

        $columns_user = $this->getColumnsUser();

        foreach($columns_user as $key => $values)
        { 
            $exist = false;
            foreach($data_columns as $key2 => $values2)
            {
                if($values == $key2)
                {
                    $exist = true;
                }
            }
            if(!$exist)
            {
                unset($columns_user[$key]);
            }
        }
        sort($columns_user);

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
        
        $stmt->execute();

        $stmt = $this->pdo->query('SELECT LAST_INSERT_ID()');

        $id_user = $stmt->fetch()[0];

        return $this->createAddress($data_columns, $id_user);
    }

    public function updateAddress(array $data_columns, $id_user): bool
    {
        $columns_address = $this->getColumnsaddress();
        
        foreach($columns_address as $key => $values)
        { 
            $exist = false;
            foreach($data_columns as $key2 => $values2)
            {
                if($values == $key2)
                {
                    $exist = true;
                }
            }
            if(!$exist)
            {
                unset($columns_address[$key]);
            }
        }
        sort($columns_address);

        if(empty($columns_address))
        {
            return false;
        }
        
        $placeholders_address = array_map(function($column) 
        {
            return ":$column";
        }, $columns_address);
         
        $update = "";
        for($i = 0; $i < count($columns_address); $i++)
        {
            $update = $update . ", " . $columns_address[$i] . " = " . $placeholders_address[$i];
        }
        $update = substr($update, 2);

        $sql = 'UPDATE address SET ' . $update . ' WHERE id_user = ' . $id_user;
        $stmt = $this->pdo->prepare($sql);

        for($i = 0; $i < count($columns_address); $i++)
        {
            $stmt->bindValue($placeholders_address[$i], $data_columns[$columns_address[$i]]);
        }
        
        return $stmt->execute();
    }

    public function updateUser(array $data_columns, $args): bool
    {
        $this->updateAddress($data_columns, $args["id"]);

        $columns_user = $this->getColumnsUser();
        
        foreach($columns_user as $key => $values)
        { 
            $exist = false;
            foreach($data_columns as $key2 => $values2)
            {
                if($values == $key2)
                {
                    $exist = true;
                }
            }
            if(!$exist)
            {
                unset($columns_user[$key]);
            }
        }
        sort($columns_user);
        
        $placeholders_user = array_map(function($column) 
        {
            return ":$column";
        }, $columns_user);
         
        $update = "";
        for($i = 0; $i < count($columns_user); $i++)
        {
            $update = $update . ", " . $columns_user[$i] . " = " . $placeholders_user[$i];
        }
        $update = substr($update, 2);

        $sql = 'UPDATE users SET ' . $update . ' WHERE id_user = ' . $args["id"];
        $stmt = $this->pdo->prepare($sql);

        for($i = 0; $i < count($columns_user); $i++)
        {
            $stmt->bindValue($placeholders_user[$i], $data_columns[$columns_user[$i]]);
        }
        
        return $stmt->execute();
    }

    public function deleteAddressById($id)
    {
        $sql = 'DELETE FROM address WHERE id_user = :id';

        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindValue(':id', $id);
        
        return $stmt->execute();
    }

    public function deleteUserById($id)
    {
        $this->deleteAddressById($id);

        $sql = 'DELETE FROM users WHERE id_user = :id';

        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindValue(':id', $id);
        
        return $stmt->execute();
    }
}