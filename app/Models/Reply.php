<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
  use HasFactory;

  public $incrementing = false;

  protected $fillable = [
    'id',
    'comment_id',
    'user_id',
    'content',
  ];

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function comment()
  {
    return $this->belongsTo(Comment::class, 'comment_id');
  }
}
