<?php
namespace App\Controllers;

use App\Database;
use App\Repositories\BookingRepository;
use App\Repositories\VehicleRepository;
use App\Services\BookingService;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class BookingController
{ 
  protected $bookingRepository;

  protected $bookingService;

  protected $vehicleRepository;
  
  public function __construct()
  {
    $database = new Database();
    
    $this->bookingRepository = new BookingRepository($database);

    $this->bookingService = new BookingService();

    $this->vehicleRepository = new VehicleRepository($database);
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

  public function createNewBooking(Request $request, Response $response, $args)
  {
    $data = get_object_vars(json_decode($request->getBody()));
    
    try{
      $vehicle = $this->vehicleRepository->getVehicleById($data['id_vehicle']); 
      $pricePerDay = $vehicle['preco'];
      
      $days = $this->bookingService->calculateIntervalDays($data['pickupDate'],$data['returnDate']);
      $bookingPrice = $this->bookingService->calculateBooking($pricePerDay, $days);
      
      $data['total_price'] = $bookingPrice;

      $newBooking = $this->bookingRepository->createNewBooking($data);
      
      $response->getBody()->write(json_encode(["Reserva feita com sucesso!" => $newBooking]));
      return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
      
    }
    catch(PDOException $e)
    {
      $response->getBody()->write(json_encode($e->getMessage()));
      return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
  }
  
  public function getAllBookings(Request $resquest, Response $response, $args)
  {
    
  }
  
  public function getAllBookingsByUserId(Request $resquest, Response $response, $args)
  {
    
  }
  
  public function updateBookingById(Request $resquest, Response $response, $args)
  {
    
  }
  
  public function deleteBookingById(Request $resquest, Response $response, $args)
  {
    
  }
  
}