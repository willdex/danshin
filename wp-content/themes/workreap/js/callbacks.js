var $isClicked = false;
(function($){
    "use strict";

    jQuery(document).on('change','#wr-list-type',function(e){
        e.preventDefault();
        let url_link    = jQuery(this).find(':selected').attr('data-url');
        jQuery('#wr-header-form').attr('action', url_link);

    });

    //toggle mobile menu
    jQuery(".wr-dbmenu").on("click", function () {
        jQuery(".wr-asidewrapper , .wr-asidedetail").toggleClass("wr-opendbmenu");
    });

    // blog sort by
    jQuery(document).on('change', '#blog-sort', function (e) {
        let sort_val = jQuery(this).val();
        if (sort_val) {
            var url = window.location.href;
            url = workreapRemoveParam("sort_by", url);
            if (url.indexOf('?') > -1) {
                url += '&sort_by=' + sort_val
            } else {
                url += '?sort_by=' + sort_val
            }
            window.location.href = url;
        }
        e.preventDefault();
    });

    jQuery('.menu-header-menu-container > ul.menu li').each(function(){
        var submenu = $(this).find('li > .sub-menu');
        if(submenu.length){
            let submenuPosition = submenu.offset();
            let bodyWidth = $('body').width();
            if (submenuPosition.left + submenu.width() > bodyWidth) {
                submenu.addClass('wr-edge');
            } else {
                submenu.removeClass('wr-edge');
            }
        }
    });

})(jQuery);



//Remove param from URL
function workreapRemoveParam(key, sourceURL) {
	var rtn = sourceURL.split("?")[0],
		param,
		params_arr = [],
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

//MOBILE MENU
function workreapCollapseMenu(){
    jQuery('.wr-themenav li.menu-item-has-children, .wr-themenav li.page_item_has_children').prepend('<span class="wr-dropdowarrow"><i class="wr-icon-chevron-right"></i></span>');
    jQuery('.wr-themenav ul.menu .wr-mega-menu, .wr-themenav ul.menu .wr-megamenu-holder').prepend('<span class="wr-dropdowarrow"><i class="wr-icon-chevron-right"></i></span>');

    if (jQuery(window).width() < 1200 && $isClicked === false) {
        $isClicked = true;
        jQuery('.wr-themenav li.menu-item-has-children > span, .wr-themenav li.page_item_has_children > span,.wr-themenav ul.menu .wr-mega-menu > span,.wr-themenav ul.menu .wr-megamenu-holder > span').on('click', function() {
            jQuery(this).parent('li').toggleClass('wr-menuopen');
            if(jQuery(this).parent().hasClass('wr-megamenu-on-responsive-show')){
                jQuery(this).parent().find('.workreap-megamenu').slideToggle(300);
            }else{
                jQuery(this).parent().find('.sub-menu').first().slideToggle(300);
            }
        });
        
        jQuery('.wr-navbar-nav li.menu-item-has-children > a, .wr-navbar-nav ul li.page_item_has_children > a').on('click', function() {
            if ( location.href.indexOf("#") != -1 ) {
                jQuery(this).parent('li').toggleClass('wr-menuopen');
                jQuery(this).next().slideToggle(300);
            }
        });
    }
}

workreapCollapseMenu();
jQuery( window ).resize(function() {
    workreapCollapseMenu();
});

jQuery(window).load(function () {
    var loading_duration = workreap_vars.loading_duration;
    jQuery(".preloader-outer").delay(loading_duration).fadeOut();
    jQuery(".sv-preloader-holder").delay(loading_duration).fadeOut("slow");
});

//SVG Render
jQuery("img.amsvglogo").each(function(){var t=jQuery(this),r=t.attr("id"),a=t.attr("class"),e=t.attr("src");jQuery.get(e,function(e){var i=jQuery(e).find("svg");void 0!==r&&(i=i.attr("id",r)),void 0!==a&&(i=i.attr("class",a+" replaced-svg")),i=i.removeAttr("xmlns:a"),t.replaceWith(i)},"xml")});