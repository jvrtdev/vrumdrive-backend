<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;

class BookingRepository
{   
    protected $pdo;

    public function __construct(Database $database)
    {
      $this->pdo = $database->getConnection();
    }

    public function getAllBookings()
    {
      
    }

    public function getBookingsByUserId()
    {
      
    }

    public function updateBookingById()
    {
      
    }
    
    public function deleteBookingById()
    {
      
    }


}