<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  use HasFactory;

  public $incrementing = false;
  protected $fillable = [
    'id',
    'caption',
    'user_id',
    'image',
    'shared_id',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function likes()
  {
    return $this->hasMany(Like::class, 'post_id');
  }

  public function comments()
  {
    return $this->hasMany(Comment::class, 'post_id');
  }

  public function shared()
  {
    return $this->belongsTo(Post::class, 'shared_id');
  }

  public function shares()
  {
    return $this->hasMany(Post::class, 'shared_id');
  }
}
