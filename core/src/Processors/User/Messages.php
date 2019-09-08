<?php


namespace App\Processors\User;

use App\Model\Message;
use Illuminate\Database\Eloquent\Builder;

class Messages extends \App\GetProcessor
{
    protected $class = 'App\Model\Message';


    public function post()
    {


        return $this->container->user->messages()->where(['read' => false])->count();
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
        $c->where(['user_id' => $this->container->user->id])
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
                    'photo' => $object->sender->photo
                        ? $object->sender->photo->getUrl()
                        : null,
                ]
                : null,
        ];
        $object->read = true;
        $object->save();

        return $array;
    }
}