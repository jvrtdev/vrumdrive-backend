<?php

namespace App\Services;

use App\Database;
use App\Repositories\BookingRepository;
use App\Repositories\VehicleRepository;
use DateTime;
use PDOException;

class BookingService
{
    private $bookingRepository;
    private $vehicleRepository;

    public function __construct()
    {
      $database = new Database();

      $this->bookingRepository = new BookingRepository($database);  

      $this->vehicleRepository = new VehicleRepository($database);

    }


    public function pickDateBooking($dateString)
    {
      //cria um objeto DateTime a partir da string
      $date = new \DateTime($dateString);

      //formatar o objeto DateTime para pegar apenas o dia 

      return $date;      
    }

    
    public function calculateBooking(float $pricePerDay, int $daysRental): float
    { 
      if($daysRental >= 7){
        return $pricePerDay * $daysRental - (10/100);
      }
      $total_price =  $pricePerDay * $daysRental;
      
        return $total_price;
    }

    public function calculateIntervalDays($pickupDate, $returnDate)
    {
      $startDate = $this->pickDateBooking($pickupDate);
      $endDate = $this->pickDateBooking($returnDate);

      //calcular o intervalo
      $interval = $startDate->diff($endDate);

      //pegar o intervalo em meses e dias

      return $interval->days;
    }

    public function handleStatusVehicleOnDeleteBooking($id_booking)
    {
      try{
        $currentBooking = $this->bookingRepository->getBookingById($id_booking);
        $this->bookingRepository->deleteBookingById($id_booking);
        $this->vehicleRepository->updateVehicleById(["status" => "disponivel"], $currentBooking['id_vehicle']);
        return true;
      }catch(PDOException $e)
      {
        return $e->getMessage();
      }
    }
    
}