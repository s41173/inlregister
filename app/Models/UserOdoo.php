<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOdoo extends Model
{
    protected $table = 'res_users';
    protected $connection = 'pgsql_sec';
    
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login',
        'company_id',
        'partner_id',
        'notification_type',
        'odoobot_state',
        'password_ext',
        'level_ext'
    ];
}
