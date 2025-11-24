<?php

Route::prefix('operate/member')
    ->middleware(['auth'])
    ->group(function () {
    Route::name('listMember')
        ->get('/', 'Admin\MemberAdminController@index')
        ->middleware(['permission:super|member-index']);
    Route::name('createMember')
        ->get('create', 'Admin\MemberAdminController@create')
        ->middleware(['permission:super|member-create']);
    Route::name('storeMember')
        ->post('create', 'Admin\MemberAdminController@store')
        ->middleware(['permission:super|member-create']);
    Route::name('editMember')
        ->get('{member}/edit', 'Admin\MemberAdminController@edit')
        ->middleware(['permission:super|member-edit']);
    Route::name('editMember')
        ->post('{member}/edit', 'Admin\MemberAdminController@update')
        ->middleware(['permission:super|member-edit']);
    Route::name('deleteMember')
        ->get('{member}/delete', 'Admin\MemberAdminController@destroy')
        ->middleware(['permission:super|member-destroy']);
});
