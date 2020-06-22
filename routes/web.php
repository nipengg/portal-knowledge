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

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', 'MainController@index');
Route::get('/done', 'MainController@done');
Route::get('/question/category/{category}', 'MainController@tagCategory');
Route::get('/login', function(){
    if(Session::has('username')) return redirect()->action('MainController@index');
    return view('login');
});
Route::get('/lol', function(){
    return view('article/lol');
});
Route::post('/login', 'UserController@authLogin');
Route::post('/signup', 'UserController@validateSignup');
Route::get('/logout', 'UserController@logout');

Route::get('/profile/{id}', 'MainController@profile');
Route::post('/changepassword', 'UserController@changePassword');
Route::get('/ask', 'QuestionController@newQuestionForm');
Route::post('/ask', 'QuestionController@newQuestionSubmit');

Route::get('/qa', 'QaController@create');
Route::get('/qa/index', 'QaController@index');
Route::get('/qa/open', 'QaController@share');
Route::post('/qa/create', 'QaController@store');
Route::get('/qa/chat/{qa_id}', 'QaController@qa');
Route::post('/qa/answer', 'QaController@message');
Route::post('/qa/accept-answer', 'QaController@acceptAnswer');

Route::resource('article','ArticleController');
Route::get('/articles', 'ArticleController@articleform');
Route::get('/articles/all', 'ArticleController@admin');
Route::get('/articles/approve', 'ArticleController@approve');
Route::get('/articles/approve/view/{id}', 'ArticleController@approveview');
Route::get('/articles/{id}', 'ArticleController@personal');
Route::get('/articles/department/{id}', 'ArticleController@department');
Route::get('/articles/tag/{id}', 'ArticleController@articleformtag');
Route::get('/articles/view/{id}', 'ArticleController@articleview');
Route::get('/articles/edit/{id}', 'ArticleController@articleEditForm');
Route::post('/article/edit/id/{id}', 'ArticleController@articleEdit');
Route::get('/articles/delete/{id}', 'ArticleController@delete');
Route::get('/articles/approved/{id}', 'ArticleController@approved');
Route::get('/articles/unapproved/{id}', 'ArticleController@unapproved');
Route::get('/articless/search', 'ArticleController@search');

Route::get('/users', 'UserController@userform');
Route::get('/users/edit/{id}', 'UserController@userEditForm');
Route::post('/users/edit/id/{id}', 'UserController@userEdit');
Route::post('/users/approved/{id}', 'UserController@approve');
Route::get('/users/approved/admin/{id}', 'UserController@approveadmin');
Route::post('/users/reject/{id}', 'UserController@reject');
Route::post('/users/inactive/{id}', 'UserController@inactive');
Route::get('/users/delete/{id}', 'UserController@destroy');

Route::get('/report', 'ReportController@index');
Route::get('/report/chart', 'ReportController@indexChart');
Route::get('/chart/{filter}', 'ReportController@chart');
Route::get('/report/excel', 'ReportController@excel');
Route::get('/report/pdf', 'ReportController@pdf');

Route::get('topics', 'TopicController@index');
Route::get('/topics/create','TopicController@create');
Route::get('/topics/update/{id}','TopicController@update');
Route::post('/topics/update/id/{id}', 'TopicController@edit');
Route::post('/topics/store','TopicController@store');

Route::get('departments', 'DepartmentController@index');
Route::get('/departments/edit/{id}', 'DepartmentController@edit');
Route::post('/departments/edit/id/{id}', 'DepartmentController@update');
Route::post('/departments/active/{id}', 'DepartmentController@active');
Route::post('/departments/inactive/{id}', 'DepartmentController@inactive');
Route::get('/departments/create','DepartmentController@create');
Route::post('/departments/store','DepartmentController@store');

Route::get('/categories', 'CategoryController@index');
Route::get('/categories/edit/{id}', 'CategoryController@edit');
Route::get('/categories/delete/{id}', 'CategoryController@destroy');
Route::post('/categories/active/{id}', 'CategoryController@active');
Route::post('/categories/inactive/{id}', 'CategoryController@inactive');

Route::get('/sumber', 'SumberController@index');
Route::post('/sumber/active/{id}', 'SumberController@active');
Route::post('/sumber/inactive/{id}', 'SumberController@inactive');
Route::get('/sumber/create','SumberController@create');
Route::post('/sumber/store','SumberController@store');

Route::get('/question/search','MainController@search');
Route::get('/done/search','MainController@searchdone');
Route::get('/question/{question}', 'MainController@question');
Route::get('/question/tag/{id}', 'MainController@personal');
Route::get('/question/tag/department/{id}', 'MainController@department');
Route::get('/question/edit/{question}', 'QuestionController@questionEditForm');
Route::get('/question/rating/{id}', 'QuestionController@rating');
Route::post('/question/edit/id/{question}/{first_post}', 'QuestionController@questionEdit');
Route::post('/question/date/edit/{id}', 'QuestionController@dateEdit');
Route::post('/question/date/edit/meeting/{id}', 'QuestionController@dateMeetingEdit');
Route::get('/vote/{post_id}', 'QuestionController@votePost');
Route::get('/post/edit/{post_id}', 'QuestionController@newEditForm');
Route::post('/post/edit/id/{post_id}', 'QuestionController@postEdit');
Route::get('/unvote/{post_id}', 'QuestionController@unvotePost');
Route::post('/question/answer', 'QuestionController@answer');
Route::post('/question/accept-answer', 'QuestionController@acceptAnswer');
Route::post('/question/decline-answer', 'QuestionController@declineAnswer');
Route::post('/question/approve/stop', 'QuestionController@approveStop');
Route::post('/question/cancel/stop', 'QuestionController@cancelStop');
Route::post('/question/stop', 'QuestionController@stopRequest');
Route::post('/question/additional/information', 'QuestionController@info');


Route::get('/themes', 'ThemeController@index');
Route::post('/themes/active/{id}', 'ThemeController@active');
Route::post('/themes/inactive/{id}', 'ThemeController@inactive');
Route::get('/themes/create','ThemeController@create');
Route::post('/themes/store','ThemeController@store');

Route::get('/search', 'SearchController@index');

Route::get('/done/tag/{tag}', 'MainController@tagdone');
Route::get('/done/category/{category}', 'MainController@doneCategory');
Route::get('/done/question/theme/{theme}', 'MainController@themedone');

Route::get('/tag/{tag}', 'MainController@tag');
Route::get('/tags', 'MainController@tagIndex');
Route::get('/tag/article/{tag}', 'ArticleController@tag');
Route::get('/tag/article/sumber/{sumber}', 'ArticleController@sumber');
Route::get('/tag/article/theme/{theme}', 'ArticleController@theme');
Route::get('/tag/question/theme/{theme}', 'MainController@theme');
Route::get('/about', function(){
    return view('about');
});

Route::post('/add-tags', 'AdminController@addTags');
// for testing only!
//Route::get('/seed', 'MainController@seed');

Route::get('test', function () {
	echo "Test without leading '/'";
});

Route::get('test/foo', function () {
	echo "test/foo";
});

Route::get('hello/{name}',function($name){
   echo 'Hello, '.$name;
});

//Route::get('user/{name?}',function($name = 'Default'){
//   echo "User: ".$name;
//});

Route::get('role',[
   'middleware' => 'Role: editor, editor2', //use a specific middleware "Role" passing "editor" and "editor2" as a param
   'uses' => 'TestController@index', //let TestController, index function, to determine what should happen
]);

Route::get('terminate', [
	'middleware' => 'terminate',
	'uses' => 'ABCController@index',
]);

Route::get('usercontroller/path',[
   'middleware' => 'First',
   'uses' => 'UserController@showPath'
]);

Route::resource('my', 'MyController'); 
// let MyController handle all URL that starts with /my
// handled path:
// Verb			Path			Method		Route Name
// GET 			/my				index		my.index
// GET			/my/create		create		my.create
// POST			/my				store		my.store
// GET			/my/{my}		show		my.show
// GET			/my/{my}/edit	edit		my.edit
// PUT/PATCH	/my/{my}		update		my.update
// DELETE		/my/{my}		destroy		my.destroy

class MyClass{
   public $foo = 'bar';
   private $bar = 'foo';
}
Route::get('/myclass','ImplicitController@index');
Route::get('foo/bar', 'UriController@index' );

Route::get('/register', function(){
    return view('register');
});
Route::post('/user/register', 'UserRegistration@postRegister');


Route::get('/cookie/set', 'CookieController@setCookie');
Route::get('/cookie/get', 'CookieController@getCookie');

Route::get('basic_response', function(){
   return 'Hello world';
});

Route::get('header', function() {
   return response("Hello", 200)->header('Content-Type', 'text/html')
       ->withcookie('expiredin_1', 'Virat Gandhi', 1);
});

Route::get('json', function() {
   return response()->json(['name' => 'Jacky', 'lastName' => 'Efendi']);
});

Route::get('test', function() {
    $data = ["name" => "Jacky"];
    return view('test', $data);
});

Route::get('share1', function() {
    return view('share1');
});
Route::get('share2', function() {
    return view('share2');
});

Route::get('blade', function() {
    return view('page', ["name" => "Jacky Efendi"]);
});

Route::get('user/profile', ['as' => 'asd', function() { //this route is given the name 'asd' using the keyword 'as'
    return view('profile');
}]);
Route::get('redirect', function() {
    return redirect()->route('asd'); //redirect to the route named 'asd'
});

Route::get('rr','RedirectController@index'); //this route use RedirectController@index action
Route::get('redirect2', function(){
    return redirect()->action('RedirectController@index'); //redirect to the route that use RedirectController@index
});

Route::get('createform', 'StudInsertController@insertform');
Route::post('create', 'StudInsertController@insert');
Route::get('stud', 'StudViewController@index');

Route::get('view/{id}', 'StudUpdateController@detail');
Route::get('edit/{id}', 'StudUpdateController@updateform');
Route::post('edit/submit/{id}', 'StudUpdateController@update');
Route::get('delete/{id}', 'StudUpdateController@delete');
Route::get('compare/{id}/{id2}', 'StudUpdateController@compare');

Route::get('localization/{locale}', 'LocalizationController@index');

Route::get('session/view', 'SessionController@accessSessionData');
Route::get('session/put', 'SessionController@putSessionData');
Route::get('session/delete', 'SessionController@deleteSessionData');

Route::get('/validation','ValidationController@showform');
Route::post('/validation','ValidationController@validateform');

Route::get('404', function (){
    abort(404);
});

Route::get('facadeex', function (){
    return TestFacades::testingFacades();
});

//Route::get('test', 'ImplicitController');
// Implicit controller routes let the Controller handle all paths with the prefix /test
// The controller must define methods such as 
// - getProfile() to handle GET /test/profile path
// - getProfile($num) to handle GET /test/profile/1 path
// - postProfile() to handle POST /test/profile path
// nevermind, it's deprecated


