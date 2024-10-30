<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bakery extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'opening_hours', 'profile_picture', 'active'];

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
