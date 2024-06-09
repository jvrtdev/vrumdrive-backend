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
      $sql = 'SELECT * FROM bookings';

      $stmt = $this->pdo->prepare($sql);

      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBookingsByUserId($id)
    {
      $sql = 'SELECT * FROM bookings WHERE id_user = :id';

      $stmt = $this->pdo->prepare($sql);
      $stmt->bindValue(':id', $id);

      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateBookingById()
    {
      
    }
    
    public function deleteBookingById($id)
    {
      $sql = 'DELETE FROM bookings WHERE id_booking = :id';

      $stmt = $this->pdo->prepare($sql);

      $stmt->bindValue(':id', $id);

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createNewBooking($data)
    {
      $sql = 'INSERT INTO bookings (id_user, id_vehicle, location, pickup_date_time, return_date_time, total_price) VALUES(:id_user, :id_vehicle, :location, :pickup_date_time, :return_date_time, :total_price)';
      
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindValue(':id_user', $data['id_user']);
      $stmt->bindValue(':id_vehicle', $data['id_vehicle']);
      $stmt->bindValue(':location', $data['location']);
      $stmt->bindValue(':pickup_date_time', $data['pickup_date_time']);
      $stmt->bindValue(':return_date_time', $data['return_date_time']);
      $stmt->bindValue(':total_price', $data['total_price']);

      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}