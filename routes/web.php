<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

use App\Models\User;
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

Route::get('/', function () {
    return view('welcome');
})->name('index');

//short link for schematics/upload/{shortId}

//Route::get('/auth/azure', function () {
//    $minecraftUser = Socialite::driver('minecraft')->user();
//    $user = User::updateOrCreate([
//        'uuid' => $minecraftUser->uuid,
//    ]);
//
//    Auth::login($user, true);
//    return redirect()->route('index');
//});

//Route::get('/auth/minecraft', function () {
//    return Socialite::driver('minecraft')
//        ->with(['prompt' => 'select_account'])
//        ->redirect();
//})->name('login-minecraft');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('index');
    })->name('dashboard');
});

Route::get('/schematics/gif.worker.js', function () {
    $content = Cache::rememberForever('gif.worker.js', function () {
        $cdnUrl = 'https://cdn.jsdelivr.net/npm/gif.js/dist/gif.worker.js';
        return Http::get($cdnUrl)->body();
    });
    return response($content, 200, ['Content-Type' => 'application/javascript']);
});
