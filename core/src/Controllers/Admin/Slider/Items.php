<?php

namespace App\Controllers\Admin\Slider;

use App\Model\File;
use App\Model\SliderItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\ModelController;

class Items extends ModelController
{
    protected $model = SliderItem::class;
    protected $scope = 'settings';

    public function post(): ResponseInterface
    {
        if (!$action = $this->getProperty('action')) {
            return $this->failure('Вы должны указать action');
        }
        if (!$id = (int)$this->getPrimaryKey()) {
            return $this->failure('Вы должны указать id объекта');
        }
        /** @var SliderItem $object */
        if (!$object = SliderItem::query()->find($id)) {
            return $this->failure('Не могу загрузить объект');
        }
        /** @var SliderItem $other */
        switch ($action) {
            case 'move_up':
                if ($object->rank > 0) {
                    $object->rank--;
                    if ($other = $object->slider->items()->where('rank', $object->rank)->first()) {
                        $other->rank++;
                        $other->save();
                    }
                    $object->save();
                }
                break;
            case 'move_down':
                $object->rank++;
                if ($other = $object->slider->items()->where('rank', $object->rank)->first()) {
                    $other->rank--;
                    $other->save();
                }
                $object->save();
                break;
            default:
                return $this->failure('Указан неверный action');
        }

        $rank = 0;
        $records = $object->slider->items()
            ->orderBy('rank', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();
        /** @var SliderItem $record */
        foreach ($records as $record) {
            $record->rank = $rank;
            $record->save();
            $rank++;
        }

        return $this->success();
    }

    /**
     * @param Model|SliderItem $record
     * @return Model|bool
     */
    protected function beforeSave(Model $record)
    {
        if ($image = $this->getProperty('new_image', $this->getProperty('image'))) {
            if (is_array($image) && !empty($image['file'])) {
                if (!$file = $record->image) {
                    $file = new File();
                }

                if ($file->uploadFile($image['file'], $image['metadata'])) {
                    $record->image_id = $file->id;
                }
            }
        } elseif (!$record->exists) {
            return 'Вы должны загрузить изображение для слайда';
        }

        return true;
    }

    protected function beforeGet(Builder $c): Builder
    {
        $c->with('image:id,title,width,height,updated_at');
        $c->with('video:id,remote_key');

        return $c;
    }

    protected function beforeCount(Builder $c): Builder
    {
        if ($query = trim($this->getProperty('query'))) {
            $c->where('title', 'LIKE', "%{$query}%");
            $c->orWhere('description', 'LIKE', "%{$query}%");
        }

        if ($position_id = (int)$this->getProperty('position_id')) {
            $c->where('slider_id', $position_id);
        }

        return $c;
    }

    protected function afterCount(Builder $c): Builder
    {
        $c->with('image:id,updated_at');

        return $c;
    }
}