<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Zoho_Gf_Integration
 * @subpackage Zoho_Gf_Integration/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<!-- Add Required templates -->
<?php do_action( 'mwb_zgf_header_start' ); ?>
<?php do_action( 'mwb_zgf_nav_tab' ); ?>
<?php do_action( 'mwb_zgf_output_screen' ); ?>
<?php do_action( 'mwb_zgf_header_end' ); ?>
