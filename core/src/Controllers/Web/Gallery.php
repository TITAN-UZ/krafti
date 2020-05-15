<?php

namespace App\Controllers\Web;

use App\Model\GalleryItem;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelGetController;

class Gallery extends ModelGetController
{
    protected $model = GalleryItem::class;

    protected function beforeGet($c)
    {
        return $this->beforeCount($c);
    }

    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount($c)
    {
        $c->where([
            'object_id' => (int)$this->getProperty('object_id'),
            'object_name' => $this->getProperty('object_name'),
            'active' => true,
        ]);
        $c->orderBy('rank', 'asc');

        return parent::beforeCount($c);
    }

    /**
     * @param Builder $c
     * @return Builder
     */
    protected function afterCount($c)
    {
        $c->select('file_id');
        $c->with('file:id,updated_at');

        return $c;
    }
}
