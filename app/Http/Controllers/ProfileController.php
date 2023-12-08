<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
  public function index($id, Request $request)
  {
    $user = User::find($id);
    $posts = Post::where('user_id', $user->id)->latest()->paginate(10);
    return PostResource::collection($posts);
  }
}
