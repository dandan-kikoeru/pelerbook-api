<?php

namespace App\Http\Resources;

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

    return [
      'id' => $this->id,
      'caption' => $this->caption,
      'createdAt' => $this->created_at,
      'user' => new UserResource($this->user),
      'likes' => $this->likes->count(),
      'likedByUser' => $this->likes->where('user_id', auth()->user()->id)->isNotEmpty(),
      'image' => $this->image,
      'commentsCount' => $this->comments->count(),
      'comments' => CommentResource::collection($this->comments),
    ];
  }
}
