<?php
/**
 * The proxy file for generating the CSS output.
 * @since      1.0.0
 * @package    Technify
 * @author     Scott Lewis <scott@vectoricons.net>
 */

$folders         = explode('/', dirname( __FILE__ ));
$plugin_dir_name = $folders[count($folders)-3];
$compressor_file = @$_SERVER['CONTEXT_DOCUMENT_ROOT'] . "/wp-content/plugins/{$plugin_dir_name}/includes/compressor-base.php";

if ( file_exists( $compressor_file ) ) {
    require_once( $compressor_file );
    $Compressor = Compressor::singleton();
    $Compressor->css();
}
exit(0);