<?php
namespace App\Validation;

use Exception;
use \Firebase\JWT\JWT;


class AuthUser
{
  private $secret_key;

  public function __construct($secret_key)
  {
    $this->secret_key = $secret_key;
  }
  
  
  public function createToken($userData)
  {
    $payload = [
      'cpf' => $userData['cpf'],
      'login' => $userData['login'],
      'senha' => $userData['senha'],
    ];
    
    $jwt = JWT::encode($payload, $this->secret_key , 'HS256');//args->informacoes, chave secreta, criptografia
    
    return $jwt;
  }


  public function authToken($token)
  {
    try {
      $decoded = JWT::decode($token, $this->secret_key);
      
      $userCpf = $decoded->cpf;
      //falta adicionar a logica aqui para fazer a autenticação do token com as informacoes do usuario
      return $userCpf;
      
    } catch (Exception $e) {
      # code...
      echo $e->getMessage();
    }
  }
}