// JavaScript Document
(function ($) {
	$(document).ready(function () {
		$('form[name="GestionPortfeuilleFormulaire"]').submit( function(event) {
			event.preventDefault(); // Bloquer submit
			var version 			= $(this).children('.version').val();
			var tpe 				= $(this).children('.tpe').val();
			var action 				= $(this).children('.action').val();
			var aliascb 			= $(this).children('.aliascb').val();
			var mac 				= $(this).children('.mac').val();
			var identifiant_carte 	= $(this).children('.identifiant_carte').val();
			$.ajax({
				url: ajaxurl,
				type: "POST",
				data: {
					'action': 'paiement_express',
					'version': version,
					'tpe': tpe,
					'action_express': action,
					'aliascb': aliascb,
					'mac': mac,
					'identifiant_carte': identifiant_carte
				}
			}).done(function (response) {
				const retour = response.split('*');
				$('#abw_retour_express').slideDown(100);
				$('#abw_retour_express').html(retour[2]);
				$('#abw_retour_express').delay(2000).slideUp(500);
				if(retour[0]=='desactiver_carte')
					$('#'+retour[0]+'_'+retour[1]).closest('tr').fadeTo('slow', 0.2);
				if(retour[0]=='positionner_carte_defaut'){
					$('form[id^="positionner_carte_defaut"]').each(function() {
						if(retour[0]+'_'+retour[1]==$( this ).attr('id')) {
							$( this ).closest('td').prev().html(retour[3]);
							$( this ).slideUp(500);
						} else {
							$( this ).closest('td').prev().html("");
							$( this ).toggle().slideDown(100);
						}
					});
				}
          });
		});
  });
})(jQuery);