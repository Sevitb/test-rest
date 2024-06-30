<?php

use Src\Routing\Route;
use App\Middleware\AuthenticateByKey;
use App\Middleware\ValidateCreateOrderRequest;
use App\Middleware\ValidateAddItemsRequest;
use App\Middleware\ValidateGetOrdresRequest;
use App\Middleware\CheckIfOrderDone;
use App\Controllers\OrderController;

return [

    Route::get('/orders', [OrderController::class, 'getList'], [AuthenticateByKey::class, ValidateGetOrdresRequest::class]),
    Route::get('/orders/{order_id}', [OrderController::class, 'get']),
    Route::post('/orders', [OrderController::class, 'create'], [ValidateCreateOrderRequest::class]),
    /** 
     * В описании к маршруту написно: "Добавление товаров в созданный заказ, заказ не должен быть в статусе done = false"
     * А ниже, в описании полей: "После перевода в статус done = true с заказом нельзя производить никаких операций"
     * Опирался на последнее условие, поэтому проверяю CheckIfOrderDone и отсекаю, если уже true
     */
    Route::post('/orders/{order_id}/items', [OrderController::class, 'addProducts'], [CheckIfOrderDone::class, ValidateAddItemsRequest::class]),
    Route::post('/orders/{order_id}/done', [OrderController::class, 'setAsDone'], [AuthenticateByKey::class, CheckIfOrderDone::class]),

];