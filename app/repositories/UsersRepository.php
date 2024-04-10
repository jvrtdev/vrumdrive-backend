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

    public function listarcolunas(): array
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->query('SHOW COLUMNS FROM users');

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function createUser(array $columns)
    {
        $sql = 'INSERT INTO users (nome, datanasc, sexo, nome_mat, cpf, email, celular, telefone, cep, estado, cidade, rua, logradouro, login, senha) values (:nome, :datanasc, :sexo, :nome_mat, :cpf, :email, :celular, :telefone, :cep, :estado, :cidade, :rua, :logradouro, :login, :senha)';

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nome', $columns[1]);
        $stmt->bindValue(':datanasc', $columns[2]);
        $stmt->bindValue(':sexo', $columns[3]);
        $stmt->bindValue(':nome_mat', $columns[4]);
        $stmt->bindValue(':cpf', $columns[5]);
        $stmt->bindValue(':email', $columns[6]);
        $stmt->bindValue(':celular', $columns[7]);
        $stmt->bindValue(':telefone', $columns[8]);
        $stmt->bindValue(':login', $columns[9]);
        $stmt->bindValue(':senha', $columns[10]);
        $stmt->bindValue(':cep', $columns[11]);
        $stmt->bindValue(':estado', $columns[12]);
        $stmt->bindValue(':cidade', $columns[13]);
        $stmt->bindValue(':rua', $columns[14]);
        $stmt->bindValue(':logradouro', $columns[15]);
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