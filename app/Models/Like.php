<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
  use HasFactory;

  public $incrementing = false;
  protected $fillable = [
    'post_id',
    'user_id',
    'comment_id',
    'reply_id',
  ];

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function post()
  {
    return $this->belongsTo(Post::class, 'post_id');
  }

  public function comment()
  {
    return $this->belongsTo(Comment::class, 'comment_id');
  }

  public function reply()
  {
    return $this->belongsTo(Reply::class, 'reply_id');
  }
}
