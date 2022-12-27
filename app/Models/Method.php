<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Method extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'ms_method_coa';
    public $timestamps = false;
}
