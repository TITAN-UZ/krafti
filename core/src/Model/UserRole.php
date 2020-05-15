<?php

namespace App\Model;

use Carbon\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property array $scope
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read User[] $users
 */
class UserRole extends \Vesp\Models\UserRole
{
}