# S3 Backup

Bu repo, sitenizin yedeklemesini alıp Amazon S3'ye yüklemek için kullanılır.

## Gereksinimler

- PHP 8.1 veya daha yeni bir sürüm
- Composer (https://getcomposer.org/) - Bağımlılıkları yönetmek için kullanılır
- AWS hesabı ve erişim anahtarları

## Kurulum

1. Proje dizinine gidin ve terminalde `composer require aws/aws-sdk-php` komutunu çalıştırarak bağımlılıkları yükleyin.

## Yapılandırma

1. `s3.php` dosyasını açın.
2. `AWS_ACCESS_KEY`, `AWS_SECRET_ACCESS_KEY`, `AWS_REGION` ve `AWS_BUCKET_NAME` sabitlerini AWS erişim anahtarlarınız ve S3 bilgilerinizle güncelleyin.

## Kullanım

`/path/to/your/directory` kısmını dizininizin gerçek yoluna göre güncelleyin.
Terminalde, `php s3.php` komutunu çalıştırarak yedeklemeyi S3'ye gönderin.
