<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SamplePlant extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function mainPlant()
    {
        return $this->belongsTo(SamplePlant::class, 'main_plant');
    }
}
