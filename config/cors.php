<?php

return [
	'paths' => ['api/*', 'sanctum/csrf-cookie', 'v1/*'],  // Adjust the paths as necessary
	'allowed_methods' => ['*'],  // Allow all methods or specify specific ones
	'allowed_origins' => [
		'http://localhost:3000',  // Local dev URL
		'https://online-shop-front-seven.vercel.app',  // Your front-end production URL
	],
	'allowed_headers' => ['*'],  // Allow all headers or specify
	'exposed_headers' => [],
	'max_age' => 0,
	'supports_credentials' => true,
];

