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
    $rand = Str::random(4);

    if ($request->hasFile('image')) {
      $request->validate([
        'image' => ['mimes:jpeg,png,jpg,webp', 'max:2048'],
      ]);
      $imageName = '/images/' . $randId . '.webp';
      Image::make($request->file('image'))
        ->encode('webp', 90)
        ->save(public_path($imageName));
      $imageName = $imageName . '#' . $rand;
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
      return abort(400);
    }

    $request->validate([
      'caption' => ['required'],
    ]);

    $imageName = null;
    $rand = Str::random(4);
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
      $imageName = $imageName . '#' . $rand;
    }
    $post->caption = htmlspecialchars($request->caption);
    $post->image = $imageName;
    $post->save();
    return response()->json(new PostResource($post), 200);
  }

  public function destroy($id)
  {
    $post = Post::find($id);
    if (auth()->user()->id === $post->user_id) {
      $post->delete();
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
