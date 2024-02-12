<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\ReplyResource;

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

    $reply = Comment::create([
      'content' => htmlspecialchars($request->content),
      'id' => $id,
      'user_id' => $user->id,
      'comment_id' => $comment->id,
    ]);
    return response()->json(new ReplyResource($reply), 201);
  }

  public function update(Request $request)
  {}
  public function destroy(Request $request)
  {}
}
