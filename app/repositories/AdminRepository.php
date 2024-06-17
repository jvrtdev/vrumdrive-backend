<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;
use PDOException;
use App\Repositories\BookingRepository;

class AdminRepository
{   
    protected $pdo;

    protected $bookingRepository;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnection();

        $this->bookingRepository = new BookingRepository($database);
    }

    public function getSubscribes()
    {
        $stmt = $this->pdo->query('SELECT COUNT(*) FROM users');

        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["COUNT(*)"];
    }

    public function getNumberBookings()
    {
        $stmt = $this->pdo->query('SELECT COUNT(*) FROM bookings');

        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["COUNT(*)"];
    }

    public function getProfits()
    {
        $reservas =  $this->bookingRepository->getAllBookings();
        $profits = 0;

        for($i = 0; $i < count($reservas); $i++){
            $profits += $reservas[$i]["total_price"];
        }

        return $profits;
    }

    public function getVehicleByAvailable()
    {
        $stmt = $this->pdo->query('SELECT COUNT(*) FROM vehicles WHERE status = "disponivel"');

        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["COUNT(*)"];
    }
}