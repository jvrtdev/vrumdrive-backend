<?php
namespace App\Controllers;

use App\Database;
use PDOException;
use App\Repositories\AdminRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminController 
{
    protected $adminRepository;
  
    public function __construct() 
    {
        $database = new Database;
        
        $this->adminRepository = new AdminRepository($database);
    }

    public function getSubscribes(Request $request, Response $response){
        try{
            $data = $this->adminRepository->getSubscribes();

            $response->getBody()->write(json_encode(['subs' => $data]));
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        }
        catch(PDOException $e){
            $response->getBody()->write(json_encode($e->getMessage()));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }

    public function getBookings(Request $request, Response $response){
        try{
            $data = $this->adminRepository->getBookings();

            $response->getBody()->write(json_encode(['bookings' => $data]));
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        }
        catch(PDOException $e){
            $response->getBody()->write(json_encode($e->getMessage()));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }
}