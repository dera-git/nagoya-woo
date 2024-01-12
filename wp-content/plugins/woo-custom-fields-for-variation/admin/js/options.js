jQuery( document ).ready( function($) {
	
	
	
	custom_options_conditional_logics();
	
		$(this).trigger( 'show_options_final_total' );
	
	$(this).on( 'change', '.product-option-div input, .custom_textarea, input.qty ,.variations select, .reset_variations , .custom_select,.custom_range_picker', function() {
	
		$(this).trigger( 'show_options_final_total' );
	
		custom_options_conditional_logics();
		
	});
	
	$(this).on( 'found_variation', function( event, variation ) {
			
			var $variation_form = $(this);
			
			var $totals = $variation_form.find( '#product-options-total' );
				
			if ( $( variation.price_html ).find('.amount:last').size() ) {
				
		 		product_price = $( variation.price_html ).find('.amount:last').text();
				
				product_price = product_price.replace( woocommerce_custom_options_params.thousand_separator, '' );
				
				product_price = product_price.replace( woocommerce_custom_options_params.decimal_separator, '.' );
				
				product_price = product_price.replace(/[^0-9\.]/g, '');
				
				product_price = parseFloat( product_price );

				$totals.data( 'product-price', product_price );
				
			}
		
			$variation_form.trigger( 'show_options_final_total' );
	});
	
	

	$(this).find( '.variations select' ).change();

	function custom_options_conditional_logics() {
		
		var product_option_clogics = $('body').find('.product-option-clogics');
		
			product_option_clogics.each(function(i,elements){
				
			$(elements).each(function(j,el){
				
				$(el).each(function(theindex,theelement){
					
					var output = 0;
					
					var div_ele =  $(elements);

					var rules_if =  $(elements).data('rules-if');

					var rules_action =  $(elements).data('rules-action');
						
					var $this = $(this);
					
					matches  = $this.data('rules');
					//alert(matches);
					$.each(matches,function(i,match){
					
					
					
						if( jQuery('.custom_options_'+match.custom_field_dd).is('.custom_field') )
						{
							
							var sel_textbx = jQuery('.custom_options_'+match.custom_field_dd).val();
							
							
							
							if( match.custom_con == 'is' )
							{
								
								if(sel_textbx == match.custom_field )
								{
									
									output++;
									
								}
								
							}
							else if( match.custom_con == 'isnot' )
							{
								
								if( sel_textbx != match.custom_field )
								{
									
									output++;
									
								}
								 
							}
							else if( match.custom_con == 'isnotempty' )
							{
								
								if( sel_textbx != '' )
								{
									
									output++;
									
								}
									
							}
							else if( match.custom_con == 'isempty' )
							{
								
								if( sel_textbx == '' )
								{
									
									output++;
									
								}

							}
							
						}
						else if( jQuery('.custom_options_'+match.custom_field_dd).is('.custom_textarea') )
						{
							
							var sel_texta = jQuery('.custom_options_'+match.custom_field_dd).val();
							
							if( match.custom_con == 'is' )
							{
								
								if(sel_texta == match.custom_field )
								{

									output++;
								}
								
							}
							else if( match.custom_con == 'isnot' )
							{
								
								if(sel_texta != match.custom_field )
								{
									

									output++;
									
								}
								
							}
							else if( match.custom_con == 'isnotempty' )
							{
								
								if( sel_texta != '' )
								{
									
									output++;
								}
								
							}
							else if( match.custom_con == 'isempty' )
							{
								
								if( sel_texta == '' )
								{
									
									output++;
									
								}

							}

						}
						else if( jQuery('.custom_options_'+match.custom_field_dd).is('.custom_checkbox') )
						{

							var $boxes = jQuery('.custom_options_'+match.custom_field_dd+':checked');

							if( match.custom_con != 'isempty' )
							{
							
								if($boxes.length > 0)
								{
								
									$boxes.each(function(){
										
										var sel_chkbx = jQuery( this ).val();
										
										if( match.custom_con == 'is' )
										{
											 
											if(sel_chkbx == match.custom_field )
											{
												
												output++;
												
											}
											
										}
										else if( match.custom_con == 'isnot' )
										{
											
											if(sel_chkbx != match.custom_field )
											{
												
												output++;
												
											}
											
										}
										else if( match.custom_con == 'isnotempty' )
										{
											
											output++;
											
										}
											
									});
									
								}
								
							}
							else
							{
							
								if($boxes.length == 0)
								{
								
									output++;
									
								}
								
							}							
								
						}
						else if( jQuery('.custom_options_'+match.custom_field_dd).is('.custom_raio') )
						{

							var sel_radio = jQuery('.custom_options_'+match.custom_field_dd+':checked').val();
							
							var radio_count = jQuery('.custom_options_'+match.custom_field_dd+':checked').length;
							
							if( match.custom_con == 'is' )
							{
								
								if(sel_radio == match.custom_field )
								{

									output++;
									
								}
								
							}
							else if( match.custom_con == 'isnot' )
							{
								
								if(sel_radio != match.custom_field )
								{

									output++;
								}
								
							}
							else if( match.custom_con == 'isnotempty' )
							{
								
								if(radio_count > 0 )
								{
									
									output++;
									
								}
									
							}
							else if( match.custom_con == 'isempty' )
							{
								
								if(radio_count == 0 )
								{
									
									output++;
									
								}

							}

						} 
						else if( jQuery('.custom_options_'+match.custom_field_dd).is('.custom_select') )
						{

							var sel_optn = jQuery('.custom_options_'+match.custom_field_dd+' option:selected').val();
							  
							if( match.custom_con == 'is' ) 
							{
								
								if(sel_optn == match.custom_field )
								{
					
									output++;
									
								}
								
							}
							else if( match.custom_con == 'isnot' )
							{
								
								if(sel_optn != match.custom_field )
								{

									output++;
									
								}
								
							}
							else if( match.custom_con == 'isnotempty' )
							{
								
								if( sel_optn != '' )
								{
									
									output++;
									
								}
									
							}
							else if( match.custom_con == 'isempty' )
							{
								
								if( sel_optn == '' )
								{
									
									output++;
									
								}


							}
							
						}
						
					});
					
					//console.log( div_ele.closest('.product-option-div') );
					
					if( rules_if == 'all' )
					{
					
						if( output == matches.length )
						{

							if( rules_action == 'show' )
							{
							
								div_ele.closest('.product-option-div').show();
								
								div_ele.closest('.product-option-div').find('input,textarea,select,radio,file,checkbox').each( function() {
									
									jQuery(this).prop('disabled', false);
									
								});
								
							}
							else if( rules_action == 'hide' )
							{
								
								div_ele.closest('.product-option-div').hide();
								
								div_ele.closest('.product-option-div').find('input,textarea,select,radio,file,checkbox').each( function() {
									
									jQuery(this).prop('disabled', true);
									
								});
								
							}
							
						}
						else
						{

							if( rules_action == 'show' )
							{
							
								div_ele.closest('.product-option-div').hide();
								
								div_ele.closest('.product-option-div').find('input,textarea,select,radio,file,checkbox').each( function() {
									
									jQuery(this).prop('disabled', true);
									
								});
								
							}
							else if( rules_action == 'hide' )
							{
							
								div_ele.closest('.product-option-div').show();
								
								div_ele.closest('.product-option-div').find('input,textarea,select,radio,file,checkbox').each( function() {
									
									jQuery(this).prop('disabled', false);
									
								});
								
							}
							
						}
						
					}
					else if( rules_if == 'any' )
					{
					
						if( output > 0 )
						{

							if( rules_action == 'show' )
							{
							
								div_ele.closest('.product-option-div').show();
								
								div_ele.closest('.product-option-div').find('input,textarea,select,radio,file,checkbox').each( function() {
									
									jQuery(this).prop('disabled', false);
									
								});
								
							}
							else if( rules_action == 'hide' )
							{
							
								div_ele.closest('.product-option-div').hide();
								
								div_ele.closest('.product-option-div').find('input,textarea,select,radio,file,checkbox').each( function() {
									
									jQuery(this).prop('disabled', true);
									
								});
								
							}
							
						}
						else
						{
							
							if( rules_action == 'show' )
							{
							
								div_ele.closest('.product-option-div').hide();
								
								div_ele.closest('.product-option-div').find('input,textarea,select,radio,file,checkbox').each( function() {
									
									jQuery(this).prop('disabled', true);
									
								});
								
							}
							else if( rules_action == 'hide' )
							{
							
								div_ele.closest('.product-option-div').show();
								
								div_ele.closest('.product-option-div').find('input,textarea,select,radio,file,checkbox').each( function() {
									
									jQuery(this).prop('disabled', false);
									
								});
								
							}
							
						}
						
					}

				});
				
			});          
			
		});
		
	}
	function phoen_called(){
		// alert();
		jQuery('.variations select').each(function(){
			jQuery(this).trigger('change');
		});
	}
	
		
	
	setTimeout(phoen_called, 100);
	
});
