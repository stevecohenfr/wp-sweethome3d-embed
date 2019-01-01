<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://thevagabonds.fr
 * @since      1.0.0
 *
 * @package    Sweethome3dembed
 * @subpackage Sweethome3dembed/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sweethome3dembed
 * @subpackage Sweethome3dembed/public
 * @author     Steve Cohen <cohensteve@hotmail.fr>
 */
class Sweethome3dembed_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	private $database;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sweethome3dembed-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sweethome3dembed-public.js', array( 'jquery' ), $this->version, false );

        wp_enqueue_script($this->plugin_name.'big', plugin_dir_url( __FILE__ ) . 'js/lib/big.min.js', array(), $this->version, false);
        wp_enqueue_script($this->plugin_name.'gl-matrix', plugin_dir_url( __FILE__ ) . 'js/lib/gl-matrix-min.js', array(), $this->version, false);
        wp_enqueue_script($this->plugin_name.'jszip', plugin_dir_url( __FILE__ ) . 'js/lib/jszip.min.js', array(), $this->version, false);
        wp_enqueue_script($this->plugin_name.'core', plugin_dir_url( __FILE__ ) . 'js/lib/core.min.js', array(), $this->version, false);
        wp_enqueue_script($this->plugin_name.'geom', plugin_dir_url( __FILE__ ) . 'js/lib/geom.min.js', array(), $this->version, false);
        wp_enqueue_script($this->plugin_name.'triangulator', plugin_dir_url( __FILE__ ) . 'js/lib/triangulator.min.js', array(), $this->version, false);
        wp_enqueue_script($this->plugin_name.'viewmodel', plugin_dir_url( __FILE__ ) . 'js/lib/viewmodel.min.js', array(), $this->version, false);
        wp_enqueue_script($this->plugin_name.'viewhome', plugin_dir_url( __FILE__ ) . 'js/lib/viewhome.min.js', array(), $this->version, false);
	}

	public function init() {
        require_once plugin_dir_path( __FILE__ ) . '../includes/class-sweethome3dembed-database.php';
        $this->database = Sweethome3dembed_Database::getInstance();
        add_shortcode('sh3d', array(&$this, 'display_sh3d'));
    }

    function display_sh3d($atts) {
        $data = $this->database->getById($atts['id']);
        if ($data === null) {
            return '<span style="color:red;">This SweetHome3D is invalid. Use the shortcode that is displayed in admin SweetHome3D part (eg. [sh3d id=1])</span>';
        }
        $html = file_get_contents(plugin_dir_path( __FILE__ ) . "partials/sweethome3dembed-public-display.php");
        return strtr($html, array(
           "{{model_url}}" => $data['url']
        ));
    }

}
