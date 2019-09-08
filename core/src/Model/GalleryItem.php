<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $file_id
 * @property int $object_id
 * @property string $object_name
 * @property string $hash
 * @property int $rank
 * @property bool $active
 *
 * @property-read File $file
 */
class GalleryItem extends Model
{
    public $primaryKey = 'file_id';
    public $timestamps = false;
    protected $fillable = ['file_id', 'object_id', 'object_name', 'hash', 'rank', 'active'];
    protected $casts = [
        'active' => 'boolean',
    ];


    /**
     * @return BelongsTo
     */
    public function file()
    {
        return $this->belongsTo('\App\Model\File');
    }


    /**
     * @param array $options
     *
     * @return bool
     */
    public function save(array $options = [])
    {
        if ($this->isDirty('file_id') && $this->rank === null) {
            $this->rank = GalleryItem::query()->where(['object_id' => $this->object_id, 'object_name' => $this->object_name])->count();
        }

        return parent::save($options);
    }


    /**
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        $this->file->delete();

        return parent::delete();
    }

}