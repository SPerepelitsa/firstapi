<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserStat extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_stat';
    public $timestamps = false;
}
