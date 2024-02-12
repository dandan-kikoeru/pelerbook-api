<?php

namespace App\Http\Resources;

use App\Services\Formatting;
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
      'content' => Formatting::format_message($this->content),
      'createdAt' => $this->created_at,
      'replies' => ReplyResource::collection($this->replies),
      'likes' => $this->likes->count(),
      'likedByUser' => $this->likes->where('user_id', auth()->user()->id)->isNotEmpty(),
      'postId' => $this->post->id,
    ];
  }
}
