<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;


/**
 * @property int $id
 * @property int $user_id
 * @property int $lesson_id
 * @property int $parent_id
 * @property string $text
 * @property bool $deleted
 * @property bool $review
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read User $user
 * @property-read Lesson $lesson
 * @property-read Comment $parent
 * @property-read Comment[] $comment
 */
class Comment extends Model
{
    protected $fillable = ['user_id', 'lesson_id', 'parent_id', 'text', 'deleted', 'review'];
    protected $casts = [
        'deleted' => 'boolean',
        'review' => 'boolean',
        //'created_at' => 'datetime:Y-m-d',
    ];


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
    public function lesson()
    {
        return $this->belongsTo('App\Model\Lesson');
    }


    /**
     * @return hasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Model\Comment', 'id', 'parent_id');
    }


    public function parent() {
        return $this->belongsTo('App\Model\Comment');
    }

}