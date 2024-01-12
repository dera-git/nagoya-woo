<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div class="woocommerce_product_option wc-metabox closed">
	<h3>
		<button type="button" class="remove_option button"><?php _e( 'Remove', 'custom-variation' ); ?></button>
		<div class="handlediv" title="<?php _e( 'Click to toggle', 'custom-variation' ); ?>"></div>
		<strong><?php _e( 'Option', 'custom-variation' ); ?> <span class="group_name"><?php if ( $option['name'] ) echo '"' . esc_attr( $option['name'] ) . '"'; ?></span> &mdash; </strong>
		<select name="product_option_type[<?php echo $variation_id; ?>][<?php echo $loop; ?>]" class="product_option_type">
			<option <?php selected('custom_field', $option['type']); ?> value="custom_field"><?php _e('Custom input (text field)', 'custom-variation'); ?></option>
			<option <?php selected('custom_textarea', $option['type']); ?> value="custom_textarea"><?php _e('Custom input (text area)', 'custom-variation'); ?></option>
		</select>
		<input type="hidden" name="product_option_position[<?php echo $variation_id; ?>][<?php echo $loop; ?>]" class="product_option_position" value="<?php echo $loop; ?>" />
	</h3>
	<table cellpadding="0" cellspacing="0" class="wc-metabox-content" style="display:none;">
		<tbody>
			<tr>
				<td class="option_name" width="50%">
					<label for="option_name_<?php echo $loop; ?>"><?php _e( 'Option name( must be unique )', 'custom-variation' ); ?></label>
					<input type="text" id="option_name_<?php echo $loop; ?>" name="product_option_name[<?php echo $variation_id; ?>][<?php echo $loop; ?>]" value="<?php echo esc_attr( $option['name'] ) ?>" />
				</td>
				<td class="option_required" width="50%">
					<label for="option_required_<?php echo $loop; ?>"><?php _e( 'Required fields?', 'custom-variation' ); ?></label>
					<input type="checkbox" id="option_required_<?php echo $loop; ?>" name="product_option_required[<?php echo $variation_id; ?>][<?php echo $loop; ?>]" <?php checked( $option['required'], 1 ) ?> />
				</td>
			</tr>
			<tr>
				<td class="data" colspan="3">
					<table cellspacing="0" cellpadding="0">
						<thead>
							<tr>
								<th><?php _e('Option Label', 'custom-variation'); ?></th>
								<th class="price_column"><?php _e('Option Price', 'custom-variation'); ?></th>
								<th class="minmax_column"><?php _e('Max character length', 'custom-variation'); ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><input class="clear_class<?php echo $variation_id.$loop; ?>" type="text" name="product_option_label[<?php echo $variation_id; ?>][<?php echo $loop; ?>]" value="<?php echo esc_attr($option['label']) ?>" placeholder="<?php _e('Label', 'custom-variation'); ?>" /></td>
								<td class="price_column"><input class="clear_class<?php echo $variation_id.$loop; ?>" type="number" min="0" name="product_option_price[<?php echo $variation_id; ?>][<?php echo $loop; ?>]" value="<?php echo esc_attr( wc_format_localized_price( $option['price'] ) ) ? esc_attr( wc_format_localized_price( $option['price'] ) ):'0'; ?>" placeholder="0.00" class="wc_input_price" /></td>
								<td class="minmax_column"><input class="clear_class<?php echo $variation_id.$loop; ?>" type="number" name="product_option_max[<?php echo $variation_id; ?>][<?php echo $loop; ?>]" value="<?php echo isset($option['max'])?esc_attr( $option['max'] ):1; ?>" placeholder="N/A" min="1" step="any" /></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<style>
.product_option_type {

    margin-left: 10px !important;

}
.wc-metaboxes-wrapper .wc-metabox h3 strong {
    float: left;

}
</style>