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

$app->get('/counts/users/{userId}/type/{type}/start/{startTime}/end/{endTime}','Index@counts');
$app->get('/infos/users/{userId}/type/{type}/start/{startTime}/end/{endTime}','Index@infos');
$app->post('/add','Index@add');
$app->post('/expend','Index@expend');