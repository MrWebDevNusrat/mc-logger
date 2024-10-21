<?php

use Illuminate\Support\Facades\Route;

Route::get('calculyator',function (){
    echo "Hello World!";
});

Route::get('add/{a}/{b}',[\MC\Logger\LoggerController::class,'add']);
