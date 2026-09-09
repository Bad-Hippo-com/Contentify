<?php

ModuleRoute::context('Friends');

ModuleRoute::group(['middleware' => 'auth'], function()
{
    ModuleRoute::get('friends/{id}', 'FriendsController@show');
    ModuleRoute::post('friends/add/{id}', 'FriendsController@add');
    ModuleRoute::post('friends/confirm/{id}', 'FriendsController@confirm');
    ModuleRoute::delete('friends/{id}', 'FriendsController@destroy');
});
