<?php

Route::prefix('api/v1/blog')->namespace('\EPink\Blog\Http\Controllers')->middleware('api')->group(function () {
    Route::post('login', 'AuthController@login');
    Route::get('public', 'PublicController@getData');
    
    Route::middleware('auth:api')->group(function () {
        Route::post('post/publish', 'PublishController@publishPost');
        Route::delete('post/{post}/delete', 'PublishController@deletePost');
        Route::post('post/{post}/edit', 'PublishController@editPost');
        Route::post('post/categories', 'PublishController@editPostCategories');
        Route::post('post/tags', 'PublishController@editPostTags');

        Route::apiResource('authors', 'AuthorController');
        Route::apiResource('categories', 'CategoryController');
        Route::apiResource('posts', 'PostController');
        Route::apiResource('tags', 'TagController');
    });
});