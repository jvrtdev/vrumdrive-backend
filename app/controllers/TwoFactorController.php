<?php

namespace App\Controllers;

use App\Services\TwoFactorService;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class twoFactorController
{
    protected $twoFactorService;

    public function __construct() 
    {
        $this->twoFactorService = new TwoFactorService();
    }

    public function twoFactor(Request $request, Response $response, $args)
    {
        $data = get_object_vars(json_decode($request->getBody()));

        try{
            $verify = $this->twoFactorService->twoFactor($args["id"], $data);

            if ($verify)
            {
                $response->getBody()->write(json_encode(['message' => "sucesso"]));
                return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
            }
        }
        catch(PDOException $e)
        {
            $response->getBody()->write(json_encode($e->getMessage()));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }
    }
}