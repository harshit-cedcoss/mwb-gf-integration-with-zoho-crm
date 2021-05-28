<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the settings page.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    MWB_GF_Integration_with_ZOHO_CRM
 * @subpackage MWB_GF_Integration_with_ZOHO_CRM/admin/partials/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( isset( $_POST['mwb_zgf_submit_setting'] ) ) {

	/* Nonce verification */
	check_admin_referer( 'mwb_zoho_gf_setting', 'zoho_gf_setting_nonce' );

	$formdata = ! empty( $_POST['mwb_setting'] ) ? map_deep( wp_unslash( $_POST['mwb_setting'] ), 'sanitize_text_field' ) : '';
	$response = apply_filters( 'mwb_zgf_save', $formdata, 'setting' );
}

$option = get_option( 'mwb_zgf_setting', Zoho_Gf_Helper::mwb_zgf_default_settings() );
	echo '<pre>'; print_r( $option ); echo '</pre>';
?>

<div class="mwb_zgf__account-wrap">

	<div class="mwb_zgf__logo-wrap">
		<div class="mwb_zgf__logo-zoho">
			<img src="<?php echo esc_url( ZOHO_GF_INTEGRATION_URL . 'admin/images/zoho-logo.png' ); ?>" alt="<?php esc_html_e( 'Zoho', 'mwb-gf-integration-with-zoho-crm' ); ?>">
		</div>
		<div class="mwb_zgf__logo-contact">
			<img src="<?php echo esc_url( ZOHO_GF_INTEGRATION_URL . 'admin/images/contact-form.svg' ); ?>" alt="<?php esc_html_e( 'Zoho', 'mwb-gf-integration-with-zoho-crm' ); ?>">
		</div>
	</div>

	<?php
	if ( ! empty( $response ) && is_array( $response ) ) {

		if ( array_key_exists( 'email_error', $response ) ) {
			Zoho_Gf_Integration_Admin::mwb_zgf_notices( $response['email_error']['class'], $response['email_error']['message'] );
		}

		if ( array_key_exists( 'db_response', $response ) ) {
			Zoho_Gf_Integration_Admin::mwb_zgf_notices( $response['db_response']['class'], $response['db_response']['message'] );
		}

		if ( array_key_exists( 'error', $response ) ) {
			Zoho_Gf_Integration_Admin::mwb_zgf_notices( $response['error']['class'], $response['error']['message'] );
		}
	}
	?>

	<form method="post" id="mwb_zgf_settings_form">
		<?php wp_nonce_field( 'mwb_zoho_gf_setting', 'zoho_gf_setting_nonce' ); ?>
		<div class="mwb_zgf_table_wrapper">
			<table class="mwb_zgf_table">
				<tbody>

					<!-- Enable logs start -->
					<tr>
						<th>
							<label><?php esc_html_e( 'Enable logs', 'mwb-gf-integration-with-zoho-crm' ); ?></label>
						</th>

						<td>
							<?php
							$desc = esc_html__( 'Enable logging of all the forms data sent over zoho', 'mwb-gf-integration-with-zoho-crm' );
							echo esc_html( Zoho_Gf_Integration_Admin::mwb_zgf_tooltip( $desc ) );

							$enable_logs = ! empty( $option['enable_logs'] ) ? sanitize_text_field( wp_unslash( $option['enable_logs'] ) ) : 'no';
							?>
							<input type="checkbox" name="mwb_setting[enable_logs]" value="yes" <?php checked( 'yes', $enable_logs ); ?>>
						</td>
					</tr>
					<!-- Enable logs end-->

					<!-- Data delete start -->
					<tr>
						<th>
							<label><?php esc_html_e( 'Plugin Data', 'mwb-gf-integration-with-zoho-crm' ); ?></label>
						</th>

						<td>
							<?php
							$desc = esc_html__( 'Enable plugin data delete at uninstall', 'mwb-gf-integration-with-zoho-crm' );
							echo esc_html( Zoho_Gf_Integration_Admin::mwb_zgf_tooltip( $desc ) );

							$data_delete = ! empty( $option['data_delete'] ) ? sanitize_text_field( wp_unslash( $option['data_delete'] ) ) : 'no';
							?>
							<input type="checkbox" name="mwb_setting[data_delete]" value="yes"  <?php checked( 'yes', $data_delete ); ?>>
						</td>
					</tr>
					<!-- data delete end -->

					<!-- Restore Entry start -->
					<tr>
						<th>
							<label><?php esc_html_e( 'Restore Entry', 'mwb-gf-integration-with-zoho-crm' ); ?></label>
						</th>

						<td>
							<?php
							$desc = esc_html__( 'Send Entry data to ZOHO CRM if entry is restored in Gravity Forms', 'mwb-gf-integration-with-zoho-crm' );
							echo esc_html( Zoho_Gf_Integration_Admin::mwb_zgf_tooltip( $desc ) );

							$restore_entry = ! empty( $option['restore_entry'] ) ? sanitize_text_field( wp_unslash( $option['restore_entry'] ) ) : 'no';
							?>
							<input type="checkbox" name="mwb_setting[restore_entry]" value="yes"  <?php checked( 'yes', $restore_entry ); ?>>
						</td>
					</tr>
					<!-- Restore Entry end -->

					<!-- Update Entry start -->
					<tr>
						<th>
							<label><?php esc_html_e( 'Update Entry to ZOHO', 'mwb-gf-integration-with-zoho-crm' ); ?></label>
						</th>

						<td>
							<?php
							$desc = esc_html__( 'Update Entry data to ZOHO CRM when updated in the Gravity Forms', 'mwb-gf-integration-with-zoho-crm' );
							echo esc_html( Zoho_Gf_Integration_Admin::mwb_zgf_tooltip( $desc ) );

							$update_entry = ! empty( $option['update_entry'] ) ? sanitize_text_field( wp_unslash( $option['update_entry'] ) ) : 'no';
							?>
							<input type="checkbox" name="mwb_setting[update_entry]" value="yes"  <?php checked( 'yes', $update_entry ); ?>>
						</td>
					</tr>
					<!-- Update Entry end -->

					<!-- Delete entry start -->
					<tr>
						<th>
							<label><?php esc_html_e( 'Delete Entry from ZOHO', 'mwb-gf-integration-with-zoho-crm' ); ?></label>
						</th>

						<td>
							<?php
							$desc = esc_html__( 'Delete Entry data from the ZOHO CRM when deleted from the Gravity Forms', 'mwb-gf-integration-with-zoho-crm' );
							echo esc_html( Zoho_Gf_Integration_Admin::mwb_zgf_tooltip( $desc ) );

							$delete_entry = ! empty( $option['delete_entry'] ) ? sanitize_text_field( wp_unslash( $option['delete_entry'] ) ) : 'no';
							?>
							<input type="checkbox" name="mwb_setting[delete_entry]" value="yes"  <?php checked( 'yes', $delete_entry ); ?>>
						</td>
					</tr>
					<!-- Delete Entry end -->

					<!-- Enable email notif start -->
					<tr>
						<th>
							<label><?php esc_html_e( 'Email notification', 'mwb-gf-integration-with-zoho-crm' ); ?></label>
						</th>

						<td>
							<?php
								$desc = esc_html__( 'Enable email notification on errors', 'mwb-gf-integration-with-zoho-crm' );
								echo esc_html( Zoho_Gf_Integration_Admin::mwb_zgf_tooltip( $desc ) );

								$enable_notif = ! empty( $option['enable_notif'] ) ? sanitize_text_field( wp_unslash( $option['enable_notif'] ) ) : 'no';
							?>
							<input type="checkbox" name="mwb_setting[enable_notif]" value="yes" <?php checked( 'yes', $enable_notif ); ?> >
						</td>
					</tr>
					<!-- Enable email notif end -->

					<!-- Email field start -->
					<tr >
						<th>
						</th>
						<td>
							<div id="mwb_zgf_email_notif" class="<?php echo esc_attr( ( 'yes' != $enable_notif ) ? 'is_hidden' : '' ); // @codingStandardsIgnoreLine ?>">
								<?php
									$desc = esc_html__( 'Enter email address. An email will be sent if any sort of error occurs', 'mwb-gf-integration-with-zoho-crm' );
									echo esc_html( Zoho_Gf_Integration_Admin::mwb_zgf_tooltip( $desc ) );

									$email_notif = ! empty( $option['email_notif'] ) ? sanitize_email( wp_unslash( $option['email_notif'] ) ) : '';
								?>
								<input type="email" name="mwb_setting[email_notif]" value="<?php echo esc_html( $email_notif ); ?>" >
							</div>
						</td>
					</tr>	
					<!--Email field end  -->

					<!-- Delete logs start -->
					<tr>
						<th>
							<label><?php esc_html_e( 'Delete logs after N days', 'mwb-gf-integration-with-zoho-crm' ); ?></label>
						</th>

						<td>
							<?php
							$desc = esc_html__( 'This will delete the logs data after N no. of days', 'mwb-gf-integration-with-zoho-crm' );
							echo esc_html( Zoho_Gf_Integration_Admin::mwb_zgf_tooltip( $desc ) );

							$delete_logs = ! empty( $option['delete_logs'] ) ? sanitize_text_field( wp_unslash( $option['delete_logs'] ) ) : 30;
							?>
							<input type="number" name="mwb_setting[delete_logs]" value="<?php echo esc_html( $delete_logs ); ?>">
						</td>
					</tr>
					<!-- Delete logs end -->

					<!-- Save settings start -->
					<tr>
						<th>
						</th>
						<td>
							<input type="submit" name="mwb_zgf_submit_setting" class="mwb_zgf_submit_setting" value="<?php esc_html_e( 'Save', 'mwb-gf-integration-with-zoho-crm' ); ?>" >
						</td>
					</tr>
					<!-- Save settings end -->

				</tbody>
			</table>
		</div>
	</form>
</div>
