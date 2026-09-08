<?php

ModuleRoute::context('Friends');

ModuleRoute::group(['middleware' => 'auth'], function()
{
    ModuleRoute::get('friends/{id}', 'FriendsController@show');
    ModuleRoute::match(['GET', 'POST'], 'friends/add/{id}', 'FriendsController@add')->middleware(\App\Http\Middleware\ConfirmMutation::class);
    ModuleRoute::match(['GET', 'POST'], 'friends/confirm/{id}', 'FriendsController@confirm')->middleware(\App\Http\Middleware\ConfirmMutation::class);
    ModuleRoute::delete('friends/{id}', 'FriendsController@destroy');
});
