<?php

namespace App\Models;

use App\Models\{Datum, Device};
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\{Builder, Model};

class FigureParent extends Model
{
    use HasUlids;

    protected $table = 'figure';

    public $timestamps = false;
}
