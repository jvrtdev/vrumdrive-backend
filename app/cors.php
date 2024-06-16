<?php
namespace App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Middleware de CORS
return function (Request $request, $handler): Response {
    // Obtém a resposta do manipulador
    $response = $handler->handle($request);

    // Verifica se a requisição é um método OPTIONS
    if ($request->getMethod() === 'OPTIONS') {
        // Se for OPTIONS, retorna uma resposta vazia com o código de status 200
        return $response->withStatus(200);
    }

    // Adiciona os cabeçalhos CORS à resposta
    $response = $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');

    // Retorna a resposta modificada
    return $response;
};