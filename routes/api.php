<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Helpers\JWT;

use App\Http\Controllers\Schematic\SchematicCreate;
use App\Http\Controllers\Schematic\SchematicUpload;
use App\Http\Controllers\PasswordSet;
use App\Models\Schematic;

use App\Http\Controllers\Auth\MojangAuthIssuer;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    if (config('app.env') === 'local') {
        Route::get('/get-test-token', function () {
            return response()->json(['token' => JWT::getTestToken()], 200);
        });
    }
    Route::middleware('ensure_valid_jwt')->group(function () {
        Route::get('/test', function () {
            return response()->json(['message' => 'Hello World!'], 200);
        });

        Route::post('/schematic', SchematicCreate::class);
        Route::post('/schematic-upload', SchematicUpload::class);
    });

    Route::middleware('ensure_valid_jwt:canManagePassword')->group(function () {
        Route::post('/password-set', PasswordSet::class);
    });
    Route::post('/authorize-mojang', MojangAuthIssuer::class);
    Route::get('/download-schematic/{id}', function (Request $request, $id) {
        $schematic = Schematic::find($id);

        if (!$schematic) {
            return response()->json(['error' => 'Schematic not found'], 404);
        }

        $file = $schematic->file;

        return response($file)
            ->header('Content-Type', 'application/octet-stream')
            ->header('Content-Disposition', 'attachment; filename="' . $schematic->name . '.schematic"');
    });
});
