<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Reading;
use App\Models\DeviceConfig;

class Device extends Model
{
    use HasFactory;
    use HasUlids;

    protected $table = 'device';

    public $timestamps = true;

    protected $fillable = ['name'];

    protected $casts = [
        'alerted' => 'datetime'
    ];

    public function reading_latest()
    {
        return $this->hasMany(Reading::class)->orderBy('timestamp', 'desc')->limit(1);
    }

    public function readings()
    {
        return $this->hasMany(Reading::class)->orderBy('timestamp', 'desc')->limit(144);
    }

    public function configs()
    {
        return $this->hasMany(DeviceConfig::class);
    }

}
