<?php

namespace App\Models;

use Src\Database\Database;
use Src\Http\Exceptions\RouteNotFoundException;

class Order
{
    /**
     * @var \Src\Database\Database
     */
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * @param string $orderId
     * @return array
     * @throws \Src\Http\Exceptions\RouteNotFoundException
     */
    public function getById(string $orderId): array
    {
        $order = $this->db->preparedQuery(
            "SELECT orders.order_id, orders.done, JSON_ARRAYAGG(order_product.product_id) as items FROM orders
             RIGHT JOIN (SELECT * FROM order_product WHERE order_id=:order_id LIMIT 5000) order_product ON orders.order_id = order_product.order_id
             WHERE orders.order_id = :order_id
             GROUP BY orders.order_id;",
            ['order_id' => $orderId],
        );

        if (empty($order)) {
            throw new RouteNotFoundException('order not found');
        }

        return $this->prepareData($order[0]);
    }

    /**
     * @param ?int $done
     * @return array
     */
    public function getList(?int $done = null): array
    {
        $query = 'SELECT * FROM orders';

        if (isset($done)) {
            $query .= ' WHERE done=:done';
            $data = ['done' => $done];
        }

        $orders = $this->db->preparedQuery($query, $data ?? []);

        foreach ($orders as $key => $order) {
            $orders[$key] = $this->prepareData($order);
        }

        return $orders;
    }

     /**
     * @param array $items
     * @return string
     */
    public function create(array $items):string
    {
        $orderId = bin2hex(random_bytes(7));

        $this->db->preparedQuery("INSERT INTO orders VALUES (:order_id, 0);", ['order_id' => $orderId]);

        $this->addProducts($orderId, $items);

        return $orderId;
    }

    /**
     * @param string $orderId
     * @return bool
     */
    public function setAsDone(string $orderId): bool
    {
        $result = $this->db->preparedQuery('UPDATE orders SET done=1 WHERE order_id=:order_id', ['order_id' => $orderId]);

        return $result ? true : false;
    }

    /**
     * @param string $orderId
     * @param array $items
     * @return bool
     */
    public function addProducts(string $orderId, array $items): bool
    {
        $values = '';

        foreach ($items as $key => $item) {
            $values .= "('$orderId', ?)";
            if (array_key_last($items) != $key)
            {
                $values .= ", ";
            }
        }

        $result = $this->db->preparedQuery("INSERT INTO order_product (order_id, product_id) VALUES $values;", $items);

        return $result ? true : false;
    }

     /**
     * @param array $order
     * @return array
     */
    private function prepareData(array $order): array
    {
        if (isset($order['done'])) {
            $order['done'] = $order['done'] ? true : false;
        }
        if (isset($order['items'])) {
            $order['items'] = json_decode($order['items'], true);
        }

        return $order;
    }

     /**
     * @param string $orderId
     * @return bool
     */
    public function checkIfDone(string $orderId): bool
    {
        $order = $this->getById($orderId);

        return $order['done'];
    }
}
