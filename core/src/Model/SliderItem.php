<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Throwable;

/**
 * @property int $id
 * @property int $slider_id
 * @property int $image_id
 * @property int $video_id
 * @property string $title
 * @property string $description
 * @property string $type
 * @property string $link
 * @property int $rank
 * @property bool $active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Slider $slider
 * @property-read File $image
 * @property-read Video $video
 */
class SliderItem extends Model
{
    protected $fillable = [
        'slider_id',
        'image_id',
        'video_id',
        'title',
        'description',
        'type',
        'link',
        'rank',
        'active',
    ];
    protected $casts = [
        'active' => 'boolean',
    ];

    public function slider(): BelongsTo
    {
        return $this->belongsTo(Slider::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    public function save(array $options = []): bool
    {
        if ($this->rank === null) {
            $this->rank = self::query()->where('slider_id', $this->slider_id)->count();
        }

        return parent::save($options);
    }

    /**
     * @return bool|null
     * @throws Throwable
     */
    public function delete(): ?bool
    {
        if ($this->image) {
            $this->image->delete();
        }

        return parent::delete();
    }

}