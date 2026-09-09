<?php

ModuleRoute::context('Cups');

ModuleRoute::get('admin/cups/config', 'AdminConfigController@edit');
ModuleRoute::put('admin/cups/config', 'AdminConfigController@update');

ModuleRoute::get('cups', 'CupsController@index');
ModuleRoute::get('cups/{id}/{slug?}', 'CupsController@show')
    ->where('id', '[0-9]+');
ModuleRoute::get('cups/{slug}', 'CupsController@showBySlug');
ModuleRoute::post('cups/join/{cupId}/{participantId}', 'CupsController@join')->middleware('auth');
ModuleRoute::post('cups/check-in/{cupId}', 'CupsController@checkIn')->middleware('auth');
ModuleRoute::post('cups/check-out/{cupId}', 'CupsController@checkOut')->middleware('auth');
ModuleRoute::post('cups/swap/{cupId}', 'CupsController@swap');

ModuleRoute::get('admin/cups', ['as' => 'admin.cups.index', 'uses' => 'AdminCupsController@index']);
ModuleRoute::get('admin/cups/create', 'AdminCupsController@create');
ModuleRoute::post('admin/cups', 'AdminCupsController@store');
ModuleRoute::get('admin/cups/edit/{id}', ['as' => 'admin.cups.edit', 'uses' => 'AdminCupsController@edit']);
ModuleRoute::put('admin/cups/{id}', 'AdminCupsController@update');
ModuleRoute::post('admin/cups/search', 'AdminCupsController@search');
ModuleRoute::post('admin/cups/seed/{id}', 'AdminCupsController@seed');
ModuleRoute::delete('admin/cups/{cups}', ['as' => 'admin.cups.destroy', 'uses' => 'AdminCupsController@destroy']);

ModuleRoute::get('admin/participants', 'AdminParticipantsController@redirect');
ModuleRoute::get('admin/cups/participants/{cupId}', 'AdminParticipantsController@index');
ModuleRoute::post('admin/cups/participants/{cupId}', 'AdminParticipantsController@add');
ModuleRoute::post('admin/cups/participants/delete/{cupId}/{participantId}', 'AdminParticipantsController@delete');

ModuleRoute::get('cups/teams/overview/{userId?}', 'TeamsController@overview');
ModuleRoute::get('cups/teams/{teamId}/{slug?}', 'TeamsController@show')->where('teamId', '[0-9]+');
ModuleRoute::post('cups/teams/organizer/{teamId}/{userId}', 'TeamsController@organizer');
ModuleRoute::get('cups/teams/join/{teamId}', 'TeamsController@join')->middleware(['auth', \App\Http\Middleware\ConfirmMutation::class]);
ModuleRoute::post('cups/teams/join/{teamId}', 'TeamsController@join');
ModuleRoute::post('cups/teams/leave/{teamId}/{userId}', 'TeamsController@leave')->middleware('auth');
ModuleRoute::get('cups/teams/create', 'TeamsController@create');
ModuleRoute::post('cups/teams', 'TeamsController@store');
ModuleRoute::get('cups/teams/edit/{teamId}', 'TeamsController@edit');
ModuleRoute::put('cups/teams/{teamId}', 'TeamsController@update');
ModuleRoute::post('cups/teams/delete/{teamId}', 'TeamsController@delete')->middleware('auth');

ModuleRoute::get('cups/matches/{matchId}/{slug?}', 'MatchesController@show')->where('matchId', '[0-9]+');
ModuleRoute::post('cups/matches/confirm-left/{matchId}', 'MatchesController@confirmLeft');
ModuleRoute::post('cups/matches/confirm-right/{matchId}', 'MatchesController@confirmRight');
ModuleRoute::post('cups/matches/winner', 'MatchesController@winner');
