<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the header of feeds section.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    MWB_GF_Integration_with_ZOHO_CRM
 * @subpackage MWB_GF_Integration_with_ZOHO_CRM/includes/framework/templates/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// echo '<pre>'; print_r( get_current_screen() ); echo '</pre>';
?>

<div class="mwb_zgf__feeds-wrap">
	<div class="mwb-zgf__logo-wrap">
		<div class="mwb-zgf__logo-zoho">
			<img src="<?php echo esc_url( ZOHO_GF_INTEGRATION_URL . 'admin/images/zoho-logo.png' ); ?>" alt="<?php esc_html_e( 'Zoho', 'zoho-gf-integration' ); ?>">
		</div>
		<div class="mwb-zgf__logo-contact">
			<img src="<?php echo esc_url( ZOHO_GF_INTEGRATION_URL . 'admin/images/contact-form.svg' ); ?>" alt="<?php esc_html_e( 'Zoho', 'zoho-gf-integration' ); ?>">
		</div>
	</div>
