<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'store_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'store_id' => 'integer',
    ];

    public function store(): object
    {
        return $this->belongsTo(Store::class);
    }

    public function user(): object
    {
        return $this->belongsTo(User::class);
    }
}
