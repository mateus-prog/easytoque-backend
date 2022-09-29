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

//retirar essa rota
Route::get('/users/corporate/hash', [UserCorporateController::class, 'getUserHash']);
Route::get('/users/corporate/{userId}/edit', [UserCorporateController::class, 'getUserCorporateEditByUser']);
Route::get('/users/corporate/{userId}', [UserCorporateController::class, 'getUserCorporateByUser']);

Route::put("/users/bank/{userId}", [UserBankController::class, 'update']);

Route::group(["middleware" => "auth:sanctum"], function () {
    Route::group(["middleware" => ["role:administrator|collaborator|financial"]], function () {
        Route::get("/users", [UserController::class, 'index'])->middleware("permission:read-users");
        Route::get("/users/role/{roleId}", [UserController::class, 'getUsersByRole'])->middleware("permission:read-users");
        Route::get("/users/delete", [UserController::class, 'deletePendente']);
        Route::get("/users/{userId}", [UserController::class, 'edit'])->middleware("permission:read-users");
        
        Route::post("/users", [UserController::class, 'store'])->middleware("permission:create-users");
        Route::post("/users/corporate", [UserCorporateController::class, 'store'])->middleware("permission:create-users");
        Route::post("/users/bank", [UserBankController::class, 'store'])->middleware("permission:create-users");
        
        Route::delete("/users/{userId}", [UserController::class, 'destroy'])->middleware("permission:delete-users");
        
        Route::patch("/users/{userId}/role/{roleId}", [UsersRoleController::class, 'store'])->middleware(
            "permission:update-users"
        );
        Route::delete("/users/{userId}/role/{roleId}", [UsersRoleController::class, 'destroy'])->middleware(
            "permission:update-users"
        );

        Route::put("/users/active/{userId}", [UserController::class, 'activeUser'])->middleware("permission:delete-users");
        Route::put("/users/blocked/{userId}", [UserController::class, 'blockedUser'])->middleware("permission:delete-users");
    });
    Route::put("/users/{userId}", [UserController::class, 'update']);
    
    Route::get("/users/bank/{userId}", [UserBankController::class, 'getUserBankByUser'])->middleware(
        "permission:read-users"
    );
});