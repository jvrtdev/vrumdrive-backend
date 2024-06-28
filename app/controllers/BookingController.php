<?php
namespace App\Controllers;

use App\Database;
use App\Repositories\BookingRepository;
use App\Repositories\VehicleRepository;
use App\Repositories\UserRepository;
use App\Services\BookingService;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class BookingController
{ 
  protected $bookingRepository;

  protected $bookingService;

  protected $vehicleRepository;

  protected $userRepository;
  
  public function __construct()
  {
    $database = new Database();
    
    $this->bookingRepository = new BookingRepository($database);

    $this->bookingService = new BookingService();
    
    $this->vehicleRepository = new VehicleRepository($database);

    $this->userRepository = new userRepository($database);
  }

  public function getTotalPriceBooking(Request $request, Response $response)
  {
    $data = get_object_vars(json_decode($request->getBody()));
    try{
      
      $vehicle = $this->vehicleRepository->getVehicleById($data['id_vehicle']); 
      $pricePerDay = $vehicle['preco'];
      $days = $this->bookingService->calculateIntervalDays($data['pickupDate'],$data['returnDate']);
      $bookingPrice = $this->bookingService->calculateBooking($pricePerDay, $days);

      $response->getBody()->write(json_encode([
        "interval_days" => $days,
        "total_price" => $bookingPrice,
        "vehicle" => $vehicle
      ]));
      return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
      
    }
    catch(PDOException $e)
    {
      $response->getBody()->write(json_encode($e->getMessage()));
      return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

  }

  public function createNewBooking(Request $request, Response $response)
  {
    $data = get_object_vars(json_decode($request->getBody()));
    
    try{
      $vehicle = $this->vehicleRepository->getVehicleById($data['id_vehicle']);
      
      if($vehicle["status"] == "ocupado")
      {
        $response->getBody()->write(json_encode(["message" => "Veículo já reservado"]));
        return $response->withStatus(409)->withHeader('Content-Type', 'application/json');
      }
      
      $pricePerDay = $vehicle['preco'];
      
      $days = $this->bookingService->calculateIntervalDays($data['pickup_date_time'],$data['return_date_time']);
      $bookingPrice = $this->bookingService->calculateBooking($pricePerDay, $days);
      
      $data['total_price'] = $bookingPrice;

      $this->bookingRepository->createNewBooking($data);

      $this->vehicleRepository->updateVehicleById(["status" => "ocupado"], $data['id_vehicle']);
      
      $response->getBody()->write(json_encode(["message" => "Reserva feita com sucesso!"]));
      return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
      
    }
    catch(PDOException $e)
    {
      $response->getBody()->write(json_encode($e->getMessage()));
      return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
  }
  
  public function getAllBookings(Request $resquest, Response $response)
  {
    try{
      $data = $this->bookingRepository->getAllBookings();

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
  
  public function getBookingById(Request $resquest, Response $response, $args)
  {
    try{
      $data = $this->bookingRepository->getBookingById($args["id"]);

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

  public function getBookingByUserId(Request $resquest, Response $response, $args)
  {
    try{
      $data = $this->bookingRepository->getBookingByUserId($args["id"]);

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
  
  public function deleteBookingById(Request $resquest, Response $response, $args)
  {
    try{
      $this->bookingService->handleStatusVehicleOnDeleteBooking($args["id"]);
      
      $response->getBody()->write(json_encode(['message' => "sucesso"]));
      return $response->withHeader('Content-Type', 'application/json');
    }
    catch(PDOException $e)
    {
      $response->getBody()->write(json_encode($e->getMessage()));
      return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
  }
}