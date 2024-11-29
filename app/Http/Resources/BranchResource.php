<?php

namespace App\Http\Resources;

use App\Utils\UrlHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'bakeryId' => $this->bakery_id,
            'name' => $this->name,
            'address' => $this->address,
            'openingHours' => $this->opening_hours,
            'profilePicture' => UrlHelper::getServerFtpUrl($this->profile_picture),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'active' => $this->active,
        ];
    }
}
