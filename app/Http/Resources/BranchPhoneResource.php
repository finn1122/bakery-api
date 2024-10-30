<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchPhoneResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'branchId' => $this->branch_id,
            'phoneId' => $this->phone_id,
            'number' => $this->number,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'active' => $this->active,
        ];
    }
}
