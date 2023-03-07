<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Figure;

class Device extends Model
{
    use HasFactory;
    use HasUlids;

    protected $table = 'device';

    public $timestamps = true;

    protected $guarded = [];

    protected $casts = [
        'alerted' => 'datetime'
    ];

    // public function reading_latest()
    // {
    //     return $this->hasMany(Reading::class)->orderBy('timestamp', 'desc')->limit(1);
    // }

    public function figures()
    {
        return $this->hasMany(Figure::class);
    }

}
