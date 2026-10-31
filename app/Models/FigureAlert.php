<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'figure_id',
    'enabled',
    'value',
    'lower',
    'email',
])]
#[Table(name: 'figure_alert')]
#[WithoutTimestamps]
class FigureAlert extends Model
{
    use HasUlids;

    public function figure()
    {
        return $this->belongsTo(Figure::class);
    }

    protected function casts(): array
    {
        return [
            'activated' => 'datetime',
        ];
    }
}
