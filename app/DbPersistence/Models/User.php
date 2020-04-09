<?php

namespace App\DbPersistence\Models;
use App\Domain\TaskList\Models\User\User as ModelUser;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

/**
 * Class User
 * @package App\DbPersistence\Models
 * @property string $id;
 * @property string $name;
 * @property string $surname;
 */
class User extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tl_users';

}
