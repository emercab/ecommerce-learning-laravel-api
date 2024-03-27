<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
{
  public function toArray(Request $request): array
  {
    return [
      'data' => CategoryResource::collection($this->collection),
    ];
  }
}
