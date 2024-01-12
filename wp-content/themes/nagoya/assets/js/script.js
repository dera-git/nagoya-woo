/* Javascript */

(function ($) {
	jQuery(document).ready(function () {

		//Init js mansory
		/*$('.masonry-item').each(function(){
			$(this).css({
				'height': $(this).outerHeight(),
				//'line-height': $(this).outerHeight()+'px',
			});
		});*/

		if ( $('.woocommerce-notices-wrapper').children().length > 0 ) {			
		     $('.woocommerce-notices-wrapper').addClass('fixed');
		} else {			
		     $('.woocommerce-notices-wrapper').removeClass('fixed');			
		}

		// $('#masthead nav').css('opacity', '0');

		// $('#main-nav').on('shown.bs.collapse', function () {
		// 	//Set equal height to menu
		// 	equalHeight('header .navbar-nav > li > a');
		// 	$('#masthead nav').css('opacity', '1');
		// });

		$('label[for=pa_taille]').text('Je saisie ma taille');

		toggleShow('.show-parts');
		toggleShow('.handleClose');


		var url = 'https://www.youtube.com/embed/'+$("#tutoVideo").attr('data-src');

        $("#tutoVideo").on('hide.bs.modal', function() {
            $(".video-container iframe").attr('src', '');
        });

        $("#tutoVideo").on('show.bs.modal', function() {
            $(".video-container iframe").attr('src', url);
        });    


        $('.toInsertHtml').each(function(){
        	var $html = $(this).html();
        	var $attr_class = 'label.'+$(this).attr('data-attr');

        	$($attr_class).append($html);

        });

        $('.fake-input-pa_police').on('focusout',function(){
        	$('#text-1663435736489').val($(this).val());
        })

	});


	//Set equal height
	function equalHeight($elem = null) {
	  var $maxHeight = 0;

	  $($elem).each(function () {
	    var $currentHeight = $(this).outerHeight();
	    console.log($currentHeight)

	    if ($currentHeight > $maxHeight) {
	      $maxHeight = $currentHeight;
	    }
	  });

	  $($elem).css("height", $maxHeight);
	}

	function toggleShow($elem){
		$($elem).each(function(){
			$(this).click(function(e){
				e.preventDefault();
				var $id = $(this).attr('data-toggle');

				$($id).toggleClass('d-none');
			});
		});
	}

})(jQuery);


	jQuery(document).ready(function($){
	// on focus
	$(".wpcf7-form .inputText").focus(function() {
			$(this).parent().siblings('label').addClass('has-value');
	})
	// blur input fields on unfocus + if has no value
	.blur(function() {
		var text_val = $(this).val();
		if(text_val === "") {
			$(this).parent().siblings('label').removeClass('has-value');
		}
	});

	$(window).scroll(function(){
		if($('#main-nav').hasClass('show')){
			$('#main-nav').slideUp(500);
			setTimeout(function(){
				$('#main-nav').removeClass('show').removeAttr('style');
			},750);
		}
	});
});
