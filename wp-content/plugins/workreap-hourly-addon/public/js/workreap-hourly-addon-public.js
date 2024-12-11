
( function ( $ ) {
    'use strict';
	jQuery(document).ready(function($){
	 	// Project complete action
		jQuery('.wr_project_completed').on('click', function(){
			var _this       	= jQuery(this);
			var $trigger 		= jQuery(".wr-projectsstatus_option > a");
			if($trigger !== event.target && !$trigger.has(event.target).length){
				jQuery(".wr-contract-list").slideUp("fast");
			}
			var proposal_id    	= _this.data('proposal_id');
			var title     		= _this.data('title');
			var counter 		= Math.floor((Math.random() * 999999) + 999);
			var load_task 		= wp.template('load-completed_project_form');
			var data 			= {counter: counter, proposal_id: proposal_id};
			load_task 			= load_task(data);

			jQuery('#wr_projectcomplete_form').html(load_task);
			jQuery('#wr_project_ratingtitle').html(title);
			jQuery('#wr_project_completetask').modal('show');
			wr_rating_options();
		});

		//Submit time slots
		jQuery('.wr_send_timeslot').on('click',function () {
			let _this           	= jQuery(this);
			let id     				= _this.data('id');
			let transaction_id     	= _this.data('transaction_id');
			jQuery.confirm({
				icon: 'wr-icon-clock',
				title: false,
				content: hourly_scripts_vars.hourly_invoice_detail,
				closeIcon: true,
				boxWidth: '600px',
				theme: 'modern',
				draggable: false,
				useBootstrap: false,
				typeAnimated: true,
				onOpenBefore: function(){
					var self = this;
					self.$body.addClass('wr-confirm-modern-alert');
					self.setContentPrepend(`<h4 class="jconfirm-custom-title">${hourly_scripts_vars.hourly_invoice_title}</h4>`);
				},
				buttons: {
					yes: {
						text: scripts_vars.yes,
						btnClass: 'wr-btn',
						action: function () {
							jQuery('body').append(loader_html);
							jQuery.ajax({
								type: "POST",
								url: scripts_vars.ajaxurl,
								data: {
									'action'			: 'workreap_submite_hourly_activities',
									'security'			: scripts_vars.ajax_nonce,
									'id'				: id,
									'transaction_id'	: transaction_id
								},
								dataType: "json",
								success: function (response) {
									jQuery('.wr-preloader-section').remove();
									if (response.type === 'success') {
										StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: 2000});
										window.setTimeout(function() {
											window.location.reload();
										}, 2000);
									} else {
										StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
									}
								}
							});
							
						}
					},
					no: {
						text: scripts_vars.no_btntext,
						btnClass: 'wr-btnvthree',
					}
				}
			});
		});

		// Time slot model
		jQuery('.wr_add_timeslot').on('click',function () {
			let _this           = jQuery(this);
			let time_id     	= _this.data('time');
			let formated_date   = _this.data('formated_date');
			let timeslot_date   = _this.data('timeslot_date');
			let time_string   	= _this.data('time_string');
			let details   		= jQuery('#wr_'+timeslot_date).text();
			jQuery('#wr_timeslot_date_format').html(formated_date);
			jQuery('#wr_form_time_id').val(time_id);
			if (typeof time_string !== 'undefined' && time_string !== '') {
				jQuery('#wr-working-time').val(time_string);
			}
			jQuery('#wr_timeslot_details').val(details);
			jQuery('#wr_form_date').val(timeslot_date);
			jQuery('#wr_workinghours').modal('show');
		});

		// Decline hourly timeslot
		jQuery('.wr_decline_hourly').on('click',function () {
			let _this           	= jQuery(this);
			let detail   			= jQuery('#wr_decline_detail').val();
			let transaction_id     	= _this.data('transaction_id');
			let proposal_id     	= _this.data('proposal_id');
			jQuery('body').append(loader_html);
			jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: {
					'action'				: 'workreap_update_hourly_decline',
					'security'				: scripts_vars.ajax_nonce,
					'detail'				: detail,
					'transaction_id'		: transaction_id,
					'proposal_id'			: proposal_id,
				},
				dataType: "json",
				success: function (response) {
				jQuery('.wr-preloader-section').remove();

				if (response.type === 'success') {
						StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: 2000});
						window.setTimeout(function() {
							window.location.reload();
						}, 2000);
					} else {
						StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
					}
				}
			});
		});

		// Update timeslot
		jQuery('.wr_timetracking_btn').on('click',function () {
			let _serialized   	= jQuery('#wr_timetracking_form').serialize();
			jQuery('body').append(loader_html);
			jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: {
					'action'	: 'workreap_update_hourly_timetracking',
					'security'	: scripts_vars.ajax_nonce,
					'data'		: _serialized,
				},
				dataType: "json",
				success: function (response) {
				jQuery('.wr-preloader-section').remove();

				if (response.type === 'success') {
						StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: 2000});
						window.setTimeout(function() {
							window.location.reload();
						}, 2000);
					} else {
						StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
					}
				}
			});
		});
		
		// Submit proposal 
		jQuery('.wr_hire_job_proposal').on('click',function () {
			let _this           = jQuery(this);
			let proposal_id     = _this.data('id');
			jQuery.confirm({
				icon: 'wr-icon-bell',
				title: false,
				content: scripts_vars.hiring_request_message,
				closeIcon: true,
				boxWidth: '600px',
				theme: 'modern',
				draggable: false,
				useBootstrap: false,
				typeAnimated: true,
				onOpenBefore: function(){
					var self = this;
					self.$body.addClass('wr-confirm-modern-alert');
					self.setContentPrepend(`<h4 class="jconfirm-custom-title">${scripts_vars.hiring_title}</h4>`);
				},
				buttons: {
					yes: {
						text: scripts_vars.yes_btntext,
						btnClass: 'wr-btn',
						action: function () {
							jQuery('body').append(loader_html);
							jQuery.ajax({
								type: "POST",
								url: scripts_vars.ajaxurl,
								data: {
									'action'	: 'workreap_hire_hourly_project',
									'security'	: scripts_vars.ajax_nonce,
									'id'		: proposal_id,
								},
								dataType: "json",
								success: function (response) {
								jQuery('.wr-preloader-section').remove();
				
								if (response.type === 'success') {
										StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: 2000});
										window.setTimeout(function() {
											window.location.replace(response.checkout_url);
										}, 2000);
									} else {
										StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
									}
								}
							});
							
						}
					},
					no: {
						text: scripts_vars.btntext_cancelled,
						btnClass: 'wr-btnvthree'
					}
				}
			});
			
		});

		// Hired hourly proposal 
		jQuery('.wr_hire_hourly_proposal').on('click',function () {
			let _this           = jQuery(this);
			let proposal_id     = _this.data('id');
			jQuery.confirm({
				icon: 'wr-icon-bell',
				title: false,
				content: scripts_vars.hiring_request_message,
				closeIcon: true,
				boxWidth: '600px',
				theme: 'modern',
				draggable: false,
				useBootstrap: false,
				typeAnimated: true,
				onOpenBefore: function(){
					var self = this;
					self.$body.addClass('wr-confirm-modern-alert');
					self.setContentPrepend(`<h4 class="jconfirm-custom-title">${scripts_vars.hiring_title}</h4>`);
				},
				buttons: {
					yes: {
						text: scripts_vars.yes_btntext,
						btnClass: 'wr-btn',
						action: function () {
							jQuery('body').append(loader_html);
							jQuery.ajax({
								type: "POST",
								url: scripts_vars.ajaxurl,
								data: {
									'action'	: 'workreap_hire_hourly_project',
									'security'	: scripts_vars.ajax_nonce,
									'id'		: proposal_id,
								},
								dataType: "json",
								success: function (response) {
								jQuery('.wr-preloader-section').remove();
				
								if (response.type === 'success') {
										StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: 2000});
										window.setTimeout(function() {
											window.location.replace(response.checkout_url);
										}, 2000);
									} else {
										StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
									}
								}
							});
							
						}
					},
					no: {
						text: scripts_vars.btntext_cancelled,
						btnClass: 'wr-btnvthree'
					}
				}
			});
			
		});

		// Hired hourly proposal 
		jQuery('.wr_hourly_slot_payment').on('click',function () {
			let _this           = jQuery(this);
			let proposal_id     = _this.data('id');
			let transaction_id  = _this.data('key');
			jQuery.confirm({
				icon: 'wr-icon-bell',
				title: false,
				content: scripts_vars.hiring_request_message,
				closeIcon: true,
				boxWidth: '600px',
				theme: 'modern',
				draggable: false,
				useBootstrap: false,
				typeAnimated: true,
				onOpenBefore: function(){
					var self = this;
					self.$body.addClass('wr-confirm-modern-alert');
					self.setContentPrepend(`<h4 class="jconfirm-custom-title">${scripts_vars.hiring_title}</h4>`);
				},
				buttons: {
					yes: {
						text: scripts_vars.yes_btntext,
						btnClass: 'wr-btn',
						action: function () {
							jQuery('body').append(loader_html);
							jQuery.ajax({
								type: "POST",
								url: scripts_vars.ajaxurl,
								data: {
									'action'	: 'workreap_hire_hourly_project',
									'security'	: scripts_vars.ajax_nonce,
									'id'		: proposal_id,
									'transaction_id' : transaction_id
								},
								dataType: "json",
								success: function (response) {
								jQuery('.wr-preloader-section').remove();
				
								if (response.type === 'success') {
										StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: 2000});
										window.setTimeout(function() {
											window.location.replace(response.checkout_url);
										}, 2000);
									} else {
										StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
									}
								}
							});
							
						}
					},
					no: {
						text: scripts_vars.btntext_cancelled,
						btnClass: 'wr-btnvthree'
					}
				}
			});
			
		});

		// hired project with wallet
		$(document).on('click', '.wr_hourly_proposal_hiring', function (e) {
			let _this			= $(this);
			let proposal_id     = _this.data('id');
			let transaction_id  = _this.data('key');

			jQuery.confirm({
				icon: 'wr-icon-bell',
				title: false,
				content: scripts_vars.wallet_account_message,
				closeIcon: true,
				boxWidth: '600px',
				theme: 'modern',
				draggable: false,
				useBootstrap: false,
				typeAnimated: true,
				onOpenBefore: function(){
					var self = this;
					self.$body.addClass('wr-confirm-modern-alert');
					self.setContentPrepend(`<h4 class="jconfirm-custom-title">${scripts_vars.wallet_account}</h4>`);
				},
				buttons: {
					yes: {
						text: scripts_vars.btn_with_wallet,
						btnClass: 'wr-btn',
						action: function () {
							jQuery('body').append(loader_html);
							jQuery.ajax({
								type: "POST",
								url: scripts_vars.ajaxurl,
								data: {
									'action'	: 'workreap_hire_hourly_project',
									'security'	: scripts_vars.ajax_nonce,
									'id'		: proposal_id,
									'transaction_id' : transaction_id,
									'wallet'	: true
								},
								dataType: "json",
								success: function (response) {
									jQuery('.wr-preloader-section').remove();

									if (response.type === 'success') {
										window.location.replace(response.checkout_url);
									} else {
										StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
									}
								}
							});
							
						}
					},
					no: {
						text: scripts_vars.btn_without_wallet,
						btnClass: 'wr-btnvthree',
						action: function () {
							jQuery('body').append(loader_html);
							jQuery.ajax({
								type: "POST",
								url: scripts_vars.ajaxurl,
								data: {
									'action'	: 'workreap_hire_hourly_project',
									'security'	: scripts_vars.ajax_nonce,
									'id'		: proposal_id,
									'transaction_id' : transaction_id
								},
								dataType: "json",
								success: function (response) {
									jQuery('.wr-preloader-section').remove();

									if (response.type === 'success') {
										window.location.replace(response.checkout_url);
									} else {
										StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
									}
								}
							});
							
						}
					}
				}
			});
		});

		// Approved hourly proposal 
		jQuery('.wr_approve_hours').on('click',function () {
			let _this           = jQuery(this);
			let proposal_id     = _this.data('id');
			let transaction_id  = _this.data('key');
			jQuery.confirm({
				icon: 'wr-icon-bell',
				title: false,
				content: hourly_scripts_vars.approved_time_detail,
				closeIcon: true,
				boxWidth: '600px',
				theme: 'modern',
				draggable: false,
				useBootstrap: false,
				typeAnimated: true,
				onOpenBefore: function(){
					var self = this;
					self.$body.addClass('wr-confirm-modern-alert');
					self.setContentPrepend(`<h4 class="jconfirm-custom-title">${hourly_scripts_vars.approved_time_title}</h4>`);
				},
				buttons: {
					yes: {
						text: scripts_vars.yes_btntext,
						btnClass: 'wr-btn',
						action: function () {
							jQuery('body').append(loader_html);
							jQuery.ajax({
								type: "POST",
								url: scripts_vars.ajaxurl,
								data: {
									'action'	: 'workreap_approved_hourly_project',
									'security'	: scripts_vars.ajax_nonce,
									'id'		: proposal_id,
									'transaction_id' : transaction_id
								},
								dataType: "json",
								success: function (response) {
								jQuery('.wr-preloader-section').remove();
				
								if (response.type === 'success') {
										StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: 2000});
										window.setTimeout(function() {
											window.location.reload();
										}, 2000);
									} else {
										StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
									}
								}
							});
							
						}
					},
					no: {
						text: scripts_vars.btntext_cancelled,
						btnClass: 'wr-btnvthree'
					}
				}
			});
			
		});
	});
} ( jQuery ) );