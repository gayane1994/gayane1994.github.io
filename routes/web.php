<?php

Route::get('/', function () {
    return view('file');
});

Auth::routes();
