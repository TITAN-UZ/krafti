<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $remote_key
 * @property string $preview
 * @property int $width
 * @property int $height
 * @property int $duration
 * @property int $views_count
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 */
class Video extends Model
{
    protected $fillable = [
        'title',
        'description',
        'remote_key',
        'preview',
        'width',
        'height',
        'duration',
        'views_count',
    ];
    protected $casts = [
        'preview' => 'array',
    ];
}
