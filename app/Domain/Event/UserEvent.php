<?php


namespace App\Domain\Event;


use App\Domain\User;
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
