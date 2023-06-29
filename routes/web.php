<?php

use App\Events\ChatMessage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExampleController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/admin", function () {
    return "Admin Page";
})->middleware("can:visitAdminPages");

Route::get('/', [UserController::class, 'homepage'])->name('homepage');

Route::post('/register', [UserController::class, 'register'])->middleware('guest');

Route::post('/login', [UserController::class, 'login'])->middleware('guest');

Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

Route::get("/manage-avatar", [UserController::class, "showAvatarForm"])->middleware('auth');
Route::post("/manage-avatar", [UserController::class, "updateAvatar"])->middleware('auth');

Route::get("/create-post", [PostController::class, "showCreatePost"])->middleware('auth');

Route::post("/create-post", [PostController::class, "storeNewPost"])->middleware('auth');

Route::get("/posts/{post}", [PostController::class, "showPost"]);

Route::get("/profile/{username:username}", [UserController::class, "showProfile"]);
Route::get("/profile/{username:username}/followers", [UserController::class, "showProfileFollowers"]);
Route::get("/profile/{username:username}/following", [UserController::class, "showProfileFollowing"]);

Route::delete("/posts/{post}", [PostController::class, "delete"])->middleware(['auth', 'can:delete,post']);

Route::get("posts/{post}/edit", [PostController::class, "showEditPost"])->middleware(['auth', 'can:update,post']);

Route::put("posts/{post}", [PostController::class, "update"])->middleware(['auth', 'can:update,post']);

// follow
Route::post("/follow/{user}", [FollowController::class, "createFollow"])->middleware('auth');
Route::delete("/follow/{user}", [FollowController::class, "removeFollow"])->middleware('auth');

// search
Route::get("/search/{term}", [PostController::class, "search"]);

// chat route
Route::post("/send-chat-message", function (Request $request) {
    $formFields = $request->validate([
        "textvalue" => "required|min:1|max:1000"
    ]);

    if (!trim(strip_tags($formFields["textvalue"]))) {
        return response()->noContent();
    };
    broadcast(new ChatMessage([
        "textvalue" => trim(strip_tags($formFields["textvalue"])),
        "username" => auth()->user()->username,
        "avatar" => auth()->user()->avatar
    ]));
});
