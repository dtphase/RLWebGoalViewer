<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Replay extends Model
{
    protected $fillable = ['name', 'status', 'players', 'data', 'analysis', 'goals'];
}
