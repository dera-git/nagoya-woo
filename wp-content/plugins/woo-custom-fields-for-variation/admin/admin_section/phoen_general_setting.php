<?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'] ) )  {

	$nonce_check = sanitize_text_field( $_POST['_wpnonce_general_setting'] );

	if ( wp_verify_nonce( $nonce_check, 'create_nonce_general_setting' ) ) {

		$phoen_enable_custom_option = isset($_POST['phoen_enable_custom_option'] )? sanitize_text_field(  $_POST['phoen_enable_custom_option'] ):0;

		$phoen_show_options_total 	= isset($_POST['phoen_show_options_total'] )? sanitize_text_field(  $_POST['phoen_show_options_total'] ):0;
		
		$phoen_show_final_total 	= isset($_POST['phoen_show_final_total'] )? sanitize_text_field(  $_POST['phoen_show_final_total'] ):0;

		update_option( 'woo_var_option_plugin', $phoen_enable_custom_option );
		update_option( 'woo_custom_var_option_optn_total', $phoen_show_options_total );
		update_option( 'woo_custom_var_option_fnl_total', $phoen_show_final_total );

		_e('<div class="updated notice is-dismissible below-h2" id="message"><p>Successfully Saved Data. </p></div>');
	
	}else{

		exit('Sorry..!! Nonce Didn\'t Verrify');
	}
}

$woo_var_option_plugin 				=  get_option( 'woo_var_option_plugin' );		
$woo_custom_var_option_optn_total 	=  get_option( 'woo_custom_var_option_optn_total' );
$woo_custom_var_option_fnl_total 	=  get_option( 'woo_custom_var_option_fnl_total' ); ?>

<div style="background:white;padding: 10px;"  class=" phoeniixx_phoe_book_wrap_profile_div">
	
	<form method="post" action="">

		<?php $get_nonce = wp_create_nonce( 'create_nonce_general_setting' ); ?>

		<input type="hidden" value="<?php echo $get_nonce; ?>" name="_wpnonce_general_setting" id="_wpnonce_general_setting" />
		
		<table class="form-table" >

			<!-- <h2 style="color: #58504d;border-bottom: 1px solid #ccbbb6;"><?= _e('General Options', 'custom-variation'); ?></h2> -->
			
			<tbody>	

				<tr class="user-nickname-wrap">

					<th>
						<label for="phoen_enable_custom_option"><?php _e('Enable Custom Options', 'custom-variation'); ?></label>
					</th>

					<td>
						<input type="checkbox" value="1" <?php if($woo_var_option_plugin == 1){ echo "checked"; }  ?> id="phoen_enable_custom_option" name="phoen_enable_custom_option" >
					</td>

				</tr>

				<tr class="user-nickname-wrap">

					<th>
						<label for="phoen_show_options_total"><?php _e('Show Options Total', 'custom-variation'); ?></label>
					</th>

					<td>
						<input type="checkbox" value="1" <?php if($woo_custom_var_option_optn_total == 1){ echo "checked"; } ?> id="phoen_show_options_total" name="phoen_show_options_total" >
					</td>

				</tr>

				<tr class="user-nickname-wrap">

					<th>
						<label for="phoen_show_final_total"><?php _e('Show Final Total', 'custom-variation'); ?></label>
					</th>

					<td>
						<input type="checkbox" value="1" <?php if($woo_custom_var_option_fnl_total == 1){ echo "checked"; } ?> id="phoen_show_final_total" name="phoen_show_final_total" >
					</td>

				</tr>

				<tr class="user-nickname-wrap">
					<td colspan="2">
						<input type="submit" value="Save" class="button button-primary" id="submit" name="submit">
					</td>
				</tr>
	
			</tbody>
			
		</table>
		
	</form>
	
</div>