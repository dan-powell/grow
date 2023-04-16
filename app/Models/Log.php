<?php

namespace App\Models;

use App\Models\{Datum, Device};
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\{Builder, Model};

class Log extends Model
{
    use HasUlids;

    protected $table = 'log';

    public $timestamps = true;

    protected $fillable = [
        'name', 'summary', 'device_id', 'icon'
    ];
}
