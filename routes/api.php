<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Excel-change
// Pinyin (中文名字获取拼音)
Route::post('import-excel-get-py', 'Excel\ExcelControllers@getPy');
// ImageName（照片名称命名）
Route::post('img-change-name', 'Excel\ExcelControllers@imgChangeName');
