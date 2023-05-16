<?php

require 'vendor/autoload.php';

use Aws\S3\S3Client;

define('AWS_ACCESS_KEY', 'your-access-key');
define('AWS_SECRET_ACCESS_KEY', 'your-secret-access-key');
define('AWS_REGION', 'your-aws-region');
define('AWS_BUCKET_NAME', 'your-bucket-name');


function createTarGz($source, $destination)
{
    $rootPath = realpath($source);

    $zip = new ZipArchive();
    $zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }

    $zip->close();
}

function sendBackupToS3($file)
{
    $backupDate = date('Y-m-d');
    $tempFile = '/tmp/backup_' . $backupDate . '.tar.gz';

    // Dosyaları sıkıştır
    createTarGz($file, $tempFile);

    // AWS S3'ye yedek gönder
    $s3Client = new S3Client([
        'version' => 'latest',
        'region' => AWS_REGION,
        'credentials' => [
            'key' => AWS_ACCESS_KEY,
            'secret' => AWS_SECRET_ACCESS_KEY,
        ],
    ]);

    $backupName = 'backup_' . $backupDate . '.tar.gz';

    $result = $s3Client->putObject([
        'Bucket' => AWS_BUCKET_NAME,
        'Key' => $backupName,
        'SourceFile' => $tempFile,
    ]);

    if ($result['ObjectURL']) {
        echo 'Yedekleme başarıyla tamamlandı. URL: ' . $result['ObjectURL'];
    } else {
        echo 'Yedekleme sırasında bir hata oluştu.';
    }

    // Geçici dosyayı sil
    unlink($tempFile);
}

// Yedeklemek istenilen dosya veya dizin:
sendBackupToS3('/path/to/your/directory/');

?>
