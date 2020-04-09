<?php

namespace App\DbPersistence\Models;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

/**
 * Class TaskList
 * @package App\DbPersistence\Models
 * @property string $id;
 * @property string $name;
 */
class TaskList extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tl_task_lists';

}
