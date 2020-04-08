<?php

namespace App\DbPersistence\Models;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tl_tasks';
}
