<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

Route::get('/', function(){ return redirect()->route('clients.index'); });

Route::controller(ClientController::class)->group(function(){
    Route::get('/clients', 'index')->name('clients.index');
    Route::get('/clients/create', 'create')->name('clients.create');
    Route::post('/clients', 'store')->name('clients.store');
    Route::get('/clients/{client}', 'show')->name('clients.show');
    Route::get('/clients/{client}/edit', 'edit')->name('clients.edit');
    Route::put('/clients/{client}', 'update')->name('clients.update');
    Route::delete('/clients/{client}', 'destroy')->name('clients.destroy');

    // Compras
    Route::post('/clients/{client}/purchases', 'addPurchase')->name('clients.purchases.store');

    // Export
    Route::get('/export/csv', 'exportCsv')->name('clients.export.csv');
    Route::get('/export/pdf', 'exportPdf')->name('clients.export.pdf');
});