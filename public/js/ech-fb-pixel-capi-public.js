(function( $ ) {
	'use strict';

	$(function() {
		/*********** Whatsapp Click send to FB Capi ***********/
		let webDomain = window.location.host;
		// there is global trigger code in Dr.reborn web function.php 
		if(webDomain != "www.drreborn.com"){
			
			jQuery("[data-btn='whatsapp'],.f_wtsapp_btn a").on('click', function(){
				FBEventTrack('Contact','whatsapp');
			});
			
			//omnichat whatsapp button
			jQuery('body').on('click', function(e) { 
				if(jQuery(e.target).attr('id')=='social-subscriber-link-whatsapp-button'){
					FBEventTrack('Contact','whatsapp');
				}
			});

		}
		/*********** (END) Whatsapp Click send to FB Capi ***********/

		/*********** Phone Click send to FB Capi ***********/
		jQuery("[data-btn='phone'],.f_phone_btn a").on('click', function(){
			FBEventTrack('Phone_call','phone');
		});
		/*********** (END) Phone Click send to FB Capi ***********/
	});
	function FBEventTrack(eventName,contentName) {

		const event_id = new Date().getTime();
		const event_name = eventName;
		const content_name = contentName;
		// var ajaxurl = jQuery("#ech_lfg_form").data("ajaxurl");
		var ajaxurl = "/wp-admin/admin-ajax.php";
		var fb_data = {
			'action': 'FB_event_click',
			'website_url': window.location.href,
			'user_agent':navigator.userAgent,
			'event_id': event_id,
			'event_name': event_name,
			'content_name': content_name,
		};
		fbq('track', event_name , {}, {eventID: event_name + event_id});
		fbq('track', 'Purchase', {value: 0, currency: 'HKD'}, {eventID: 'Purchase' + event_id});

		jQuery.post(ajaxurl, fb_data, function(rs) {
			let result = JSON.parse(rs);
			Object.keys(result).forEach(eventName => {
				const event = result[eventName];
				if (event.hasOwnProperty('events_received')) {
						console.log(eventName + ': ' + event.events_received);
				}else{
					console(event);
				}
			});
		});

	} // FBEventTrack

})( jQuery );
