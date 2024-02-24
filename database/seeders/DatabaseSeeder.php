<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {

    $users = User::factory()->count(25)->create();
    $userIds = $users->pluck('id')->toArray();

    for ($i = 0; $i < 25; $i++) {
      $posts = Post::factory()->create([
        'user_id' => $userIds[array_rand($userIds)],
      ]);
    }
    $postIds = $posts->pluck('id')->toArray();
    for ($i = 0; $i < 5; $i++) {
      $post = Post::find($postIds[array_rand($postIds)]);
      Post::factory()->create([
        'user_id' => $userIds[array_rand($userIds)],
        'shared_id' => $post->id,
        'created_at' => fake()->dateTimeBetween($post->created_at, 'now'),
      ]);
    }
    $postIds = $posts->pluck('id')->toArray();

    for ($i = 0; $i < 1000; $i++) {
      $post = Post::find($postIds[array_rand($postIds)]);
      $comments = Comment::factory()->create([
        'user_id' => $userIds[array_rand($userIds)],
        'post_id' => $post->id,
        'created_at' => fake()->dateTimeBetween($post->created_at, 'now'),
      ]);
    }
    $commentIds = $comments->pluck('id')->toArray();

    for ($i = 0; $i < 250; $i++) {
      $comment = Comment::find($commentIds[array_rand($commentIds)]);
      $replies = Reply::factory()->create([
        'user_id' => $userIds[array_rand($userIds)],
        'comment_id' => $comment->id,
        'created_at' => fake()->dateTimeBetween($comment->created_at, 'now'),
      ]);
    }
    $replyIds = $replies->pluck('id')->toArray();

    for ($i = 0; $i < 1000; $i++) {
      Like::factory()->create([
        'user_id' => $userIds[array_rand($userIds)],
        'post_id' => $postIds[array_rand($postIds)],
      ]);
    }

    for ($i = 0; $i < 500; $i++) {
      Like::factory()->create([
        'user_id' => $userIds[array_rand($userIds)],
        'comment_id' => $commentIds[array_rand($commentIds)],
      ]);
    }

    for ($i = 0; $i < 100; $i++) {
      Like::factory()->create([
        'user_id' => $userIds[array_rand($userIds)],
        'reply_id' => $replyIds[array_rand($replyIds)],
      ]);
    }
  }
}
