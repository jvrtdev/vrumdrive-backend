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

    public function createUser($nome, $data_nasc, $sexo, $nome_mat, $cpf, $email, $celular, $telefone, $cep, $estado, $cidade, $rua, $logradouro, $login, $senha)
    {
        $sql = 'INSERT INTO users (nome, data_nasc, sexo, nome_mat, cpf, email, celular, telefone, cep, estado, cidade, rua, logradouro, login, senha) values (:nome, :data_nasc, :sexo, :nome_mat, :cpf, :email, :celular, :telefone, :cep, :estado, :cidade, :rua, :logradouro, :login, :senha)';

        $pdo = $this->database->getConnection();

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':data_nasc', $data_nasc);
        $stmt->bindValue(':sexo', $sexo);
        $stmt->bindValue(':nome_mat', $nome_mat);
        $stmt->bindValue(':cpf', $cpf);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':celular', $celular);
        $stmt->bindValue(':telefone', $telefone);
        $stmt->bindValue(':cep', $cep);
        $stmt->bindValue(':estado', $estado);
        $stmt->bindValue(':cidade', $cidade);
        $stmt->bindValue(':rua', $rua);
        $stmt->bindValue(':logradouro', $logradouro);
        $stmt->bindValue(':login', $login);
        $stmt->bindValue(':senha', $senha);
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