<?php

use Illuminate\Support\Facades\Route;
use Picobaz\GridView\Controllers\GridViewController;

/**
 * GridView Package Routes
 *
 * @author PicoBaz <picobaz3@gmail.com>
 */

Route::group(['prefix' => 'gridview', 'middleware' => ['web']], function () {

    // Inline editing route
    Route::post('/inline-update', [GridViewController::class, 'inlineUpdate'])
        ->name('gridview.inline.update');

    // Bulk actions route
    Route::post('/bulk-action', [GridViewController::class, 'bulkAction'])
        ->name('gridview.bulk.action');
});