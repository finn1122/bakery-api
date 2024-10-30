<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchSocialMedia extends Model
{
    use HasFactory;

    protected $fillable = ['branch_id', 'social_media_id', 'url', 'active'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function socialMedia()
    {
        return $this->belongsTo(SocialMedia::class);
    }
}
