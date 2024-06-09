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

    public function celularValidator($cel)
    {
        if(strlen($cel) != 17)
        {
            return "Número de caractéres inválido";
        }

        if(substr($cel, 0, 9) != "(+55)21-9" || !preg_match('/^\d+$/', substr($cel, 10, 8)))
        {
            return "Formato incorreto";
        }
    }

    public function telefoneValidator($tel)
    {
        if(!empty($tel)){
            if(strlen($tel) != 16){
                return "Número de caractéres inválido";
            }
            // AND substr($tel, 9) > 5
            if(substr($tel, 0, 8) != "(+55)21-" || 2 > substr($tel, 8, 1) || substr($tel, 8, 1) > 5 || !preg_match('/^\d+$/', substr($tel, 10, 7))){
                return "Formato incorreto";
            }
        }
        
    }
}