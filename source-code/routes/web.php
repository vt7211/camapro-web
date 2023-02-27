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

// Route::get('/', function () {
//     return view('welcome');
// });

route::get('/',['as'=>'home', 'uses'=>'GuestController@home']);
Route::group(['prefix'=>'admin', 'middleware' => ['check','xss']], function(){
    route::get('/',['as'=>'admin.dashboard', 'uses'=>'adminController@dashboard']);
    route::post('checkalias',['as'=>'checkalias', 'uses'=>'AliasController@checkalias']);
    route::get('dashboard',['as'=>'admin.dashboard', 'uses'=>'adminController@dashboard']);
    route::group(["prefix"=>"user"], function(){
        route::get('list',['as'=>'admin.user.getList', 'uses'=>'userController@getList'])->middleware('can:user.list');
        route::get('add',['as'=>'admin.user.getadd', 'uses'=>'userController@getaddUser'])->middleware('can:user.create');
        route::post('add',['as'=>'admin.user.postadd', 'uses'=>'userController@postaddUser'])->middleware('can:user.create');
        route::get('delete/{id}',['as'=>'admin.user.getDelete', 'uses'=>'userController@getDelete'])->middleware('can:user.delete');
        route::get('edit/{id}',['as'=>'admin.user.getEdit', 'uses'=>'userController@getEdit'])->middleware('can:user.update,id');
        route::post('edit/{id}',['as'=>'admin.user.postEdit', 'uses'=>'userController@postEdit'])->middleware('can:user.update,id');
    });
    route::group(["prefix"=>"news"], function(){
        route::get('list/{type}/{status}',['as'=>'admin.news.getList', 'uses'=>'newsController@getList'])->middleware('can:news.list');
        route::get('add/{type}',['as'=>'admin.news.getadd', 'uses'=>'newsController@getaddNews'])->middleware('can:news.create');
        route::post('add/{type}',['as'=>'admin.news.postadd', 'uses'=>'newsController@postaddNews'])->middleware('can:news.create');
        route::get('delete/{id}',['as'=>'admin.news.getDelete', 'uses'=>'newsController@getDelete'])->middleware('can:news.delete');
        route::get('edit/{type}/{id}',['as'=>'admin.news.getEdit', 'uses'=>'newsController@getEdit'])->middleware('can:news.update,id');
        route::post('edit/{type}/{id}',['as'=>'admin.news.postEdit', 'uses'=>'newsController@postEdit'])->middleware('can:news.update,id');
        route::get('status/{id}/{status}',['as'=>'admin.news.getStatus', 'uses'=>'newsController@getStatus'])->middleware('can:news.delete');
    });
    route::group(['prefix'=>'comments'], function(){
        route::get('list/{type}/{status}',['as'=>'admin.comment.getList', 'uses'=>'commentController@getList'])->middleware('can:comment.list');
        route::get('accept/{type}/{id}/{status}',['as'=>'admin.comment.getStatus', 'uses'=>'commentController@getStatus'])->middleware('can:comment.update');
        route::get('delete/{id}',['as'=>'admin.comment.getDelete', 'uses'=>'commentController@getDelete'])->middleware('can:comment.delete');
    });
    route::group(["prefix"=>"page"], function(){
        route::get('list/{status}',['as'=>'admin.page.getList', 'uses'=>'newsController@getListPage'])->middleware('can:news.list');
        route::get('add',['as'=>'admin.page.getadd', 'uses'=>'newsController@getaddPage'])->middleware('can:news.create');
        route::post('add',['as'=>'admin.page.postadd', 'uses'=>'newsController@postaddPage'])->middleware('can:news.create');
        route::get('edit/{id}',['as'=>'admin.page.getEdit', 'uses'=>'newsController@getEditPage'])->middleware('can:news.update,id');
        route::post('edit/{id}',['as'=>'admin.page.postEdit', 'uses'=>'newsController@postEditPage'])->middleware('can:news.update,id');
    });
    route::group(["prefix"=>"menu"], function(){
        route::get('list/{id}',['as'=>'admin.menu.getList', 'uses'=>'menuController@getList'])->middleware('can:menu.list');
        route::post('add',['as'=>'admin.menu.postadd', 'uses'=>'menuController@postadd'])->middleware('can:menu.create');
        route::post('edit/{id}',['as'=>'admin.menu.postedit', 'uses'=>'menuController@postedit'])->middleware('can:menu.update');
        route::get('deletedetail/{idcha}/{id}',['as'=>'admin.menu.getDeleteCon', 'uses'=>'menuController@getDeleteCon'])->middleware('can:menu.delete');
        route::get('delete/{id}',['as'=>'admin.menu.getDelete', 'uses'=>'menuController@getDelete'])->middleware('can:menu.delete');
    });
    route::group(["prefix"=>"visit"], function(){
        route::get('list',['as'=>'admin.visit.getList', 'uses'=>'VisitController@getList'])->middleware('can:visit.list');
        route::post('edit/{id}',['as'=>'admin.visit.postedit', 'uses'=>'VisitController@postedit'])->middleware('can:visit.update');
        route::get('delete/{id}',['as'=>'admin.visit.getDelete', 'uses'=>'VisitController@getDelete'])->middleware('can:visit.delete');
    });
    
    route::group(['prefix'=>'cate'], function(){
        route::get('add/{type}',['as'=>'admin.cate.getAdd', 'uses'=>'CateController@getAdd'])->middleware('can:cate.create');
        route::post('add/{type}',['as'=>'admin.cate.postAdd', 'uses'=>'CateController@postAdd'])->middleware('can:cate.create');
        route::get('list/{type}',['as'=>'admin.cate.getList', 'uses'=>'CateController@getList'])->middleware('can:cate.list');
        route::get('delete/{type}/{id}',['as'=>'admin.cate.getDelete', 'uses'=>'CateController@getDelete'])->middleware('can:cate.delete');
        route::get('edit/{type}/{id}',['as'=>'admin.cate.getEdit', 'uses'=>'CateController@getEdit'])->middleware('can:cate.update');
        route::post('edit/{type}/{id}',['as'=>'admin.cate.postEdit', 'uses'=>'CateController@postEdit'])->middleware('can:cate.update');
    });
    route::group(['prefix'=>'setting'], function(){
        route::get('list',['as'=>'admin.setting.getList', 'uses'=>'settingController@getList'])->middleware('can:setting.list');
        route::post('savegeneral',['as'=>'admin.setting.postSavegeneral', 'uses'=>'settingController@postSavegeneral'])->middleware('can:setting.update');
        route::post('saveslider',['as'=>'admin.setting.postSaveslider', 'uses'=>'settingController@postSaveslider'])->middleware('can:setting.update');
    });
});

route::get('login',['as'=>'login', 'uses'=>'Auth\LoginController@getLogin']);
route::get('login',['as'=>'getlogin', 'uses'=>'Auth\LoginController@getLogin']);
route::post('login',['as'=>'postlogin', 'uses'=>'Auth\LoginController@postLogin']);
route::get('logout',['as'=>'getthoat', 'uses'=>'Auth\LoginController@logout']);