import os
import shutil
import tarfile
import boto3
from datetime import date

AWS_ACCESS_KEY = 'your-access-key'
AWS_SECRET_ACCESS_KEY = 'your-secret-access-key'
AWS_REGION = 'your-aws-region'
AWS_BUCKET_NAME = 'your-bucket-name'


def create_tar_gz(source, destination):
    with tarfile.open(destination, "w:gz") as tar:
        tar.add(source, arcname=os.path.basename(source))


def send_backup_to_s3(file):
    backup_date = date.today().strftime('%Y-%m-%d')
    temp_file = f'/tmp/backup_{backup_date}.tar.gz'

    # Dosyaları sıkıştır
    create_tar_gz(file, temp_file)

    # AWS S3'ye yedek gönder
    s3_client = boto3.client('s3',
                             region_name=AWS_REGION,
                             aws_access_key_id=AWS_ACCESS_KEY,
                             aws_secret_access_key=AWS_SECRET_ACCESS_KEY)

    backup_name = f'backup_{backup_date}.tar.gz'

    s3_client.upload_file(temp_file, AWS_BUCKET_NAME, backup_name)

    object_url = f"https://{AWS_BUCKET_NAME}.s3.{AWS_REGION}.amazonaws.com/{backup_name}"

    print(f'Yedekleme başarıyla tamamlandı. URL: {object_url}')

    # Geçici dosyayı sil
    os.remove(temp_file)


# Yedeklemek istenilen dosya veya dizin:
send_backup_to_s3('/path/to/your/directory/')