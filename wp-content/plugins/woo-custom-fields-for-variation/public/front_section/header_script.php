<script>
						
	var  woocommerce_custom_var_options_params = {
		
		currency_symbol 	: "<?php echo get_woocommerce_currency_symbol() ?>",
		op_show 			: "<?php _e('Options Total', 'custom-variation'); ?>",
		ft_show 			: "<?php _e('Final Total', 'custom-variation'); ?>",
		show_op 			: "<?php echo $this->woo_custom_var_option_optn_total ?>",
		show_ft 			: "<?php echo $this->woo_custom_var_option_fnl_total ?>",
		num_decimals 		: "<?php echo absint( get_option( 'woocommerce_price_num_decimals' ) ) ?>",
		decimal_separator 	: "<?php echo esc_attr( stripslashes( get_option( 'woocommerce_price_decimal_sep' ) ) ) ?>",
		thousand_separator 	: "<?php echo esc_attr( stripslashes( get_option( 'woocommerce_price_thousand_sep' ) ) ) ?>"
	}
		
</script>