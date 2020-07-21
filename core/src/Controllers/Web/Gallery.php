<?php

namespace App\Controllers\Web;

use App\Model\GalleryItem;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelGetController;

class Gallery extends ModelGetController
{
    protected $primaryKey = 'file_id';
    protected $model = GalleryItem::class;

    protected function beforeCount(Builder $c): Builder
    {
        $c->where('active', true);
        if ($object_name = $this->getProperty('object_name', 'Course')) {
            $c->where('object_name', $object_name);
        }
        if ($object_id = (int)$this->getProperty('object_id')) {
            $c->where('object_id', $object_id);
        }

        return $c;
    }

    protected function afterCount(Builder $c): Builder
    {
        $c->select('file_id');
        $c->with('file:id,updated_at,width,height');
        $c->orderBy('rank', 'asc');

        return $c;
    }
}
