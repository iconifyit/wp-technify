<?php

@date_default_timezone_set(date_default_timezone_get());

if (function_exists('ini_set') && is_callable('ini_set')) {
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 'On');
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	ini_set('error_log', './php_errors.log');
}

try {
	require_once("../php/functions.php");
	
	$output_file = "all.js";

/*
<script src="<?php iconify_js('plugins.js'); ?>"></script>
<script src="<?php iconify_js('jquery-breakpoint/jquery.breakpoint-min.js'); ?>"></script>

<script src="<?php iconify_js('jquery.mmenu.min.all.js'); ?>"></script>

<script src="<?php iconify_js('jquery.scrollTo.min.js'); ?>"></script>

<script src="<?php iconify_js('jquery.easing.min.js'); ?>"></script>
<script src="<?php iconify_js('jquery.scrollUp.js'); ?>"></script>

<script src="<?php iconify_js('jquery.cookie.js'); ?>"></script>
<script src="<?php iconify_js('json2.js'); ?>"></script>
<script src="<?php iconify_js('jquery.storageapi.min.js'); ?>"></script>
<script src="<?php iconify_js('main.js'); ?>"></script>
*/
	$aggregate = array(
	    "./vendor/modernizr-2.6.2.min.js",
	    "./jquery-breakpoint/jquery.breakpoint-min.js",
	    "./jquery.mmenu.min.all.js",
	    "./jquery.scrollTo.min.js",
	    "./navigation.js",
	    
	);
	
	$pack = array(
	    "./plugins.js",
	    "./jquery.easing.min.js",
	    "./jquery.scrollUp.js",
	    "./main.js",
	);

	ob_start();
	
	$count = count($aggregate);
	for ($i=0; $i<$count; $i++) {
	    echo do_read_file($aggregate[$i]);
	}
	
	$count = count($pack);
	for ($i=0; $i<$count; $i++) {
	    echo pack_javascript(do_read_file($pack[$i]));
	}
	
	$output = ob_get_contents();
	ob_end_clean();
	
	/**
	 * Now, write the output you captured in the buffer into the cache file
	 */
	$result = "The aggregated JavaScript file could not be created";
	if ($fp = fopen($output_file, 'w')) {
		if (fwrite($fp, $output)) {
			$result = "The aggregated JavaScript file was created";
		}
		fclose($fp);
	}
	
	echo $result;
}
catch(Exception $e) {
    echo $e->getMessage();
}