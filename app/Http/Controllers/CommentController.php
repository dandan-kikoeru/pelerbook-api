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
      'content' => ['required'],
    ]);

    $user = auth()->user();
    $post = Post::find($postId);
    $id = Str::random(16);

    $comment = Comment::create([
      'content' => htmlspecialchars($request->content),
      'id' => $id,
      'user_id' => $user->id,
      'post_id' => $post->id,
    ]);
    return response()->json(new CommentResource($comment), 201);
  }

  public function update(Request $request, $id)
  {
    $request->validate([
      'content' => ['required'],
    ]);

    $comment = Comment::find($id);

    if (auth()->user()->id === $comment->user_id) {
      $comment->content = htmlspecialchars($request->content);
      $comment->save();
      return response()->json(new CommentResource($comment), 200);
    }
    return abort(400);
  }
  public function destroy(Request $request, $id)
  {
    $comment = Comment::find($id);
    if (auth()->user()->id === $comment->user_id) {
      $comment->delete();
      return response()->json(['message' => 'Comment deleted successfully'], 200);
    }
    return abort(400);
  }
}
