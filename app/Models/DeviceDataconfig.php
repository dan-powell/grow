<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Device;

class DeviceDataconfig extends Model
{
    use HasFactory;

    protected $table = 'device_dataconfig';

    public $timestamps = false;

    protected $fillable = ['name', 'key', 'summary', 'location', 'calibrate', 'calibrate_value', 'calibrate_percentage'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

}
