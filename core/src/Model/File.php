<?php

namespace App\Model;

use App\Helpers\Filesystem;
use Carbon\Carbon;

/**
 * @property int $id
 * @property string $file
 * @property string $path
 * @property string $title
 * @property string $type
 * @property int $width
 * @property int $height
 * @property array $metadata
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class File extends \Vesp\Models\File
{

    /** @var Filesystem $filesystem */
    protected $filesystem;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->filesystem = new Filesystem();
    }
}
