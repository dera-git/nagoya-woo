<?php
$required = isset($options['required'])?$options['required']:'';
$price 			= ($options['price'] != 0) ? ':- ('.wc_price( $options['price'] ).')' : '';
$option_name 	= isset($options['name'])?strtolower(str_replace(' ','-',$options['name'] )):'';
$entered_data = '';
if(isset($_POST['custom-variation'][sanitize_title( $options['name'] )])){

	$entered_data = isset($_POST['custom-variation'][sanitize_title( $options['name'] )])?$_POST['custom-variation'][sanitize_title( $options['name'] )]:'';
	$entered_data = str_replace('\"','"',$entered_data);
	$entered_data = str_replace("\'","'",$entered_data);	
} ?>
<p class="phoen_minss phoenwwe_<?php echo $variation_id; ?> form-row form-row-wide custom_<?php echo sanitize_title( $options['name'] ); ?>" style="display:none;">
	<?php if ( ! empty( $options['label'] ) ) :
		$show  = "<label>";
		$show .= wptexturize( $options['label'] ) . ' ' . $price; 
		$show .= ($required == 1) ? '<abbr title="required" class="required">*</abbr>' : '';
		$show .= "</label>";
	endif; ?>
	<?= _e($show) ?>
	<textarea class="input-text custom-variation custom_textarea" data-price="<?php echo $options['price'] ? $options['price'] : '0'; ?>" name="custom-variation[<?php echo $variation_id; ?>][<?php echo sanitize_title( $option_name ); ?>]"  <?php if ( ! empty( $options['max'] ) ) echo 'maxlength="' . $options['max'] .'"'; ?> ><?php if( ! empty($entered_data) ){ echo $entered_data; } ?></textarea>
</p>