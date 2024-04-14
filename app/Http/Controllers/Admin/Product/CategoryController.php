<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\CategoryCollection;
use App\Http\Resources\Product\CategoryResource;
use Illuminate\Http\Request;

use App\Models\Product\Category;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
  public function index(Request $request)
  {
    // Catch the search query
    $search = $request->search;
    
    // Get all the categories from the search
    $categories = Category::where('name', 'like', '%'.$search.'%')
      ->orderBy('id', 'desc')
      ->paginate(2);

    return response()->json([
      'categories' => CategoryCollection::make($categories),
      'totalPages' => $categories->total(),
    ]);
  }


  public function store(Request $request)
  {
    // Check if the name already exists
    $existsCategory = Category::where('name', $request->name)->first();
    if ($existsCategory) {
      return response()->json([
        'message' => 'Category already exists.',
        'code' => 403,
      ], 403);
    }

    // Check if image is present
    if ($request->hasFile('img')) {
      $image = $request->file('img');
      $path = Storage::putFile('category', $image);

      // Add the image path to the request
      $request->request->add(['image' => $path]);
    }

    // Create the category
    $category = Category::create($request->all());

    return response()->json([
      'message' => 'Category created successfully.',
      'category' => CategoryResource::make($category),
      'code' => 200,
    ]);
  }


  public function show(string $id)
  {
    // Get the category
    $category = Category::findOrFail($id);

    return response()->json([
      'category' => CategoryResource::make($category),
      'code' => 200,
    ]);
  }


  public function update(Request $request, string $id)
  {
    // Check if the name already exists
    $existsCategory = Category::where('id', '<>', $id)->where('name', $request->name)->first();
    if ($existsCategory) {
      return response()->json([
        'message' => 'Category already exists',
        'code' => 403,
      ]);
    }

    // Get the category to update
    $category = Category::findOrFail($id);

    // Check if image is present
    if ($request->hasFile('img')) {
      // Delete the old image
      if ($category->image) {
        Storage::delete($category->image);
      }
      $image = $request->file('image');
      $path = Storage::putFile('category', $image);

      // Add the image path to the request
      $request->request->add(['image' => $path]);
    }

    // Update the category
    $category->update($request->all());

    return response()->json([
      'message' => 'Category updated successfully.',
      'code' => 200,
    ]);
  }


  public function destroy(string $id)
  {
    // Get the category to delete
    $category = Category::findOrFail($id);
    $category->delete();
    
    return response()->json([
      'message' => 'Category deleted successfully.',
      'code' => 200,
    ]);
  }


  public function listCategories()
  {
    $departments = Category::whereNull('department_id')->whereNull('category_id')->get();
    $categories = Category::whereNotNull('department_id')->whereNull('category_id')->get();

    return response()->json([
      'departments' => $departments,
      'numDepartments' => $departments->count(),
      'categories' => $categories,
      'numCategories' => $categories->count(),
      'code' => 200,
    ]);
  }
}
