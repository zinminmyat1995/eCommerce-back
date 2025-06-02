<?php
namespace App\Interfaces;

interface LoginInterface
{

	function register($name, $email,$password);
	function placeOrder($data);
	function getOrdersByUserId($id);
	function cancel($id);
	function login($email, $password);
	function getAllProducts();
}
