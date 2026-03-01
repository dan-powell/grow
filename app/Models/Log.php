<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasUlids;

    protected $table = 'log';

    public $timestamps = true;

    protected $fillable = [
        'name', 'summary', 'device_id', 'icon',
    ];

    protected $appends = [
        'created_at_formatted',
        'created_at_date',
        'created_at_time',
        'created_at_diff',
    ];

    protected function createdAt(): Attribute
    {
        return Attribute::get(fn ($value): Carbon => Carbon::parse($value)->setTimezone('Europe/London'));
    }

    protected function createdAtFormatted(): Attribute
    {
        return Attribute::get(fn (): string => $this->created_at->toDateTimeString());
    }

    protected function createdAtDate(): Attribute
    {
        return Attribute::get(fn (): string => $this->created_at->format('jS M o'));
    }

    protected function createdAtTime(): Attribute
    {
        return Attribute::get(fn (): string => $this->created_at->format('h:ia'));
    }

    protected function createdAtDiff(): Attribute
    {
        return Attribute::get(fn (): string => $this->created_at->diffForHumans());
    }
}
