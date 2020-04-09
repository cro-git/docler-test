<?php
namespace App\Listener;


use App\DbPersistence\Models\User;
use App\Domain\TaskList\Event\User\UserHasBeenCreated;
use App\Domain\TaskList\Event\User\UserHasBeenDeleted;
use App\Domain\TaskList\Event\User\UserHasBeenUpdated;
use App\Domain\TaskList\Repository\UsersRepositoryInterface;
use Illuminate\Events\Dispatcher;

class UserSubscriber
{
    private function getRepository()
    {
        return resolve(UsersRepositoryInterface::class);
    }

    public function userHasBeenCreated(UserHasBeenCreated $event)
    {
        $this->getRepository()->saveUser($event->user);
    }

    public function userHasBeenUpdated(UserHasBeenUpdated $event)
    {
        $this->getRepository()->updateUser($event->user);
    }

    public function userHasBeenDeleted(UserHasBeenDeleted $event)
    {
        $this->getRepository()->deleteUser($event->user->getId());
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            UserHasBeenCreated::class,
            'App\Listener\UserSubscriber@userHasBeenCreated'
        );

        $events->listen(
            UserHasBeenUpdated::class,
            'App\Listener\UserSubscriber@userHasBeenUpdated'
        );

        $events->listen(
            UserHasBeenDeleted::class,
            'App\Listener\UserSubscriber@userHasBeenDeleted'
        );
    }
}
