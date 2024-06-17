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

    public function getAdminData(Request $request, Response $response){
        try{
            $subs = $this->adminRepository->getSubscribes();
            $bookings = $this->adminRepository->getNumberBookings();
            $profits = $this->adminRepository->getProfits();
            $available = $this->adminRepository->getVehicleByAvailable();

            $response->getBody()->write(json_encode([
                "subs" => $subs,
                "bookings" => $bookings,
                "profits" => $profits,
                "available" => $available
            ]));
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        }
        catch(PDOException $e){
            $response->getBody()->write(json_encode($e->getMessage()));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }
}