<?php

namespace App\Model;

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
 * @property string $created_at
 * @property string $updated_at
 *
 */
class Video extends Model
{
    public $timestamps = true;
    protected $fillable = ['title', 'description', 'remote_key', 'preview', 'width', 'height', 'duration', 'views_count'];
    protected $casts = [
        'preview' => 'array',
    ];

}