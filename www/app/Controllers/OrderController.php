<?php

namespace App\Controllers;

use App\Models\Order;
use Src\Http\Controllers\AbstractController;
use Src\Http\Responses\Response;
use Src\Http\Responses\JsonResponse;

class OrderController extends AbstractController
{
    /**
     * @var \App\Models\Order
     */
    private Order $orderModel;

    public function __construct()
    {
        $this->orderModel = new Order();
    }

    /**
     * @return \Src\Http\Responses\Response
     */
    public function getList(): Response
    {
        $orders = $this->orderModel->getList($this->request->getQueryParam('done'));

        return new JsonResponse($orders);
    }

    /**
     * @param string $order_id
     * @return \Src\Http\Responses\Response
     */
    public function get(string $order_id): Response
    {
        $order = $this->orderModel->getById($order_id);

        return new JsonResponse($order);
    }

    /**
     * @param string $order_id
     * @return \Src\Http\Responses\Response
     */
    public function addProducts(string $order_id): Response
    {
        $this->orderModel->addProducts($order_id, $this->request->getJsonDataList());

        return new JsonResponse(['status' => 'success', 'order_id' => $order_id]);
    }

    /**
     * @return \Src\Http\Responses\Response
     */
    public function create(): Response
    {
        $items = $this->request->getJsonData('items');

        $orderId = $this->orderModel->create($items);

        return new JsonResponse($this->orderModel->getById($orderId));
    }

    /**
     * @param string $order_id
     * @return \Src\Http\Responses\Response
     */
    public function setAsDone(string $order_id): Response
    {
        $this->orderModel->setAsDone($order_id);

        return new JsonResponse(['status' => 'success', 'order_id' => $order_id]);
    }
}
