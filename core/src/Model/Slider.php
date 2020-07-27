<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $title
 * @property int $timeout
 *
 * @property-read SliderItem[] $items
 */
class Slider extends Model
{
    protected $fillable = ['title', 'timeout'];
    public $timestamps = false;

    public function items(): HasMany
    {
        return $this->HasMany(SliderItem::class);
    }
}