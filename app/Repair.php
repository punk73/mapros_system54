<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    protected $table ='repairs';

    protected $fillable = ['unique_id','dummy_id'];
}
