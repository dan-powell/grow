<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reading;

class device extends Model
{
    use HasFactory;

    protected $table = 'device';

    public $timestamps = true;

    protected $fillable = ['name'];

    public function readings()
    {
        return $this->hasMany(Reading::class);
    }

}
