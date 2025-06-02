<?php

use Illuminate\Support\Str;

return [

	/*
	|--------------------------------------------------------------------------
	| Default Session Driver
	|--------------------------------------------------------------------------
	|
	| This option determines the default session driver that is utilized for
	| incoming requests. Laravel supports a variety of storage options to
	| persist session data. Database storage is a great default choice.
	|
	| Supported: "file", "cookie", "database", "apc",
	|            "memcached", "redis", "dynamodb", "array"
	|
	*/

	'compiled' => env(
		'VIEW_COMPILED_PATH',
		realpath(storage_path('framework/views'))
	),



];
