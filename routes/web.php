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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/v1', 'as' => 'api.v1', 'middleware' => ['base-response']], function () use ($router) {
    $router->group(['namespace' => 'Admin', 'as' => 'admin', 'prefix' => 'admin'], function() use ($router) {
        // Articles
        $router->get('articles', ['as' => 'articles.index', 'uses' => 'ArticleController@index']);
        $router->get('articles/{articleId}', ['as' => 'articles.show', 'uses' => 'ArticleController@show']);
        $router->post('articles', ['as' => 'articles.store', 'uses' => 'ArticleController@store']);
        $router->delete('articles/{articleId}', ['as' => 'articles.delete', 'uses' => 'ArticleController@destroy']);
    });
});
