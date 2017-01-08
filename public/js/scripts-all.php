<?php
/**
 * The proxy file for generating the JS output.
 * @since      1.0.0
 * @package    Technify
 * @author     Scott Lewis <scott@vectoricons.net>
 */

try {
    $folders         = explode( '/', dirname( __FILE__ ) );
    $plugin_dir      = implode( '/', array_slice( $folders, 0, -2 ) );

    require_once( $plugin_dir . "/includes/compressor-base.php" );
    $Compressor = Compressor::singleton();
    $Compressor->js();
}
catch(Exception $e) {/* Fail gracefully */}
exit(0);