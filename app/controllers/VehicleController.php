<?php
namespace App\Controllers;

use App\Database;
use App\Repositories\VehicleRepository;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class VehicleController 
{
    protected $vehicleRepository;

    public function __construct() 
    {
        $database = new Database;

        $this->vehicleRepository = new VehicleRepository($database);
    }
    
    public function getAllVehicles(Request $request, Response $response) 
    {
        try{
            $data = $this->vehicleRepository->getAllVehicles();

            $body = json_encode($data); 
            
            $response->getBody()->write($body);
            return $response->withHeader('Content-Type', 'application/json');
        }
        catch(PDOException $e)
        {
            $response->getBody()->write(json_encode($e->getMessage()));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }

    public function getVehicleById(Request $request, Response $response, $args)
    {   
        try{
            $data = $this->vehicleRepository->getVehicleById($args['id']);
            
            $body = json_encode($data); 

            $response->getBody()->write($body);
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        }
        catch(PDOException $e)
        {
            $response->getBody()->write(json_encode($e->getMessage()));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }

    public function getVehicleByModel(Request $request, Response $response, $args)
    {   
        try{
            $data = $this->vehicleRepository->getVehicleByModel($args['modelo']);
            
            $body = json_encode($data); 

            $response->getBody()->write($body);
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        }
        catch(PDOException $e)
        {
            $response->getBody()->write(json_encode($e->getMessage()));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }

    public function createVehicle(Request $request, Response $response)
    {
        $data = get_object_vars(json_decode($request->getBody()));

        try{
            $this->vehicleRepository->createVehicle($data);
            $response->getBody()->write(json_encode(['message' => 'Vehicle create successfully']));
            return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
        }
        catch(PDOException $e)
        {
            $response->getBody()->write(json_encode($e->getMessage()));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }

    public function updateVehicleById(Request $request, Response $response, $args)
    {
        $data = json_decode($request->getBody());

        try{
            $this->vehicleRepository->updateVehicleById($data, $args["id"]);

            $response->getBody()->write(json_encode(['message' => "sucesso"]));
            return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
        }
        catch(PDOException $e)
        {
            $response->getBody()->write(json_encode(['message' => $e]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }

    public function deleteVehicleById(Request $request, Response $response, $args)
    {
        try{
            $this->vehicleRepository->deleteDetailsById($args['id']);

            $response->getBody()->write(json_encode(['message' => "sucesso"]));
            return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
        }
        catch(PDOException $e){
        $response->getBody()->write(json_encode($e->getMessage()));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }
}