<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supir extends Model
{
    protected $table = 'msupir';    
    public $timestamps = false;
    protected $fillable = ['no_polisi','ket','transporter','supir','no_surat','ban'];
}
