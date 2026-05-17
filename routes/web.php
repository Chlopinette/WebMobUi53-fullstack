<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PollDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TokenController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $posts = Post::orderBy('created_at', 'desc')->with('user')->with('likes')->limit(3)->get();

    return view('home', ['posts' => $posts]);
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/p/{token}', function () {
    return view('polls.vote');
})->name('polls.vote');

Route::get('/@{username}', [ProfileController::class, 'show'])->where('username', '[A-Za-z0-9-_]+');

// Routes publiques pour les posts
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

Route::controller(AuthController::class)->group(function () {
    Route::get('/auth/register', 'showRegister');
    Route::post('/auth/register', 'register');
    Route::get('/auth/login', 'showLogin')->name('login');
    Route::post('/auth/login', 'login');
});

Route::middleware('auth')->group(function () {
    Route::get('/polls/dashboard', PollDashboardController::class)->name('polls.dashboard');

    // Routes authentifiées pour les posts
    // La route 'create' doit être définie avant la route 'show' pour éviter les conflits de wildcard
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::patch('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    Route::singleton('my-profile', MyProfileController::class)->destroyable();
    Route::match(['put', 'patch'], '/likes/{post}', [LikeController::class, 'update']);
    Route::resource('tokens', TokenController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

// La route show doit être définie après toutes les routes fixes comme 'create'
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
