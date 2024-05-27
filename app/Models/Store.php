<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Store extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'uuid',
        'user_id',
        'tagline',
        'type',
        'description',
        'web_address',
        'address',
        'image',
        'courier_name',
        'followers'
    ];

    protected $casts=[
        'name'=>'string',
        'uuid' => 'string',
        'user_id'=>'integer',
        'tagline' => 'string',
        'type' => 'string',
        'description' => 'string',
        'web_address' => 'string',
        'address' => 'json',
        'image' => 'string',
        'courier_name' => 'string',
        'followers' => 'integer',
    ];

    public function products():object {
        return $this->hasMany(Product::class);
    }

    public function followers(): object
    {
        return $this->hasMany(Follower::class);
    }

    public function orders(): object
    {
        return $this->hasMany(Order::class);
    }

    public function user():object {
        return $this->belongsTo(User::class);
    }

    
} 
