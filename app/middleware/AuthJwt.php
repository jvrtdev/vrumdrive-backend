<?php 
namespace App\Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;


class AuthJwt
{
    protected string $secret_key;

    public function __construct()
    {
      $this->secret_key = "vrumvrumdrivetopdemais";
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $token = $request->getHeaderLine('Authorization');
      if ($token) {
        if (strpos($token, 'Bearer ') === 0) {
          // Remove o prefixo "Bearer " da string
          $token = substr($token, 7);
      }
      $token = trim($token);
      

        try {
            $decoded = JWT::decode($token, new Key($this->secret_key, 'HS256'));
            $request = $request->withAttribute('token', $decoded);
            
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode($decoded));
            return $handler->handle($request);
        } catch (Exception $e) {
            $response = new \Slim\Psr7\Response();
            print_r($e->getMessage());
            $response->getBody()->write(json_encode(['error' => 'Erro ao autenticar ']));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }
      }
      $response = new \Slim\Psr7\Response();
      $response->getBody()->write(json_encode(['error' => 'Token not provided']));
      return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }
}