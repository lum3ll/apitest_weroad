<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Travel extends Model
{
    use HasFactory;

    protected $table = 'api_travels';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'id',
        'isPublic',
        'slug',
        'name',
        'description',
        'numberOfDays',
        'numberOfNights',
        'moods'
    ];

    protected $casts = [
        'moods' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($travel) {
            $travel->numberOfNights = $travel->numberOfDays - 1;
        });
    }

    public function tours()
    {
        return $this->hasMany(Tour::class);
    }

    public function getNumNightsAttribute()
    {
        return $this->numberOfDays - 1;
    }

}
