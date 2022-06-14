<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersRoleController;
use App\Http\Controllers\UserCorporateController;
use App\Http\Controllers\UserBankController;

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
|
*/

Route::get('/users/corporate/{userId}/edit', [UserCorporateController::class, 'getUserCorporateEditByUser']);
Route::get('/users/corporate/{userId}', [UserCorporateController::class, 'getUserCorporateByUser']);

Route::put("/users/bank/{userId}", [UserBankController::class, 'update']);

Route::group(["middleware" => "auth:sanctum"], function () {
    Route::group(["middleware" => ["role:administrator"]], function () {
        Route::get("/users", [UserController::class, 'index'])->middleware("permission:read-users");
        Route::get("/users/role/{roleId}", [UserController::class, 'getUsersByRole'])->middleware("permission:read-users");
        Route::get("/users/{userId}", [UserController::class, 'edit'])->middleware("permission:read-users");
        
        Route::post("/users", [UserController::class, 'store'])->middleware("permission:create-users");

        Route::delete("/users/{userId}", [UserController::class, 'destroy'])->middleware("permission:delete-users");
        
        Route::patch("/users/{userId}/role/{roleId}", [UsersRoleController::class, 'store'])->middleware(
            "permission:update-users"
        );
        Route::delete("/users/{userId}/role/{roleId}", [UsersRoleController::class, 'destroy'])->middleware(
            "permission:update-users"
        );

        Route::get("/users/bank/{userId}", [UserBankController::class, 'getUserBankByUser'])->middleware(
            "permission:read-users"
        );
    });
    Route::put("/users/{userId}", [UserController::class, 'update']);
});