<?php

namespace App\Processors\Web;

use App\Model\Course;

class Courses extends \App\GetProcessor
{

    protected $class = '\App\Model\Course';


    protected function beforeCount($c)
    {
        $c->where(['active' => true]);

        if ($exclude = $this->getProperty('exclude')) {
            $c->whereNotIn('id', explode(',', $exclude));
        }

        if ($category = trim($this->getProperty('category'))) {
            //$c->where(['category' => $category]);
        }

        return $c;
    }


    /**
     * @param Course $object
     *
     * @return array
     */
    public function prepareRow($object)
    {
        $array = [
            'id' => $object->id,
            'title' => $object->title,
            'tagline' => $object->tagline,
            'description' => $object->description,
            'category' => $object->category,
            'price' => $object->price,
            'age' => $object->age,
            'views_count' => $object->views_count,
            'reviews_count' => $object->reviews_count,
            'likes_count' => $object->likes_count,
            'lessons_count' => $object->lessons_count,
            'cover' => $object->cover
                ? $object->cover->getUrl()
                : '',
            'video' => $object->video
                ? $object->video->preview
                : '',
            'bonus' => $object->bonus
                ? $object->bonus->preview
                : '',
        ];

        return $array;
    }
}