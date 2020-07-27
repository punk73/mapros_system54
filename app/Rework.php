<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rework extends Model
{
    protected $table = 'rework';

    public $incrementing = false;

    public $timestamps = false;

    protected $primaryKey = 'barcode';
}
