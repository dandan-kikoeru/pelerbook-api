<?php

use App\Models\Post;
if (!function_exists('totalCommentsCount')) {
  function totalCommentsCount(Post $post): int
  {
    $totalCommentsCount = $post->comments->count();

    foreach ($post->comments as $comment) {
      $totalCommentsCount += $comment->replies->count();
    }

    return $totalCommentsCount;
  }
}
