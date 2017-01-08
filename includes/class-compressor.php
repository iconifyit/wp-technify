<?php
/**
 * CSS & JS Compressor class
 *
 * @link       http://iconfinder.com/iconify
 * @since      2.0.0
 *
 * @package    Technify
 */
class Compressor {

    /**
     * @var array
     */
    var $operations = array( 'minify', 'aggregate' );

    /**
     * @var array|mixed|void
     */
    var $options = array();

    /**
     * @var array
     */
    var $styles  = array();

    /**
     * @var
     */
    var $css_enabled;

    /**
     * @var
     */
    var $js_enabled;

    /**
     * @var array
     */
    var $scripts = array();

    function __construct() {

        $this->options = get_option( 'Technify' );
        $this->parse_options();
    }

    /**
     * Singleton for getting an instance of the Compressor.
     * @return Compressor
     */
    public static function singleton() {
        static $instance;
        if ( ! is_a( $instance, 'Compressor' ) ) {
            $instance  = new Compressor();
        }
        return $instance;
    }

    /**
     * Get the style paths.
     * @param string $op    The sub-section (minify or aggregate)
     *
     * @return array|mixed
     */
    public function get_styles( $op='' ) {
        $values = $this->styles;

        if (! empty($op) && isset($values[$op]) ) {
            $values = $values[$op];
        }
        return $values;
    }

    /**
     * Get the JS paths.
     * @param string $op    The sub-section (minify or aggregate)
     *
     * @return array|mixed
     */
    public function get_scripts( $op='' ) {
        $values = $this->scripts;

        if (! empty($op) && isset($values[$op]) ) {
            $values = $values[$op];
        }
        return $values;
    }

    /**
     * Split the blobs of file paths into usable data types.
     */
    private function parse_options() {

        /**
         * Parse stylesheet URLs
         */

        $styles = array(
            'aggregate' => explode( "\n", Tools::get( $this->options, 'css_aggregate' ) ),
            'minify'    => explode( "\n", Tools::get( $this->options, 'css_minify' ) )
        );

        foreach ( $this->operations as $op ) {
            $this->styles[$op] = $this->get_paths( Tools::get( $styles, $op, array() ) );
        }

        /**
         * Parse JS URLs
         */

        $scripts = array(
            'aggregate' => explode( "\n", Tools::get( $this->options, 'js_aggregate' ) ),
            'minify'    => explode( "\n", Tools::get( $this->options, 'js_minify' ) )
        );

        foreach ( $this->operations as $op ) {
            $this->scripts[$op] = $this->get_paths( Tools::get( $scripts, $op, array() ) );
        }

        $options = get_option( 'Technify' );

        $this->css_enabled = false;
        if ( Tools::get( $options, 'css_enabled', 0 ) == 1 ) {
            $this->css_enabled = true;
        }

        $this->js_enabled = false;
        if ( Tools::get( $options, 'js_enabled', 0 ) == 1 ) {
            $this->js_enabled = true;
        }
    }

    /**
     * Process the list of CSS and JS paths.
     * @param $files
     *
     * @return array
     */
    private function get_paths( $files ) {

        $objects = array();

        if ( ! count( $files )) return $objects;

        foreach ( $files as $line ) {

            $file = trim( $line );
            if ( empty($file) ) continue;

            $url  = wp_parse_url( $file );
            $site = wp_parse_url( get_bloginfo('url') );

            if ( isset( $url['host'] ) && isset( $site['host'] ) ) {
                if ( $url['host'] == $site['host'] ) {
                    $file = Tools::get( $url, 'path' );
                    if ($file{strlen($file)-1} == "_") {
                        $file = substr($file, 0, -1);
                    }
                }
            }
            $objects[] = $file;
        }
        return $objects;
    }

    /**
     * The public interface for aggregating and compressing CSS code.
     */
    public function process_css() {
        $this->process( 'styles' );
    }

    /**
     * The public interface for aggregating and compressing JS code.
     */
    public function process_js() {
        $this->process( 'scripts' );
    }

    /**
     * Process JS & CSS files
     */
    public function process_all() {
        Tools::debug( $this );
        $this->process_css();
        $this->process_js();
    }

    /**
     * For debugging ajax calls.
     * @param $what
     */
    private function debug( $what ) {
        echo json_encode(array(
            'text' => json_encode($what),
            'class' => 'notice-info'
        ));
        exit(0);
    }

    /**
     * @param string $output    The output from the a CSS or JS file.
     * @return string           The new output
     */
    public function parse_urls( $file, $output ) {

        /**
         * ./ (dot slash)       = replace with path to the file directory
         * null                 = replace with path to file directory
         * / (slash)            = replace with URL to web root
         * ../ (dot dot slash)  = replace with directory above path to the file
         */

        $pattern = '|url\s*\(([^\)]+)\)|';

        $matches = array();
        preg_match_all( $pattern, $output, $matches );

        if ( is_array( $matches ) && count( $matches ) >= 2 ) {
            $attrs = Tools::get( $matches, 0, array() );
            $links = Tools::get( $matches, 1, array() );
            for ($i=0; $i<count($attrs); $i++ ) {
                $search = $attrs[$i];
                $link   = $links[$i];

                $link = str_replace( array("'", '"'), "", $link );

                $char1 = $link{0};
                $char2 = $link{1};

                $path = "/";
                if ( $char1.$char2 == "./" )  {
                    $path = dirname($file) . '/';
                }
                else if ( $char1 != "." ) {
                    $path = dirname($file) . '/';
                }

                $replace = get_bloginfo('url') . $path . $link;
                $replace = "url('{$replace}')";
                $output = str_replace( $search, $replace, $output );
            }
        }
        return $output;
    }

    /**
     * Processes the aggregation & compression of JS or CSS code.
     * @param $type
     */
    private function process( $type ) {

        $option_key = TECH_STYLES_KEY;
        $func_pack  = 'compress';

        if ( $type === 'scripts' ) {
            $option_key = TECH_SCRIPTS_KEY;
            $func_pack  = 'pack_js';
        }

        try {

            ob_start();

            try {
                foreach ( $this->operations as $op ) {

                    $files = array_map(
                        'trim',
                        Tools::get( $this->$type, $op, array() )
                    );

                    foreach ( $files as $file ) {

                        if ( empty($file) ) continue;

                        $output = $this->read_file( ABSPATH . $file );

                        if ( trim($output) == '' ) continue;

                        if ( $type == 'styles' ) {
                            $output = $this->parse_urls( $file, $output );
                        }

                        if ( $op == 'minify' ) {
                            $output = call_user_func( array($this, $func_pack), $output );
                        }
                        echo $output;
                    }
                }
            }
            catch(Exception $e) {
                trigger_error($e->getMessage());
            }

            $output = ob_get_contents();
            ob_end_clean();

            $class   = 'notice-error';
            $message = "The {$type} were not saved.";

            if ( $this->save_code( $option_key, $output ) ) {
                $class   = 'notice-success';
                $message = "The {$type} were saved.";
            }
            else {
                $errors = get_settings_errors( $option_key, true );
                if ( is_array($errors) ) {
                    $message .= Filter::get( $errors, 0, ' Undefined update_option error.' );
                }
            }
            echo json_encode(array(
                'text'  => $message,
                'class' => $class
            ));
        }
        catch(Exception $e) {
            echo json_encode(array(
                'text'  => $e->getMessage(),
                'class' => 'notice-error'
            ));
        }
        exit(0);
    }

    /**
     * Saves CSS or JS code as a wordpress option. The value is first JSON encoded to prevent errors.
     * @param $option_key
     * @param $code
     *
     * @return bool
     */
    function save_code( $option_key, $code ) {
        delete_option( $option_key );
        return update_option( $option_key, wp_json_encode($code), false );
    }

    /**
     * Retrieves the decoded CSS or JS value previously stored as an option.
     * @param $option_key
     *
     * @return array|mixed|object
     */
    function get_code( $option_key ) {
        return json_decode( get_option( $option_key ) );
    }

    /**
     * Reads the contents of a file from disk
     *
     * @param string   The path to the file to read
     * @return string  The file contents
     */
    public function read_file($file) {
        if (is_dir($file)) return null;
        if (! file_exists($file)) return null;
        if (! is_readable($file)) return null;
        $str = "";
        $fp = fopen($file, 'r');
        if (! $fp) return null;
        if (filesize($file) > 0) {
            $str = fread($fp, filesize($file));
        }
        return $str;
    }

    /**
     * Outputs a JSON Header
     * @return void
     */
    public function header_json() {
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
    public function header_js() {
        if (! headers_sent()) {
            header("Cache-Control: no-cache, must-revalidate");
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Content-type: application/javascript");
        }
    }

    /**
     * Outputs an XML Header
     */
    public function header_xml() {
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
    public function header_css() {
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
    public function compress($source) {
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
    public function pack_js($source) {
        $packer = new JavaScriptPacker($source, 'Normal', true, false);
        return $packer->pack();
    }

    /**
     * Output compressed JS
     */
    public function js() {

        $this->header_js();
        echo "/* Technify-generated scripts - " . date("D M j G:i:s T Y") . " */\n";
        echo $this->get_code( TECH_SCRIPTS_KEY );
    }

    /**
     * Output compressed CSS
     */
    public function css() {

        $this->header_css();
        echo "/* Technify-generated styles - " . date("D M j G:i:s T Y") . " */\n";
        echo $this->get_code( TECH_STYLES_KEY );
    }
}