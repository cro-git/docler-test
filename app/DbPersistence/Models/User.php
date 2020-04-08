<?php

namespace App\DbPersistence\Models;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tl_users';

}
