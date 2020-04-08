<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name','last_name','phone_number','citizenship_country_id'];

    /**
     * Get the user that owns this detail
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the user citizenship
     */
    public function citizenship()
    {
        return $this->belongsTo('App\Country','citizenship_country_id');
    }
}
