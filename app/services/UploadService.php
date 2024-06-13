<?php

use Aws\Exception\AwsException;
use Aws\S3\S3Client;

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
    



  public function ImgUserProfile($uploadedFile)
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
            'Key'    => "users_profile/{$filename}",
            'Body'   => $stream,
            'ACL'    => 'public-read', // Opcional: se quiser que o arquivo seja público
        ]);

        return $imageUrl = $result['ObjectURL'];      
      }
      catch(AwsException $e)
      {
        return $e->getMessage();
      }
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

        return $imageUrl = $result['ObjectURL'];      
      }
      catch(AwsException $e)
      {
        return $e->getMessage();
      }
    }
  }
}