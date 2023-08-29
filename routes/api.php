<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Psy\Readline\Hoa\Console;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test/{name?}',function($name='guys'){
	echo "hello,{$name}";
});

Route::get('/userlist/{uid?}',function($uid=''){
    $users = DB::select("select uid,ifnull(cname,'')as cname from UserInfo where uid = ? or ''= ?",[$uid,$uid] );
    $str = json_encode($users,JSON_UNESCAPED_UNICODE);
    return response($str)
                ->header('content-type','application/json')
                ->header('charset','utf-8');
});

Route::post('/register', function(Request $request) {
    try {
        $uid = $request->uid;
        $pwd = $request->pwd;
        DB::insert("insert into UserInfo(uid,pwd) values(?,?)", [$uid, $pwd]);
        echo '{"result": "註冊成功"}';
    } catch (Exception $e) {
        $errorMessage = "錯誤失敗" . $e->getMessage();
        return response()->json(["error" => $errorMessage], 500);
       
    }
});

Route::post('/login',function(Request $request){
    $uid = $request->uid;
    $pwd = $request->pwd;
    $islogin = DB::scalar("select count(*) from UserInfo where uid = ? and pwd = ?",[$uid,$pwd]);
    echo "(\"result\" : {$islogin})";
});

Route::get('/accountExist/{uid}',function($uid){
    $isExist = DB::scalar("select count(*) from UserInfo where uid = ?", [$uid]);
    $response = ["result" => $isExist];  // Build JSON response as an associative array
    return response()->json($response);  // Return JSON response;
});
    
