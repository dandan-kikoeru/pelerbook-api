<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
  use HasFactory;

  public $incrementing = false;

  protected $fillable = [
    'id',
    'post_id',
    'user_id',
    'content',
  ];

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function post()
  {
    return $this->belongsTo(Post::class, 'post_id');
  }

  public function replies()
  {
    return $this->hasMany(Reply::class);
  }

  public function likes()
  {
    return $this->hasMany(Like::class, 'comment_id');
  }

}
