<?php
namespace App\Validation;

class Validate
{
    public function cpfValidator($cpf)
    {
      $cpf = preg_replace('/[^0-9]/', '', $cpf);

      if (strlen($cpf) != 11)
      {
          return "Número de caractéres inválido";
      }

      if (preg_match('/(\d)\1{10}/', $cpf)) 
      {
          return "CPF inválido";
      }
      
      for ($i = 10, $soma = 0, $k = 0; $i > 1; $i--) 
      {
          $soma += $cpf[$k++] * $i;
      }
      
      $verificador1 = (($soma % 11) < 2) ? 0 : 11 - ($soma % 11);
      
      for ($i = 11, $soma = 0, $k = 0; $i > 1; $i--) 
      {
          $soma += $cpf[$k++] * $i;
      }
      
      $verificador2 = (($soma % 11) < 2) ? 0 : 11 - ($soma % 11);

      if ($verificador1 != $cpf[9] || $verificador2 != $cpf[10]) 
      {
        return "CPF inexistente";
      }

      return false;
    }
}