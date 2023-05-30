#!/bin/bash

AWS_ACCESS_KEY='your-access-key'
AWS_SECRET_ACCESS_KEY='your-secret-access-key'
AWS_REGION='your-aws-region'
AWS_BUCKET_NAME='your-bucket-name'

backupPath='/path/to/your/directory/'
backupDate=$(date +"%Y-%m-%d")

function sendBackupToS3() {
    backupFile=$1
    objectKey=$(basename "$backupFile")
    aws s3 cp "$backupFile" "s3://$AWS_BUCKET_NAME/$objectKey"
    if [ $? -eq 0 ]; then
        echo "Yedekleme başarıyla tamamlandı. URL: https://s3.$AWS_REGION.amazonaws.com/$AWS_BUCKET_NAME/$objectKey"
    else
        echo "Yedekleme sırasında bir hata oluştu."
    fi
}

# Belirtilen yolu sıkıştır ve S3'ye gönder
tar -czvf "$backupPath/backup_$backupDate.tar.gz" -C "$(dirname $backupPath)" "$(basename $backupPath)"
sendBackupToS3 "$backupPath/backup_$backupDate.tar.gz"
