<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://vectoricons.net
 * @since      1.0.0
 *
 * @package    Technify
 * @subpackage Technify/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Technify
 * @subpackage Technify/public
 * @author     Scott Lewis <scott@vectoricons.net>
 */
class Technify_Public {

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
     * Whether or not CSS aggregation & minification is enabled.
     * @var bool
     */
	public $css_enabled;

    /**
     * Whether or not JS aggregation & minification is enabled.
     * @var bool
     */
	public $js_enabled;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$options = get_option( $this->plugin_name );

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
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		if ( $this->css_enabled ) {
            wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/technify-public.css', array(), $this->version, 'all' );
            wp_enqueue_style( $this->plugin_name . '-styles-all' , plugin_dir_url( __FILE__ ) . 'css/styles-all.php', array(), null, 'all' );
            add_action( 'wp_print_styles', array( $this, 'dequeue_styles' ) );
        }
	}

	public function dequeue_styles() {

        global $wp_styles;

        $Compressor = Compressor::singleton();

	    $unload =  array_values($Compressor->get_styles('minify'));
        $unload =  array_merge( $unload, array_values($Compressor->get_styles('aggregate')) );

        foreach ( $wp_styles->registered as $style ) {

            $url = wp_parse_url( $style->src );
            $path = Tools::get( $url, 'path' );
            if ( in_array( $path, $unload )) {
                wp_dequeue_style( $style->handle );
                wp_deregister_style( $style->handle );
            }
        }
    }

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		if ( $this->js_enabled ) {
            wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/technify-public.js', array( 'jquery' ), $this->version, false );
            wp_enqueue_script( $this->plugin_name . '-scripts-all', plugin_dir_url( __FILE__ ) . 'js/scripts-all.php', array(), null, false );
            add_action( 'wp_print_scripts', array( $this, 'dequeue_scripts' ) );
        }
	}

    public function dequeue_scripts() {

        global $wp_scripts;

        $Compressor = Compressor::singleton();

        $unload =  array_values($Compressor->get_scripts('minify'));
        $unload =  array_merge( $unload, array_values($Compressor->get_scripts('aggregate')) );

        foreach ( $wp_scripts->registered as $style ) {

            $url = wp_parse_url( $style->src );
            $path = Tools::get( $url, 'path' );
            if ( in_array( $path, $unload )) {
                wp_dequeue_script( $style->handle );
                wp_deregister_script( $style->handle );
            }
        }
    }
}
