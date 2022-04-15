<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Reponse;
use App\Models\Post;
use Exception;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller {
  public function __construct() {
    $this->middleware('api.auth', ['except' => ['index', 'show', 'getPostsByCategory', 'getPostsByAuthor']]);
  }

  public function index() {
    $posts = Post::all()->load('category');

    $data = array(
      'status' => 'successfull',
      'code' => 200,
      'data' => $posts
    );

    return response()->json($data, 200);
  }

  public function show($id) {
    $post = Post::find($id)->load('category');

    if (!is_object($post)) {
      $data = array(
        'status' => 'error',
        'code' => 400,
        'message' => 'Post not found'
      );

      return response()->json($data, 400);
    }

    $data = array(
      'status' => 'successfull',
      'code' => 200,
      'data' => $post
    );

    return response()->json($data, 200);
  }

  public function store(Request $request) {
    $validator = Validator::make($request->all(), array(
      'title' => 'required',
      'content' => 'required',
      'category_id' => 'required|numeric'
    ));

    if ($validator->fails()) {
      $data = array(
        'status' => 'error',
        'code' => 400,
        'errors' => $validator->errors()
      );

      return response()->json($data, 400);
    }

    $post = new Post();
    $post->user_id = $request->currentUser->sub;
    $post->title = $request->title;
    $post->content = $request->content;
    $post->category_id = $request->category_id;

    $post->save();

    $data = array(
      'status' => 'successfull',
      'code' => 200,
      'data' => $post
    );

    return response()->json($data, 200);
  }

  public function update($id, Request $request) {
    $validator = Validator::make($request->all(), array(
      'title' => '',
      'content' => '',
      'category_id' => 'numeric'
    ));

    if ($validator->fails()) {
      $data = array(
        'status' => 'error',
        'code' => 400,
        'errors' => $validator->errors()
      );

      return response()->json($data, 400);
    }

    $updateData = array();

    if (boolval($request->title)) $updateData['title'] = $request->title;
    if (boolval($request->content)) $updateData['content'] = $request->content;
    if (boolval($request->category_id)) $updateData['category_id'] = $request->category_id;

    $post = Post::where('id', $id)->first();
    
    if (!is_object($post)) {
      $data = array(
        'status' => 'error',
        'code' => 404,
        'message' => 'post not found'
      );

      return response()->json($data, 400);
    }

    if ($post->user_id !== $request->currentUser->sub) {
      $data = array(
        'status' => 'error',
        'code' => 400,
        'message' => 'permisition denied'
      );

      return response()->json($data, 400);
    }

    $post->update($updateData);

    $data = array(
      'status' => 'successfull',
      'code' => 200,
      'message' => 'Post updated successfully',
      'data' => $post
    );

    return response()->json($data, 200);
  }

  public function destroy($id, Request $request) {
    $post = Post::find($id);

    if (!is_object($post)) {
      $data = array(
        'status' => 'error',
        'code' => 400,
        'message' => 'Post not found'
      );

      return response()->json($data, 400);
    }

    if ($post->user_id !== $request->currentUser->sub) {
      $data = array(
        'status' => 'error',
        'code' => 400,
        'message' => 'Permission denied'
      );

      return response()->json($data, 400);
    }

    $post->delete();

    $data = array(
      'status' => 'successfull',
      'code' => 200,
      'message' => 'Post deleted successfully'
    );

    return response()->json($data, 200);
  }

  public function getPostsByCategory($id) {
    $posts = Post::where('category_id', $id)->get();

    $data = array(
      'status' => 'successfull',
      'code' => 200,
      'data' => $posts
    );

    return response()->json($data, 200);
  }

  public function getPostsByAuthor($id) {
    $posts = Post::where('user_id', $id)->get();

    $data = array(
      'status' => 'successfull',
      'code' => 200,
      'data' => $posts
    );

    return response()->json($data, 200);
  }
}
