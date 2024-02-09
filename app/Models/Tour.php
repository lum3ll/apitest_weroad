<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function getPriceAttribute($value)
    {
        return number_format($value / 100, 2);
    }

    public function travel()
    {
        return $this->belongsTo(Travel::class, 'travelId', 'id');
    }
    
}
