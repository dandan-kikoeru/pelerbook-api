<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Image;

class UserController extends Controller
{

  public function register(RegisterRequest $request)
  {
    $user = User::create($request->all());
    Auth::login($user);
    $request->session()->regenerateToken();
    return response()->json(new UserResource($user), 201);
  }

  public function login(LoginRequest $request)
  {
    if (Auth::attempt([
      'email' => $request->email,
      'password' => $request->password,
    ])) {
      $user = Auth::user();
      $request->session()->regenerate();
      return response()->json([
        'user' => new UserResource($user),
      ], 200);
    }

    return response()->json(['message' => 'Invalid credentials'], 400);
  }

  public function logout(Request $request)
  {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return response()->json(['message' => 'Logged out successfully'], 200);
  }

  public function update(UserUpdateRequest $request)
  {
    $user = Auth::user();
    $rand = Str::random(4);

    if ($request->hasFile('avatar')) {
      $request->validate([
        'avatar' => ['required', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
      ]);
      $avatarName = $user->id . '.webp';
      $avatar = Image::make($request->file('avatar'))->encode('webp', 90);
      $avatar
        ->resize(256, null, function ($constraint) {
          $constraint->aspectRatio();
        })
        ->save(public_path('/avatars/' . $avatarName));
      $user->avatar = '/avatars/' . $avatarName . '#' . $rand;
    }

    if ($request->hasFile('cover')) {
      $request->validate([
        'cover' => ['required', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
      ]);
      $coverName = $user->id . '.webp';
      Image::make($request->file('cover'))
        ->encode('webp', 90)
        ->save(public_path('/covers/' . $coverName));
      $user->cover = '/covers/' . $coverName . '#' . $rand;
    }

    if ($request->first_name) {
      $user->first_name = $request->first_name;
      $user->surname = $request->surname;
    }
    $user->save();
    return response()->json(new UserResource($user), 200);
  }

  public function show(Request $request)
  {
    $user = $request->user();
    return response()->json([
      'user' => new UserResource($user),
    ], 200);
  }
}
