<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Reading;

class ReadingData extends Model
{
    protected $table = 'reading_data';

    public $timestamps = true;

    protected $fillable = ['name', 'value'];

    public function reading()
    {
        return $this->belongsTo(Reading::class);
    }

}
