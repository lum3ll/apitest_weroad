<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tour extends Model
{
    use HasFactory;

    protected $table = 'api_tours';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'travelId',
        'name',
        'startingDate',
        'endingDate',
        'price'
    ];

    protected $casts = [
        'startingDate' => 'datetime',
        'endingDate' => 'datetime'
    ];

    protected static function boot() 
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) { 
                $model->id = Str::uuid();
            }
        });
    }

    public function getPriceAttribute($value)
    {
        return number_format($value / 100, 2);
    }

    public function travel()
    {
        return $this->belongsTo(Travel::class, 'travelId', 'id');
    }
    
}
