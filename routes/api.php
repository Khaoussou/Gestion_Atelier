<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\FournisseurController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::delete("/delete/{id?}", [CategorieController::class, "delete"]);
Route::get("/search/{libelle}", [CategorieController::class, "search"]);
Route::get("/recherche/{fournisseur}", [FournisseurController::class, "search"]);
Route::get("/list/{paginator?}", [CategorieController::class, "list"]);
Route::apiResource("/categories", CategorieController::class);
Route::post("/articles/{article}", [ArticleController::class, "update"]);
Route::apiResource("/articles", ArticleController::class);