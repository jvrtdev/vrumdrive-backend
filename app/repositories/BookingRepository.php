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
      $sql = 'SELECT * FROM bookings LEFT JOIN vehicles ON (bookings.id_vehicle = vehicles.id_vehicle) LEFT JOIN users ON (bookings.id_user = users.id_user)';

      $stmt = $this->pdo->prepare($sql);

      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBookingById($id)
    {
      $sql = 'SELECT * FROM bookings LEFT JOIN vehicles ON (bookings.id_vehicle = vehicles.id_vehicle) LEFT JOIN users ON (bookings.id_user = users.id_user) WHERE id_booking = :id';

      $stmt = $this->pdo->prepare($sql);
      $stmt->bindValue(':id', $id);

      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
    }

    public function getBookingByUserId($id)
    {
      $sql = 'SELECT * FROM bookings WHERE id_user = :id';

      $stmt = $this->pdo->prepare($sql);
      $stmt->bindValue(':id', $id);

      $stmt->execute();

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

    public function deleteBookingById($id)
    {
      $sql = 'DELETE FROM bookings WHERE id_booking = :id';

      $stmt = $this->pdo->prepare($sql);
      
      $stmt->bindValue(':id', $id);
      
      return $stmt->execute();
    }
}