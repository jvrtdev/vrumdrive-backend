<?php
namespace App\Controllers;

use App\Database;
use App\Repositories\BookingRepository;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class BookingController
{ 
  protected $bookingRepository;
  
  public function __construct()
  {
    $database = new Database();
    
    $this->bookingRepository = new BookingRepository($database);
  }

  public function createNewBooking(Request $resquest, Response $response, $args)
  {
    
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