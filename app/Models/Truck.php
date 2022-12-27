<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'ms_truck';
    public $timestamps = false;
    protected $fillable = [
        'id', 'bk', 'quota'
    ];
}
