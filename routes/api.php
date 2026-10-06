<?php

use Illuminate\Support\Facades\Route;

// Routes API (vide par défaut — extensible)
Route::middleware('auth:sanctum')->get('/user', function (\Illuminate\Http\Request $request) {
    return $request->user();
});
