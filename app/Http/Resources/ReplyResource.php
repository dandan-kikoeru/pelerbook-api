<?php

namespace App\Http\Resources;

use App\Services\Formatting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReplyResource extends JsonResource
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
    ];
  }
}
