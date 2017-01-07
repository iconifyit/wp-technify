<?php

/**
 * @version        2.0 
 * @date           2011-07-31 08:58:00
 * @package        SkyBlueCanvas
 * @copyright      Copyright (C) 2005 - 2011 Iconify.it, LLC
 * @license        GNU/GPL, see COPYING.txt
 * SkyBlueCanvas is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYING.txt for copyright notices and details.
 */

require_once('Filter.php'); 
require_once('JavaScriptPacker.php');

/**
 * Retrieves a variable from an object or array. This is just a wrapper function for Filter::get().
 *
 * @param Array|Object  The object or array from which to retrieve the variable
 * @param string        The name of the variable to retrieve.
 * @default mixed       The default value to return if the variable is not found
 * @return mixed
 */
function get_var($subject, $key, $default='') {
    return Filter::get($subject, $key, $default);
}

/**
 * Reads the contents of a file from disk
 *
 * @param string   The path to the file to read
 * @return string  The file contents
 */
function do_read_file($file) {
    if (is_dir($file)) return null;
    if (! file_exists($file)) return null;
    if (! is_readable($file)) return null;
    $str = "";
    $fp = fopen($file, 'r');
    if (!$fp) return false;
    if (filesize($file) > 0) {
        $str = fread($fp, filesize($file));
    }
    return $str;
}

/**
 * Outputs a JSON Header
 * @return void
 */
function header_json() {
    if (! headers_sent()) {
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Content-type: application/json");
    }
}

/**
 * Outputs a JavaScript Header
 * @return void
 */
function header_javascript() {
    if (! headers_sent()) {
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Content-type: application/javascript");
    }
}

/**
 * Outputs an XML Header
 */
function header_xml() {
    if (! headers_sent()) {
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("content-type: text/xml");
    }
}

/**
 * Outputs an XML Header
 * @return void
 */
function header_css() {
    if (! headers_sent()) {
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("content-type: text/css; charset: UTF-8");
    }
}

/**
 * Strips comments and new lines from a CSS file
 * @param string    The source code to compress
 * @return string   The compressed source code.
 */
function compress_source($source) {
    return preg_replace(
        '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', 
        str_replace(
            array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', 
            $source
        )
    );
}

/**
 * Packs JavaScript source
 * @param string    The source code to compress
 * @return string   The compressed source code
 */
function pack_javascript($source) {
    $packer = new JavaScriptPacker($source, 'Normal', true, false);
    return $packer->pack();
}