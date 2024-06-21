<?php

declare(strict_types=1);

namespace App\Services;

use App\Database;
use App\Repositories\UserRepository;

class TwoFactorService
{
    protected $userRepository;

    public function __construct() 
    {
        $database = new Database;
        
        $this->userRepository = new UserRepository($database);
    }

    public function twoFactor($id, $data): bool
    {
        $user = $this->userRepository->getUserById($id);

        $verifier = ["data_nasc" => $user["data_nasc"], "nome_mat" => $user["nome_mat"], "cep" => $user["cep"]];

        foreach($verifier as $key => $value){
            if($value == $data["response"]){
                $response = $key;
            }
        }

        if($data["question"] == $response){
            return true;
        }
        return false;
    }
}