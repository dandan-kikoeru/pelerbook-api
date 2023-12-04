<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;

class LikeController extends Controller
{
  public function like($id)
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
      return;
    }

    $userAlreadyLiked->delete();
    return;
  }

}
