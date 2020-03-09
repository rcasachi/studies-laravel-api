<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JSONAPIIdentifierResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => (string) $this->id,
            'type' => $this->type(),
        ];
    }
}
