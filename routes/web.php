<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'ProposalsController@index')->name('home');

Route::resource('comments',            'CommentsController');
Route::resource('proposals',           'ProposalsController');
Route::resource('proposals.articles',  'ArticlesController');
Route::resource('votes4comments',      'Votes4commentsController');
Route::resource('votes4proposals',     'Votes4proposalsController');