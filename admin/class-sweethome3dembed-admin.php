<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://thevagabonds.fr
 * @since      1.0.0
 *
 * @package    Sweethome3dembed
 * @subpackage Sweethome3dembed/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sweethome3dembed
 * @subpackage Sweethome3dembed/admin
 * @author     Steve Cohen <cohensteve@hotmail.fr>
 */
class Sweethome3dembed_Admin {

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

    public $customers_obj;

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

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sweethome3dembed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sweethome3dembed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sweethome3dembed-admin.css', array(), $this->version, 'all' );
        wp_enqueue_style('thickbox'); //Provides the styles needed for this window.
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sweethome3dembed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sweethome3dembed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sweethome3dembed-admin.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script('media-upload'); //Provides all the functions needed to upload, validate and give format to files.
        wp_enqueue_script('thickbox'); //Responsible for managing the modal window.
        wp_enqueue_script('script', plugins_url('upload.js', __FILE__), array('jquery'),'', true); //It will initialize the parameters needed to show the window properly.

	}

	public function init() {

    }

	public function allow_sh3d_extension($mime_types) {
        $mime_types['sh3d'] = 'application/sh3d';
        return $mime_types;
    }

	public function add_to_admin_menu() {
        $hook = add_menu_page( 'SweetHome3D', 'SweetHome3D', 'manage_options', 'sweethome3d-admin', array(&$this, 'sweethome3d_admin_page'), 'dashicons-tickets', 6  );
        add_action( "load-$hook", [ $this, 'screen_option' ] );
    }

    /**
     * Screen options
     */
    public function screen_option() {
        $option = 'per_page';
        $args   = [
            'label'   => 'Customers',
            'default' => 5,
            'option'  => 'customers_per_page'
        ];
        add_screen_option( $option, $args );
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/sweethome3dembed-admin-display-table.php';
        $this->customers_obj = new Customers_List();
    }

    function sweethome3d_admin_page() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/sweethome3dembed-admin-display.php';
    }

    public function set_screen( $status, $option, $value ) {
        return $value;
    }
}
