<?php


namespace App\Domain\TaskList\Event\User;

use App\Domain\TaskList\Models\User\User;
use Illuminate\Queue\SerializesModels;

class UserEvent
{
     use SerializesModels;

     /**
      * @var User
      */
     public $user;

     public function __construct(User $user)
     {
         $this->user = $user;
     }
 }
