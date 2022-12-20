<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//hermes-store-website
Route::get('/', function () {
    return view('welcome');
})->middleware('guest');



Auth::routes();
Route::match(['get'], 'login', function(){ return redirect('/'); })->name('login');

Route::group(['middleware' => 'auth'], function(){
    
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    //===================================================ADMIN=======================================================================
    Route::group(['middleware' => 'isAdmin'], function(){
        // user management get request
        Route::get('/show-users', [App\Http\Controllers\Admin\DashboardController::class, 'showUsers'])->name('user');
        Route::get('/add-users', [App\Http\Controllers\Admin\DashboardController::class, 'addUsers'])->name('add.user');
        Route::get('/edit-user/{id}', [App\Http\Controllers\Admin\DashboardController::class, 'editUsers'])->name('edit.user');
        Route::get('/dleete-user/{id}', [App\Http\Controllers\Admin\DashboardController::class, 'deleteUser'])->name('delete.user');
        // user management post request
        Route::post('/add-users', [App\Http\Controllers\Admin\DashboardController::class, 'storeUser'])->name('store.user');
        Route::patch('/update-user/{id}', [App\Http\Controllers\Admin\DashboardController::class, 'updateUser'])->name('update.user');
        // items get
        Route::get('/edit-items/{id}', [App\Http\Controllers\Admin\ItemsController::class, 'editItems'])->name('edit.item');
        // items post
        Route::patch('/update-items/{id}', [App\Http\Controllers\Admin\ItemsController::class, 'update'])->name('update.item');
        // update sales discount
        Route::get('/update-sales-discount/{value}', [App\Http\Controllers\Admin\SalesController::class, 'updateDiscount']);
        // items post action
        Route::post('/import-items', [App\Http\Controllers\Admin\ItemsController::class, 'importItems'])->name('import.items');
        Route::post('/store-items', [App\Http\Controllers\Admin\ItemsController::class, 'store'])->name('store.items');

        //===================================================CATEGORY=======================================================================
        // category view
        Route::get('/categories', [App\Http\Controllers\Admin\CategoriesController::class, 'index'])->name('categories');
        // category post action
        Route::post('/create-category', [App\Http\Controllers\Admin\CategoriesController::class, 'store'])->name('store.category');
        Route::post('/update-category', [App\Http\Controllers\Admin\CategoriesController::class, 'updateCategory'])->name('update.category');
        // category get action
        Route::get('/get-category', [App\Http\Controllers\Admin\CategoriesController::class, 'getCategory']);
        Route::get('/edit-category/{id}', [App\Http\Controllers\Admin\CategoriesController::class, 'editCategory']);
        Route::get('/filter-category/{input}', [App\Http\Controllers\Admin\CategoriesController::class, 'filter']);
        Route::get('/delete-category/{input}', [App\Http\Controllers\Admin\CategoriesController::class, 'deleteCategory']);

        //===================================================SUB CATEGORY=======================================================================
        // Sub category view
        Route::get('/sub-categories', [App\Http\Controllers\Admin\SubCategoryController::class, 'index'])->name('sub.categories');
        // sub category post action
        Route::post('/create-sub-category', [App\Http\Controllers\Admin\SubCategoryController::class, 'store'])->name('store.sub.category');
        Route::post('/update-sub-category', [App\Http\Controllers\Admin\SubCategoryController::class, 'updateSubCategory'])->name('update.sub.category');
        // sub category get action
        Route::get('/get-sub-category', [App\Http\Controllers\Admin\SubCategoryController::class, 'getSubCategory']);
        Route::get('/edit-sub-category/{id}', [App\Http\Controllers\Admin\SubCategoryController::class, 'editSubCategory']);
        Route::get('/filter-sub-category/{input}', [App\Http\Controllers\Admin\SubCategoryController::class, 'filter']);
        Route::get('/delete-sub-category/{input}', [App\Http\Controllers\Admin\SubCategoryController::class, 'deleteSubCategory']);

        //===================================================REPORTS=======================================================================
        // report view
        Route::get('/reports/revenue-report', [App\Http\Controllers\Admin\ReportsController::class, 'revenueReport'])->name('revenue.report');
        Route::get('/reports/transfered-in', [App\Http\Controllers\Admin\ReportsController::class, 'transferedIn'])->name('transfered.in.report');
        Route::get('/reports/transfered-out', [App\Http\Controllers\Admin\ReportsController::class, 'transferedOut'])->name('transfered.out.report');
        Route::get('/reports/delivery-report', [App\Http\Controllers\Admin\ReportsController::class, 'deliveryReport'])->name('delivery.report');
        Route::get('/reports/sales-report', [App\Http\Controllers\Admin\ReportsController::class, 'salesReport'])->name('sales.report');
        Route::get('/reports/inventory-report', [App\Http\Controllers\Admin\ReportsController::class, 'inventoryReport'])->name('inventory.report');
        
        // export reports
        Route::get('/reports/export/revenue-report/{from}/{to}', [App\Http\Controllers\Admin\ReportsController::class, 'ExportRevenueReport']);
        Route::get('/reports/export/transfered-in/{from}/{to}', [App\Http\Controllers\Admin\ReportsController::class, 'ExportTransferedInReport']);
        Route::get('/reports/export/transfered-out/{from}/{to}', [App\Http\Controllers\Admin\ReportsController::class, 'ExportTransferedOutReport']);
        Route::get('/reports/export/delivery-report/{from}/{to}', [App\Http\Controllers\Admin\ReportsController::class, 'ExportDeliveryReport']);
        Route::get('/reports/export/sales-report/{from}/{to}', [App\Http\Controllers\Admin\ReportsController::class, 'ExportSalesReport']);
        Route::get('/reports/export/inventory-report/{from}/{to}', [App\Http\Controllers\Admin\ReportsController::class, 'ExportInventoryReport']);
    });

    //===================================================SALES=======================================================================
    // sales view
    Route::get('/sales', [App\Http\Controllers\Admin\SalesController::class, 'index'])->name('sales');
    // sales get method
    Route::get('/sale-collect-item/{input}', [App\Http\Controllers\Admin\SalesController::class, 'collectItem']);
    Route::get('/sales-collect-data/{id}', [App\Http\Controllers\Admin\SalesController::class, 'collectItemData']);
    Route::get('/get-sales-list', [App\Http\Controllers\Admin\SalesController::class, 'getSalesList']);
    Route::get('/delete-sales-list/{id}', [App\Http\Controllers\Admin\SalesController::class, 'deleteSalesList']);
    Route::get('/update-sales-delivery-status/{id}', [App\Http\Controllers\Admin\SalesController::class, 'updateDeliveryStatus']);
    // sales post method
    Route::post('/add-sales-list', [App\Http\Controllers\Admin\SalesController::class, 'addSalesList']);
    Route::post('/submit-sales-list', [App\Http\Controllers\Admin\SalesController::class, 'submitSalesList']);

    //===================================================ITEMS=======================================================================
    // items view
    Route::get('/items', [App\Http\Controllers\Admin\ItemsController::class, 'index'])->name('items');
    // items post action
    // Route::post('/import-items', [App\Http\Controllers\Admin\ItemsController::class, 'importItems'])->name('import.items');
    // Route::post('/store-items', [App\Http\Controllers\Admin\ItemsController::class, 'store'])->name('store.items');
    Route::post('/get-items/paginate', [App\Http\Controllers\Admin\ItemsController::class, 'getPaginate']);
    // items get action 
    Route::get('/export-items', [App\Http\Controllers\Admin\ItemsController::class, 'exportItems'])->name('export.items');
    Route::get('/get-items/{page}/{filter}', [App\Http\Controllers\Admin\ItemsController::class, 'getItems']);
    Route::get('/view-items/{id}', [App\Http\Controllers\Admin\ItemsController::class, 'viewItems']);
    Route::get('/delete-items/{id}', [App\Http\Controllers\Admin\ItemsController::class, 'destroy']);
    Route::get('/filter-items/{input}', [App\Http\Controllers\Admin\ItemsController::class, 'filter']);
    Route::get('/collect-sub-categories/{id}', [App\Http\Controllers\Admin\ItemsController::class, 'collectSubCategory']);

    // //===================================================CATEGORY=======================================================================
    // // category view
    // Route::get('/categories', [App\Http\Controllers\Admin\CategoriesController::class, 'index'])->name('categories');
    // // category post action
    // Route::post('/create-category', [App\Http\Controllers\Admin\CategoriesController::class, 'store'])->name('store.category');
    // Route::post('/update-category', [App\Http\Controllers\Admin\CategoriesController::class, 'updateCategory'])->name('update.category');
    // // category get action
    // Route::get('/get-category', [App\Http\Controllers\Admin\CategoriesController::class, 'getCategory']);
    // Route::get('/edit-category/{id}', [App\Http\Controllers\Admin\CategoriesController::class, 'editCategory']);
    // Route::get('/filter-category/{input}', [App\Http\Controllers\Admin\CategoriesController::class, 'filter']);
    // Route::get('/delete-category/{input}', [App\Http\Controllers\Admin\CategoriesController::class, 'deleteCategory']);

    // //===================================================SUB CATEGORY=======================================================================
    // // Sub category view
    // Route::get('/sub-categories', [App\Http\Controllers\Admin\SubCategoryController::class, 'index'])->name('sub.categories');
    // // sub category post action
    // Route::post('/create-sub-category', [App\Http\Controllers\Admin\SubCategoryController::class, 'store'])->name('store.sub.category');
    // Route::post('/update-sub-category', [App\Http\Controllers\Admin\SubCategoryController::class, 'updateSubCategory'])->name('update.sub.category');
    // // sub category get action
    // Route::get('/get-sub-category', [App\Http\Controllers\Admin\SubCategoryController::class, 'getSubCategory']);
    // Route::get('/edit-sub-category/{id}', [App\Http\Controllers\Admin\SubCategoryController::class, 'editSubCategory']);
    // Route::get('/filter-sub-category/{input}', [App\Http\Controllers\Admin\SubCategoryController::class, 'filter']);
    // Route::get('/delete-sub-category/{input}', [App\Http\Controllers\Admin\SubCategoryController::class, 'deleteSubCategory']);
    

    //===================================================TRANSFER IN=======================================================================
    // transfer in view
    Route::get('/transfer-in', [App\Http\Controllers\Admin\TransferInController::class, 'index'])->name('transfer.in');
    // transfer in post method
    Route::post('/add-list', [App\Http\Controllers\Admin\TransferInController::class, 'addList']);
    Route::post('/submit-list', [App\Http\Controllers\Admin\TransferInController::class, 'submitList']);
    // transfer in get method
    Route::get('/collect-item-names/{id}', [App\Http\Controllers\Admin\TransferInController::class, 'collectItemName']);
    Route::get('/collect-data/{id}', [App\Http\Controllers\Admin\TransferInController::class, 'collectData']);
    Route::get('/get-list', [App\Http\Controllers\Admin\TransferInController::class, 'getList']);
    Route::get('/delete-list/{id}', [App\Http\Controllers\Admin\TransferInController::class, 'deleteList']);

    //===================================================TRANSFER OUT=======================================================================
    // transfer out view
    Route::get('/deduct-items', [App\Http\Controllers\Admin\DeductController::class, 'index'])->name('deduct.items');
    // transfer in post method
    Route::post('/d-add-list', [App\Http\Controllers\Admin\DeductController::class, 'addList']);
    Route::post('/d-submit-list', [App\Http\Controllers\Admin\DeductController::class, 'submitList']);
    // transfer in get method
    Route::get('/d-collect-item-names/{id}', [App\Http\Controllers\Admin\DeductController::class, 'collectItemName']);
    Route::get('/d-collect-data/{id}', [App\Http\Controllers\Admin\DeductController::class, 'collectData']);
    Route::get('/d-get-list', [App\Http\Controllers\Admin\DeductController::class, 'getList']);
    Route::get('/d-delete-list/{id}', [App\Http\Controllers\Admin\DeductController::class, 'deleteList']);

    // //===================================================REPORTS=======================================================================
    // // report view
    // Route::get('/reports/revenue-report', [App\Http\Controllers\Admin\ReportsController::class, 'revenueReport'])->name('revenue.report');
    // Route::get('/reports/transfered-in', [App\Http\Controllers\Admin\ReportsController::class, 'transferedIn'])->name('transfered.in.report');
    // Route::get('/reports/transfered-out', [App\Http\Controllers\Admin\ReportsController::class, 'transferedOut'])->name('transfered.out.report');
    // Route::get('/reports/delivery-report', [App\Http\Controllers\Admin\ReportsController::class, 'deliveryReport'])->name('delivery.report');
    // Route::get('/reports/sales-report', [App\Http\Controllers\Admin\ReportsController::class, 'salesReport'])->name('sales.report');
    // Route::get('/reports/inventory-report', [App\Http\Controllers\Admin\ReportsController::class, 'inventoryReport'])->name('inventory.report');
    
    // // export reports
    // Route::get('/reports/export/revenue-report/{from}/{to}', [App\Http\Controllers\Admin\ReportsController::class, 'ExportRevenueReport']);
    // Route::get('/reports/export/transfered-in/{from}/{to}', [App\Http\Controllers\Admin\ReportsController::class, 'ExportTransferedInReport']);
    // Route::get('/reports/export/transfered-out/{from}/{to}', [App\Http\Controllers\Admin\ReportsController::class, 'ExportTransferedOutReport']);
    // Route::get('/reports/export/delivery-report/{from}/{to}', [App\Http\Controllers\Admin\ReportsController::class, 'ExportDeliveryReport']);
    // Route::get('/reports/export/sales-report/{from}/{to}', [App\Http\Controllers\Admin\ReportsController::class, 'ExportSalesReport']);
    // Route::get('/reports/export/inventory-report/{from}/{to}', [App\Http\Controllers\Admin\ReportsController::class, 'ExportInventoryReport']);

    //===================================================ITEM QUANTITY CHECK=======================================================================
    // item quantity check view
    Route::get('/item-quantity-check', [App\Http\Controllers\Admin\ItemQuantityCheckController::class, 'index'])->name('item.quantity.check');
    // quantity check post method
    Route::post('/filter-items-available', [App\Http\Controllers\Admin\ItemQuantityCheckController::class, 'filterItemsAvailable']);
    // quantity check get method
    Route::get('/check-sub-categories/{id}', [App\Http\Controllers\Admin\ItemQuantityCheckController::class, 'checkSubCategory']);

    //===================================================DELIVERY=======================================================================
    // post method
    Route::post('/collect-form-number', [App\Http\Controllers\Admin\DeliveryController::class, 'collectFormNumber']);

    // get method
    Route::get('/item-logs', [App\Http\Controllers\Admin\DeliveryController::class, 'index'])->name('item.logs');
    Route::get('/get-delivery-count', [App\Http\Controllers\Admin\DeliveryController::class, 'getDeliveryCount']);
    // delivery status actions
    Route::get('/delivered-update/{id}', [App\Http\Controllers\Admin\DeliveryController::class, 'updateToDelivered'])->name('action.delivered');
    Route::get('/for-delivery-update/{id}', [App\Http\Controllers\Admin\DeliveryController::class, 'updateToForDeliver'])->name('action.for.deliver');
    Route::get('/cancel-delivery-update/{id}', [App\Http\Controllers\Admin\DeliveryController::class, 'updateToCancelled'])->name('action.cancelled');
    
    
});
