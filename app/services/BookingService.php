<?php

namespace App\Services;

use DateTime;

class BookingService
{
    public function pickDateBooking($dateString)
    {
      //cria um objeto DateTime a partir da string
      $date = new \DateTime($dateString);

      //formatar o objeto DateTime para pegar apenas o dia 
      //$day = $date->format('d');

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
      //$months = $interval->m + ($interval->y * 12);
      //$days = $interval->d;

      return $interval->days;
    }
    
}