<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CommentController extends Controller
{
  public function store(Request $request, $postId)
  {
    $request->validate([
      'comment' => ['required'],
    ]);

    $user = auth()->user();
    $post = Post::find($postId);
    $id = Str::random(16);

    $comment = Comment::create([
      'comment' => htmlspecialchars($request->comment),
      'id' => $id,
      'user_id' => $user->id,
      'post_id' => $post->id,
    ]);
    return response()->json(new CommentResource($comment), 201);
  }

  public function update(Request $request)
  {}
  public function destroy(Request $request)
  {}
}
