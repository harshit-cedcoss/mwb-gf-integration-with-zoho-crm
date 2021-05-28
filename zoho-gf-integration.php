<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://makewebbetter.com
 * @since             1.0.0
 * @package           MWB_GF_Integration_with_ZOHO_CRM
 *
 * @wordpress-plugin
 * Plugin Name:       MWB GF Integration with ZOHO CRM
 * Plugin URI:        https://makewebbetter.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            MakeWebBetter
 * Author URI:        https://makewebbetter.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mwb-gf-integration-with-zoho-crm
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Check Plugin Dependency on Gravity Forms plugin
 *
 * @return array
 */
function mwb_zoho_gf_plugin_activation() {

	$active['status'] = false;
	$active['msg']    = 'gf_inactive';

	if ( true === mwb_zoho_gf_is_plugin_active( 'gravityforms/gravityforms.php' ) ) {
		$active['status'] = true;
		$active['msg']    = '';
	}

	return $active;
}

/**
 * Check if a particular plugin is active or not.
 *
 * @param string $slug Slug of the plugin to check if active or not.
 * @return boolean
 */
function mwb_zoho_gf_is_plugin_active( $slug = '' ) {

	if ( empty( $slug ) ) {
		return;
	}

	$active_plugins = get_option( 'active_plugins', array() );

	if ( is_multisite() ) {
		$active_plugins = array_merge( $active_plugins, get_option( 'active_sitewide_plugins', array() ) );
	}

	if ( in_array( $slug, $active_plugins, true ) || array_key_exists( $slug, $active_plugins ) ) {
		return true;
	} else {
		return false;
	}
}

$zoho_gf_is_plugin_active = mwb_zoho_gf_plugin_activation();

if ( true === $zoho_gf_is_plugin_active['status'] ) {

	/**
	 * Currently plugin version.
	 * Start at version 1.0.0 and use SemVer - https://semver.org
	 * Rename this for your plugin and update it as you release new versions.
	 */
	define( 'ZOHO_GF_INTEGRATION_VERSION', '1.0.0' );

	// Define all the necessary details of the plugin.

	define( 'ZOHO_GF_INTEGRATION_URL', plugin_dir_url( __FILE__ ) ); // Plugin URL directory path.

	define( 'ZOHO_GF_INTEGRATION_DIRPATH', plugin_dir_path( __FILE__ ) ); // Plugin filesystem directory path.

	define( 'ZOHO_GF_INTEGRATION_DIRPATH_ADMIN', plugin_dir_path( __FILE__ ) . 'admin/partials/' ); // Plugin filesystem path to admin.

	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-zoho-gf-integration-activator.php
	 */
	function activate_zoho_gf_integration() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-zoho-gf-integration-activator.php';
		Zoho_Gf_Integration_Activator::activate();
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-zoho-gf-integration-deactivator.php
	 */
	function deactivate_zoho_gf_integration() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-zoho-gf-integration-deactivator.php';
		Zoho_Gf_Integration_Deactivator::deactivate();
	}

	register_activation_hook( __FILE__, 'activate_zoho_gf_integration' );
	register_deactivation_hook( __FILE__, 'deactivate_zoho_gf_integration' );

	// Add settings link in plugin action links.
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'zoho_gf_settings_link' );

	/**
	 * Add settings link callback.
	 *
	 * @since 1.0.0
	 * @param string $links link to the admin area of the plugin.
	 */
	function zoho_gf_settings_link( $links ) {

		$plugin_links = array(
			'<a href="' . admin_url( 'admin.php?page=mwb_zoho_gf&tab=accounts' ) . '">' . esc_html__( 'Settings', 'mwb-gf-integration-with-zoho-crm' ) . '</a>',
		);

		return array_merge( $plugin_links, $links );
	}

	add_filter( 'plugin_row_meta', 'zoho_gf_important_links', 10, 2 );

	/**
	 * Add custom links.
	 *
	 * @param   string $links link to index file of plugin.
	 * @param   string $file index file of plugin.
	 *
	 * @since    1.0.0
	 */
	function zoho_gf_important_links( $links, $file ) {

		if ( strpos( $file, basename( __FILE__ ) ) !== false ) {

			$row_meta = array(
				'demo'    => '<a href="#" target="_blank"><img src="' . ZOHO_GF_INTEGRATION_URL . 'admin/images/Demo.svg" style="width: 20px;padding-right: 5px;"></i>' . esc_html__( 'Demo', 'zoho-cf7-integration' ) . '</a>',
				'doc'     => '<a href="#" target="_blank"><img src="' . ZOHO_GF_INTEGRATION_URL . 'admin/images/Documentation.svg" style="width: 20px;padding-right: 5px;"></i>' . esc_html__( 'Documentation', 'zoho-cf7-integration' ) . '</a>',
				'support' => '<a href="#" target="_blank"><img src="' . ZOHO_GF_INTEGRATION_URL . 'admin/images/Support.svg" style="width: 20px;padding-right: 5px;"></i>' . esc_html__( 'Support', 'zoho-cf7-integration' ) . '</a>',
			);

			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}
	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-zoho-gf-integration.php';

	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */
	function run_zoho_gf_integration() {

		$plugin = new Zoho_Gf_Integration();
		$plugin->run();

	}
	run_zoho_gf_integration();
} else {

	// Deactivate the plugin if Gravity forms is not active.
	add_action( 'admin_init', 'mwb_zoho_gf_activation_failure' );

	/**
	 * Deactivate the plugin.
	 */
	function mwb_zoho_gf_activation_failure() {

		deactivate_plugins( plugin_basename( __FILE__ ) );
	}

	// Add admin error notice.
	add_action( 'admin_notices', 'mwb_zoho_gf_activation_notice' );

	/**
	 * This function displays plugin activation error notices.
	 */
	function mwb_zoho_gf_activation_notice() {

		global $zoho_gf_is_plugin_active;

		// To hide Plugin activated notice.
		unset( $_GET['activate'] ); // @codingStandardsIgnoreLine

		?>

		<?php if ( 'gf_inactive' === $zoho_gf_is_plugin_active['msg'] ) { ?>

			<div class="notice notice-error is-dismissible">
				<p><strong><?php esc_html_e( 'Gravity Forms', 'mwb-gf-integration-with-zoho-crm' ); ?></strong><?php esc_html_e( ' is not activated, Please activate Gravity Forms first to activate ', 'mwb-gf-integration-with-zoho-crm' ); ?><strong><?php esc_html_e( ' Gravity Form - Zoho Integration', 'mwb-gf-integration-with-zoho-crm' ); ?></strong><?php esc_html_e( '.', 'mwb-gf-integration-with-zoho-crm' ); ?></p>
			</div>

			<?php
		}
	}
}
