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

    if ($request->hasFile('image')) {
      $request->validate([
        'image' => ['mimes:jpeg,png,jpg,webp', 'max:2048'],
      ]);
      $imageName = '/images/' . $randId . '.webp';
      Image::make($request->file('image'))
        ->encode('webp', 90)
        ->save(public_path($imageName));
    }

    Post::create([
      'caption' => htmlspecialchars($request->caption),
      'id' => $randId,
      'user_id' => auth()->user()->id,
      'image' => $imageName,
    ]);
    return back()->with('success', 'Success created post');
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
    }

    $post->caption = htmlspecialchars($request->caption);
    $post->image = $imageName;
    $post->save();
    return back()->with('success', 'Success edited post');
  }

  public function destroy(Post $id)
  {
    if (auth()->user()->id = $id->user_id) {
      $id->likes()->delete();
      $id->delete();
      return back()->with('success', 'Success deleted post');
    }
    return abort(401);
  }
  public function index(Request $request)
  {
    $takePage = $request->input('take', 1);
    $posts = Post::latest()->paginate(10 * $takePage);
    return PostResource::collection($posts);
  }

  public function show($id)
  {
    $post = Post::find($id);
    return new PostResource($post);
  }
}
