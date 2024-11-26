<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bakery extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'opening_hours', 'profile_picture', 'active', 'user_id'];

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    // Relación inversa con el User
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
