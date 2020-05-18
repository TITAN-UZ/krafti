<?php

namespace App\Controllers\User;

use App\Model\Lesson;
use App\Model\User;
use Vesp\Controllers\Controller;

class Progress extends Controller
{
    protected $scope = 'profile';

    /** @var User $user */
    protected $user;

    public function post()
    {

        /** @var Lesson $lesson */
        if (!$lesson = Lesson::query()->find((int)$this->getProperty('lesson_id'))) {
            return $this->failure('Не могу загрузить урок', 404);
        }
        $course = $lesson->course;
        if (!$course->wasBought($this->user)) {
            return $this->failure('Вы забыли оплатить этот курс', 403);
        }

        $progress = $this->user->getProgress($course);
        $makeProgress =
            $lesson->section == $progress->section &&
            $lesson->rank + 1 > $progress['rank'] &&
            $course->lessons()->where('section', $lesson->section)->where('rank', '>', $lesson->rank)->count();

        if ($makeProgress) {
            $progress = $this->user->makeProgress($course, $lesson->section, $lesson->rank + 1);
        }/* elseif ($course->lessons()->where('section', '>', $lesson->section)->count()) {
            $progress = $this->user->makeProgress($course, $lesson->section + 1, 1);
        }*/

        return $this->success([
            'section' => $progress->section,
            'rank' => $progress->rank,
        ]);
    }
}
