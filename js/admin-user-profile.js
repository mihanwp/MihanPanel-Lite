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
});