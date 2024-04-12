<?php
namespace App\Controllers;

use App\Database;
use App\Repositories\CarrosRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CarroController 
{
    protected $vehicleRepository;

    public function __construct() 
    {
        $database = new Database;

        $this->vehicleRepository = new CarrosRepository($database);
    }

    public function hello(Request $request, Response $response)
    {   
        $response->getBody()->write("Hello world!");
        return $response;
    }
    
    public function carros(Request $request, Response $response) 
    {
        $data = $this->vehicleRepository->getVehicles();

        $body = json_encode($data);  
        
        $response->getBody()->write($body);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function carros_id(Request $request, Response $response, $args)
    //falta alterar essa função usando repository
    {
        $data = $this->vehicleRepository->listarVeiculosId($args);
        
        $body = json_encode($data);  
        
        $response->getBody()->write($body);
        return $response->withHeader('Content-Type', 'application/json');
    }
}