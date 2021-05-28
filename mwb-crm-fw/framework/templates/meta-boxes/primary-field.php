<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the primary field of feeds section.
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

?>
<div id="mwb-primary-field-section-wrapper"  class="mwb-feeds__content  mwb-content-wrap row-hide">
	<a class="mwb-feeds__header-link active">
		<?php esc_html_e( 'Primary Field', 'zoho-cf7-integration' ); ?>
	</a>
	<?php
		$desc = esc_html__( 'Select a unique identifier, if you want to update a pre-existing record', 'zoho-cf7-integration' );

		Zoho_Gf_Integration_Admin::mwb_zgf_tooltip( $desc );
	?>
	<div class="mwb-feeds__meta-box-main-wrapper">
		<div class="mwb-feeds__meta-box-wrap">
			<div class="mwb-form-wrapper">
				<label for="primary_field"><?php esc_html_e( 'Select Primary Field', 'zoho-cf7-integration' ); ?></label>
				<select id="primary-field-select" name="primary_field" primary_field="<?php echo esc_attr( $params['primary_field'] ); ?>" ></select>
				<p class="mwb-description">
					<?php
						esc_html_e( 'Please select a field which should be used as "primary key" to update an existing record.', 'zoho-cf7-integration' );
					?>
				</p>
				<p class="mwb-description">
					<?php
						esc_html_e( 'Make sure "Do not allow duplicate values" is checked in property settings, in order to prevent duplicate record creation.', 'zoho-cf7-integration' );
					?>
				</p>
			</div>
		</div>
	</div>
</div>
