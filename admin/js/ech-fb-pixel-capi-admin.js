(function( $ ) {
	'use strict';
	$(function(){
		/************* GENERAL FORM **************/
		$('#fbpcapi_gen_settings_form').on('submit', function(e){
			e.preventDefault();
			console.log(1);
			$('.statusMsg').removeClass('error');
			$('.statusMsg').removeClass('updated');

			var statusMsg = '';
			var validStatus = true;

			// set error status msg
			if ( !validStatus ) {
				$('.statusMsg').html(statusMsg);
				$('.statusMsg').addClass('error');
				return;
			} else {
				$('#fbpcapi_gen_settings_form').attr('action', 'options.php');
				$('#fbpcapi_gen_settings_form')[0].submit();
				// output success msg
				statusMsg += 'Settings updated <br>';
				$('.statusMsg').html(statusMsg);
				$('.statusMsg').addClass('updated');
			}
		});
		/************* (END) GENERAL FORM **************/
	}); // document ready
})( jQuery );
