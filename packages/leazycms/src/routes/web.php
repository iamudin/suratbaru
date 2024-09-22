<?php

use Illuminate\Support\Facades\Route;
use Leazycms\Web\Http\Controllers\WebController;
use Leazycms\Web\Http\Controllers\SetupController;
use Leazycms\Web\Http\Controllers\ExtController;


$modules = collect(get_module())->where('name','!=','halaman')->where('active', true)->where('public', true);
    foreach($modules as $modul)
     {
            Route::controller(WebController::class)
                ->prefix($modul->name)
                ->middleware(['public'])
                ->group(function () use ($modul) {
                    if($modul->web->index){
                        Route::match(['get', 'post'],'/', 'index');
                    }
                    if ($modul->form->post_parent) {
                    Route::get('/' . $modul->form->post_parent[1] . '/{slug?}', 'post_parent');
                    }
                    if ($modul->web->api) {
                        Route::match(['get', 'post'],'api/{id?}', 'api');
                    }
                    if ($modul->web->detail) {
                        Route::match(['get', 'post'], '/{slug}', 'detail');
                    }
                    if ($modul->web->archive){
                        Route::match(['get', 'post'],'archive/{year?}/{month?}/{date?}', 'archive');
                    }
                    if ($modul->form->category) {
                        Route::match(['get', 'post'], 'category/{slug}','category');
                    }

                });
    }



Route::match(['get', 'post'],'/', [WebController::class, 'home'])->name('home')->middleware(['public']);


Route::match(['get', 'post'],'install', [SetupController::class, 'index'])->name('install');
Route::match(['get', 'post'],'install/initializing', [SetupController::class, 'initializing'])->name('initializing');



