<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReplyResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReplyController extends Controller
{
  public function store(Request $request, $commentId)
  {
    $request->validate([
      'content' => ['required'],
    ]);

    $user = auth()->user();
    $comment = Comment::find($commentId);
    $id = Str::random(16);

    $reply = Reply::create([
      'content' => htmlspecialchars($request->content),
      'id' => $id,
      'user_id' => $user->id,
      'comment_id' => $comment->id,
    ]);
    $totalCommentsCount = totalCommentsCount(Post::find($comment->post->id));
    return response()->json([
      'data' => new ReplyResource($reply),
      'commentsCount' => $totalCommentsCount,
    ], 201);
  }

  public function update(Request $request, $id)
  {
    $request->validate([
      'content' => ['required'],
    ]);

    $reply = Reply::find($id);

    if (auth()->user()->id === $reply->user_id) {
      $reply->content = htmlspecialchars($request->content);
      $reply->save();
      return response()->json(new ReplyResource($reply), 200);
    }
    return abort(400);
  }

  public function destroy(Request $request, $id)
  {
    $reply = Reply::find($id);

    if (auth()->user()->id === $reply->user_id) {
      $reply->delete();
      $totalCommentsCount = totalCommentsCount(Post::find($reply->comment->post->id));
      return response()->json(['commentsCount' => $totalCommentsCount], 200);
    }
    return abort(400);
  }
}
