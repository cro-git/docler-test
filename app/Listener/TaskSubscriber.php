<?php

namespace App\Listener;

use App\DbPersistence\Repository\TaskRepository;
use App\Domain\TaskList\Event\Task\TaskHasBeenCreated;
use App\Domain\TaskList\Event\Task\TaskHasBeenDeleted;
use App\Domain\TaskList\Event\Task\TaskHasBeenUpdated;
use App\Domain\TaskList\Repository\TaskRepositoryInterface;
use Illuminate\Events\Dispatcher;

class TaskSubscriber
{
    /**
     * @return TaskRepository|mixed
     */
    private function getRepository()
    {
        return resolve(TaskRepositoryInterface::class);
    }

    public function taskHasBeenCreated(TaskHasBeenCreated $event)
    {
        $this->getRepository()->saveTask($event->task);
    }

    public function taskHasBeenDeleted(TaskHasBeenDeleted $event)
    {
        $this->getRepository()->deleteTask($event->task->getId());
    }

    public function taskHasBeenUpdated(TaskHasBeenUpdated $event)
    {
        $this->getRepository()->updateTask($event->task);
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
            'App\Listener\TaskSubscriber@taskHasBeenCreated'
        );

        $events->listen(
            TaskHasBeenDeleted::class,
            'App\Listener\TaskSubscriber@taskHasBeenDeleted'
        );

        $events->listen(
            TaskHasBeenUpdated::class,
            'App\Listener\TaskSubscriber@taskHasBeenUpdated'
        );
    }
}
