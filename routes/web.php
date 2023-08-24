<?php

use App\Http\Controllers\BrokerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LineController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('users/list', [UserController::class, 'list'])->name('users.list');
    Route::resource('users', UserController::class);

    Route::get('lines/list', [LineController::class, 'list'])->name('lines.list');
    Route::resource('lines', LineController::class);

    Route::get('machines/list', [MachineController::class, 'list'])->name('machines.list');
    Route::resource('machines', MachineController::class);

    Route::get('products/list', [ProductController::class, 'list'])->name('products.list');
    Route::resource('products', ProductController::class);

    Route::get('brokers/list', [BrokerController::class, 'list'])->name('brokers.list');
    Route::get('brokers/{broker:id}/change-status', [BrokerController::class, 'changeStatus'])->name('brokers.change');
    Route::resource('brokers', BrokerController::class);

    Route::get('topics/list', [TopicController::class, 'list'])->name('topics.list');
    Route::resource('topics', TopicController::class);
});
