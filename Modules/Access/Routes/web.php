<?php

Route::group(array('middleware' => 'front','prefix' => (request()->segment(1) == 'ar' || request()->segment(1) == 'en') ? request()->segment(1) : ''), function () {
    foreach (File::allFiles(__DIR__ . '/Front') as $route) {
        require_once $route->getPathname();
    }
});


foreach (File::allFiles(__DIR__ . '/Admin') as $route) {
    require_once $route->getPathname();
}
//
//foreach (File::allFiles(__DIR__ . '/Front') as $route) {
//    require_once $route->getPathname();
//}
Route::prefix('operate/role')->group(function () {
    Route::name('listRoles')->get('', 'RoleController@index')->middleware(['permission:super']);
    Route::name('createRole')->get('create', 'RoleController@create')->middleware(['permission:super']);
    Route::name('storeRole')->post('', 'RoleController@store')->middleware(['permission:super']);
    Route::name('editRole')->get('{role}/edit', 'RoleController@edit')->middleware(['permission:super']);
    Route::name('editRole')->post('{role}/edit', 'RoleController@update')->middleware(['permission:super']);
    Route::name('deleteRole')->get('{role}/delete', 'RoleController@destroy')->middleware(['permission:super']);
});
