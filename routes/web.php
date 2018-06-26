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

Route::get('/', 'ProposalsController@index');

Route::resource('comments',            'CommentsController');
Route::resource('proposals',           'ProposalsController');
Route::resource('proposals.articles',  'ArticlesController');
Route::resource('proposal-supporters', 'ProposalSupportersController');