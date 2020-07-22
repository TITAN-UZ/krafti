<?php

namespace App\Service;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;

class YandexCloud extends Filesystem
{
    public function __construct(string $bucket)
    {
        $client = new S3Client([
            'credentials' => [
                'key' => getenv('YANDEX_CLOUD_ID'),
                'secret' => getenv('YANDEX_CLOUD_KEY'),
            ],
            'endpoint' => getenv('YANDEX_CLOUD_URI'),
            'region' => 'ru-central1',
            'version' => 'latest',
        ]);
        $adapter = new AwsS3Adapter($client, $bucket);

        parent::__construct($adapter);
    }
}
