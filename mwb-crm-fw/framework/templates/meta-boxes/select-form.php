<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the select form section of feeds.
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

// $forms = Zoho_Cf7_Helper::get_contact_forms();
$forms = GFAPI::get_forms();
// echo '<pre>'; print_r( $forms ); echo '</pre>'; die('lklkl');

?>
<div class="mwb-feeds__content  mwb-content-wrap">
	<a class="mwb-feeds__header-link active">
		<?php esc_html_e( 'Select Form', 'mwb-gf-integration-with-zoho-crm' ); ?>
	</a>
	<?php
		$desc = esc_html__( 'Select the form you would like to integrate with zoho', 'mwb-gf-integration-with-zoho-crm' );

		Zoho_Gf_Integration_Admin::mwb_zgf_tooltip( $desc );
	?>
	<div class="mwb-feeds__meta-box-main-wrapper">
		<div class="mwb-feeds__meta-box-wrap">
			<div class="mwb-form-wrapper">
				<select name="crm_form" id="mwb_zgf_select_form" class="mwb-form__dropdown">
					<option value="-1"><?php esc_html_e( 'Select Form', 'mwb-gf-integration-with-zoho-crm' ); ?></option>
					<optgroup label="<?php esc_html_e( 'Gravity Forms', 'mwb-gf-integration-with-zoho-crm' ); ?>" ></optgroup>
					<?php if ( ! empty( $forms ) && is_array( $forms ) ) : ?>
						<?php foreach ( $forms as $key => $value ) : ?>
							<option value="<?php echo esc_html( $value['id'] ); ?>" <?php selected( $params['form'], $value['id'] ); ?>><?php echo esc_html( $value['title'] ); ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>
		</div>
	</div>
</div>
