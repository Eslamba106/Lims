<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function subplants()
    {
        return $this->hasMany(Plant::class, 'main_plant');
    }
    public function mainPlant()
    {
        return $this->belongsTo(Plant::class, 'main_plant');
    }
    public function samplePlants()
    {
        return $this->hasMany(SamplePlant::class, 'main_plant');
    }
}
