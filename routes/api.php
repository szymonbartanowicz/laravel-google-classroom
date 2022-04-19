<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api')->group(function () {

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('home/user', 'UserController@getUser');

        Route::get('home/statuses', 'UserController@getStatuses');

        Route::resource('lessons', 'LessonController');

        Route::resource('tasks', 'TaskController');
        Route::post('tasks', 'TaskController@store')->middleware('isTeacher');

        Route::post('lessons/join', 'LessonController@join');

        Route::get('lessons/{id}/people', 'LessonController@getPeople');

        Route::get('lessons/{id}/tasks', 'LessonController@getTasks');

        Route::put('updateStatus', 'UserController@updateStatus');

        Route::get('isTeacher', 'LessonController@isTeacher');

        Route::get('unreadNotifications', 'UserController@getUnreadNotificationsNumber');

        Route::get('notifications', 'UserController@getNotifications');

        Route::post('markNotificationsAsRead', 'UserController@markNotificationsAsRead');

        Route::resource('posts', 'PostController');

        Route::get('lessons/{id}/posts', 'LessonController@getPosts');
    });
});