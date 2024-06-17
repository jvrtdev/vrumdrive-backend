<?php 
namespace App\Controllers;

use App\Database;
use App\Repositories\UserRepository;
use App\Repositories\VehicleRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Aws\Exception\AwsException;
use Aws\S3\S3Client;



class UploadController
{
    
    private $s3Client;
    private $bucketName = 'vrumdrive';
    protected $userRepository;
    protected $vehiclesRepository;
    

    public function __construct()
    {
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region' => 'us-east-1',
            'credentials' => [
              'key' => $_ENV['ACCESS_KEY_ID'],
              'secret' => $_ENV['SECRET_ACCESS_KEY']
            ]
          ]);

        $database = new Database();
        
        $this->userRepository = new UserRepository($database);

        $this->vehiclesRepository = new VehicleRepository($database);
    }

    public function uploadProfileImg(Request $request, Response $response, $args)
    {
        $uploadedFiles = $request->getUploadedFiles();

        if (empty($uploadedFiles['image'])) {
            $response->getBody()->write('No file uploaded');
            return $response->withStatus(400);
        }

        $uploadedFile = $uploadedFiles['image'];

        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = $uploadedFile->getClientFilename();
            $stream = $uploadedFile->getStream();

            try {
                $result = $this->s3Client->putObject([
                    'Bucket' => $this->bucketName,
                    'Key'    => "users_profile/{$filename}",
                    'Body'   => $stream,
                    'ACL'    => 'public-read', // Opcional: se quiser que o arquivo seja público
                ]);

                $imageUrl = $result['ObjectURL'];
                
                $userImgUpdated = $this->userRepository->updateProfileImgByUserId($imageUrl, $args['id']);
                
                            
                $response->getBody()->write(json_encode(['url' => $imageUrl]));
                return $response->withStatus(200);
            }
            catch (AwsException $e)
            {
                $response->getBody()->write('Error uploading file: ' . $e->getMessage());
                return $response->withStatus(500);
            }
        }
        $response->getBody()->write('Error uploading file');
        return $response->withStatus(500);
    }

    public function uploadVehicleImages(Request $request, Response $response, $args)
    {
        $uploadedFiles = $request->getUploadedFiles();

    if (empty($uploadedFiles['image'])) {
        $response->getBody()->write('No file uploaded');
        return $response->withStatus(400);
    }

    $uploadedFile = $uploadedFiles['image'];

    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        $filename = $uploadedFile->getClientFilename();
        $stream = $uploadedFile->getStream();

        try {
            $result = $this->s3Client->putObject([
                'Bucket' => $this->bucketName,
                'Key'    => "public/{$filename}",
                'Body'   => $stream,
                'ACL'    => 'public-read', // Opcional: se quiser que o arquivo seja público
            ]);

            $imageUrl = $result['ObjectURL'];
            
            $vehicleImgUpdated = $this->vehiclesRepository->addVehicleImgById($imageUrl, $args['id']);
                     
            $response->getBody()->write('File uploaded successfully: ' . $imageUrl);
            return $response->withStatus(200);
        } catch (AwsException $e) {
            $response->getBody()->write('Error uploading file: ' . $e->getMessage());
            return $response->withStatus(500);
        }
      }
    }

    
}