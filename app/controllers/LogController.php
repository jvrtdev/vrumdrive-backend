<?php
namespace App\Controllers;

use App\Database;
use App\Repositories\LogRepository;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LogController 
{
    protected $LogRepository;

    public function __construct() 
    {
        $database = new Database;

        $this->LogRepository = new LogRepository($database);
    }
    
    public function getAllLogs(Request $request, Response $response) 
    {
        $data = $this->LogRepository->getAllLogs();

        $body = json_encode($data);  
        
        $response->getBody()->write($body);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getLogsById(Request $request, Response $response, $args) 
    {
        $data = $this->LogRepository->getLogsById($args["id"]);

        $body = json_encode($data);  
        
        $response->getBody()->write($body);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getFilterLogs(Request $request, Response $response, $args) 
    {
        //$columns_user = 

        $data = $this->LogRepository->getFilterLogs($args["value"]);

        $body = json_encode($data);  
        
        $response->getBody()->write($body);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function createLog(Request $request, Response $response)
    {
        $data = get_object_vars(json_decode($request->getBody()));

        try{
            $this->LogRepository->createLog($data);
            $response->getBody()->write(json_encode(['message' => 'Log created successfully']));
            return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
        }
        catch(PDOException $e)
        {
            $response->getBody()->write(json_encode($e->getMessage()));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }
}