<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;

class Image extends \Vesp\Controllers\Data\Image
{
    /**
     * @return ResponseInterface
     */
    public function get(): ResponseInterface
    {
        if ($size = $this->route->getArgument('size')) {
            [$width, $height] = explode('x', $size);
            if (!empty($width)) {
                $this->setProperty('w', $width);
            }
            if (!empty($height)) {
                $this->setProperty('h', $height);
            }
        }

        if ($params = $this->route->getArgument('params')) {
            if (strpos($params, 'fit') !== false) {
                $this->setProperty('fit', 'crop');
            } elseif (strpos($params, 'resize') !== false) {
                $this->setProperty('fit', 'contain');
            }
        }

        return parent::get();
    }
}
