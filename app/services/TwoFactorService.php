<?php

declare(strict_types=1);

namespace App\Services;

use App\Database;
use App\Repositories\UserRepository;
use App\Repositories\LogRepository;

class TwoFactorService
{
    protected $userRepository;

    protected $logRepository;

    public function __construct() 
    {
        $database = new Database;
        
        $this->userRepository = new UserRepository($database);

        $this->logRepository = new LogRepository($database);
    }

    public function twoFactor($id, $data)
    {
        if (!isset($_SESSION['failed_attempts'])) {
            $_SESSION['failed_attempts'] = 0;
        }

        $user = $this->userRepository->getUserById($id);

        $verifier = ["data_nasc" => $user["data_nasc"], "nome_mat" => $user["nome_mat"], "cep" => $user["cep"]];
        $response = "";

        foreach($verifier as $key => $value){
            if($value == $data["response"]){
                $response = $key;
            }
        }

        if($data["question"] == $response)
        {
            $_SESSION['failed_attempts'] = 0;
            return $this->logRepository->createLog(["id_user" => $id, "fator_autenticacao" => $response, "data_hora_login" => date('Y-m-d H:i:s')]);
        }

        if($_SESSION['failed_attempts'] >= 2)
        {
            $_SESSION['failed_attempts'] = 0;
            return false;
        }

        $_SESSION['failed_attempts']++;
        return $_SESSION['failed_attempts'];
    }
}