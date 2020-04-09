<?php

namespace App\DbPersistence\Models;
use DateTime;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;

/**
 * Class Task
 * @package App\DbPersistence\Models
 * @property string $id;
 * @property string $description;
 * @property DateTime $due_date;
 * @property int $status;
 * @property string $task_list_id;
 */
class Task extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tl_tasks';

}
