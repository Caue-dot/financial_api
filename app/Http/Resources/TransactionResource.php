<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reportId' => $this->report_id,
            'value' => $this->value,
            'name' => $this->name,
            'description'=> $this->description,
            'type' => $this->type,
            'category' => $this->category,
            'recurrent' => (bool)$this->recurrent,
            'createdAt' => $this->created_at->format('Y-m-d h:m:s'),
        ];
    }
}
