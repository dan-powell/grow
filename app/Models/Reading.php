<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Device;
use App\Models\ReadingData;

class reading extends Model
{
    protected $table = 'reading';

    public $timestamps = true;

    protected $fillable = ['timestamp'];

    protected $dates = [
        'timestamp'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function data()
    {
        return $this->hasMany(ReadingData::class);
    }
}
