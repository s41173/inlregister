<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Segelbc extends Model
{
    protected $table = 'ms_segel_bc';    
    public $timestamps = false;
    protected $fillable = ['nomor','status'];
}
