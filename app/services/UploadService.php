<?php
namespace App\Services;
use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UploadService
{
  protected $client;
  protected $bucketName = 'vrumdrive';

  public function __construct()
  {
    $this->client = new S3Client([
      'version' => 'latest',
      'region' => 'us-east-1',
      'credentials' => [
        'key' => $_ENV['ACCESS_KEY_ID'],
        'secret' => $_ENV['SECRET_ACCESS_KEY']
      ]
    ]);
  }

  public function uploadImage($file, $fileName)
    {
        try {
            $result = $this->client->putObject([
                'Bucket' => $this->bucketName,
                'Key'    => $fileName,
                'SourceFile' => $file,
                'ACL'    => 'public-read', // Permite leitura pública
            ]);

            return $result->get('ObjectURL');
        } catch (AwsException $e) {
            throw new Exception('Erro ao fazer upload da imagem: ' . $e->getMessage());
        }
    }
    

  public function awsS3Client()
  {
    return $this->client = new S3Client([
      'version' => 'latest',
      'region' => 'us-east-1',
      'credentials' => [
        'key' => $_ENV['ACCESS_KEY_ID'],
        'secret' => $_ENV['SECRET_ACCESS_KEY']
      ]
    ]);
  }
  
  public function uploadProfileImgToS3($filename, $stream)
  {
    
    try {
      $result = $this->client->putObject([
          'Bucket' => $this->bucketName,
          'Key'    => "users_profile/{$filename}",
          'Body'   => $stream,
          'ACL'    => 'public-read', // Opcional: se quiser que o arquivo seja público
      ]);

      $imageUrl = $result['ObjectURL'];
      return $imageUrl;
      
  } catch (AwsException $e) {
      return false;
  }
      
  }

  public function ImgPublic($uploadedFile)
  {
    if(empty($uploadedFile)){
      return false;
    }
    
    if($uploadedFile->getError() === UPLOAD_ERR_OK) {
      $filename = $uploadedFile->getClientFilename();
      $stream = $uploadedFile->getStream();

      try{
        $result = $this->client->putObject([
            'Bucket' => $this->bucketName,
            'Key'    => "public/{$filename}",
            'Body'   => $stream,
            'ACL'    => 'public-read', // Opcional: se quiser que o arquivo seja público
        ]);

        return $result['ObjectURL'];      
      }
      catch(AwsException $e)
      {
        return $e->getMessage();
      }
    }
  }




  public function ImgUserProfile($file)
  {
      if (!$file || !isset($file['profile_img'])) {
          throw new \Exception('No file provided aiai');
      }

      $filePath = $file['tmp_name'];
      $fileName = $file['name'];
      $key = 'users_profile/' . $fileName;

      try {
          $result = $this->client->putObject([
              'Bucket' => $this->bucketName,
              'Key'    => $key,
              'SourceFile' => $filePath,
              'ACL'    => 'public-read', // Certifique-se de que o bucket permite ACLs
          ]);

          return $result['ObjectURL'];
      } catch (AwsException $e) {
          throw new \Exception('Error uploading file: ' . $e->getMessage());
      }
  }
}