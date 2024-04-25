<?php
namespace App\Controllers;

use App\Database;
use App\Repositories\CarrosRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CarroController {
    public function hello(Request $request, Response $response)
    {   
        $response->getBody()->write("Hello world!");
        return $response;
    }
    
    public function carros(Request $request, Response $response) 
    {
        $database = new Database;
        
        $repository = new CarrosRepository($database);

        $data = $repository->getVehicles();

        $body = json_encode($data);  
        
        $response->getBody()->write($body);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function carros_id(Request $request, Response $response, $args)
    //falta alterar essa função usando repository
    {
        $database = new Database;
        
        $repository = new CarrosRepository($database);

        $data = $repository->listarVeiculosId($args);
        
        $body = json_encode($data);  
        
        $response->getBody()->write($body);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function carros_id_delete(Request $request, Response $response, $args)
    
    {
        $database = new Database;
        
        $repository = new CarrosRepository($database);

        $execute = $repository->deleteVehicles($args);

        $response->getBody()->write('Veiculo com o ID: '. $args["id"] .' Excluído com sucesso!');
        return $response->withHeader('Content-Type', 'application/json');
    }

}