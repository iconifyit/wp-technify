<?php
/**
 * The proxy file for generating the JS output.
 * @since      1.0.0
 * @package    Technify
 * @author     Scott Lewis <scott@vectoricons.net>
 */

$compressor_file = @$_SERVER['CONTEXT_DOCUMENT_ROOT'] . "/wp-content/plugins/technify/includes/compressor-base.php";

if ( file_exists( $compressor_file ) ) {
    require_once( $compressor_file );

    $Compressor->js();
}
exit(0);


