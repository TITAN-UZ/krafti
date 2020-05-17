<?php

namespace App\Controllers\User;

use App\Model\Message;
use App\Model\User;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelGetController;

class Messages extends ModelGetController
{
    protected $model = Message::class;

    /** @var User $user */
    protected $user;

    public function post()
    {
        return $this->user->messages()->where(['read' => false])->count();
    }

    /**
     * @param Builder $c
     *
     * @return Builder|mixed
     */
    protected function beforeGet($c)
    {
        return $this->beforeCount($c);
    }

    /**
     * @param Builder $c
     *
     * @return Builder
     */
    public function beforeCount($c)
    {
        $c->where(['user_id' => $this->user->id])
            ->orderBy('created_at', 'desc');

        return $c;
    }

    /**
     * @param Message $object
     *
     * @return array
     */
    public function prepareRow($object)
    {
        $array = [
            'id' => $object->id,
            'message' => $object->message,
            'type' => $object->type,
            'created_at' => $object->created_at->toIso8601String(),
            'data' => $object->data,
            'read' => $object->read,
            'sender' => $object->sender
                ? [
                    'fullname' => $object->sender->fullname,
                    'photo' => $object->sender->photo_id
                        ? [
                            'id' => $object->sender->photo->id,
                            'updated_at' => $object->sender->photo->updated_at,
                        ]
                        : null,
                ]
                : null,
        ];
        $object->read = true;
        $object->save();

        return $array;
    }
}
