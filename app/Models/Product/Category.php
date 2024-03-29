<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
  use HasFactory;
  use SoftDeletes;

  protected $fillable = [
    'name',
    'icon',
    'image',
    'status',
    'department_id',
    'category_id',
    'position',
    'type_category',
  ];


  public function department()
  {
    return $this->belongsTo(Category::class);
  }


  public function category()
  {
    return $this->belongsTo(Category::class);
  }
  
}
