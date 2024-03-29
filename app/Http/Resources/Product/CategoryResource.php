<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->resource->id,
      'name' => $this->resource->name,
      'icon' => $this->resource->icon,
      'image' => $this->resource->image ? env('APP_URL') . 'storage/' . $this->resource->image : null,
      'status' => $this->resource->status,
      'department_id' => $this->resource->department_id,
      'department' => $this->resource->department
        ? [
            'name' => $this->resource->department->name,
          ]
        : null,
      'category_id' => $this->resource->category_id,
      'category' => $this->resource->category
        ? [
            'name' => $this->resource->category->name,
          ]
        : null,
      'position' => $this->resource->position,
      'type_category' => $this->resource->type_category,
      'created_at' => $this->resource->created_at->format('Y-m-d H:i:s'),
    ];
  }
}
