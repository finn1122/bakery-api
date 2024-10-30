<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchPhone extends Model
{
    use HasFactory;

    protected $fillable = ['branch_id', 'phone_id', 'number', 'active'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function phone()
    {
        return $this->belongsTo(Phone::class);
    }
}
