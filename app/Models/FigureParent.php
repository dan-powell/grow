<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class FigureParent extends Model
{
    use HasUlids;

    protected $table = 'figure';

    public $timestamps = false;
}
