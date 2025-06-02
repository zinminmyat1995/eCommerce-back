<?php
namespace App\Interfaces;

interface UserRegistrationInterface
{
	// login
	function login($code,$password);
	
	// save user data 
	function save($name, $email, $phone_no,$address,$password,$login_id);

	// reset password
	function resetPassword($code, $password, $login_id );
	
}
