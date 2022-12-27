<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Segel extends Model
{
    protected $table = 'mnosegel';    
    public $timestamps = false;
    protected $fillable = ['nomor','keterangan'];
}
