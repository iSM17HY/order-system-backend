<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{

    private $database;

    public function __construct()
    {
        $this->database = new \PDO('mysql:host=localhost;dbname=order_system', 'root', '');
    }

    /**
     * Retrieve a given order
     * @param Request $request
     */
    public function getOrder(Request $request)
    {

    }

    /**
     * Insert a new order record
     * @param Request $request
     */
    public function addOrder(Request $request)
    {

    }

    /**
     * Retrieve all orders
     * @param Request $request
     */
    public function getOrders(Request $request)
    {

    }

}
