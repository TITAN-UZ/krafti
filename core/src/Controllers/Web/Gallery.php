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
        $c->where([
            'object_id' => (int)$this->getProperty('object_id'),
            'object_name' => $this->getProperty('object_name'),
            'active' => true,
        ]);
        $c->orderBy('rank', 'asc');

        return parent::beforeCount($c);
    }

    protected function afterCount(Builder $c): Builder
    {
        $c->select('file_id');
        $c->with('file:id,updated_at');

        return $c;
    }
}
