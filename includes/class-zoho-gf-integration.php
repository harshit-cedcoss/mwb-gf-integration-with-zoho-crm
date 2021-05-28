<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    MWB_GF_Integration_with_ZOHO_CRM
 * @subpackage MWB_GF_Integration_with_ZOHO_CRM/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    MWB_GF_Integration_with_ZOHO_CRM
 * @subpackage MWB_GF_Integration_with_ZOHO_CRM/includes
 * @author     MakeWebBetter <https://makewebbetter.com>
 */
class Zoho_Gf_Integration {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Zoho_Gf_Integration_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'ZOHO_GF_INTEGRATION_VERSION' ) ) {
			$this->version = ZOHO_GF_INTEGRATION_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'zoho-gf-integration';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_template_manager_hooks();
		$this->define_feed_cpt_hooks();
		$this->define_ajax_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Zoho_Gf_Integration_Loader. Orchestrates the hooks of the plugin.
	 * - Zoho_Gf_Integration_i18n. Defines internationalization functionality.
	 * - Zoho_Gf_Integration_Admin. Defines all hooks for the admin area.
	 * - Zoho_Gf_Integration_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-zoho-gf-integration-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-zoho-gf-integration-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-zoho-gf-integration-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-zoho-gf-integration-public.php';

		/**
		 * The class responsible for defining all the templates that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-zoho-gf-template-manager.php';

		/**
		 * The class responsible for defining all the helper methods of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-zoho-gf-helper.php';

		/**
		 * The class responsible for all base api definitions of Zoho crm in the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'mwb-crm-fw/api/class-mwb-zgf-api-base.php';

		/**
		 * The class responsible for all zoho api definitions in the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'mwb-crm-fw/api/class-zgf-api.php';

		/**
		 * The class responsible for handling ajax requests.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-zgf-ajax-handler.php';

		/**
		 * The class responsible for handling of feeds cpt.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-zgf-feed-cpt.php';

		/**
		 * The class responsible for handling of connect framework.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'mwb-crm-fw/framework/class-mwb-zgf-connect-framework.php';

		$this->loader = new Zoho_Gf_Integration_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Zoho_Gf_Integration_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Zoho_Gf_Integration_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Zoho_Gf_Integration_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_filter( 'gform_addon_navigation', $plugin_admin, 'mwb_zoho_gf_add_submenu' );

		// Admin init processess.
		$this->loader->add_action( 'admin_init', $plugin_admin, 'admin_init_process' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'save_zoho_modules' );

		$this->loader->add_action( 'mwb_zgf_save', $plugin_admin, 'save_crm_settings', 10, 2 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Zoho_Gf_Integration_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'gform_after_submission', $plugin_public, 'get_form_entries', 10, 2 );

	}
	/**
	 * Register all of the hooks related to the template manager
	 *
	 * @return void
	 */
	private function define_template_manager_hooks() {

		$plugin_template_manager = new Zoho_GF_Template_Manager();

		$this->loader->add_action( 'mwb_zgf_header_start', $plugin_template_manager, 'render_header_content_start' );
		$this->loader->add_action( 'mwb_zgf_nav_tab', $plugin_template_manager, 'render_navigation_tab' );
		$this->loader->add_action( 'mwb_zgf_header_end', $plugin_template_manager, 'render_header_content_end' );
	}

	/**
	 * Register all hooks related to ajax request of the plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function define_ajax_hooks() {

		$ajax_cb = new Mwb_Zgf_Ajax_Handler();

		// All ajax callbacks.
		$this->loader->add_action( 'wp_ajax_mwb_zgf_ajax_request', $ajax_cb, 'mwb_zgf_ajax_callback' );
		// Data table callback.
		$this->loader->add_action( 'wp_ajax_get_datatable_logs', $ajax_cb, 'get_datatable_data_cb' );

	}

	/**
	 * Register all hooks related to the Feeds cpt of the plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function define_feed_cpt_hooks() {

		$cpt_module = new Mwb_Zgf_Feed_Cpt();

		// Register custom post type.
		$this->loader->add_action( 'init', $cpt_module, 'mwb_zgf_feeds_post' );
		// Save metadata.
		$this->loader->add_action( 'save_post', $cpt_module, 'save_feeds_data' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Zoho_Gf_Integration_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
