<?php

namespace App\Middleware;

use App\Models\Order;
use Src\Http\Requests\Request;
use Src\Http\Middleware\MiddlewareContract;
use Src\Http\Exceptions\HttpException;

class CheckIfOrderDone implements MiddlewareContract
{
    /**
     * @throws \Src\Http\Exceptions\HttpException;
     */
    public function handle(Request $request)
    {
        $orderModel = new Order();

        $orderId = $request->getUrlParam('order_id');

        if (isset($orderId)) {
            $isDone = $orderModel->checkIfDone($orderId);

            if ($isDone) {
                throw new HttpException('Order is already done');
            }
        }
    }
}
