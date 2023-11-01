<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\TodoItemController::class, 'index'])->name('showTodosPage');
Route::get('/todos', [App\Http\Controllers\TodoItemController::class, 'todos'])->name('loadTodos');
Route::post('/todos', [App\Http\Controllers\TodoItemController::class, 'store'])->name('storeTodos');
Route::put('/todos/{todoItem}', [App\Http\Controllers\TodoItemController::class, 'update'])->name('updateTodo');
Route::delete('/todos/{todoItem}', [App\Http\Controllers\TodoItemController::class, 'destroy'])->name('deleteTodo');
