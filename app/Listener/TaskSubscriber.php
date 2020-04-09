<?php

namespace App\Listener;

use App\DbPersistence\Models\Task;
use App\Domain\TaskList\Event\Task\TaskHasBeenCreated;

use App\Domain\TaskList\Event\Task\TaskHasBeenDeleted;
use App\Domain\TaskList\Event\Task\TaskHasBeenUpdated;
use App\Domain\TaskList\Event\User\UserHasBeenUpdated;
use Illuminate\Events\Dispatcher;

class TaskSubscriber
{
    public function taskHasBeenCreated(TaskHasBeenCreated $event)
    {
        Task::createFromModel($event->task);
    }

    public function taskHasBeenDeleted(TaskHasBeenCreated $event)
    {

    }

    public function taskHasBeenUpdated(TaskHasBeenUpdated $event)
    {

    }


    /**
     * Register the listeners for the subscriber.
     *
     * @param  Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            TaskHasBeenCreated::class,
            'App\Listeners\TaskSubscriber@taskHasBeenCreated'
        );

        $events->listen(
            TaskHasBeenDeleted::class,
            'App\Listeners\TaskSubscriber@taskHasBeenDeleted'
        );

        $events->listen(
            TaskHasBeenUpdated::class,
            'App\Listeners\TaskSubscriber@taskHasBeenUpdated'
        );
    }
}
