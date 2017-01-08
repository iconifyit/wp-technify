<?php
/**
 * Class of globally-used utility functions.
 *
 * @link       http://iconfinder.com/iconify
 * @since      2.0.0
 *
 * @package    Technify
 */
class Tools {

    /**
     * Returns the requested value or default if empty
     * @param mixed $subject
     * @param string $key
     * @param mixed $default
     * @return mixed
     *
     * @since 1.1.0
     */
    public static function get($subject, $key, $default=null) {
        $value = $default;
        if (is_array($subject)) {
            if (isset($subject[$key])) {
                $value = $subject[$key];
            }
        }
        else if (is_object($subject)) {
            if (isset($subject->$key)) {
                $value = $subject->$key;
            }
        }
        else if (! empty($subject)) {
            $value = $subject;
        }
        return $value;
    }

    /**
     * Tests a mixed variable for true-ness.
     * @param int|null|bool|string $value
     * @param null|string|bool|int $default
     * @return bool|null
     */
    public static function is_true($value, $default=null) {
        $result = $default;
        $trues  = array(1, '1', 'true', true, 'yes', 'da', 'si', 'oui', 'absolutment', 'yep', 'yeppers', 'fuckyeah');
        $falses = array(0, '0', 'false', false, 'no', 'non', 'nein', 'nyet', 'nope', 'nowayjose');
        if (in_array(strtolower($value), $trues, true)) {
            $result = true;
        }
        else if (in_array(strtolower($value), $falses, true)) {
            $result = false;
        }
        return $result;
    }

    /**
     * This is a debug function and ideally should be removed from the production code.
     * @param array|object  $what   The object|array to be printed
     * @param bool          $die    Whether or not to die after printing the object
     * @return string
     */
    public static function dump($what, $die=true) {

        if (is_string( $what )) $what = array( 'debug' => $what );
        $output = sprintf( '<pre>%s</pre>', print_r($what, true) );
        if ( $die ) die( $output );
        return $output;
    }

    /**
     * This is an alias for Tools::dump()
     * @param array|object  $what   The object|array to be printed
     * @param bool          $die    Whether or not to die after printing the object
     * @return string
     */
    public static function debug($what, $die=true) {

        return Tools::dump( $what, $die );
    }

    /**
     * Buffers the output from a file and returns the contents as a string.
     * You can pass named variables to the file using a keyed array.
     * For instance, if the file you are loading accepts a variable named
     * $foo, you can pass it to the file  with the following:
     *
     * @example
     *
     *      do_buffer('path/to/file.php', array('foo' => 'bar'));
     *
     * @param string $path
     * @param array $vars
     * @return string
     */
    public static function buffer( $path, $vars=null ) {
        $output = null;
        if (! empty($vars)) {
            extract($vars);
        }
        if (file_exists( $path )) {
            ob_start();
            include_once( $path );
            $output = ob_get_contents();
            ob_end_clean();
        }
        return $output;
    }

    /**
     * Writes the provided string data to the specified file
     * @param string  The file to write
     * @param string  The string contents of the file
     * @param string  The file mode
     * @return bool   Whether or not the file was written
     */
    function write_file($file, $str, $mode='FILE_APPEND') {
        if (! file_exists($file)) return false;
        return file_put_contents( $file, $str ,FILE_APPEND );
//        $fp = fopen($file, $mode);
//        if (!$fp) return false;
//        if (!fwrite($fp, $str)) return false;
//        fclose($fp);
//        return true;
    }

    /**
     * Reads the contents of a file
     * @param string  The file to read
     * @return string The file contents
     */
    function read_file($file) {
        if (is_dir($file)) return null;
        if (! file_exists($file)) return null;
        return file_get_contents( $file );
//        $str = "";
//        $fp = fopen($file, 'r');
//        if (!$fp) return false;
//        if (filesize($file) > 0) {
//            $str = fread($fp, filesize($file));
//        }
//        return $str;
    }

    /**
     * Filter the entire dataset of iconsets searching for
     * specific iconset_ids.
     * @param array $iconsets The whole dataset
     * @param array $sets An array of iconset_ids to find
     * @return array
     */
    public static function filter_iconsets( $iconsets, $sets ) {
        $filtered = array();
        if (is_array($iconsets) && count($iconsets)) {
            foreach ($iconsets as $iconset) {
                if (in_array($iconset['iconset_id'], $sets)) {
                    array_push($filtered, $iconset);
                }
            }
        }
        return $filtered;
    }

    public static function parse_url($url) {
        $parts = wp_parse_url($url);
        $bits = explode('.', Tools::get($parts, 'host'));
        $parts['tld'] = Tools::get($bits, 1);
        $parts['domain'] = Tools::get($bits, 0);
        return $parts;
    }
}