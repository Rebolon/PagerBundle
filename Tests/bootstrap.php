<?php

$loader = require __DIR__.'/../vendor/.composer/autoload.php';
$loader->register();

// @todo : resolve this autoload requirements whereas it should not be !!!
spl_autoload_register(function($class) {
	if (0 === strpos($class, 'Rebolon\\PagerBundle\\')) {
		$path = __DIR__ . '/../' . implode('/', array_slice(explode('\\', $class), 2)) . '.php';

		if (!stream_resolve_include_path($path)) {
			return false;
		}
		require_once $path;
		return true;
	}
});
