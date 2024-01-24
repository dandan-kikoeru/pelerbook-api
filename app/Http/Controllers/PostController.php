<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Image;

class PostController extends Controller
{
  public function store(Request $request)
  {
    $request->validate([
      'caption' => ['required'],
    ]);

    $imageName = null;
    $randId = Str::random(16);
    $randNum = random_int(0, 63);

    if ($request->hasFile('image')) {
      $request->validate([
        'image' => ['mimes:jpeg,png,jpg,webp', 'max:2048'],
      ]);
      $imageName = '/images/' . $randId . '.webp';
      Image::make($request->file('image'))
        ->encode('webp', 90)
        ->save(public_path($imageName));
      $imageName = $imageName . '?' . $randNum;
    }

    $post = Post::create([
      'caption' => htmlspecialchars($request->caption),
      'id' => $randId,
      'user_id' => auth()->user()->id,
      'image' => $imageName,
    ]);
    return response()->json(new PostResource($post), 201);
  }

  public function update(Request $request, $id)
  {
    $post = Post::find($id);
    if (auth()->user()->id !== $post->user_id) {
      return abort(401);
    }

    $request->validate([
      'caption' => ['required'],
    ]);

    $imageName = null;
    $randNum = random_int(0, 63);
    if ($request->image !== null) {
      $imageName = $post->image;
    }

    if ($request->hasFile('image')) {
      $request->validate([
        'image' => ['mimes:jpeg,png,jpg,webp', 'max:2048'],
      ]);
      $imageName = '/images/' . $post->id . '.webp';
      Image::make($request->file('image'))
        ->encode('webp', 90)
        ->save(public_path($imageName));
      $imageName = $imageName . '?' . $randNum;
    }
    $post->caption = htmlspecialchars($request->caption);
    $post->image = $imageName;
    $post->save();
    return response()->json(new PostResource($post), 200);
  }

  public function destroy(Post $id)
  {
    if (auth()->user()->id = $id->user_id) {
      $id->likes()->delete();
      $id->delete();
      return response()->json(['message' => 'Post deleted successfully'], 200);
    }
    return abort(400);
  }
  public function index(Request $request)
  {
    $posts = Post::latest()->paginate(10);
    return PostResource::collection($posts);
  }

  public function show($id)
  {
    if ($post = Post::find($id)) {
      return new PostResource($post);
    }
    return abort(404);
  }
}
