<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller {
  public function __construct() {
    $this->middleware('api.auth', array(
      'except' => ['index', 'show']
    ));
  }

  public function index() {
    $categories = Category::all();

    $data = array(
      'status' => 'successfull',
      'code' => 200,
      'data' => $categories
    );

    return response()->json($data, 200);
  }

  public function show($id) {
    $category = Category::find($id);

    if (!is_object($category)) {
      $data = array(
        'status' => 'error',
        'code' => 400,
        'message' => 'Category not found'
      );

      return response()->json($data, 400);
    }

    $data = array(
      'status' => 'successfull',
      'code' => 200,
      'data' => $category
    );

    return response()->json($data, 200);
  }

  public function store(Request $request) {
    $validator = Validator::make($request->all(), array(
      'name' => ['required']
    ));

    if ($validator->fails()) {
      $data = array(
        'status' => 'error',
        'code' => 400,
        'errors' => $validator->errors()
      );

      return response()->json($data, 400);
    }

    $category = new Category();
    $category->name = $request->name;
    $category->save();

    $data = array(
      'status' => 'successfull',
      'code' => 200,
      'data' => $category
    );

    return response()->json($data, 200);
  }

  public function update($id, Request $request) {
    $validator = Validator::make($request->all(), array(
      'name' => ['required']
    ));

    if ($validator->fails()) {
      $data = array(
        'status' => 'error',
        'code' => 400,
        'errors' => $validator->errors()
      );

      return response()->json($data, 400);
    }

    $dataUpdate = ['name' => $request->name];
    Category::where('id', $id)->update($dataUpdate);

    $data = array(
      'status' => 'successfull',
      'code' => 200,
      'message' => 'Category updated successfully'
    );

    return response()->json($data, 200);
  }
}
