<?php
namespace App\Listener;

use App\Domain\TaskList\Event\TaskList\TaskListHasBeenCreated;
use App\Domain\TaskList\Event\TaskList\TaskListHasBeenDeleted;
use App\Domain\TaskList\Event\TaskList\TaskListHasBeenUpdated;
use App\Domain\TaskList\Repository\TaskListRepositoryInterface;
use Illuminate\Events\Dispatcher;

class TaskListSubscriber
{
    private function getRepository()
    {
        return resolve(TaskListRepositoryInterface::class);
    }

    public function taskListHasBeenCreated(TaskListHasBeenCreated $event)
    {
        $this->getRepository()->saveTaskList($event->taskList);
    }

    public function taskListHasBeenUpdated(TaskListHasBeenUpdated $event)
    {
        $this->getRepository()->updateTaskList($event->taskList);
    }

    public function taskListHasBeenDeleted(TaskListHasBeenDeleted $event)
    {
        $this->getRepository()->deleteTaskList($event->taskList->getId());
    }


    /**
     * Register the listeners for the subscriber.
     *
     * @param  Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            TaskListHasBeenCreated::class,
            'App\Listener\TaskListSubscriber@taskListHasBeenCreated'
        );

        $events->listen(
            TaskListHasBeenUpdated::class,
            'App\Listener\TaskListSubscriber@taskListHasBeenUpdated'
        );

        $events->listen(
            TaskListHasBeenDeleted::class,
            'App\Listener\TaskListSubscriber@taskListHasBeenDeleted'
        );
    }
}
