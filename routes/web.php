<?php

/**
 * * API
 * // Don't ask why I put it here
 */

// Route::middleware(['guest'])->group(function () {
//   Route::post('/api/user/register', [UserController::class, 'register']);
//   Route::post('/api/user/login', [UserController::class, 'login']);
// });

// Route::middleware(['auth'])->group(function () {
//   Route::post('/api/user/logout', [UserController::class, 'logout']);
//   Route::post('/api/user/update', [UserController::class, 'update']);

//   Route::post('/api/post/store', [PostController::class, 'store']);
//   Route::post('/api/post/update/{id}', [PostController::class, 'update']);
//   Route::post('/api/post/destroy/{id}', [PostController::class, 'destroy']);

//   Route::get('/api/post', [PostController::class, 'index']);
//   Route::get('/api/post/{id}', [PostController::class, 'show']);
//   Route::post('/api/like/{id}', [LikeController::class, 'like']);

//   Route::get('/api/profile/{id}', [ProfileController::class, 'index']);
// });

// Route::get('/login', function () {
//   return Inertia::render('Login');
// })->name('login')->middleware('guest');

// $japaneseGoblin = ['/recover', '/help', '/about', '/about/terms', '/about/privacy', '/about/cookies'];
// foreach ($japaneseGoblin as $route) {
//   Route::get($route, function () {
//     return Inertia::location('https://youtu.be/UIp6_0kct_U');
//   });
// }

// Route::middleware(['auth'])->group(function () {
//   Route::redirect('/home', '/');

//   /**
//    * * Home
//    */

//   Route::get('/', function (Request $request) {
//     return Inertia::render('Home');
//   });

//   Route::get('/settings', function () {
//     return Inertia::render('Settings');
//   });

//   /**
//    * * Post
//    */

//   Route::get('/post/{id}', function ($id) {
//     $post = Post::find($id);
//     if (!$post) {
//       return abort(404);
//     }

//     return Inertia::render('SinglePost', [
//       'post' => new PostResource($post),
//     ]);
//   });

//   /**
//    * * Profile
//    */

//   Route::get('/{id}', function ($id) {
//     $user = User::find($id);
//     return Inertia::render('Profile', [
//       'profile' => [
//         'id' => $user->id,
//         'firstname' => $user->firstname,
//         'surname' => $user->surname,
//         'avatar' => $user->avatar,
//         'cover' => $user->cover,
//         'createdAt' => $user->created_at->format('F Y'),
//       ],
//     ]);
//   });
// });

Route::get('/', function () {
  return view('App');
});
