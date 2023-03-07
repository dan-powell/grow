<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use App\Models\Figure;

class Datum extends Model
{
    use HasFactory;
    use HasUlids;

    protected $table = 'datum';

    public $timestamps = true;

    protected $fillable = ['name', 'value'];

    public function figure()
    {
        return $this->belongsTo(Figure::class);
    }

}
