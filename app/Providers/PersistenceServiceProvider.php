<?php

namespace App\Providers;

use App\DbPersistence\Repository\UsersRepository;
use App\Domain\TaskList\Repository\TaskListRepositoryInterface;
use App\Domain\TaskList\Repository\UsersRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class PersistenceServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        UsersRepositoryInterface::class => UsersRepository::class,
    ];
}
