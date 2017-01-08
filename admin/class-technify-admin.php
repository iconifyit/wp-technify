<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://vectoricons.net
 * @since      1.0.0
 *
 * @package    Technify
 * @subpackage Technify/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Technify
 * @subpackage Technify/admin
 * @author     Scott Lewis <scott@vectoricons.net>
 */
class Technify_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/technify-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		# wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/technify-admin.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( 'ajax-script', plugin_dir_url( __FILE__ ) . 'js/technify-ajax.js', array( 'jquery' ) );
        wp_localize_script( 'ajax-script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => '' ) );
	}

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu() {

        add_menu_page( 'Technify Me', 'Technify Me', 'manage_options', $this->plugin_name . '-compressor', array($this, 'display_compressor'));
    }

    public function display_compressor() {
        echo $this->apply_admin_theme(null, 'technify-admin-display.php');
    }

    /**
     * Apply the custom or default theme to the output
     * @param array $data
     * @param string $filename
     * @return string
     */
    private function apply_admin_theme($data, $filename) {

        $output = "";

        $admin_file = null;
        if ($filename != "") {
            $admin_file = plugin_dir_path( __FILE__ ) . "partials/{$filename}";
        }

        $message = __( 'Nothing to display', $this->plugin_name );
        if (isset($data['message'])) {
            $message = $data['message'];
        }

        ob_start();
        include $admin_file;
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    /**
     *  Save the plugin options
     *
     * @since    1.0.0
     */
    public function options_update() {

        register_setting( $this->plugin_name , $this->plugin_name, array($this, 'validate') );
    }

    /**
     * Validate all options fields
     * @param array $input
     * @return array
     * @since 1.0.0
     */
    public function validate( $input ) {

        $valid = array();

        $valid['css_aggregate'] = Tools::get( $input, 'css_aggregate', null );
        $valid['css_minify']    = Tools::get( $input, 'css_minify', null );
        $valid['js_aggregate']  = Tools::get( $input, 'js_aggregate', null );
        $valid['js_minify']     = Tools::get( $input, 'js_minify', null );
        $valid['css_enabled']   = Tools::get( $input, 'css_enabled', 0 );
        $valid['js_enabled']    = Tools::get( $input, 'js_enabled', 0 );

        return $valid;
    }

}
