<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Device;
use App\Models\Datum;

class FigureParent extends Model
{
    use HasUlids;

    protected $table = 'figure';

    public $timestamps = false;

}
