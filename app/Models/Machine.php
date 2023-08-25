<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;
    protected $guarded = [];

    function line()
    {
        return $this->belongsTo(Line::class);
    }

    function topics()
    {
        return $this->belongsToMany(Topic::class);
    }
}
