<?php

require 'vendor/autoload.php';

use Aws\S3\S3Client;

define('AWS_ACCESS_KEY', 'your-access-key');
define('AWS_SECRET_ACCESS_KEY', 'your-secret-access-key');
define('AWS_REGION', 'your-aws-region');
define('AWS_BUCKET_NAME', 'your-bucket-name');


function sendBackupToS3($file)
{
    $s3Client = new S3Client([
        'version' => 'latest',
        'region' => AWS_REGION,
        'credentials' => [
            'key' => AWS_ACCESS_KEY,
            'secret' => AWS_SECRET_ACCESS_KEY,
        ],
    ]);

    $result = $s3Client->putObject([
        'Bucket' => AWS_BUCKET_NAME,
        'Key' => basename($file),
        'SourceFile' => $file,
    ]);

    if ($result['ObjectURL']) {
        echo 'Yedekleme başarıyla tamamlandı. URL: ' . $result['ObjectURL'];
    } else {
        echo 'Yedekleme sırasında bir hata oluştu.';
    }
}

// Yedeklemek istenilen dosya veya dizin:
sendBackupToS3('/path/to/your/directory/');

?>
