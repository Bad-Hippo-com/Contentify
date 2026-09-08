<?php

ModuleRoute::context('Forums');

ModuleRoute::group(['as' => ModuleRoute::getAdminNamePrefix()], function () {
    ModuleRoute::get('admin/forums/config', 'AdminConfigController@edit');
    ModuleRoute::put('admin/forums/config', 'AdminConfigController@update');

    ModuleRoute::resource('admin/forums', 'AdminForumsController');
    ModuleRoute::get(
        'admin/forums/{id}/restore',
        ['as' => 'forums.restore', 'uses' => 'AdminForumsController@restore']
    );
    ModuleRoute::post('admin/forums/search', 'AdminForumsController@search');

    ModuleRoute::get(
        'admin/forum-reports',
        ['as' => 'reports.index', 'uses' => 'AdminReportsController@index']
    );
    ModuleRoute::delete(
        'admin/forum-reports/{id}',
        ['as' => 'reports.destroy', 'uses' => 'AdminReportsController@destroy']
    );
});

ModuleRoute::get('forums', 'ForumsController@index');
ModuleRoute::get('forums/{id}/{slug?}', 'ForumsController@show')->where('id', '[0-9]+');

ModuleRoute::get('forums/threads/{id}/{slug?}', 'ThreadsController@show')->where('id', '[0-9]+');
ModuleRoute::get('forums/threads/new', 'ThreadsController@showNew');
ModuleRoute::group(['middleware' => 'auth'], function()
{
    ModuleRoute::get('forums/threads/create/{id}', 'ThreadsController@create');
    ModuleRoute::post('forums/threads/{id}', 'ThreadsController@store');
    ModuleRoute::get('forums/threads/edit/{id}', 'ThreadsController@edit');
    ModuleRoute::put('forums/threads/{id}', 'ThreadsController@update');
    ModuleRoute::match(['GET', 'POST'], 'forums/threads/sticky/{id}', 'ThreadsController@sticky')->middleware(\App\Http\Middleware\ConfirmMutation::class);
    ModuleRoute::match(['GET', 'POST'], 'forums/threads/closed/{id}', 'ThreadsController@closed')->middleware(\App\Http\Middleware\ConfirmMutation::class);
    ModuleRoute::get('forums/threads/move/{id}', 'ThreadsController@getMove');
    ModuleRoute::post('forums/threads/move/{id}', 'ThreadsController@postMove');
    ModuleRoute::match(['GET', 'POST'], 'forums/threads/delete/{id}', 'ThreadsController@delete')->middleware(\App\Http\Middleware\ConfirmMutation::class);
});
ModuleRoute::post('forums/search', 'ThreadsController@search');

ModuleRoute::get('forums/posts/perma/{id}/{slug?}', 'PostsController@show');
ModuleRoute::get('forums/posts/user/{id}/{slug?}', 'PostsController@showUserPosts');
ModuleRoute::group(['middleware' => 'auth'], function()
{
    ModuleRoute::get('forums/posts/{id}', 'PostsController@get');
    ModuleRoute::match(['GET', 'POST'], 'forums/posts/delete/{id}', 'PostsController@delete')->middleware(\App\Http\Middleware\ConfirmMutation::class);
    ModuleRoute::post('forums/posts/{id}', 'PostsController@store');
    ModuleRoute::get('forums/posts/edit/{id}', 'PostsController@edit');
    ModuleRoute::match(['GET', 'POST'], 'forums/posts/report/{id}', 'PostsController@report')->middleware(\App\Http\Middleware\ConfirmMutation::class);
    ModuleRoute::put('forums/posts/{id}', 'PostsController@update');
});
