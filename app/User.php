<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Suitmedia\Cacheable\Contracts\CacheableModel;
use Suitmedia\Cacheable\Traits\Model\CacheableTrait;

class User extends Model implements CacheableModel
{
    use CacheableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email','active'];


    /**
     * Get the detail associated with this user
     */
    public function detail()
    {
        return $this->hasOne('App\UserDetail','user_id');
    }
}
