<?php

namespace App\Models;

use App\Models\{Figure};
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\{Builder, Model};

class FigureAlert extends Model
{
    use HasUlids;

    protected $table = 'figure_alert';

    public $timestamps = false;

    protected $fillable = [
        'figure_id',
        'enabled',
        'value',
        'lower',
        'email',
    ];

    protected $casts = [
        'activated' => 'datetime',
    ];

    public function figure()
    {
        return $this->belongsTo(Figure::class);
    }
}
