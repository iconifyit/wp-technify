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
	
	$output_file = "all.css";

	/*
	$files = array(
	    "./normalize.css",
	    "./main.css",
	    "./custom.css",
	    "./jquery.mmenu.positioning.css",
	    "./jquery.mmenu.all.css"
	);
	*/

	$objects = array(
	    array("file" => "./edd.css",                      "action" => "compress"),
	    array("file" => "./normalize.min.css",            "action" => "compress"),
	    array("file" => "./main.css",                     "action" => "compress"),
	    array("file" => "./custom.css",                   "action" => "compress"),
	    array("file" => "./jquery.mmenu.all.css",         "action" => "compress"),
	    array("file" => "./jquery.mmenu.positioning.css", "action" => "compress"),
	    array("file" => "./iconified.css",                "action" => "compress"),
	    array("file" => "./iconify-free.css",             "action" => "compress"),
	    array("file" => "./styles.css",                   "action" => "compress"),
	);
	
	ob_start();
	
	try {
		$count = count($objects);
		for ($i=0; $i<$count; $i++) {
		    $object = $objects[$i];
		    $file   = $object['file'];
		    $action = $object['action'];
		    $data = do_read_file($file);
		    if ("compress" == $action) {
		        $data = compress_source($data);
		    }
		    echo $data;
		}
	}
	catch(Exception $e) {
	    trigger_error($e->getMessage());
	}

	$output = ob_get_contents();
	ob_end_clean();
	
	/**
	 * Now, write the output you captured in the buffer into the cache file
	 */
	$result = "The compressed CSS file could not be created";
	if ($fp = fopen($output_file, 'w')) {
		if (fwrite($fp, $output)) {
			$result = "The compressed CSS file was created";
		}
		fclose($fp);
	}
	
	echo $result;
}
catch(Exception $e) {
    trigger_error($e->getMessage());
}

