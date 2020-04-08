<?php

namespace App\Domain\Event;

use App\Domain\User;
use Illuminate\Queue\SerializesModels;

class UserHasBeenDeleted extends UserEvent
{
}
