<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $course_id
 * @property int $child_id
 * @property int $file_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read User $user
 * @property-read Course $course
 * @property-read UserChild $child
 * @property-read File $file
 */
class Diploma extends Model
{

    protected $fillable = ['user_id', 'course_id', 'child_id', 'file_id'];


    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }


    /**
     * @return BelongsTo
     */
    public function course()
    {
        return $this->belongsTo('App\Model\Course');
    }


    /**
     * @return BelongsTo
     */
    public function child()
    {
        return $this->belongsTo('App\Model\UserChild');
    }


    /**
     * @return BelongsTo
     */
    public function file()
    {
        return $this->belongsTo('App\Model\File');
    }

    /**
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        if ($file = $this->file) {
            $file->delete();
        }

        return parent::delete();
    }
}