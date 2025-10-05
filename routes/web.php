<?php

use App\Http\Controllers\PostDashboardController;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('home', [
        'title' => 'Home Page',
        'heading' => 'Home'
    ]);
});

Route::get('/posts', function () {
    // $posts = Post::with(['author', 'category'])->latest()->get();
    $posts = Post::latest()->filter(request(['search', 'category', 'author']))->paginate(6)->withQueryString();

    return view('posts', [
        'title' => 'Blog Page',
        'heading' => 'Blog',
        'posts' => $posts
    ]);
});

Route::get('/authors/{user:username}', function (User $user) {
    $posts = Post::latest()
        ->where('author_id', $user->id)
        ->with(['author', 'category'])
        ->paginate(6)
        ->withQueryString();

    return view('posts', [
        'title' => 'Blog Page',
        'heading' => $posts->total() . ' Article by. ' . $user->name,
        'posts' => $posts,
    ]);
});

Route::get('/categories/{category:slug}', function (Category $category) {
    $posts = Post::latest()
        ->where('category_id', $category->id)
        ->with(['author', 'category'])
        ->paginate(6)
        ->withQueryString();

    return view('posts', [
        'title' => 'Blog Page',
        'heading' => 'Category: ' . $category->name,
        'posts' => $posts,
    ]);
});

Route::get('/posts/{post:slug}', function (Post $post) {
    return view('post', [
        'title' => 'Blog Post',
        'heading' => 'Blog Post',
        'post' => $post
    ]);
});

Route::get('/about', function () {
    return view('about', [
        'title' => 'About Page',
        'heading' => 'About'
    ]);
});

Route::get('/contact', function () {
    return view('contact', [
        'title' => 'Contact Page',
        'heading' => 'Contact'
    ]);
});


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


// Route::get('/dashboard', [PostDashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Route::post('/dashboard', [PostDashboardController::class, 'store'])->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/dashboard/create', [PostDashboardController::class, 'create'])->middleware(['auth', 'verified']);

// Route::delete('/dashboard/{post:slug}', [PostDashboardController::class, 'destroy'])->middleware(['auth', 'verified']);

// Route::get('/dashboard/{post:slug}', [PostDashboardController::class, 'show'])->middleware(['auth', 'verified']);


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [PostDashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard', [PostDashboardController::class, 'store']);
    Route::get('/dashboard/create', [PostDashboardController::class, 'create']);
    Route::delete('/dashboard/{post:slug}', [PostDashboardController::class, 'destroy']);
    Route::get('/dashboard/{post:slug}/edit', [PostDashboardController::class, 'edit']);
    Route::patch('/dashboard/{post:slug}', [PostDashboardController::class, 'update']);
    Route::get('/dashboard/{post:slug}', [PostDashboardController::class, 'show']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/upload', [ProfileController::class, 'upload']);
});

require __DIR__ . '/auth.php';
