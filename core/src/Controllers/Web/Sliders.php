<?php

namespace App\Controllers\Web;

use App\Model\Slider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Vesp\Controllers\ModelGetController;

class Sliders extends ModelGetController
{
    protected $model = Slider::class;

    protected function beforeGet(Builder $c): Builder
    {
        return $this->beforeCount($c);
    }

    protected function beforeCount(Builder $c): Builder
    {
        $c->select('id', 'timeout');
        $c->with([
            'items' => static function (HasMany $c) {
                $c->where('active', true);
                $c->select('id', 'slider_id', 'image_id', 'video_id', 'type', 'title', 'description', 'link');
                $c->orderBy('rank', 'asc');
            },
            'items.image' => static function (BelongsTo $c) {
                $c->select('id', 'updated_at');
            },
            'items.video' => static function (BelongsTo $c) {
                $c->select('id', 'remote_key');
            },
        ]);

        return $c;
    }
}