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

    public function getColumnsBookings(): array
    {
      $stmt = $this->pdo->query('SHOW COLUMNS FROM bookings');

      return $stmt->fetchAll(PDO::FETCH_COLUMN);
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
      $columns_bookings = $this->getColumnsBookings();

      array_shift($columns_bookings);

      $placeholders_bookings = array_map(function($column) 
      {
          return ":$column";
      }, $columns_bookings);

      $sql = 'INSERT INTO bookings (' . implode(', ', $columns_bookings) . ') VALUES (' . implode(', ', $placeholders_bookings) . ')';

      $stmt = $this->pdo->prepare($sql);

      for($i = 0; $i < count($columns_bookings); $i++)
      {
          $stmt->bindValue($placeholders_bookings[$i], $data[$columns_bookings[$i]]);
      }

      return $stmt->execute();
    }


}