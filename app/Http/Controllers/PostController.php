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
    if (!$request->caption && !$request->hasFile('image')) {
      return response()->json(['message' => 'Caption or Image is required.'], 400);
    }
    $request->validate([
      'caption' => ['sometimes', 'nullable'],
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
    return response()->json([
      'post' => new PostResource($post),
    ], 201);
  }

  public function update(Request $request, $id)
  {
    if (!$request->caption && !$request->hasFile('image')) {
      return response()->json(['message' => 'Caption or Image is required.'], 400);
    }
    $post = Post::find($id);
    if (auth()->user()->id !== $post->user_id) {
      return abort(400);
    }

    $request->validate([
      'caption' => ['sometimes', 'nullable'],
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
    return response()->json([
      'caption' => $post->caption,
      'image' => $imageName,
    ], 200);
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
    $post = Post::find($id);
    if ($post) {
      return response()->json([
        'post' => new PostResource($post),
      ], 200);
    }
    return abort(404);
  }

  public function share(Request $request, $id)
  {
    $request->validate([
      'caption' => ['sometimes', 'nullable'],
    ]);
    $sharedPost = Post::find($id);

    if (!$sharedPost) {
      return abort(404);
    }
    $randId = Str::random(16);
    $post = Post::create([
      'id' => $randId,
      'caption' => $request->caption,
      'shared_id' => $sharedPost->id,
      'user_id' => auth()->user()->id,
    ]);

    return response()->json([
      'post' => new PostResource($post),
      'sharesCount' => $sharedPost->shares->count(),
    ], 201);
  }
}
