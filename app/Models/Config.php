<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $primaryKey = 'key';
    protected $table = 'config';
    public $timestamps = false;
}
