<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\Reply;
use App\Models\Comment;
use App\Http\Resources\PostResource;
use App\Http\Resources\ReplyResource;
use App\Http\Resources\CommentResource;

class LikeController extends Controller
{
  public function post($id)
  {
    $user = Auth()->user();
    $post = Post::find($id);

    $userAlreadyLiked = Like::where([['post_id', $post->id], ['user_id', $user->id]]);

    if (!$userAlreadyLiked->first()) {
      $like = [
        'user_id' => $user->id,
        'post_id' => $post->id,
      ];

      Like::create($like);
      return response()->json(new PostResource($post), 200);
    }

    $userAlreadyLiked->delete();
    return response()->json(new PostResource($post), 200);
  }

  public function comment($id)
  {
    $user = Auth()->user();
    $comment = Comment::find($id);

    $userAlreadyLiked = Like::where([['comment_id', $comment->id], ['user_id', $user->id]]);

    if (!$userAlreadyLiked->first()) {
      $like = [
        'user_id' => $user->id,
        'comment_id' => $comment->id,
      ];

      Like::create($like);
      return response()->json(new CommentResource($comment), 200);
    }

    $userAlreadyLiked->delete();
    return response()->json(new CommentResource($comment), 200);
  }

  public function reply($id)
  {
    $user = Auth()->user();
    $reply = Reply::find($id);

    $userAlreadyLiked = Like::where([['reply_id', $reply->id], ['user_id', $user->id]]);

    if (!$userAlreadyLiked->first()) {
      $like = [
        'user_id' => $user->id,
        'reply_id' => $reply->id,
      ];

      Like::create($like);
      return response()->json(new ReplyResource($reply), 200);
    }

    $userAlreadyLiked->delete();
    return response()->json(new ReplyResource($reply), 200);
  }
}
