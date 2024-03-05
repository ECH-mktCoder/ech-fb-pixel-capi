(function( $ ) {
	'use strict';

	$(function() {
		/*********** Whatsapp Click send to FB Capi ***********/
		let webDomain = window.location.host;
		// there is global trigger code in Dr.reborn web function.php 
		if(webDomain != "www.drreborn.com"){
			
			jQuery("[data-btn='whatsapp'],.f_wtsapp_btn a").on('click', function(){
				FBEventTrack('Contact','whatsapp','CompleteRegistration');
			});
			
			//omnichat whatsapp button
			jQuery('body').on('click', function(e) { 
				if(jQuery(e.target).attr('id')=='social-subscriber-link-whatsapp-button'){
					FBEventTrack('Contact','whatsapp','CompleteRegistration');
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
	function FBEventTrack(eventName,contentName,extraEvent) {

		const event_id = new Date().getTime(),
					event_name = eventName,
					content_name = contentName,
					website_url = window.location.href,
					website_url_no_para = location.origin + location.pathname,
					fbp = getCookieValue('_fbp');
		let extra_event = "";
		if(typeof extraEvent != "undefined"){
			extra_event = extraEvent;
		}
		let fbc = getCookieValue('_fbc');
		if(fbc==null){
			let urlParams = new URLSearchParams(website_url);
			fbc = urlParams.get('fbclid');
		}
		// var ajaxurl = jQuery("#ech_lfg_form").data("ajaxurl");
		var ajaxurl = "/wp-admin/admin-ajax.php";
		var fb_data = {
			'action': 'FB_event_click',
			'website_url': website_url_no_para,
			'user_agent':navigator.userAgent,
			'event_id': event_id,
			'event_name': event_name,
			'content_name': content_name,
			'extra_event' : extraEvent,
			'fbp': fbp,
			'fbc': fbc,
		};
		fbq('track', event_name , {}, {eventID: event_name + event_id});
		fbq('track', 'Purchase', {value: 0, currency: 'HKD'}, {eventID: 'Purchase' + event_id});
		if(extra_event != ""){
			fbq('track', 'CompleteRegistration',{},{eventID: 'CompleteRegistration' + event_id});
		}
		jQuery.post(ajaxurl, fb_data, function(rs) {
			let result = JSON.parse(rs);
			Object.keys(result).forEach(eventName => {
				let event = result[eventName];
				if (event.hasOwnProperty('events_received')) {
						console.log(eventName + ': ' + event.events_received);
				}else{
					console.log(event);
				}
			});
		});

	} // FBEventTrack

	function getCookieValue(cookieName) {
    var name = cookieName + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var cookieArray = decodedCookie.split(';');
    for(var i = 0; i < cookieArray.length; i++) {
        var cookie = cookieArray[i].trim();
        if (cookie.indexOf(name) == 0) {
            return cookie.substring(name.length, cookie.length);
        }
    }
    return null;
	} //getCookieValue

})( jQuery );
