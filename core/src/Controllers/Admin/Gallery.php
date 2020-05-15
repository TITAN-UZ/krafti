<?php

namespace App\Controllers\Admin;

use App\Model\File;
use App\Model\GalleryItem;
use Illuminate\Database\Eloquent\Builder;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use Vesp\Controllers\ModelController;

class Gallery extends ModelController
{

    protected $model = GalleryItem::class;
    protected $scope = 'gallery';


    /**
     * Upload file
     *
     * @return ResponseInterface;
     */
    public function post()
    {
        if (!$object_id = (int)$this->getProperty('object_id')) {
            return $this->failure('Вы должны указать id объекта галереи');
        }
        if (!$object_name = $this->getProperty('object_name')) {
            return $this->failure('Вы должны указать тип объекта галереи');
        }
        if (!$uploaded = $this->request->getUploadedFiles()) {
            return $this->failure('Не могу загрузить файл');
        }
        $uploaded = array_pop($uploaded);

        $key = [
            'object_id' => $object_id,
            'object_name' => $object_name,
        ];

        $hash = sha1(base64_encode($uploaded->getStream()->getContents()));
        if (GalleryItem::query()->where($key)->where('hash', $hash)->count()) {
            return $this->failure('Файл "' . $uploaded->getClientFilename() . '" уже есть в галерее');
        }

        $file = new File();
        if ($file->uploadFile($uploaded, json_decode($_POST['metadata'], true))) {
            try {
                $record = new GalleryItem($key);
                $record->fill([
                    'file_id' => $file->id,
                    'hash' => $hash,
                    'active' => true,
                ]);
                $record->save();
                /** @var GalleryItem $record */
                $record = GalleryItem::query()->find($file->id);

                return $this->success($this->prepareRow($record));
            } catch (Throwable $e) {
                return $this->failure($e->getMessage());
            }
        }

        return $this->failure();
    }


    /**
     * Modify file
     */
    public function patch()
    {

        if (!$id = (int)$this->getProperty('id')) {
            return $this->failure('Вы должны указать id объекта галереи');
        }

        /** @var GalleryItem $record */
        $record = GalleryItem::query()->find($id);

        $active = $this->getProperty('active');
        $rank = $this->getProperty('rank');
        $db = $this->eloquent->getDatabaseManager();

        if ($active !== null) {
            $record->active = (bool)$active;
        } elseif ($rank !== null) {
            $key = [
                'object_id' => $record->object_id,
                'object_name' => $record->object_name,
            ];
            $old_rank = $record->rank;
            $new_rank = (int)$rank;

            // Move on left
            if ($new_rank < $old_rank) {
                GalleryItem::query()->where($key)
                    ->where('rank', '<', $old_rank)
                    ->where('rank', '>=', $new_rank)
                    ->update(['rank' => $db->raw('rank + 1')]);
            } else {
                GalleryItem::query()->where($key)
                    ->where('rank', '>', $old_rank)
                    ->where('rank', '<=', $new_rank)
                    ->update(['rank' => $db->raw('rank - 1')]);
            }
            $record->rank = $new_rank;
        }
        $record->save();

        return $this->success($this->prepareRow($record));
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
        ]);
        $c->orderBy('rank', 'asc');

        return $c;
    }


    /**
     * @param GalleryItem $object
     *
     * @return array
     */
    public function prepareRow($object)
    {
        return [
            'id' => $object->file_id,
            'active' => $object->active,
            'rank' => $object->rank,
            'file' => [
                'id' => $object->file->id,
                'updated_at' => $object->file->updated_at,
            ],
            'title' => $object->file->title,
            'description' => $object->file->description,
            'type' => $object->file->type,
        ];
    }
}