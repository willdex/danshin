
( function ( $ ) {
    'use strict';

	/**
	 * Download offer attachments 
	*/
	jQuery(document).ready(function($){
		//Download files
		jQuery('.wr_download_offer_files').on('click',function(e){
			let product_id	= jQuery(this).data('id');
			jQuery('body').append(loader_html);
			jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: {
					'action'		: 'workreap_download_offer_zip_file',
					'security'		: scripts_vars.ajax_nonce,
					'product_id'	: product_id,
				},
				dataType: "json",
				success: function (response) {
					jQuery('.wr-preloader-section').remove();
	
					if (response.type === 'success') {
						window.location = response.attachment;
					} else {
						StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
					}
				}
			});
		});

		//Offer introduction form
		$(document).on('submit', '#offer-introduction-form', function (e) {
			$('body').append(loader_html);
			var data = new FormData( this );
			data.append('action', 'workreap_offer_inroduction_save' );
			data.append('ajax_nonce', scripts_vars.ajax_nonce );
			$.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: data,
				dataType: "json",
				contentType: false,
				processData: false,
				success: function (response) {
					let extra_params = {};
					if (response.type === 'success') {
						if(response.post_id){
							workreap_add_offer_next_step(response.post_id, 2);
						} else {
							jQuery('.wr-preloader-section').remove();
							extra_params['note_desc'] = response.message_desc;
							if (response.type){}
							StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
						}
					} else {
						jQuery('.wr-preloader-section').remove();
						if (response.button){
							StickyAlertBtn(response.message, response.message_desc, {classList: 'danger', autoclose: 5000,button:response.button});
						} else {
							StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
						}
					}
				}
			});	
			e.preventDefault();
		});

		//Task plans form
		$(document).on('submit', '#offer-plans-form', function (e) {
			e.preventDefault();
			//jQuery('#offer-plans-form').validate();
			let dataString	= $('#offer-plans-form').serialize();
			jQuery('body').append(loader_html);
			jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: {
					'action'	: 'workreap_offer_plans_save',
					'security'	: scripts_vars.ajax_nonce,
					'data'		: dataString,
				},
				dataType: "json",
				success: function (response) {
					if (response.type === 'success') {
						if(response.post_id){
							workreap_add_offer_next_step(response.post_id, response.step);
						} else {
							jQuery('.wr-preloader-section').remove();
							StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: 5000});
						}
					} else {
						jQuery('.wr-preloader-section').remove();
						if (response.button){
							StickyAlertBtn(response.message, response.message_desc, {classList: 'danger', autoclose: 5000,button:response.button});
						} else {
							StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
						}
					}
				}
			});
		});

		//Task media attachment form
		$(document).on('submit', '#offer-media-attachments-form', function (e) {
			let _serialized   = $(this).serialize();
			jQuery('body').append(loader_html);
			jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: {
					'action'	: 'workreap_offer_media_attachments_save',
					'security'	: scripts_vars.ajax_nonce,
					'data'		: _serialized,
				},
				dataType: "json",
				success: function (response) {
				    if (response.type === 'success') {
					   jQuery('.wr-preloader-section').remove();
					   StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: 5000});

					   if(response.redirect){
							window.setTimeout(function() {
								window.location.href = response.redirect;
							}, 5000);
						}
					} else {
						jQuery('.wr-preloader-section').remove();
						if (response.button){
							StickyAlertBtn(response.message, response.message_desc, {classList: 'danger', autoclose: 5000,button:response.button});
						} else {
							StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
						}
					}
				}
			});
			e.preventDefault();
		});

		// Offer action
		jQuery('.workreap-decline-offer').on('click', function(){
			var _this       = jQuery(this);
 			var offer_id    = _this.data('offer-id');
			var post_author = _this.data('post-author');
			var employer_id    = _this.data('employer-id');

 			var load_task 	= wp.template('load-cancelled-offer-form');
			var data 		= {offer_id: offer_id, post_author: post_author, employer_id:employer_id };
			load_task 		= load_task(data);
			jQuery('#wr_offercomplete_form').html(load_task);
			jQuery('#wr_completeoffer').modal('show');
		});


	  	jQuery(document).on('click', '.wr_decline_offer', function (e) {
			let offer_id	= jQuery(this).data('offer_id');
			let post_author	= jQuery(this).data('post_author');
			let employer_id	= jQuery(this).data('employer_id');
			let details		= jQuery('#details').val();
		
			jQuery('body').append(loader_html);
			jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: {
					'action'		: 'workreap_offer_decline',
					'security'		: customized_task_offers_scripts_vars.ajax_nonce,
					'offer_id'		: offer_id,
					'post_author'	: post_author,
					'employer_id'		: employer_id,
					'details'		: details
				},
				dataType: "json",
				success: function (response) {
					jQuery('.wr-preloader-section').remove();
	
					if (response.type === 'success') {
						StickyAlert(response.message, response.message_desc, {classList: 'success', autoclose: 5000});
						window.location.href = response.redirect_url;
					} else {
						StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
					}
				}
			});
		});

		//Education description text characters count
		$(document).on('keyup', '#workreap-offer-description', function (e) {
			tuMaxLengthCounter(e.target.id);
		});

		function tuMaxLengthCounter(id = '', currentCharClass = '.wr_current_comment', maxCharClass = '.wr_maximum_comment') {
			if (id) {
				var maxCharCount 	= jQuery('#' + id).val().length
				var currentChar 	= jQuery('#' + id).parents('.wr-message-text').find(currentCharClass)
				var maximumChar 	= jQuery('#' + id).parents('.wr-message-text').find(maxCharClass)
				var maxCharLength	= jQuery('#' + id).attr('maxlength');
				if (maxCharLength) {
					var changeColor = 0.75 * maxCharLength;
					currentChar.text(maxCharCount);
		
					if (maxCharCount > changeColor && maxCharCount < maxCharLength) {
						currentChar.css('color', '#FF4500');
						currentChar.css('fontWeight', 'bold');
					} else if (maxCharCount >= maxCharLength) {
						currentChar.css('color', '#B22222');
						currentChar.css('fontWeight', 'bold');
					} else {
						var char_color = maximumChar.css('color');
						var char_font = maximumChar.css('fontWeight');
						currentChar.css('color', char_color);
						currentChar.css('fontWeight', char_font);
					}
				}
			}
		}
		 //instructor search listings sort
		jQuery(document).on('change', '#wr_task_type', function(e) {
            let sort_val = jQuery(this).val();
			
            if (sort_val) {
                var url = window.location.href;
                var url = removeParam("order_type", url);
                if (url.indexOf('?') > -1) {
                    url += '&order_type=' + sort_val
                } else {
                    url += '?order_type=' + sort_val
                }
                window.location.href = url;
            }
            e.preventDefault();
        });
		
		//Remove param from URL
		function removeParam(key, sourceURL) {
			var rtn = sourceURL.split("?")[0],
				param,
				params_arr	= [],
				queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";

			if (queryString !== "") {
				params_arr = queryString.split("&");
				for (var i = params_arr.length - 1; i >= 0; i -= 1) {
					param = params_arr[i].split("=")[0];
					if (param === key) {
						params_arr.splice(i, 1);
					}
				}
				if (params_arr.length) rtn = rtn + "?" + params_arr.join("&");
			}

			return rtn;
		}

		// Change query param
		function workreapchangeQuery(searchString, documentTitle) {
			documentTitle	= typeof documentTitle !== 'undefined' ? documentTitle : document.title;
			var urlSplit	= (window.location.href).split("?");
			let _url		= urlSplit[0];
			_url 			= _url.replace(/page.*/g,'');
			var obj 		= { Title: documentTitle, Url:_url + searchString };
			history.pushState(obj, obj.Title, obj.Url);
		}

		//Add task load next step
		function workreap_add_offer_next_step(postid, step){
			$.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: {
					'action': 'workreap_offer_next_step_template',
					'ajax_nonce': scripts_vars.ajax_nonce,
					'post_id': postid,
					'step': step,
				},
				dataType: "html",
				success: function (response) {
					$('html, body').animate({
						scrollTop: $(".wr-addservice-steps").offset().top
					}, 100);
					$('#wr-services-steps').html(response);
					$('.wr-addservice-step').removeClass('wr-addservice-step-fill');
					
					for (var n = 1; n < step; ++n){

						if(!$('.task-step-'+n).hasClass('wr-addservice-step-complete')){
							$('.task-step-' + n).addClass('wr-addservice-step-complete');
						}
					}

					$('.wr-addservice-step-' + step).addClass('wr-addservice-step-fill');
					workreapchangeQuery('?post=' + postid + '&step=' + step);
					workreap_upload_multiple_doc_offer('workreap-offer-attachment-btn', 'workreap-offer-upload-attachment', 'workreap-droparea', 'file_name', 'workreap-fileprocessing', 'load-service-media-attachments', true, 'pdf,doc,docx,xls,xlsx,ppt,pptx,csv,jpg,jpeg,gif,png,zip,rar,mp4,mp3,3gp,flv,ogg,wmv,avi,stl,obj,iges,js,php,html,txt', scripts_vars.task_max_images);
					// Make drop-down select2
					if ( $.isFunction($.fn.select2) ) {
						jQuery('.wr-select select').select2({
							theme: "default wr-select2-dropdown",
							minimumResultsForSearch: Infinity
						});
						// Make multiple drop-down select2
						jQuery('.wr-select select[multiple]').select2({
							theme: "default wr-select2-dropdown",
							multiple: true,
							placeholder: scripts_vars.select_option
						});
						jQuery('.wr-select select').on('select2:open', function (e) {
							jQuery('.select2-results__options').mCustomScrollbar('destroy');
							setTimeout(function () {
							jQuery('.select2-results__options').mCustomScrollbar();
							}, 0);
						});
					}

					if(jQuery('#tbslothandle').length > 0){
						new Sortable(tbslothandle, {
							animation: 150
						});
					}
					window.setTimeout(function() {
						jQuery('.wr-preloader-section').remove();
					}, 400);
				}
			});
		}

		 // Checkout page
		 jQuery('.wr_offers_btn_checkout').on('click', function (e) {
			var _type	= $(this).data('type');
			var _url	= $(this).data('url');
			if (scripts_vars.user_type == '' || scripts_vars.user_type == null || scripts_vars.user_type == undefined) {
				jQuery('body').append(loader_html);
				jQuery.ajax({
					type: "POST",
					url: scripts_vars.ajaxurl,
					data: {
						'action'	: 'workreap_offers_cart_page',
						'security'	: scripts_vars.ajax_nonce,
						'type'		: _type,
						'page_url'	: _url,
					},
					dataType: "json",
					success: function (response) {
						jQuery('.wr-preloader-section').remove();
						if (response.type === 'success') {
							StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 2000});
							window.setTimeout(function () {
								window.location.href = response.redirect;
							}, 2000);
						} else {
							StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
						}
					}
				});
			} else if (scripts_vars.user_type != 'employers') {
				StickyAlert(scripts_vars.error_title, scripts_vars.only_employer_option, {
					classList: 'danger',
					autoclose: 2000
				});
			} else {
				window.location.href = _url;
			}
		});

		// Task add cart button
		jQuery('#wr_offers_cart_btn').on('click', function (e) {
			/* getting checked subtasks */
			let wallet 		= jQuery('#wr_wallet_option:checked').val() ? 1 : 0;
			let _serialized = jQuery('#wr_cart_form').serialize();
			let id 			= jQuery(this).data('id');
			let offers_id 	= jQuery(this).data('offers_id');
			let dataString 	= _serialized + '&id=' + id + '&offers_id=' + offers_id;
			jQuery('body').append(loader_html);
			jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: {
					'action'	: 'workreap_offer_checkout',
					'security'	: scripts_vars.ajax_nonce,
					'data'		: dataString,
				},
				dataType: "json",
				success: function (response) {
					jQuery('.wr-preloader-section').remove();
					if (response.type === 'success') {
						window.location.replace(response.checkout_url);
					} else {
						if (response.button){
							StickyAlertBtn(response.message, response.message_desc, {classList: 'danger', autoclose: 5000,button:response.button});
						} else {
							StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
						}
					}
				}
			});
		});

		workreap_upload_multiple_doc_offer('workreap-offer-attachment-btn', 'workreap-offer-upload-attachment', 'workreap-droparea', 'file_name', 'workreap-fileprocessing', 'load-service-media-attachments', true, 'pdf,doc,docx,xls,xlsx,ppt,pptx,csv,jpg,jpeg,gif,png,zip,rar,mp4,mp3,3gp,flv,ogg,wmv,avi,stl,obj,iges,js,php,html,txt', scripts_vars.task_max_images);
		
		// multiple file upload
		function workreap_upload_multiple_doc_offer(btnID, containerID, dropareaID, type, previewID, templateID, _type, filetype = "pdf,doc,docx,xls,xlsx,ppt,pptx,csv,jpg,jpeg,gif,png,zip,rar,mp4,mp3,3gp,flv,ogg,wmv,avi,stl,obj,iges,js,php,html,txt", max_file_count=0) {
			
			if (typeof plupload === 'object') {
				console.log('upload', btnID, scripts_vars.ajax_nonce, scripts_vars.ajaxurl)
				var sys_upload_nonce = scripts_vars.sys_upload_nonce;
				var ProjectUploaderArguments = {
					browse_button: btnID, // this can be an id of a DOM element or the DOM element itself
					file_data_name: type,
					container: containerID,
					drop_element: dropareaID,
					multipart_params: {
						"type": type,
					},
					multi_selection: _type,
					url: scripts_vars.ajaxurl + "?action=workreap_temp_file_uploader&ajax_nonce=" + scripts_vars.ajax_nonce,
					filters: {
						mime_types: [
							{ title: 'file', extensions: filetype }
						],
						max_file_size: scripts_vars.upload_size,
						max_file_count: max_file_count,
						prevent_duplicates: false
					}
				};

				var ProjectUploader = new plupload.Uploader(ProjectUploaderArguments);
				ProjectUploader.init();
				//bind
				ProjectUploader.bind('FilesAdded', function (up, files) {

					var _Thumb = "";
					if (max_file_count > 1 ) {
						let prevous_files = jQuery('#'+previewID+' li').length;
						let file_count  = max_file_count - prevous_files;

						if (file_count > 1 && files.length > file_count) {
							up.files.splice(5, up.files.length - 5);
							let extra_params = {};
							extra_params['note_desc'] = '';
							StickyAlert(scripts_vars.upload_max_images+max_file_count, '', {classList: 'danger', autoclose: 5000});
							return false;
						}
						if (files.length >= file_count) {
							jQuery('#'+dropareaID).addClass('d-none');
						}
					}

					let counter = 0;
					plupload.each(files, function (file) {

						let prevous_files	= jQuery('#'+previewID+' li').length;
						let file_count		= max_file_count - prevous_files;

						if (max_file_count < 1 ||  counter < file_count) {
							var load_thumb = wp.template(templateID);
							var _size	= bytesToSize(file.size);
							var data 	= { id: file.id, size: _size, name: file.name, percentage: file.percent };

							load_thumb = load_thumb(data);
							_Thumb += load_thumb;
						}
						if (max_file_count > 1){
							counter++;
						}
					});

					console.log(previewID);
					if (_type == false) {
						jQuery('#' + previewID).html(_Thumb);
					} else {
						jQuery('#' + previewID).append(_Thumb);
					}
					jQuery('#' + previewID).removeClass('workreap-empty-uploader');
					jQuery('#' + previewID).addClass('workreap-infouploading');
					up.refresh();
					ProjectUploader.start();
				});

				//FilesRemoved
				ProjectUploader.bind('FilesRemoved', function(up, files) {

					if (max_file_count > 1 ) {

						let prevous_files	= jQuery('#'+previewID+' li').length;
						if (up.files.length >= max_file_count) {
							jQuery('#'+dropareaID).removeClass('d-none');
						}
					}
				});

				//bind
				ProjectUploader.bind('UploadProgress', function (up, file) {
					var _html = ' <span class="progress-bar uploadprogressbar" style="width:' + file.percent + '%"></span>';
					jQuery('#thumb-' + file.id + ' .progress .uploadprogressbar').replaceWith(_html);
				});

				//Error
				ProjectUploader.bind('Error', function (up, err) {

					var errorMessage = err.message
					if (err.code == '-600') {
						errorMessage = scripts_vars.file_size_error
					}
					extra_params['note_desc'] = errorMessage;
					StickyAlert('', errorMessage, {classList: 'danger', autoclose: 5000});
				});

				//display data
				ProjectUploader.bind('FileUploaded', function (up, file, ajax_response) {
					var response = jQuery.parseJSON(ajax_response.response);
					if (response.type === 'success') {
						var successIcon = '<a href="javascript:void(0);"><i class="wr-icon-check-circle"></i></a>';
						jQuery('#thumb-' + file.id + ' .workreap-filedesciption .workreap-filedesciption__icon').append(successIcon);
						jQuery('#thumb-' + file.id).removeClass('workreap-uploading');
						jQuery('#thumb-' + file.id).removeClass('wr-uploading');
						jQuery('#thumb-' + file.id).addClass('workreap-file-uploaded');
						jQuery('#thumb-' + file.id + ' .attachment_url').val(response.thumbnail);
						jQuery('#thumb-' + file.id + ' .wr-downalod-file').attr("href", response.thumbnail)
						jQuery('#thumb-' + file.id).find('.workreap-filedesciption__details a').attr("href", response.thumbnail);
					} else {
						jQuery('#workreap-droparea').removeClass('d-none');
						jQuery('#thumb-' + file.id).remove();
						StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
					}
				});

				//Delete Gallery images
				jQuery(document).on('click', '.wr-gallery-attachment', function (e) {

					e.preventDefault();
					var _this 			= jQuery(this);
					var listParent 		= _this.parents('li').parent('ul');
					let total_docs		= listParent.find('li').length;
					let	max_file_count	= scripts_vars.task_max_images;
					if (total_docs >= max_file_count) {
						jQuery('#workreap-droparea').removeClass('d-none');
					} else {
						jQuery('#workreap-droparea').removeClass('d-none');
						jQuery('#workreap-droparea').show();
						listParent.addClass('wr-empty-uploader');
					}
					_this.parents('li').remove();
				});

				//Delete Award Image
				jQuery(document).on('click', '.wr-remove-attachment', function (e) {

					e.preventDefault();
					var _this = jQuery(this);
					var listParent 	= _this.parents('li').parent('ul');
					let total_docs	= listParent.find('li').length;
					
					if (total_docs < max_file_count) {
						jQuery('#'+dropareaID).removeClass('d-none');
					} else if(total_docs == 0) {
						jQuery('#'+dropareaID).removeClass('d-none');
						listParent.addClass('wr-empty-uploader')
					}
					_this.parents('li').remove();
				});

				//Delete document
				jQuery(document).on('click', '.wr-remove-document', function (e) {

					e.preventDefault();
					var _this = jQuery(this);
					var listParent 	= _this.parents('li').parent('ul');
					let total_docs	= listParent.find('li').length;
					if (total_docs < max_file_count) {
						jQuery('#'+dropareaID).removeClass('d-none');
					} else if(total_docs == 0) {
						jQuery('#'+dropareaID).removeClass('d-none');
						listParent.addClass('wr-empty-uploader')
					}
					_this.parents('li').remove();
				});

			}
			// slide toggle
				
		}

		//convert bytes to KB< MB,GB,TB
		function bytesToSize(bytes) {
			var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
			if (bytes == 0) return '0 Byte';
			var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
			return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
		};		
		
	});
} ( jQuery ) );

// select employer for create offer
jQuery(document).on('click', '.wr-employer-select', function(event) {
	jQuery(this).children(".wr-employer-select-wrapper").slideToggle();
	jQuery('.wr-employer-select-wrapper ul').mCustomScrollbar();
});

jQuery(document).on('click', '.wr-employer-select-wrapper ul li', function(event) {
	event.stopPropagation();
	changeText(jQuery(this))
	jQuery('.wr-employer-select-wrapper').mCustomScrollbar('destroy');
});

jQuery(window).on('ready', '.check_employer', function(e){
	alert('load')
	changeText(jQuery(this))
})
function changeText(_this) {
	let listText = _this.find(".wr-selected-value").text();
	jQuery('.wr-employer-select > span').text(listText);
	jQuery(".wr-employer-select-wrapper").slideUp();
	jQuery(".wr-employer-slect-list li").removeClass("check_employer");
	jQuery(_this).closest('li').addClass('check_employer');
  }

jQuery(document).on('click', '.wr-employer-search__field', function(event) {
	event.stopPropagation();
});
jQuery(document).on('keyup', '.wr-employer-search__field', function(event) {
	var value = $(this).val();
	var el = new RegExp(value, "i");
	$('.wr-employer-slect-list').find('li').each(function() {
	  var $list = $(this);
	  if (!($list.find('h6').text().search(el) >= 0)) {
		$list.not('.wr-employer-slect-list').hide();
	  }
	  if (($list.find('h6').text().search(el) >= 0)) {
		$(this).show();
	  }
	  
	});
   
});