<?php

//exit('Disabled!');

/** @var App\Container $container */
/** @var Slim\App $app */
require '_initialize.php';

/** @var \App\Model\File $file */
foreach (\App\Model\File::query()->where('type', 'LIKE', 'image/%')->get() as $file) {

    //$content = file_get_contents($file->getFile());
    $size = getimagesize($file->getFile());
    $file->fill([
        'width' => $size[0],
        'height' => $size[1],
    ]);
    $file->save();
}