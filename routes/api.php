<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;





Route::post('/user/register', [AuthController::class, 'register']);
Route::post('/user/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function(){

    Route::post('/user/logout', [AuthController::class, 'logout']);
    
    Route::get('transactions/', [TransactionController::class, 'index']);
    Route::get('transactions/categories', [TransactionController::class, 'list_all_categories' ]);
    Route::get('transactions/categories/{category}', [TransactionController::class, 'list_by_category']);
    
    Route::get('transactions/{transaction}', [TransactionController::class, 'get_transaction']);
    Route::post('transactions/', [TransactionController::class, 'store']);
    Route::delete('transactions/{transaction}', [TransactionController::class, 'delete']);
    Route::patch('transactions/{transaction}', [TransactionController::class, 'update']);


    Route::get('/reports', [ReportController::class, 'index']);
    Route::get('/reports/date', [ReportController::class, 'get_report']);
    Route::get('/reports/current', [ReportController::class, 'get_current_report']);
    Route::get('/reports/{report}', [ReportController::class, 'get_report_by_id']);
});