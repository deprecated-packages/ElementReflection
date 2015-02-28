<?php

include __DIR__ . '/../vendor/autoload.php';


define('TEMP_DIR', __DIR__ . '/temp/' . getmypid());
@mkdir(TEMP_DIR, 0777, TRUE);


register_shutdown_function(function() {
	Nette\Utils\FileSystem::delete(__DIR__ . '/temp');
});
