<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = ['bakery_id', 'name', 'address', 'opening_hours', 'profile_picture', 'active'];

    public function bakery()
    {
        return $this->belongsTo(Bakery::class);
    }

    public function users()
    {
        return $this->hasMany(BranchUser::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function phones()
    {
        return $this->hasMany(BranchPhone::class);
    }

    public function socialMedia()
    {
        return $this->hasMany(BranchSocialMedia::class);
    }
}
