<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// $router->post('/auth/login', 'AuthController@login');

$router->post('/auth/email-authenticate', 'AuthController@passwordlessLogin');

$router->get('/auth/email-authenticate/{token}', [
    'as' => 'auth.email-authenticate',
    'uses' => 'AuthController@authenticateEmail'
]);

$router->get('social/{provider}', 'AuthController@redirectToProvider');
$router->get('social/{provider}/callback', 'AuthController@handleProviderCallback');

//All the protected routes will go under this route group
$router->group(['middleware' => 'auth:api'], function($router)
{
    $router->get('stories', 'StoryController@list');
	$router->get('stories/{id}', 'StoryController@read');
	$router->get('stories/{id}/sounds', 'StoryController@listSounds');

	$router->get('sounds/{id}', 'SoundController@read');
	$router->get('sounds/{id}/trigger', 'SoundController@getTrigger');
});

