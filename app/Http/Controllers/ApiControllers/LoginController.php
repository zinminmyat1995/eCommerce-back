<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login\LoginRequest;
use App\Interfaces\LoginInterface;
use App\Classes\Repositories\LoginRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    private LoginRepository $repository;
    public function __construct(LoginInterface $repository){
        $this->repository = $repository;
    }
   
    public function register(Request $request): JsonResponse
    {
        try {
            $name = $request->username;
            $email = $request->email;
            $password = $request->password;

            $res = $this->repository->register($name, $email, $password);

            return response()->json($res, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    public function placeOrder(Request $request)
    {
        $data = $request->all();
        $result = $this->repository->placeOrder($data);

        if ($result['success']) {
            return response()->json([
                'message' => $result['message'],
                'order' => $result['order_match_id'], // or 'order_id' if you rename it
            ], 201);
        }

        return response()->json([
            'message' => $result['message'],
        ], 400);
    }
    
    public function getOrdersByUserId($id)
    {
        if($id == "undefined"){
            return response()->json([
                'message' => 'Orders fetched successfully',
                'orders' => [],
            ], 200);
        }

        $orders = $this->repository->getOrdersByUserId($id);

        if ($orders->isEmpty()) {
            return response()->json([
                'message' => 'Orders not found',
                'orders' => [],
            ], 404);
        }

        return response()->json([
            'message' => 'Orders fetched successfully',
            'orders' => $orders,
        ], 200);
    }

    public function cancelOrder(Request $request)
    {
        $orderId = $request->input('order_id');

        if (!$orderId) {
            return response()->json(['message' => 'order_id is required'], 400);
        }

        $result = $this->repository->cancel($orderId);

        if ($result) {
            return response()->json([
                'message' => 'Order cancelled successfully',
                'order_id' => $orderId,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Order not found or cancellation failed',
            ], 404);
        }
    }


    public function login(Request $request)
    {
     
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $result = $this->repository->login($request->email, $request->password);
            // Set token as httpOnly cookie
            Cookie::queue(
                'userToken',
                $result['token'],
                7 * 24 * 60, // 7 days in minutes
                null,
                null,
                true,       // Secure
                true,       // HttpOnly
                false,
                'Strict'    // SameSite
            );

            return response()->json([
                'user' => [
                    'id' => $result['user']->id,
                    'username' => $result['user']->name,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function getProducts()
    {
        try {
            $products = $this->repository->getAllProducts();
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Products not found',
            ], 400);
        }
    }
    
}