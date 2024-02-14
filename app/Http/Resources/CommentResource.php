<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'user' => new UserResource($this->user),
      'id' => $this->id,
      'content' => $this->content,
      'createdAt' => $this->created_at,
      'repliesCount' => $this->replies->count(),
      'replies' => ReplyResource::collection($this->replies),
      'likes' => $this->likes->count(),
      'likedByUser' => $this->likes->where('user_id', auth()->user()->id)->isNotEmpty(),
      'postId' => $this->post->id,
    ];
  }
}
