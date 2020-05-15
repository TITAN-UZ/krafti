<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $user_id
 * @property int $lesson_id
 * @property int $value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read User $user
 * @property-read Lesson $lesson
 */
class UserLike extends Model
{
    use Traits\CompositeKey;

    protected $primaryKey = ['lesson_id', 'user_id'];
    protected $fillable = ['lesson_id', 'user_id', 'value'];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * @param array $options
     *
     * @return bool
     */
    public function save(array $options = [])
    {
        $update = $this->isDirty('value');
        $save = parent::save($options);
        if ($update) {
            $this->lesson->likes_count = $this->lesson->likes()->where(['value' => 1])->count();
            $this->lesson->dislikes_count = $this->lesson->likes()->where(['value' => -1])->count();
            $this->lesson->save();
        }

        return $save;
    }
}
