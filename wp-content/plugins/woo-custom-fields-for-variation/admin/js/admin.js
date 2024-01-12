jQuery( document ).ready( function($) {
		// alert(22);
	(function(){
		
			$('#woocommerce-product-data').on('woocommerce_variations_loaded', function(event) {
				
				show_hide_function();
		
				option_price_function();
				
				condition_logic_script();
				
			});
		
		
			
	})();
	
	
	function show_hide_function() 
	{
		
		$('.woocommerce_product_option_main .product_option_type').each( function() {
			
			var product_option_type =  $(this).val();
	
			if( product_option_type == 'custom_file' || product_option_type == 'custom_checkbox' || product_option_type == 'custom_radio' || product_option_type == 'custom_select')
			{
				
				
				if(product_option_type == 'custom_radio' ||  product_option_type == 'custom_checkbox'){
									
					jQuery(this).closest('.woocommerce_product_option_main').find('.phoen_replace_image').css('display','table-cell');
					
				}else{
					
					jQuery(this).closest('.woocommerce_product_option_main').find('.phoen_replace_image').hide();
					
				}
				
				jQuery(this).closest('.woocommerce_product_option_main').find('td.minmax_column, th.minmax_column').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.phoen_option_desc_hide').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('th.minmaxq_column').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.product_option_label').removeAttr("type");
											
				jQuery(this).closest('.woocommerce_product_option_main').find('.product_option_label').prop('type', 'text');
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.product_option_label').removeAttr("min");
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.price_column_pp').css("display","none");

				jQuery(this).closest('.woocommerce_product_option_main').find('th.price_column_label').show();

				jQuery(this).closest('.woocommerce_product_option_main').find('th.price_column_qp').hide();

				jQuery(this).closest('.woocommerce_product_option_main').find('th.price_columnn').show();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('td.data table').show();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('td.pheo_price_columnn').hide();
				
				if(product_option_type == 'custom_file' ){
					
					jQuery(this).closest('.woocommerce_product_option_main').find('.product_option_default').hide();
					
				}else{
					
					jQuery(this).closest('.woocommerce_product_option_main').find('.product_option_default').show();
					
				}			
								
			}else if(product_option_type == 'color_picker' ){
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.phoen_option_desc_hide').show();
									
				jQuery(this).closest('.woocommerce_product_option_main').find('td.data table').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('td.pheo_price_columnn').show();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('td.minmax_column, th.minmax_column').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('th.minmaxq_column').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.qty_type_sh').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.price_column_pp').css("display","none");

				jQuery(this).closest('.woocommerce_product_option_main').find('th.price_column_label').show();

				jQuery(this).closest('.woocommerce_product_option_main').find('th.price_column_qp').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('th.price_columnn').show();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('td.phoen_datetime_main').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('tr.phoen_range_main').hide();
				
				
			}else if(product_option_type == 'datetime_picker' ){
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.phoen_option_desc_hide').show();
									
				jQuery(this).closest('.woocommerce_product_option_main').find('td.data table').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('td.pheo_price_columnn').show();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('td.phoen_datetime_main').show();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('tr.phoen_range_main').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('td.minmax_column, th.minmax_column').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('th.minmaxq_column').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.qty_type_sh').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.price_column_pp').css("display","none");

				jQuery(this).closest('.woocommerce_product_option_main').find('th.price_column_label').show();

				jQuery(this).closest('.woocommerce_product_option_main').find('th.price_column_qp').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('th.price_columnn').show();
				
				
			}else if(product_option_type == 'range_picker' ){
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.phoen_option_desc_hide').show();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('td.data table').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('td.pheo_price_columnn').show();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('tr.phoen_range_main').show();
											
				jQuery(this).closest('.woocommerce_product_option_main').find('td.phoen_datetime_main').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('td.minmax_column, th.minmax_column').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('th.minmaxq_column').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.qty_type_sh').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.price_column_pp').css("display","none");

				jQuery(this).closest('.woocommerce_product_option_main').find('th.price_column_label').show();

				jQuery(this).closest('.woocommerce_product_option_main').find('th.price_column_qp').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('th.price_columnn').show();
								
				
			}
			
			else if( product_option_type == 'quantity_type' ) 
			{
				
				
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.phoen_option_desc_hide').show();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('th.minmaxq_column').css('display','table-cell');
				
				jQuery(this).closest('.woocommerce_product_option_main').find('td.minmax_column').show();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('th.minmax_column').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.qty_type_sh').show();

			//	jQuery(this).closest('.woocommerce_product_option_main').find('.product_option_default').show();
				
				var option_qty_type = jQuery(this).closest('.woocommerce_product_option_main').find('.option_qty_type').val();
				
				if( option_qty_type == 'custom_checkbox' || option_qty_type == 'custom_radio' || option_qty_type == 'custom_select'  )
				{
					
					if(option_qty_type == 'custom_radio' ||  option_qty_type == 'custom_checkbox'){
									
						jQuery(this).closest('.woocommerce_product_option_main').find('.phoen_replace_image').css('display','table-cell');
						
					}else{
						
						jQuery(this).closest('.woocommerce_product_option_main').find('.phoen_replace_image').hide();
						
					}
					
					jQuery(this).closest('.woocommerce_product_option_main').find('.product_option_default').show();
					
					jQuery(this).closest('.woocommerce_product_option_main').find('td.minmax_column, th.minmax_column').hide();
					
					jQuery(this).closest('.woocommerce_product_option_main').find('th.minmaxq_column').hide();

					jQuery(this).closest('.woocommerce_product_option_main').find('.product_option_label').removeAttr("type");
												
					jQuery(this).closest('.woocommerce_product_option_main').find('.product_option_label').prop('type', 'number');
					
					jQuery(this).closest('.woocommerce_product_option_main').find('.product_option_label').prop('min', '1');
					
					jQuery(this).closest('.woocommerce_product_option_main').find('.price_column_pp').css("display","none");
					
					jQuery(this).closest('.woocommerce_product_option_main').find('th.price_column_label').hide();
					
					jQuery(this).closest('.woocommerce_product_option_main').find('th.price_columnn').hide();

					jQuery(this).closest('.woocommerce_product_option_main').find('th.price_column_qp').css('display','table-cell');
					
				}
				else
				{
					
					jQuery(this).closest('.woocommerce_product_option').find('.product_option_default').hide();
					
					jQuery(this).closest('.woocommerce_product_option_main').find('.phoen_replace_image').hide();
					
					jQuery(this).closest('.woocommerce_product_option_main').find('td.minmax_column, th.minmax_column').show();
					
					jQuery(this).closest('.woocommerce_product_option_main').find('th.minmaxq_column').hide();
					
					jQuery(this).closest('.woocommerce_product_option_main').find('.product_option_label').removeAttr("type");
											
					jQuery(this).closest('.woocommerce_product_option_main').find('.product_option_label').prop('type', 'text');
					
					jQuery(this).closest('.woocommerce_product_option_main').find('.product_option_label').removeAttr("min");
					
					jQuery(this).closest('.woocommerce_product_option_main').find('.price_column_pp').css('display','table-cell');
					
					jQuery(this).closest('.woocommerce_product_option_main').find('th.price_column_label').show();

					jQuery(this).closest('.woocommerce_product_option_main').find('th.price_column_qp').hide();
					
					jQuery(this).closest('.woocommerce_product_option_main').find('th.price_columnn').show();
					
					jQuery(this).closest('.woocommerce_product_option_main').find('td.pheo_price_columnn').hide();
				
				}
				
			}
			else
			{
				
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.phoen_replace_image').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('td.pheo_price_columnn').hide();
					
				jQuery(this).closest('.woocommerce_product_option_main').find('.phoen_option_desc_hide').show();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('td.minmax_column, th.minmax_column').show();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('th.minmaxq_column').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.product_option_label').removeAttr("type");
											
				jQuery(this).closest('.woocommerce_product_option_main').find('.product_option_label').prop('type', 'text');
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.product_option_label').removeAttr("min");
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.price_column_pp').css('display','table-cell');

				jQuery(this).closest('.woocommerce_product_option_main').find('th.price_column_label').show();

				jQuery(this).closest('.woocommerce_product_option_main').find('th.price_column_qp').hide();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('th.price_columnn').show();
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.product_option_default').hide();
				
			}

		});
		
	}
	
	function option_price_function()
	{
		
		$('.woocommerce_product_option_main .option_price_type').each( function() {
										
			var option_price_type =  jQuery(this).val();
						
			var option_qty_type = jQuery(this).closest('.woocommerce_product_option_main').find('.option_qty_type').val();
			
			var product_option_type = jQuery(this).closest('.woocommerce_product_option_main').find('.product_option_type').val();
			
			if( option_price_type == 1 )
			{
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.price_column_pp').css('display','table-cell');
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.price_columnn').css("display","none");

				if(product_option_type == 'quantity_type' )
				{
					
					jQuery(this).closest('.woocommerce_product_option_main').find('.price_column_qp').last().text('Per Quantity % Price');
					
					jQuery(this).closest('.woocommerce_product_option_main').find('.price_column_pp').css("display","none");
					
					if( option_qty_type != 'custom_field' )
					{
						
						jQuery(this).closest('.woocommerce_product_option_main').find('th.minmax_column').css("display","none");
						
						jQuery(this).closest('.woocommerce_product_option_main').find('th.price_column_qp').css("display","");
						
						
					}
					else
					{
						
						jQuery(this).closest('.woocommerce_product_option_main').find('.price_column_pp').css('display','table-cell');
						
						jQuery(this).closest('.woocommerce_product_option_main').find('.price_column_qp').css("display","none");
					}
					
				}
				
			}
			else
			{
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.price_column_pp').css("display","none");
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.price_columnn').css("display","block");
				
				if(product_option_type == 'quantity_type' )
				{
					
					jQuery(this).closest('.woocommerce_product_option_main').find('.price_column_qp').last().text('Per Quantity Price');

					jQuery(this).closest('.woocommerce_product_option_main').find('.price_columnn').css("display","none");
				
					if( option_qty_type != 'custom_field' )
					{
						
						jQuery(this).closest('.woocommerce_product_option_main').find('.minmax_column').css("display","none");
						
						jQuery(this).closest('.woocommerce_product_option_main').find('.price_column_qp').css("display","");

					}
					else
					{
					
						jQuery(this).closest('.woocommerce_product_option_main').find('.price_column_qp').css("display","none");

						jQuery(this).closest('.woocommerce_product_option_main').find('.price_columnn').css("display","block");
						
					}
					
				}
				else
				{
					
					jQuery(this).closest('.woocommerce_product_option_main').find('.price_columnn').css("display","block");
				
					
				}
				
			}
		});
	}
	
	function condition_logic_script()
	{
		
		jQuery('#custom_tab_data').on( 'change', '.show_category_div_option', function() {
			
			if( ! $(this).is(':checked'))
			{
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.all_category_option_show_div').css('display','table-cell');
				
			}
			else
			{
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.all_category_option_show_div').css('display','none');
				
			}
		
		});
		
		
		jQuery('#variable_product_options').on( 'change', '.all_custom_use', function() {
			
			if( $(this).val() == 'yes')
			{
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.all_custom_s_h_div').show();
				
			}
			else
			{
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.all_custom_s_h_div').hide();
				
			}
		
		});
	
	
		jQuery('#variable_product_options').on( 'change', '.all_custom_field_dd', function() {
			
			//var yyua=jQuery(this).closest('.woocommerce_product_option_main').att('id');
			
			var select_data = $(this).children('option:selected').attr('data-otype');
			
			
			
			var select_data_val = $(this).val();
			
			//console.log(select_data_val);

			var all_custom_con = $(this).next().find('.all_custom_con').val();
			
				jQuery(this).nextAll('.all_custom_value_div').first().find('.all_custom_field').each(function(index) {

					$(this).css('display','none');
					
					$(this).prop('disabled',true);
					
				});

			if( all_custom_con == 'isnotempty' || all_custom_con == 'isempty' )
			{
				
				jQuery(this).nextAll('.all_custom_value_div').first().find('.all_custom_field_'+select_data_val).css('display','none');
				
				jQuery(this).nextAll('.all_custom_value_div').first().find('.all_custom_field_'+select_data_val).prop('disabled',false);
				
			}
			else
			{
				
				jQuery(this).nextAll('.all_custom_value_div').first().find('.all_custom_field_'+select_data_val).css('display','inline');
				
				jQuery(this).nextAll('.all_custom_value_div').first().find('.all_custom_field_'+select_data_val).prop('disabled',false);

			}
		
		});
	 
		jQuery('#variable_product_options').on( 'change', '.all_custom_con', function() {
			
			var select_data_val = $(this).val();
			
			var all_custom_con = $(this).parent().prev().val();
			
			jQuery(this).parent().nextAll('.all_custom_value_div').first().find('.all_custom_field').each(function(index) {

					$(this).css('display','none');
					
					$(this).prop('disabled',true);
					
			});
			
			if( select_data_val == 'isnotempty' || select_data_val == 'isempty' )
			{
				
				jQuery(this).parent().nextAll('.all_custom_value_div').first().find('.all_custom_field_'+all_custom_con).css('display','none');
				
				jQuery(this).parent().nextAll('.all_custom_value_div').first().find('.all_custom_field_'+all_custom_con).prop('disabled',false);
				
			}
			else
			{
				
				jQuery(this).parent().nextAll('.all_custom_value_div').first().find('.all_custom_field_'+all_custom_con).css('display','inline');
				
				jQuery(this).parent().nextAll('.all_custom_value_div').first().find('.all_custom_field_'+all_custom_con).prop('disabled',false);

			}
			
		});
		
		
		$('.woocommerce_product_option_main .all_custom_s_h_div').each( function() {
			
			var use_logic = jQuery(this).closest('.woocommerce_product_option_main').find('.all_custom_use').val();
				
			var all_custom_field_dd = jQuery(this).closest('.woocommerce_product_option_main').find('.all_custom_field_dd').val();
			
			if( use_logic == 'yes')
			{
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.all_custom_s_h_div').show();
				
			}
			else
			{
				
				jQuery(this).closest('.woocommerce_product_option_main').find('.all_custom_s_h_div').hide();
				
			}
			
			
			jQuery(this).closest('.woocommerce_product_option_main').find('.all_custom_con_main_div .all_custom_field_dd').each( function() {
				
				var dd_val = jQuery(this).val();

				var select_data = $(this).next().find('.all_custom_con').val();

				if( select_data == 'isnotempty' || select_data == 'isempty' )
				{
					
					jQuery(this).nextAll('.all_custom_value_div').first().find('.all_custom_field_'+dd_val).css('display','none');

				}
				else
				{
					
					jQuery(this).nextAll('.all_custom_value_div').first().find('.all_custom_field_'+dd_val).css('display','inline');
					
					jQuery(this).nextAll('.all_custom_value_div').first().find('.all_custom_field_'+dd_val).prop('disabled',false);

				}
				
			}); 
	
		});
		
	}
	jQuery( 'body' ).on( 'change', '#variable_product_options .woocommerce_variations :input', function(){
		
		var phoen_form_dat = jQuery(this).closest('form#post').serialize();
		
		var ajaxurl= 'admin-ajax.php';
				
			jQuery.post(
				
				ajaxurl, 
				{
					'action': 'phoen_variation_data_product',
					'form':phoen_form_dat
				}, 
				
				function(response){
					// alert(response);
					// jQuery('.phoen_arbpw_span_for_price').html(response);
				}
			);

	});
});