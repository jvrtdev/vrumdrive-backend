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
    
    public function logUser(Request $request, Response $response) 
    {
        $data = $this->LogRepository->getLog();

        $body = json_encode($data);  
        
        $response->getBody()->write($body);
        return $response->withHeader('Content-Type', 'application/json');
    }
}