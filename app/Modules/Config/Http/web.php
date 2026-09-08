<?php

ModuleRoute::context('Config');

ModuleRoute::match(['GET', 'POST'], 'admin/config/log/clear', 'AdminConfigController@clearLog')->middleware(['auth', \App\Http\Middleware\ConfirmMutation::class]);
ModuleRoute::get('admin/config', 'AdminConfigController@getIndex');
ModuleRoute::get('admin/config/info', 'AdminConfigController@getInfo');
ModuleRoute::get('admin/config/log', 'AdminConfigController@getLog');
ModuleRoute::get('admin/config/plain-log', 'AdminConfigController@getPlainLog');
ModuleRoute::match(['GET', 'POST'], 'admin/config/optimize', 'AdminConfigController@getOptimize')->middleware(['auth', \App\Http\Middleware\ConfirmMutation::class]);
ModuleRoute::get('admin/config/export', 'AdminConfigController@getExport');
ModuleRoute::match(['GET', 'POST'], 'admin/config/compile-less', 'AdminConfigController@getCompileLess')->middleware(['auth', \App\Http\Middleware\ConfirmMutation::class]);
ModuleRoute::match(['GET', 'POST'], 'admin/config/clear-cache', 'AdminConfigController@getClearCache')->middleware(['auth', \App\Http\Middleware\ConfirmMutation::class]);
ModuleRoute::put('admin/config', ['as' => 'admin.config.update', 'uses' => 'AdminConfigController@update']);
