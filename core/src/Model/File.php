<?php

namespace App\Model;

use Carbon\Carbon;

/**
 * @property int $id
 * @property string $file
 * @property string $path
 * @property string $title
 * @property string $type
 * @property int $width
 * @property int $height
 * @property string $description
 * @property array $metadata
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class File extends \Vesp\Models\File
{
    protected $fillable = ['file', 'path', 'title', 'type', 'width', 'height', 'description', 'metadata'];
    protected $casts = [
        'metadata' => 'array',
    ];
}
