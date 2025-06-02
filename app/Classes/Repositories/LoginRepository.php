<?php

namespace App\Classes\Repositories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;
use App\Models\OrderMatch;
use App\Interfaces\LoginInterface;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LoginRepository implements LoginInterface
{
	function __construct()
	{
	}

	function register($name, $email, $password)
	{
		// Check if user already exists
		if (User::where('email', $email)->exists()) {
			throw new \Exception('Email already exists');
		}

		// Create user
		$user = User::create([
			'name' => $name,
			'email' => $email,
			'password' => $password,
		]);

		// Generate JWT Token
		$payload = [
			'iss' => "your-app", // Issuer
			'sub' => $user->id,
			'iat' => time(),
			'exp' => time() + (7 * 24 * 60 * 60), // 7 days
		];
		$token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

		// Set Cookie
		Cookie::queue('userToken', $token, 7 * 24 * 60); // 7 days in minutes

		return [
			'user' => [
				'id' => $user->id,
				'username' => $user->name,
			]
		];
	}

	// Repository method
	public function placeOrder($data)
	{ logger($data['user_id']);
		try {
			DB::beginTransaction();

			// Insert into order_match table
			$orderMatchId = DB::table('order_match')->insertGetId([
				'user_id' => $data['user_id'],
				'total_item_count' => $data['totalItemCount'],
				'delivery_type' => $data['delivery_type'],
				'delivery_type_cost' => $data['delivery_type_cost'],
				'cost_before_delivery_rate' => $data['cost_before_delivery_rate'],
				'cost_after_delivery_rate' => $data['cost_after_delivery_rate'],
				'contact_number' => $data['contact_number'],
				'address' => $data['address'],
				'order_status' => 0,
				'deleted_at' => 0,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now(),
			]);

			// Insert into order table (looping through items)
			foreach ($data['items'] as $item) {
				DB::table('order')->insert([
					'order_match_id' => $orderMatchId,
					'product_id' => $item['_id'],
					'quantity' => $item['quantity'],
					'price' => $item['price'],
					'total' => $item['quantity'] * $item['price'],
					'deleted_at' => 0,
					'created_at' => Carbon::now(),
					'updated_at' => Carbon::now(),
				]);
			}

			DB::commit();

			return [
				'success' => true,
				'message' => 'Order placed successfully',
				'order_match_id' => $orderMatchId
			];
		} catch (Exception $e) {
			DB::rollBack();

			return [
				'success' => false,
				'message' => 'Order placement failed',
				'error' => $e->getMessage()
			];
		}
	}

	public function getOrdersByUserId($userId)
	{
		 // Step 1: Get order_match records for the user
		 $orderMatches = DB::table('order_match')
		 ->where('user_id', $userId)
		 ->where('deleted_at', 0)
		 ->orderByDesc('created_at')
		 ->get();

	 // Step 2: Extract order_match_ids
	 $orderMatchIds = $orderMatches->pluck('id')->toArray();

	 // Step 3: Get orders that match those order_match_ids
	 $orders = DB::table('order')
			->whereIn('order.order_match_id', $orderMatchIds)
			->join('product', 'order.product_id', '=', 'product.id')
			->select(
				'order.*',
				'order.id as _id',
				'product.p_name as name',
				'product.description',
				'product.image_url as product_image'
			)
			->get()
			->groupBy('order_match_id');

	 // Step 4: Combine order_match data with related orders
	 $result = $orderMatches->map(function ($match) use ($orders) {
		 return [
			 '_id' => $match->id,
			 'items' => $orders[$match->id] ?? [],
			 'totalItemCount' => $match->total_item_count,
			 'delivery_type' => $match->delivery_type,
			 'delivery_type_cost' => $match->delivery_type_cost,
			 'cost_before_delivery_rate' => $match->cost_before_delivery_rate,
			 'cost_after_delivery_rate' => $match->cost_after_delivery_rate,
			 'contact_number' => $match->contact_number,
			 "order_processed"=> false,
			 "order_cancelled"=> false,
			 'user_id' => $match->user_id,
			 "percentage_complete" => 0,
			 'delivery_address' => $match->address,
			 'order_status' => $match->order_status,
			 'created_at' => $match->created_at,
		 ];
	 });

	 return $result;
	}

	public function cancel($orderId)
	{
		$order = DB::table('order_match')
		->where('id', $orderId)
		->get();	
	
		if (!$order) {
			return false;
		}

		$affected = DB::table('order_match')
			->where('id', $orderId)
			->update([
				'deleted_at' => 1,
				'updated_at' => Carbon::now(),
			]);

		return $affected > 0;
	}
	

	public function login($email, $password)
	{ 
		$user = User::where('email', $email)->first();
		
		// if (!$user || !Hash::check($password, $user->password)) {
		// 	throw new \Exception("Invalid credentials.");
		// }

		$token = $this->createToken($user->id);

		// Return user and token
		return [
			'user' => $user,
			'token' => $token,
		];
	}

	protected function createToken($userId)
	{
		$payload = [
			'iss' => "laravel-jwt", // Issuer
			'sub' => $userId,
			'iat' => time(),
			'exp' => time() + (7 * 24 * 60 * 60), // 7 days
		];

		return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
	}

	public function getAllProducts()
	{

		$products = DB::table('product')
			->join('product_category', 'product.p_category_id', '=', 'product_category.id')
			->where('product.deleted_at', 0)
			->select(
				'product.*',
				'product_category.p_category_name',
				'product_category.p_category_count'
			)
			->get();

		// Map data to expected structure
		return $products->map(function ($product) {
			return [
				'description'   => $product->description,
				'category_id'  => $product->p_category_id,
				'category_name'  => $product->p_category_name,
				'category_count'  => $product->p_category_count,
				'name'          => $product->p_name,
				'price'         => $product->price,
				'remain'         => $product->remain,
				'product_image' => $product->image_url,
				'rating'        => 5,
				'times_bought'  => 0,
				'__v'           => 0,
				'_id'           => $product->id,
			];
		});
	}

}
