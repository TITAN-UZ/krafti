<?php

use App\Model\GalleryItem;

/** @var App\Container $container */
/** @var Slim\App $app */
require '_initialize.php';

foreach (GalleryItem::query()->get() as $obj) {
    $obj->delete();
}