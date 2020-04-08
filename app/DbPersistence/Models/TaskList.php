<?php

namespace App\DbPersistence\Models;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

class TaskList extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tl_task_lists';
}
