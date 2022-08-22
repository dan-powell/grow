<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reading;
use App\Models\DeviceDataconfig;

class Device extends Model
{
    use HasFactory;

    protected $table = 'device';

    public $timestamps = true;

    protected $fillable = ['name'];

    public function reading_latest()
    {
        return $this->hasMany(Reading::class)->orderBy('timestamp', 'desc');
    }

    public function readings()
    {
        return $this->hasMany(Reading::class);
    }

    public function dataconfigs()
    {
        return $this->hasMany(DeviceDataconfig::class);
    }

}
