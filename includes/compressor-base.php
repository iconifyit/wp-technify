<?php
/**
 * Base file for CSS & JS Compressor output.
 * @since      1.0.0
 * @package    Technify
 * @author     Scott Lewis <scott@vectoricons.net>
 */

$wp_load_file  = @$_SERVER['CONTEXT_DOCUMENT_ROOT'] . "/wp-load.php";
$technify_file = @$_SERVER['CONTEXT_DOCUMENT_ROOT'] . "/wp-content/plugins/technify/technify.php";

if ( file_exists( $wp_load_file ) ) {
    require_once( $wp_load_file );

    if ( file_exists( $technify_file ) ) {
        require_once( $technify_file );

        $Compressor  = Compressor::singleton();
    }
}
