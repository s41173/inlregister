<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unloading extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'unload';
    public $timestamps = false;
}
