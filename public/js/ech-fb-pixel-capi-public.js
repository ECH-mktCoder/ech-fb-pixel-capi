const pageEnterTimestamp = Math.floor(Date.now() / 1000);
(function( $ ) {
	'use strict';

	let Pll = 1;
	if (typeof echPll !== 'undefined') {
		Pll = echPll;
	}

	$(function() {
		FBThanksPageView();
		
		/*********** Whatsapp Click send to FB Capi ***********/
		// let webDomain = window.location.host;
		// there is global trigger code in Dr.reborn web function.php 
		// if(webDomain != "www.drreborn.com"){
			
			jQuery("[data-btn='whatsapp'],.f_wtsapp_btn a").on('click', function(){
				FBEventTrack('Contact','whatsapp','CompleteRegistration');
			});
			
			//omnichat whatsapp button
			jQuery('body').on('click', function(e) { 
				if(jQuery(e.target).attr('id')=='social-subscriber-link-whatsapp-button'){
					FBEventTrack('Contact','whatsapp','CompleteRegistration');
				}
			});

		// }
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
					fbp = getCookieValue('_fbp'),
					external_id = getCookieValue('_fbuuid');
		let extra_event = "";
		if(typeof extraEvent != "undefined"){
			extra_event = extraEvent;
		}
		let fbc = getCookieValue('_fbc');
		if(fbc==null){
			const urlParams = new URLSearchParams(website_url);
			const fbclid = urlParams.get('fbclid');
			if (fbclid) {
				fbc = 'fb.1.' + pageEnterTimestamp + '.' + fbclid;
			}
		}
		// var ajaxurl = jQuery("#ech_lfg_form").data("ajaxurl");
		const ajaxurl = "/wp-admin/admin-ajax.php";
		const fb_data = {
			'action': 'FB_event_click',
			'website_url': website_url_no_para,
			'user_agent':navigator.userAgent,
			'event_id': event_id,
			'event_name': event_name,
			'content_name': content_name,
			'extra_event' : extraEvent,
			'fbp': fbp,
			'fbc': fbc,
			'external_id':external_id
		};
		if(parseInt(Pll)){
			fbq('track', event_name , {}, {eventID: event_name + event_id});
			fbq('track', 'Purchase', {value: 0.00, currency: 'HKD'}, {eventID: 'Purchase' + event_id});
			if(extra_event != ""){
				fbq('track', 'CompleteRegistration',{},{eventID: 'CompleteRegistration' + event_id});
			}
		}else{
			fbq('trackCustom', event_name , { event_source_url: website_url_no_para }, {eventID: event_name + event_id, external_id: external_id});
			fbq('trackCustom', 'PurchaseWithoutPII', { value: 0.00, currency: 'HKD', event_source_url: website_url_no_para }, {eventID: 'Purchase' + event_id, external_id: external_id});
			if(extra_event != ""){
				fbq('trackCustom', 'CompleteRegistrationWithoutPII',{ event_source_url: website_url_no_para },{eventID: 'CompleteRegistration' + event_id, external_id: external_id});
			}
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

	function FBThanksPageView() {
		const url = window.location.href;
		const isThanksPage = url.includes('/thanks');
		if(isThanksPage){
			const event_id = new Date().getTime(),
					website_url = window.location.href,
					website_url_no_para = location.origin + location.pathname,
					fbp = getCookieValue('_fbp'),
					email = sessionStorage.getItem("fb_email"),
					phone = sessionStorage.getItem("fb_phone"),
					fb_fn = sessionStorage.getItem("fb_fn"),
					fb_ln = sessionStorage.getItem("fb_ln"),
					external_id = getCookieValue('_fbuuid');
			let fbc = getCookieValue('_fbc');
			if(fbc==null){
				const urlParams = new URLSearchParams(website_url);
				const fbclid = urlParams.get('fbclid');
				if (fbclid) {
					fbc = 'fb.1.' + pageEnterTimestamp + '.' + fbclid;
				}
				
			}
			const ajaxurl = "/wp-admin/admin-ajax.php";
			
			// fbq('trackCustom', 'ThanksPageView', {value: 0.00, currency: 'HKD'}, { eventID: 'ThanksPageView' + event_id });
			Promise.all([
					sha256(email ? email.toLowerCase().trim() : null),
					sha256(phone ? phone.replace(/\D/g, '') : null),
					sha256(fb_fn ? fb_fn.toLowerCase().trim() : null),
					sha256(fb_ln ? fb_ln.toLowerCase().trim() : null)
			]).then(([hashedEmail, hashedPhone, hashedFn, hashedLn]) => {
				const fb_data = {
					'action': 'FB_thanks_page_view',
					'event_id': event_id,
					'website_url': website_url_no_para,
					'user_agent':navigator.userAgent,
					'fbp': fbp,
					'fbc': fbc,
					'user_email': hashedEmail,
					'user_phone': hashedPhone,
					'user_fn': hashedFn,
					'user_ln': hashedLn,
					'external_id':external_id
				};
				if(parseInt(Pll)){
					fbq('trackCustom', 'ThanksPageView', {
						value: 0.00,
						currency: 'HKD',
						em: hashedEmail,
						ph: hashedPhone,
						fn: hashedFn,
						ln: hashedLn,
						event_source_url: website_url_no_para,
						content_category: 'Thank You Page'
					}, { eventID: 'ThanksPageView' + event_id, external_id: external_id });
				}else{
					fbq('trackCustom', 'ThanksPageView', {
						value: 0.00,
						currency: 'HKD',
						em: hashedEmail,
						event_source_url: website_url_no_para,
						content_category: 'Thank You Page'
					}, { eventID: 'ThanksPageView' + event_id, external_id: external_id });
				}

				jQuery.post(ajaxurl, fb_data, function(rs) {
					let result = JSON.parse(rs);
					if(result.hasOwnProperty('events_received')){
						console.log('ThanksPageView : ' + result.events_received);
					}else{
						console.log(result);
					}
					sessionStorage.removeItem('fb_email');
					sessionStorage.removeItem('fb_phone');
					sessionStorage.removeItem('fb_fn');
					sessionStorage.removeItem('fb_ln');
				});
			}).catch(error => {
					console.error('Hashing failed:', error);
			});
			
		}

	}
	async function sha256(str) {
    if (!str) return null;
    str = str.toLowerCase().trim();
    const msgBuffer = new TextEncoder().encode(str);
    const hashBuffer = await crypto.subtle.digest('SHA-256', msgBuffer);
    const hashArray = Array.from(new Uint8Array(hashBuffer));
    return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
	}
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
