<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Suitmedia\Cacheable\Contracts\CacheableModel;
use Suitmedia\Cacheable\Traits\Model\CacheableTrait;

class Country extends Model implements CacheableModel
{
    use CacheableTrait;

    public $timestamps = false;

    /**
     * Get the users with this citizenship
     */
    public function users()
    {
        return $this->hasMany('App\UserDetail','citizenship_country_id');
    }
}
