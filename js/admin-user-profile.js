jQuery(document).ready(function($){
    $(".mwp-change-account-status").on('click', function(e){
        let btn = $(this),
			status = btn.data('status');
        
		$.ajax({
			url: mwp_aup_data.ajax_url,
			data: {
				action: 'mw_update_user_account_status',
				status: status,
				user_id: btn.data('uid'),
				nonce: mwp_aup_data.nonce
			},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				btn.attr('disabled', true);
			},
			complete: function(res){
				res = res.responseJSON;	
				if(typeof res.success !== 'undefined' && res.success === true){
					location.reload();
				} else {
					btn.attr('disabled', false);
				}
			},
			error: function(){
				btn.attr('disabled', false);
			}
		});
    });

	$(".mwp-change-phone-status").on('click', function(e){
		let btn = $(this),
			btn_text = btn.text();
			phone_field = $('#mw_user_phone_number');

		$.ajax({
			url: mwp_aup_data.ajax_url,
			data: {
				action: 'mw_update_user_phone_status',
				status: btn.data('status'),
				user_id: btn.data('uid'),
				phone: phone_field.val(),
				nonce: mwp_aup_data.nonce
			},
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				phone_field.attr('readonly', true);
				btn.attr('disabled', true);
			},
			complete: function(res){
				res = res.responseJSON;
				if(typeof res.success !== 'undefined' && res.success === true){
					location.reload();
				} else {
					btn.attr('disabled', false);
					phone_field.attr('readonly', false);
					if(typeof res.data !== 'undefined' && typeof res.data.msg !== 'undefined'){
						btn.text(res.data.msg);
						setTimeout(() => {
							btn.text(btn_text);
						}, 5000);
					}
				}
			},
			error: function(){
				btn.attr('disabled', false);
				phone_field.attr('readonly', false);
			}
		});
	});
});