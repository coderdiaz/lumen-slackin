<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$app->group(['namespace' => 'App\Http\Controllers'], function () use ($app) {
    $app->get('/', 'IndexController@getIndex');
    $app->post('/invite', 'IndexController@postInvite');
});

$app->get('/badge.svg', function () {
    /** @var \Illuminate\Http\Request $request */
    $request = app('request');

	/** @var App\Services\SlackService $slack */
	$slack = app('App\Services\SlackService');

    $totals = $slack->getCachedUsersStatus();

    $renders = [new \PUGX\Poser\Render\SvgRender(), new \PUGX\Poser\Render\SvgFlatRender()];

    $poser = new \PUGX\Poser\Poser($renders);

    $image =  $poser->generate('slack', $totals['active']."/".$totals['total'], 'F1504F', $request->get('format', 'flat'));

    return response($image, 200, ['Content-Type' => 'image/svg+xml']);
});
