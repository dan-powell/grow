<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use App\Models\Device;
use App\Models\ReadingData;

class DeviceConfig extends Model
{
    use HasFactory;
    use HasUlids;

    protected $table = 'device_config';

    public $timestamps = false;

    protected $fillable = ['name', 'key', 'summary', 'location', 'calibrate', 'calibrate_value', 'calibrate_percentage'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function reading_data()
    {
        return $this->hasMany(ReadingData::class);
    }

}
