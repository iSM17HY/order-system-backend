<?php

namespace App\Controller;


use PDO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{

    private $database;

    public function __construct()
    {
        $this->database = new \PDO('mysql:host=localhost:3306;dbname=order_system', 'root', '');
    }

    /**
     * @Route("/api/order", name="getorder")
     * Retrieve a given order
     * @param Request $request
     * @return JsonResponse
     */
    public function getOrder(Request $request)
    {
        $orderId = $request->get('orderId');
        $responseData = [];

        $order = $this->database->prepare('
            SELECT o.id as order_number, o.date as order_date, o.customer_id
            FROM orders o
            INNER JOIN customers c on o.customer_id = c.id
            WHERE o.id = :orderId
        ');

        $order->execute([':orderId' => $orderId]);
        $orderData = $order->fetch(PDO::FETCH_ASSOC);

        $responseData['order'] = $orderData;

        $customer = $this->database->prepare('
            SELECT * FROM customers WHERE id = :customerId
        ');

        $customer->execute([':customerId' => $orderData['customer_id']]);
        $customerData = $customer->fetch(PDO::FETCH_ASSOC);

        $responseData['customer'] = $customerData;

        $orderProducts = $this->database->prepare('
            SELECT oi.*,
               p.price AS product_cost,
               p.product_name
            FROM order_items oi
            INNER JOIN products p on oi.product_id = p.id
            WHERE oi.order_id = :orderId
        ');

        $orderProducts->execute([':orderId' => $orderId]);
        $productData = $orderProducts->fetchAll(PDO::FETCH_ASSOC);

        $responseData['products'] = $productData;

        $data = new JsonResponse($responseData);
        $data->headers->set('Access-Control-Allow-Origin', '*');

        return $data;
    }

    /**
     * @Route("/api/addOrder", name="addOrder")
     * Insert a new order record
     * @param Request $request
     */
    public function addOrder(Request $request)
    {
        $customerId = $request->get('customerId');
        $selectedProducts = $request->get('products');

        $orderInsert = $this->database->prepare('
            INSERT INTO orders (customer_id, date) VALUES (?, ?);
        ');

        $orderInsert->execute([$customerId, 'NOW()']);
        $orderId = $this->database->lastInsertId();

        foreach ($selectedProducts as $selectedProduct) {
            $orderItem = $this->database->prepare('
                INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?);
            ');

            $orderItem->execute([$orderId, $selectedProduct['productId'], $selectedProduct['quantity']]);
        }
    }

    /**
     * @Route("/api/orderOptions", name="getorderingOptions")
     * Get the available options for ordering
     */
    public function getOrderingOptions()
    {
        $returnData = [];

        $customers = $this->database->prepare('
            SELECT * FROM customers
        ');

        $customers->execute();
        $allCustomers = $customers->fetchAll(PDO::FETCH_ASSOC);
        $returnData['customers'] = $allCustomers;

        $products = $this->database->prepare('
            SELECT * FROM products
        ');

        $products->execute();
        $allProducts = $products->fetchAll(PDO::FETCH_ASSOC);
        $returnData['products'] = $allProducts;

        $data = new JsonResponse($returnData);
        $data->headers->set('Access-Control-Allow-Origin', '*');

        return $data;
    }

    /**
     * @Route("/api/orders", name="orders")
     * Retrieve all orders
     * @param Request $request
     * @return JsonResponse
     */
    public function getOrders(Request $request)
    {
        $orders =  $this->database->prepare("
            SELECT o.*, SUM(p.price) as total_cost, 
                    CONCAT(c.firstname, ' ', c.surname) as customer_name 
            FROM orders o
                INNER JOIN customers c on o.customer_id = c.id
                INNER JOIN order_items oi on o.id = oi.order_id
                INNER JOIN products p on oi.product_id = p.id
            GROUP BY o.id;
        ");

        $orders->execute();
        $orderData = $orders->fetchAll(\PDO::FETCH_ASSOC);

        $data = new JsonResponse($orderData);
        $data->headers->set('Access-Control-Allow-Origin', '*');

        return $data;
    }

}
