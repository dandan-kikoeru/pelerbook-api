<?php

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    $totalCommentsCount = totalCommentsCount(Post::find($this->id));
    $comments = $this->comments()
      ->latest()
      ->paginate(2);
    // reverse the paginated array
    $comments->setCollection($comments->getCollection()->reverse());

    return [
      'id' => $this->id,
      'caption' => $this->caption,
      'createdAt' => $this->created_at,
      'user' => new UserResource($this->user),
      'likes' => $this->likes->count(),
      'likedByUser' => $this->likes->where('user_id', auth()->user()->id)->isNotEmpty(),
      'image' => $this->image,
      'commentsCount' => $totalCommentsCount,
      // /api/post/{id}?withoutComments=true
      'comments' => $request->withoutComments ? [] : CommentResource::collection($comments),
      'shared' => new PostResource($this->shared),
      'sharesCount' => $this->shares->count(),
    ];
  }
}
