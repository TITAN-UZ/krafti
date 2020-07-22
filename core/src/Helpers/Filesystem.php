<?php

namespace App\Helpers;

use App\Service\YandexCloud;

class Filesystem extends \Vesp\Helpers\Filesystem
{
    /**
     * @noinspection MagicMethodsValidityInspection
     * @noinspection PhpMissingParentConstructorInspection
     */
    public function __construct()
    {
        $this->filesystem = new YandexCloud(getenv('YANDEX_CLOUD_BUCKET_UPLOAD'));
    }

    protected function getRoot(): string
    {
        return '/';
    }
}